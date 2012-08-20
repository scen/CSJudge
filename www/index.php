<?php
$_ACTIVE = "index";
define('__ROOT__', "/var/www/");
require_once __ROOT__ . "/php/head.php";
?>
<div class="row-fluid" style="width: 98%; margin: 0 auto;">
	<h1>Main Page</h1>
	<hr>
	<div class="">
		<p>
		This is another problem grader, where you can solve and test an archive of problems. Your program is compiled and ran against many test cases instantly, giving you results immediately. To use this site, pick a problem or contest via the <code>problems</code> or <code>contests</code> link, solve the problem, and use the <code>submit</code> button on each problem's page (or use the button on top).
		<br><br>
		See the <code>help</code> page for more info.
		</p>
	</div><br>
	<h2>News</h2><hr>
	<div class="news">
		<h3>July 11, 2012</h3>
		<ul>
			<li>Login and index pages finished</li>
			<li>Revamped UI</li>
			<li>Sign in now works</li>
		</ul>
		<h3>July 8, 2012</h3>
		<ul>
			<li>Started construction on the site</li>
		</ul>
	</div>
</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>