<?php
include_once "mysql_info.php";
$_ROOT = '/judge/';
define("_ROOT", "/judge/");

define('_BASEDIR', "/home/vm/westviewcs/");

define('_PROBLEMROOT', _BASEDIR."problems/");
define('_SUBMISSIONROOT', _BASEDIR."submissions/");
define('_GRADERJAIL', _BASEDIR."jail/");
define('_RUNJAIL', _GRADERJAIL . "run/");
define('_SUPERVISOR', _BASEDIR."CSJudge/JailExec/safeexec");

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
	error_log(hash("sha256", _MYSQL_SALT . $pwd . _MYSQL_SALT));
	return hash("sha256", _MYSQL_SALT . $pwd . _MYSQL_SALT);
}

?>