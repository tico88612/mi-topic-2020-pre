<?php
include_once('head.php');
?>
<form id="myForm" action="query.php" method="get">
    <div class="field">
        <label for="query" class="label"></label>
        <div class="control">
            <input name="query" id="query" class="input" type="text" placeholder="輸入關鍵字..." onfocus="this.placeholder=''" onblur="this.placeholder='輸入關鍵字...'">
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
<?php
include_once('footer.php');
?>