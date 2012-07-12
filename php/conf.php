<?php
include_once "mysql_info.php";
$_ROOT = "/judge/";

$g_con = NULL;

function sql_init()
{
	$g_con = mysql_connect($MYSQL_SERVER, $MYSQL_USERNAME, $MYSQL_PASSWORD);
	mysql_select_db($MYSQL_DB_NAME);
}

function sql_clean()
{
	mysql_close($g_con);
}

function bIsValidEmail($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function gethash($pwd)
{
	return hash("sha256", $SALT . $pwd . $SALT);
}

?>