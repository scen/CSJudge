<?php
$_ACTIVE = "submissions";
define('__ROOT__', "/var/www/judge/");
require_once __ROOT__ . "/php/head.php";

//settings
$numPerPage = 10;
$numPaginatePages = 10;

sql_init();
$curpage = 1;
if (isset($_GET['page']))
	$curpage = mysql_real_escape_string($_GET['page']);

//Count it
$query = "SELECT COUNT(*) AS NUM FROM submissions;";
$res = mysql_query($query);
$tmp = mysql_fetch_assoc($res);
$cnt = $tmp['NUM'];

$numpages = intval($cnt / $numPerPage) + 1;

$navstring = "";
if ($numpages == $curpage)
	$navstring .= ""

?>
<div class="row-fluid" style="width: 98%; margin: 0 auto;">
	<h1>Submissions</h1><hr>
	<p>
		See <code>help</code> for a description of this table and a legend of the symbols &amp; abbreviations used. You will be able to see your own source code as well as code submitted by other users who have <em>solved</em> the same problem.
	</p>
	<br>
	<div class="pagination-centered pagination">
	<ul>
		<li><a href="?page=1">First</a></li>
		<li><a href="#">«</a></li>
		<li class="active"><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">»</a></li>
		<li><a href="#">Last</a></li>
	</ul>
	</div>
	<table class="table table-striped table-bordered">
	<thead>
		<th style="width:8%;">Date</th>
		<th style="width:10%;">User</th>
		<th style="width:40%;">Problem Name</th>
		<th style="width:3%;">Result</th>
		<th style="width:15%;">Scorecard</th>
		<th style="width:8%;">Time</th>
		<th style="width:8%;">Mem</th>
		<th style="width:8%;">Lang</th>
		<th>Actions</th>
	</thead>
	<tbody>
		<?php
		$query = "SELECT * FROM submissions ORDER BY `sid` DESC LIMIT 10;";
		$gres = mysql_query($query);
		$sub = 0;
		while ($sub = mysql_fetch_assoc($gres))
		{
			$query = "SELECT * FROM users where id=".$sub['uid'].';';
			$res = mysql_query($query);
			$user = mysql_fetch_assoc($res);
			$query = "SELECT * FROM problems where pid=".$sub['pid'].';';
			$res = mysql_query($query);
			$prob = mysql_fetch_assoc($res);
			$classname = "bad_submission";
			$icons = canview($sub['sid']) ? '<div style="text-align:center;margin: 0px auto !important;"><p style="display:inline;margin: 0px auto !important;"><a href="source/?id='.$sub['sid'].'"><i class="icon-file"></i></a>  <a href="status/?id='.$sub['sid'].'"><i class="icon-info-sign"></i></a></p>' : 'N/A';
			if ($sub['res'] == 'AC') $classname="good_submission";
		echo ' 
		<tr>
			<td class="'.$classname.' date_submission"><p class="date_submission">'.$sub['date'].'</p><br><p class="time_submission">'.$sub['time'].'</p></td>
			<td class="'.$classname.'">'.$user['username'].'</td>
			<td class="'.$classname.'">'.$prob['name'].'</td>
			<td class="'.$classname.'">'.$sub['res'].'</td>
			<td class="'.$classname.'"><p style="display: inline;">'.$sub['scorecard'].'</p></td>
			<td class="'.$classname.'">'.$sub['cpu'].'</td>
			<td class="'.$classname.'">'.$sub['mem'].'</td>
			<td class="'.$classname.'">'.$sub['lang'].'</td>
			<td class="'.$classname.'">'.$icons.'</div></td>
		</tr>';
		}
		?>

	</tbody>
	</table>
</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>