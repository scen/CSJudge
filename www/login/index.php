<?php
define('__ROOT__', "/var/www/judge");
require_once __ROOT__ . "/php/mysql_info.php";
require_once __ROOT__ . "/php/conf.php";
session_start();

$errors = array();
$usernameError = false;
$passwordError = false;
$emailError = false;
$registerError = false;


if (isset($_POST['isRegister'] )&& $_POST['isRegister'] == "1")
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	if (strlen($username) > 20 || !ctype_alnum($username) || strlen($username) == 0)
	{
		$errors[] = "Username < 20 alphanum chars.";
		$usernameError = true;
	}
	if (strlen($password) > 50|| strlen($password) == 0)
	{
		$errors[] = "Password < 50 ASCII chars.";
		$passwordError = true;
	}
	if (!bIsValidEmail($email) || strlen($email) > 50|| strlen($email) == 0)
	{
		$errors[] = "Please input a valid email address.";
		$emailError = true;
	}
	sql_init();
	if (!$usernameError)
	{

		$query = "SELECT * FROM users WHERE username='".mysql_real_escape_string($username)."';";
		$res = mysql_query($query);
		if (mysql_num_rows($res) != 0)
		{
			$usernameError = true;
			$errors[] = "That username is already taken.";
		}
	}

	$registerError = $usernameError || $passwordError || $emailError;
	if (!$registerError)
	{
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$email = mysql_real_escape_string($email);
		$query = 'INSERT INTO users (username, password, email) VALUES ("'.$username.'","'.gethash($password).'", "'.$email.'");';
		$res = mysql_query($query);
		if (!$res)
		{
			$errors[] = "An unknown error occured.";
			$registerError = true;
		}
		else
		{

		}
		sql_clean();
	}
}
else if(isset($_POST['isRegister']) && $_POST['isRegister'] == "0")
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	if (strlen($username) > 20 || !ctype_alnum($username))
	{
		$errors[] = "Username < 20 alphanum chars.";
		$usernameError = true;
	}
	if (strlen($password) > 50)
	{
		$errors[] = "Password < 50 ASCII chars.";
		$passwordError = true;
	}
	if (!$usernameError && !$passwordError)
	{
		sql_init();
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$query = 'SELECT * FROM users WHERE username="'.$username.'" AND password="'.gethash($password).'";';
		$res = mysql_query($query);
		if (mysql_num_rows($res) == 1)
		{
			$assoc = mysql_fetch_assoc($res);
			if ($assoc['verified'] == false)
			{
				$errors[] = "Please wait until you're verified.";
			}
			else
			{
				$_SESSION['loggedin'] = true;
				$_SESSION['username'] = $username;
				$_SESSION['uid'] = $assoc['id'];
				$_SESSION['admin'] = $assoc['admin'];
				header("Location: ".$_ROOT);
			}
		}
		else
		{
			$errors[] = "Invalid credentials.";
			$usernameError = $passwordError = false;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login</title>
		<meta name="description" content="Contest problem grader">
		<meta name="author" content="stanley cen">
		<link href="<?php echo $_ROOT; ?>bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo $_ROOT; ?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="<?php echo $_ROOT; ?>css/main.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo $_ROOT; ?>js/main.js" type="text/javascript"></script>
		<script src="<?php echo $_ROOT; ?>bootstrap/js/bootstrap-dropdown.js" type="text/javascript"></script>
		<script type="text/javascript">
		var isLogin = true;
		$(document).ready(function() {

			function recenter()
			{
				var w = $(window).width();
				var h = $(window).height();
				var form = $("#login_form");
				form.css("position", "absolute").css("left", ((w / 2) - (form.width() / 2)) + "px").css("top", ((h / 2) - (form.height() / 2) - (form.height() / 4)) + "px");
			}
			function registerOnClick()
			{
				$("#email_wrapper").fadeIn();
				$("#register_btn").fadeOut();
				$("#submit").text("Sign up")
				$("#isRegister").val("1");
				$("#username").focus();
				isLogin = false;
				recenter();
			}
			$(window).resize(function() {
				recenter();
			});
			recenter();
			$("#login_form").css("display", "inline");
			$("#username").focus();
			$("#register_btn").click(function() {
				registerOnClick();
			});
			<?php if ($registerError) echo "registerOnClick();"; ?>
			$("#login_form").submit(function(e) {
			});
		});
		</script>
	</head>
	<body class="login_body">
		<div id="center_form">
			<div class="span4">
				<form class="well" id="login_form" action="." method="POST">
					<div id="login_title"><h1>Westview CS Judge</h1></div>
					<?php
					if (count($errors) > 0)
					{
					?>
					<div id="error_log" class="alert alert-error">
						<ul>
						<?php 
						foreach ($errors as $str) {
							echo '<li>'.$str.'</li>';
						}
						?>
						</ul>
					</div>
					<?php
					}
					?>
					<div class="input-prepend <?php if ($usernameError) echo 'control-group error';?>">
						<span class="add-on"><i class="icon-user"></i></span><input id="username" name="username" type="text" class="span3" placeholder="username" autocomplete="off" value="<?php echo $_POST['username']; ?>">
					</div>
					<div class="input-prepend <?php if ($passwordError) echo 'control-group error';?>">
						<span class="add-on"><i class="icon-edit"></i></span><input id="password" name="password" type="password" class="span3" placeholder="password">
					</div>
					<div class="input-prepend <?php if ($emailError) echo 'control-group error';?>" id="email_wrapper" style="display:none">
						<span class="add-on" ><i class="icon-globe"></i></span><input id="email" name="email" type="text" class="span3" placeholder="email@example.com" autocomplete="off" value="<?php echo $_POST['email']; ?>">
					</div>
					<input type="hidden" name="isRegister" id="isRegister" value="0">
					<div id="buttons">
						<button class="btn" id="register_btn" type="button">Register</button>
						<button id="submit" type="submit" class="btn btn-primary">Sign in</button>
					</div>
					<div class="copyright"><p style="margin-bottom: 0px; padding-bottom: 0px;">Project hosted <a href="http://stanleycen.com/project/westview-coders/">here</a></p><p style="margin-bottom: 0px; padding-bottom: 0px;">Copyright 2012 - 2013 &copy; Stanley Cen</p>
						
					</div>
				</form>
			</div>
		</div>
	</body>
</html>	
