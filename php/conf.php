<?php
include_once "mysql_info.php";
$_ROOT = "/judge/";
$_PROBLEMROOT = "/home/stanleyc/westviewcs/problems/";
$_SUBMISSIONROOT = "/home/stanleyc/westviewcs/submissions/";
$g_con = NULL;

function sql_init()
{
	$g_con = mysql_connect(_MYSQL_SERVER, _MYSQL_USERNAME, _MYSQL_PASSWORD);
	mysql_select_db(_MYSQL_DB_NAME);
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
	return hash("sha256", _MYSQL_SALT . $pwd . _MYSQL_SALT);
}

?>