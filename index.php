<?php
session_start();   
if(isset($_SESSION["user_id"])){
    header("location:dashboard.php");
};  

    include_once("include/header.php");
?>

<!-- <div class="content"> -->

<div class="content-body">


    <div class="f-wrapper">
        <div class="account-form">
            <div class="form-header">
                <h2>Login</h2>

            </div>
            <hr>

            <div class="form-components form-body">
                <div class="errors">
                    <span class="detail-error error"></span>
                </div>
                <div class="form-element">
                    <label for="email">Email:</label> <span class="uname-error error"></span>
                    <input type="text" name="email" id="email">
                </div>

                <div class="form-element">
                    <label for="password">Password:</label> <span class="pass-error error"></span>
                    <input type="password" name="password" id="password">
                </div>

            </div>
            <div class="form-footer">
                <button type="submit" class="button btn-save" id="login">Login</button>
            </div>
        </div>
    </div>
</div>
<style>

</style>

<?php
    include_once("js_includes.php");
?>
