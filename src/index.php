<?php
include_once('head.php');
?>
<form action="query.php" method="get">
    <div class="field">
    <label for="query" class="label"></label>
    <div class="control">
        <input name="query" id="query" class="input" type="text" placeholder="輸入關鍵字">
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
    

<?php
include_once('footer.php');
?>