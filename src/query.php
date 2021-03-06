<?php
include_once('head.php');
include_once('connectdb.php');

function queryDB($query, $dbh)
{
    $sql = "SELECT * FROM `web` WHERE `webTitle` LIKE ? OR `webContext` LIKE ?";
    $sth = $dbh->prepare($sql);
    $query = "%" . $query . "%";
    $sth->execute(array($query, $query));
    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

if (!isset($_GET['query']) || trim($_GET['query']) == '') {
    header("Refresh: 0; url=/");
    die();
}

$queryResult = queryDB($_GET['query'], $dbh);
$queryLength = count($queryResult);

?>
<form id="myForm" action="query.php" method="get">
    <div class="field">
        <label for="query" class="label"></label>
        <div class="control">
            <input name="query" id="query" class="input" type="text" placeholder="輸入關鍵字..." onfocus="this.placeholder=''" onblur="this.placeholder='輸入關鍵字...'" value="<?php echo htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
    </div>
    <div class="field is-grouped">
        <div class="control">
            <input type="button" class="button is-link" value="搜尋" onclick="submitForm()">
        </div>
        <div class="control">
            <input class="button is-link is-light" value="重置" type="reset">
        </div>
    </div>
</form>
<script>
    function submitForm() {
        var str = document.getElementById('query').value;
        if (str.trim() == '') {
            Swal.fire(
                '錯誤',
                '搜尋字串不為空白！',
                'error'
            );
        } else {
            document.getElementById('query').value = str.trim();
            document.getElementById("myForm").submit()
        }
    }
</script>
<style>
    ::placeholder {
        color: gray;
        opacity: 0.5;
        /* Firefox */
    }
</style>

<p class="help">有 <?php echo $queryLength; ?> 項結果</p>

<br>

<style>
    .urltitle {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 0;
    }

    .urltext {
        font-size: 18px;
        color: #63bf68;
    }
</style>

<?php
for ($i = 0; $i < $queryLength; $i++) {
    echo '<h3 class="urltitle"><a href="' . $queryResult[$i]['webURI'] . '">' . $queryResult[$i]['webTitle'] . '</a></h2>';
    echo '<p class="urltext">' . $queryResult[$i]['webURI'] . '</p><br>';
}
?>



<?php
include_once('footer.php');
?>