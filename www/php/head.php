<?php
include_once "conf.php";

session_start();
if ((!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) && (!isset($bIsLoginPage) || !$bIsLoginPage))
{
	header('Location: '._ROOT.'login/');
}
else
{
	//LOGGING
	date_default_timezone_set("America/Los_Angeles");
}
function canview($sid)
{
	//get submission data
	$query = "SELECT * FROM submissions WHERE sid=".$sid.";"; //if this is user's submission he can view it
	$res = mysql_query($query);
	$sub = mysql_fetch_assoc($res);
	if ($sub['uid'] == $_SESSION['uid'])
	{
		return true;
	}
	if (mysql_num_rows($res) == 0) return false;
	$query = "SELECT * FROM submissions WHERE uid=".$_SESSION['uid']." AND pid=".$sub['pid'].";"; //If user has solved the problem he can view the status/source
	$res = mysql_query($query);
	while ($row = mysql_fetch_assoc($res))
	{
		if ($row['res'] == 'AC')
		{
			return true;
		}
	}
	return false;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php if (isset($_TITLE)) echo $_TITLE; else echo 'WCS Judge'; ?></title>
		<meta name="description" content="Contest problem grader">
		<meta name="author" content="stanley cen">
		<link href="<?php echo $_ROOT; ?>bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo $_ROOT; ?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="<?php echo $_ROOT; ?>css/main.css" rel="stylesheet">
		<link href="<?php echo $_ROOT; ?>gcp/prettify.css" type="text/css" rel="stylesheet" />
		<script src="<?php echo $_ROOT; ?>js/main.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo $_ROOT; ?>bootstrap/js/bootstrap-dropdown.js" type="text/javascript"></script>
		<script src="<?php echo $_ROOT; ?>js/jquery.blockUI.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo $_ROOT; ?>gcp/prettify.js"></script>
		<script src="bootstrap/js/bootstrap-collapse.js" type="text/javascript"></script>
		<script type="text/javascript">
		$(document).ready(function()
		{
			window.prettyPrint && prettyPrint();
		});
		</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4253037-7']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            			<span class="icon-bar"></span>
            			<span class="icon-bar"></span>
            			<span class="icon-bar"></span>
          			</a>
			        <!--Replace this "logo" -->
			        <a class="brand" href="#">WCS Judge</a>
			        <div class="btn-group pull-right">
			        	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
			        		<i class="icon-user"></i>
			        		<?php echo $_SESSION['username']; ?>
			        		<span class="caret"></span>
			        	</a>
			        	<ul class="dropdown-menu">
			        		<li><a href="#">My Account</a></li>
			        		<li><a href="#">My Submissions</a></li>
				            <li class="divider"></li>
				            <li><a href="<?php echo $_ROOT;?>logout/">Sign out</a></li>
			        	</ul>
			        </div>
			        <div class="nav-collapse">
			        	<ul class="nav">
			        		<li<?php if($_ACTIVE == "index") echo ' class="active"';?>><a href="<?php echo $_ROOT; ?>"><!-- <i class="icon-home icon-white"></i> -->main</a></li>
			        		<li<?php if($_ACTIVE == "problems") echo ' class="active"';?>><a href="<?php echo $_ROOT; ?>problems/"><!-- <i class="icon-file icon-white"></i> -->problems</a></li>
			        		<li<?php if($_ACTIVE == "submissions") echo ' class="active"';?>><a href="<?php echo $_ROOT; ?>submissions/"><!-- <i class="icon-folder-open icon-white"></i> -->submissions</a></li>
			        		<li<?php if($_ACTIVE == "submit") echo ' class="active"';?>><a href="<?php echo $_ROOT; ?>submit/"><!-- <i class="icon-upload icon-white"></i> -->submit</a></li>
			        		<li<?php if($_ACTIVE == "contests") echo ' class="active"';?>><a href="<?php echo $_ROOT; ?>contests/"><!-- <i class="icon-book icon-white"></i> -->contests</a></li>
			        		<li<?php if($_ACTIVE == "users") echo ' class="active"';?>><a href="<?php echo $_ROOT; ?>users/"><!-- <i class="icon-user icon-white"></i> -->users</a></li>
			        		<li<?php if($_ACTIVE == "help") echo ' class="active"';?>><a href="<?php echo $_ROOT; ?>help/"><!-- <i class="icon-comment icon-white"></i> -->help</a></li>
			        		<li<?php if($_ACTIVE == "about") echo ' class="active"';?>><a href="<?php echo $_ROOT; ?>about/">about</a></li>
			        	</ul>
			        </div>
	          	</div>
	      	</div>
      	</div>
      	<div class="container-fluid addpad">