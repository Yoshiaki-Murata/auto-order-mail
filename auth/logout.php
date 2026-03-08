<?php
session_start();
require_once __DIR__ . "/../config/function.php";
check_login();

if(isset($_SESSION["id"])){
    $_SESSION=array();
    session_destroy();
}
header("location:login.php");
exit();
?>