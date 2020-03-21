<?php
include_once('head.php');
?>
<!-- <form action="" method="post"> -->
    <div class="field">
    <label for="inputURi" class="label">網址</label>
    <div class="control">
        <input name="inputURi" id="inputURi" class="input" type="text" placeholder="E.g. https://...">
    </div>
    <!-- <p class="help">This is a help text</p> -->
    </div>
    <div class="field is-grouped">
    <div class="control">
        <button class="button is-link" onclick="submitPOST();">新增</button>
    </div>
    <div class="control">
        <button class="button is-link is-light">Cancel</button>
    </div>
    </div>
<!-- </form> -->

    <script>
    
    function submitPOST(){
        Swal.showLoading();
        let s = document.getElementById('inputURi').value;
        let data = {uri: s};
        let response = fetch(`/inserturi.php`, {
            method: 'POST', // or 'PUT'
            body: JSON.stringify(data), // data can be `string` or {object}!
            headers: new Headers({
                'Content-Type': 'application/json'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.statusText)
            }
            return response.json()
        })
        .catch(error => {
            Swal.showValidationMessage(
            `Request failed: ${error}`
            )
        })
        .then(responseJson => {
            Swal.hideLoading();
            console.log(responseJson);
            if(responseJson["HTTP_STATUS_CODE"] == 200){
                Swal.fire(
                '成功！',
                'Your URL has been added.',
                'success'
                );
            }
            else{
                Swal.fire(
                'HTTP Code:' + responseJson["HTTP_STATUS_CODE"],
                responseJson["Message"],
                'error'
                );
            }
        })
        
    }
    </script>

<?php
include_once 'footer.php';
?>
