<?php
include_once('head.php');
include_once('connectdb.php');

function queryDB($query, $dbh){
    $sql = "SELECT * FROM `web` WHERE `webTitle` LIKE ? OR `webContext` LIKE ?";
    $sth = $dbh->prepare($sql);
    $query = "%".$query."%";
    $sth->execute(array($query, $query));
    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

if(!isset($_GET['query'])){
    header("Refresh: 0;url=/");
    die();
}

$queryResult = queryDB($_GET['query'], $dbh);
$queryLength = count($queryResult);

?>
<form action="query.php" method="get">
    <div class="field">
    <label for="query" class="label"></label>
    <div class="control">
        <input name="query" id="query" class="input" type="text" placeholder="輸入關鍵字" value="<?php echo htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8'); ?>">
    </div>
    </div>
    <div class="field is-grouped">
    <div class="control">
        <button class="button is-link" type="submit">搜尋</button>
    </div>
    <div class="control">
        <button class="button is-link is-light" type="reset">重置</button>
    </div>
    </div>
</form>

<p class="help">有 <?php echo $queryLength; ?> 項結果</p>

<br>

<style>
    .urltitle{
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 0;
    }
    .urltext{
        font-size: 18px;
        color: #63bf68;
    }
</style>

<?php 
for($i = 0; $i < $queryLength; $i++){
    echo '<h3 class="urltitle"><a href="'.$queryResult[$i]['webURI'].'">'.$queryResult[$i]['webTitle'].'</a></h2>';
    echo '<p class="urltext">'.$queryResult[$i]['webURI'].'</p><br>';
}
?>



<?php
include_once('footer.php');
?>