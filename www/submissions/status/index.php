<?php
define('__ROOT__', "/var/www/judge/");
$_ACTIVE = "submissions";
require_once __ROOT__ . "/php/head.php";

if (!isset($_GET['id']))
{
	die("An unknown error occured");
}
sql_init();

$sid = mysql_real_escape_string($_GET['id']);
$query = "SELECT * FROM submissions where sid=".$sid.";";
$res = mysql_query($query);
if (mysql_num_rows($res) == 0 || !canview($sid))
{
	die("An unknown error occured or you're not permitted to see this page");
}
$submission = mysql_fetch_assoc($res);

$query = "SELECT * FROM problems WHERE pid=".$submission['pid'].';';
$res = mysql_query($query);
if (mysql_num_rows($res) == 0)
{
	die("An unknown error occured");
}
$prob = mysql_fetch_assoc($res);
$compilestatus = $submission['compile_status'];

?>
<script type="text/javascript">
</script>
<style>
.inlineh {
	display:inline !important;
}
</style>
      		<div class="row-fluid" style="width: 98%; margin: 0 auto;">
      			<h1>Detailed Submission Info</h1>
      		</div>
      			<hr>
      			<div class="row-fluid" style="width: 98%; margin: 0 auto;">
	      			<p><h4 class="inlineh">Problem: </h4><?php echo $prob['name'] . ' [' . $prob['code'] . ']'; ?></p>
	      			<p><h4 class="inlineh">Language: </h4><?php echo $submission['lang']; ?></p>
	      			<p><h4 class="inlineh">Result: </h4><code><?php echo $submission['res']; ?></code></p>
	      			<?php if ($compilestatus == 2) { //error ?>
	      				<div style="width:50%;">
	      					<h4 style="margin-bottom:12px;">Compile output:</h4>
	      					<pre><?php echo $submission['extrainfo'];?></pre>
	      				</div>
	      				<?php } else { ?>


	      					<?php } ?>
 	      		</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>