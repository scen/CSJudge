<?php
define('__ROOT__', "/var/www/judge");
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

$query = "SELECT * FROM users WHERE id=".$submission['uid'].';';
$res = mysql_query($query);
$user = mysql_fetch_assoc($res);

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
      				<div class="span4">
	      				<table class="table table-striped table-bordered" style="width: 100%;">
							<tbody>
								<tr>
									<td style="width: 25%;"><strong>Problem</strong></td>
									<td><?php echo $prob['name'] . ' [' . $prob['code'] . ']'; ?></td>
								</tr>
								<tr>
									<td><strong>User</strong></td>
									<td><?php echo $user['username']; ?></td>
								</tr>
								<tr>
									<td><strong>Language</strong></td>
									<td><?php echo $submission['lang']; ?></td>
								</tr>
								<tr>
									<td><strong>Points Earned</strong></td>
									<td><?php echo $submission['res'] == 'AC' ? $prob['point'] : 0; ?></td>
								</tr>
								<tr>
									<td><strong>Submission Date</strong></td>
									<td><?php echo $submission['date'] . " " . $submission['time']; ?></td>
								</tr>
								<tr>
									<td class="<?php echo $submission['res'] == "AC" ? 'good_submission' : 'bad_submission'; ?>"><strong>Result</strong></td>
									<td class="<?php echo $submission['res'] == "AC" ? 'good_submission' : 'bad_submission'; ?>"><?php echo $submission['res']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="span2">
						<div class="well" style="padding: 8px 0; ">
							<ul class="nav nav-list">
								<li class="nav-header">
									Submission Actions
								</li>
								<li>
									<a href="<?php echo _ROOT . 'submissions/source/'.$sid; ?>"><i class="icon-file"></i> View Source Code</a>
								</li>
								<li class="nav-header">
									Problem Actions
								</li>
								<li>
									<a href="#"><i class="icon-eye-open"></i> Analysis</a>
								</li>
								<li>
									<a href="#"><i class="icon-hdd"></i> Download Data</a>
								</li>
								<li>
									<a href="#"><i class="icon-thumbs-up"></i> Best Submissions</a>
								</li>
								<li>
									<a href="#"><i class="icon-globe"></i> All Submissions</a>
								</li>
								<li>
									<a href="#"><i class="icon-folder-open"></i> My Submissions</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row-fluid" style="width: 98%; margin: 0 auto;">
	      			<?php if ($compilestatus == 2) { //error ?>
	      				<div class="span6">
	      					<h4 style="margin-bottom:12px;">Compile output:</h4>
	      					<pre><?php echo $submission['extrainfo'];?></pre>
	      				</div>
	      				<?php } else { ?>
	      					<div class="span6">
	      					<h4 style="margin-bottom:12px;">Detailed statistics:</h4>
	      					<table class="table table-striped table-bordered table-condensed" style="width: 100%;">
	      						<thead>
	      							<th style="width:15%;">Test Case #</th>
	      							<th>Result</th>
	      							<th>Time</th>
	      							<th>Mem</th>
	      						</thead>
	      						<?php
	      						$times = explode(",", $submission['all_cpu']);
	      						$mems = explode(",", $submission['all_mem']);
	      						$ress = explode(",", $submission['all_res']);
	      						for ($i = 0; $i < count($times) - 1; $i++)
	      						{
	      							echo "<tr>";
	      							echo "<td>".($i+1)."</td>";
	      							echo "<td>".$ress[$i]."</td>";
	      							echo "<td>".$times[$i]."s</td>";
	      							echo "<td>".$mems[$i]."M</td>";
	      						}
	      						?>
	      					</table>
	      				</div>

	      					<?php } ?>
 	      		</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>