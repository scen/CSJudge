<?php
include_once "conf.php";
session_start();
if ((!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) && (!isset($bIsLoginPage) || !$bIsLoginPage))
{
	header('Location: '.$_ROOT.'login/');
}
?>