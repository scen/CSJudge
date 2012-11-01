<?php
$_ACTIVE = "problems";
define('__ROOT__', "/var/www/judge");
require_once __ROOT__ . "/php/conf.php";

if (!isset($_GET['name']))
{
	header("Location: " . $_ROOT."problems/");
}

sql_init();

$prob = array();

$name = mysql_real_escape_string($_GET['name']);

$query = "SELECT * from problems where code='" . $name . "';";
$ret = mysql_query($query);
if (!$ret || mysql_num_rows($ret) == 0)
{
	header("Location: " . $_ROOT."problems/"); //whatever..
}

$prob = mysql_fetch_assoc($ret);

$query = "SELECT * FROM categories WHERE cid=" . $prob['cid'] . ";";
$ret = mysql_query($query);
$res = mysql_fetch_assoc($ret);

$fmtstring = $res['name'];
if (isset($prob['date']) && !empty($prob['date']))
{
	$fmtstring .= ", " . $prob['date'];
}
if (isset($prob['level']) && !empty($prob['level']))
{
	$fmtstring .= ", " . $prob['level'];
}

$_TITLE = "WCS Judge - " . $prob['name'];

require_once __ROOT__ . "/php/head.php";
?>
<div class="row-fluid" style="width: 98%; margin: 0 auto;">
	<h2><?php echo $prob['name']; ?></h2>
	<h6><?php echo $fmtstring; ?></h6>
</div>
<div class="row-fluid problem-section" style="width: 98%; margin: 0 auto;">
		<div class="span10">
			<pre><?php
				$path = _PROBLEMROOT . $prob['code'] . "/" . "problem.txt";
				$fp = fopen($path, "r");
				if (!$fp)
				{
					echo 'ERROR';
				}
				$size = filesize($path);
				if ($prob["href_text"] != "")
				{
					echo "<a href='"._ROOT."res/".$prob['url_to_problem']."'>".$prob["href_text"]."</a><br/><br/>";
				}
				echo (fread($fp, $size));
				fclose($fp);
				?></pre>
		</div>
		<div class="span2">
			<div class="well" style="padding: 8px 0;">
				<ul class="nav nav-list">
					<li class="nav-header">
						Actions
					</li>
					<li>
						<a href="<?php echo _ROOT."submit/".$prob['code']; ?>"><i class="icon-upload"></i> Submit Solution</a>
					</li>
					<li class="nav-header">
						View Actions
					</li>
					<li>
						<a href="#"><i class="icon-folder-open"></i> My Submi1ssions</a>
					</li>
					<li>
						<a href="#"><i class="icon-thumbs-up"></i> Best Submissions</a>
					</li>
					<li>
						<a href="#"><i class="icon-globe"></i> All Submissions</a>
					</li>
					<li>
						<a href="#"><i class="icon-eye-open"></i> Analysis</a>
					</li>
				</ul>
			</div>
			<div class="well" id="problem_info">
				<p style="display:inline;">
					<strong>Point Value: </strong> <?php echo $prob['point'];?><br>
					<strong>Time Limit:</strong> <?php echo $prob['timelimit'].'s';?><br>
					<strong>Memory Limit:</strong> <?php echo $prob['memlimit'].'M';?>
				</p>
			</div>
		</div>
</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>