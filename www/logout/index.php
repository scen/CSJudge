<?php
define('__ROOT__', "/var/www/");
require_once __ROOT__ . "/php/conf.php";
session_start();
$_SESSION['username'] = "";
$_SESSION['loggedin'] = false;
unset($_SESSION['loggedin']);
$_SESSION = array();
session_destroy();
header("Location: " . $_ROOT);
?>