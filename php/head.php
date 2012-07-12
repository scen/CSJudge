<?php
include_once "conf.php";
session_start();
if (!isset($_SESSION['loggedin']) && (!isset($bIsLoginPage) || !$bIsLoginPage))
{
	header('Location: '.$_ROOT.'login/');
}
?>