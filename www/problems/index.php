<?php
$_ACTIVE = "problems";
define('__ROOT__', "/var/www/judge");
require_once __ROOT__ . "/php/head.php";

$probs = array();

sql_init();

$categories = array();
$query = "SELECT * FROM categories;";
$ret = mysql_query($query);

if ($ret != false)
{
	while ($categories[] = mysql_fetch_assoc($ret)) 
	{
	}
}

$cid2name = array();

function getCatName($cid, &$categories)
{
	if (isset($cid2name[$cid])) return $cid2name[$cid];
	foreach ($categories as $cat) {
		if (intval($cat['cid']) == $cid)
		{
			return ($cid2name[$cid] = $cat['name']);
		}
	}
	return "N/A";
}


$cat = mysql_real_escape_string($_GET['category']);
$date = mysql_real_escape_string($_GET['date']);
$lvl = mysql_real_escape_string($_GET['level']);

$allprobs = array();

$query = "SELECT * FROM problems;";
error_log($query);
$ret = mysql_query($query);
if ($ret != false)
{
	while ($allprobs[] = mysql_fetch_assoc($ret)) {}
}

if (isset($_GET['category']) && $_GET['category'] != 'All')
{
	$query = "SELECT * FROM categories WHERE name='". $cat . "';";
	$ret = mysql_query($query);
	if ($ret != false)
	{
		$row = mysql_fetch_assoc($ret);
		$query = "SELECT * FROM problems WHERE cid=" . $row['cid'] .";";
		$ret2 = mysql_query($query);
		if ($ret2 != false)
		{
			while ($probs[] = mysql_fetch_assoc($ret2))
			{
			}
		}
	}
}
else
{
	$probs = $allprobs;
}



sql_clean();

?>
<div class="row-fluid" style="width: 98%; margin: 0 auto;">
	<h1>Problems</h1>
	<hr>
	<div id="search_form">
		<form id="submit_form" action="." method="GET" class="well">
			<fieldset>
				<!-- <div class="control-group">
					<label class="control-label" for="keywords">Search keywords:</label>
					<div class="controls">
						<input type="text" autocomplete="off" id="keywords" name="keywords" autocomplete="off">
					</div>
				</div> -->
				<div class="control-group">
					<label for="category" class="control-label">Category:</label>
					<div class="controls">
						<select id="category" name="category">
							<option<?php if ($_GET['category'] == 'All' || !isset($_GET['category'])) echo ' selected="selected" ';?>>All</option>
							<?php
							foreach ($categories as $c) {
								if (!isset($c['name'])) continue;
								$p = $_GET['category'];
								$s = "";
								if ($p == $c['name']) $s = ' selected="selected" ';
								echo '<option'.$s.'>'.$c['name'].'</option>';
							}
							?>
  						</select>
  						<label for="level" class="control-label">Level:</label>
  						<select id="level" name="level">
  						</select>
  						<label for="date" class="control-label">Date:</label>
  						<select id="date" name="date">
  						</select>
					</div>
				</div>
				<div class="controls">
					<label class="checkbox">
						<input type="checkbox" id="hide_solved" name="hide_solved">Hide solved problems</input>
					</label>
				</div>
				<br>
				<div class="controls">
  					<button class="btn btn-primary" onclick="">Search</button>
  					<button class="btn" onclick="">Reset</button>
  				</div>
			</fieldset>
		</form>
	</div>
	<div id="results">
		<table class="table table-striped table-bordered">
			<thead>
				<th style="width:14%;">Category</th>
				<th style="width:5%;">Level</th>
				<th style="width:8%;">Date</th>
				<th>Problem Name</th>
				<th style="width:6%;">Point Value</th>
				<th style="width:6%;">Solved by</th>
			</thead>
			<tbody>
				<?php
				foreach ($probs as $prob) {
					if (!isset($prob['pid'])) continue;
					echo "<tr>";
					echo "<td>" . getCatName($prob['cid'], $categories) . '</td>';
					echo "<td>" . ((isset($prob['level']) && !empty($prob['level'])) ? $prob['level'] : 'None') . '</td>';
					echo "<td>" . ((isset($prob['date']) && !empty($prob['date'])) ? $prob['date'] : 'None') . '</td>';
					echo "<td><a href='".$_ROOT."problem/".$prob['code']."'>" . $prob['name'] . '</a></td>';
					echo "<td>" . $prob['point'] . '</td>';
					echo "<td>" . $prob['solvers'] . '</td>';
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>