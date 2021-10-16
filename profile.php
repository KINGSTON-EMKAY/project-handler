<?php
session_start();   
if(!isset($_SESSION["user_id"])){
    header("location:index.php");
};  
    include_once("include/header.php");
    include_once("include/sidebar.php");
    
?>

<div class="content">
    <div class="content-header">
        <div class="page-heading">
            <h2>My Profile</h2>
        </div>
        <div class="menu-button-wrapper">
            <div class="menu-button"></div>
        </div>


    </div>
    <div class="content-body">

    </div>


    <?php
    include_once("include/footer.php");
?>
