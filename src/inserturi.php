<?php
    ini_set('display_errors','1');
    error_reporting(E_ALL);
    include_once('connectdb.php');
    function getURI(){
        $request = json_decode(file_get_contents('php://input'), true);
        if(!isset($request['uri'])){
            return false;
        }
        return $request['uri'];
    }
    function validateURI($URI){
        $regex = "/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/m";
        if(preg_match($regex, $URI)){
            return true;
        }
        return false;
    }
    function queryURIisInDatabase($URI, $dbh){
        $sql = "SELECT * FROM `web` WHERE `webURI` = ?";
        $sth = $dbh->prepare($sql);
        $sth->execute(array($URI));
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(count($rows) == 0){
            return false;
        }
        return true;
    }
    function getContext($URI){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URI);
        //永遠抓最新
        $request_headers = array(
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.106 Safari/537.36"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        //等待時間
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        // 設定referer
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $result = curl_exec($ch);
        $result = mb_convert_encoding($result, "UTF-8");
        curl_close($ch);
        if (preg_match('/<title>(.*?)<\/title>/is',$result,$found)) {
            $title = $found[1];
        }
        return array($title, $result);
    }
    function goInDatabase($URI, $dbh){
        try {
            $sql = "INSERT INTO `web`(`webTitle`, `webURI`, `webContext`) VALUES (?, ?, ?)";
            $context = getContext($URI);
            $sth = $dbh->prepare($sql);
            $sth->execute(array($context[0], $URI, $context[1]));
            //success
            $return = array(true, "Success!");
            return $return;
        }
        catch (PDOException $e) {
            //error
            $return = array(false, "FAIL! ". $e);
            return $return;
        }
    }



    $URI = getURI();
    $returnData = array();
    if($URI == false){
        $returnData["HTTP_STATUS_CODE"] = 400;
        $returnData["Message"] = "Bad Request";
        $returnString = json_encode($returnData);
        echo $returnString;
        die();
    }
    if(validateURI($URI) === false){
        $returnData["HTTP_STATUS_CODE"] = 400;
        $returnData["Message"] = "Bad Request: URI not match.";
        $returnString = json_encode($returnData);
        echo $returnString;
        die();
    }
    if(queryURIisInDatabase($URI, $dbh)){
        $returnData["HTTP_STATUS_CODE"] = 400;
        $returnData["Message"] = "Bad Request: URI is in Database.";
        $returnString = json_encode($returnData);
        echo $returnString;
        die();
    }
    $getResult = goInDatabase($URI, $dbh);
    if($getResult[0] == true){
        $returnData["HTTP_STATUS_CODE"] = 200;
        $returnData["Message"] = "Success";
        $returnString = json_encode($returnData);
    }
    else{
        $returnData["HTTP_STATUS_CODE"] = 400;
        $returnData["Message"] = $getResult[1];
        $returnString = json_encode($returnData);
    }
    echo $returnString;


?>