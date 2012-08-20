<?php
$_ACTIVE = "submissions";
define('__ROOT__', "/var/www/");
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
$curpage = $curpage > $cnt ? $cnt : $curpage;
$numpages = intval($cnt / $numPerPage) + 1;
if ($curpage > $numpages) 
{
	header("Location: " . $numpages);
	die();
}
$navstring = '<li><a href="1">First</a>';
if (1 != $curpage)
	$navstring .= '</li><li><a href="'.($curpage - 1).'">«</a></li>';

//set center. we put the initial center @ the current page, then we  slide the range right until the left endpoint > 0
$curleft = $curpage - ($numPaginatePages / 2);
$curright = $curpage + ($numPaginatePages / 2);
while ($curleft < 1)
{
	$curleft++;
	$curright++;
}
$curright = min($numpages, $curright);

for ($i = $curleft; $i <= $curright; $i++)
{
	$navstring .= '<li'. ($i == $curpage ? ' class="active"' : '' ).'><a href="'.$i.'">'.$i.'</a></li>';
}

if ($curpage != $numpages)
{
	$navstring.='<li><a href="'.($curpage + 1).'">»</a></li>';
}
$navstring .= '<li><a href="'.$numpages.'">Last</a></li>';

$offset = ($curpage - 1) * $numPerPage;
?>
<style>
span.scorecard {
	-webkit-border-horizontal-spacing: 0px;
-webkit-border-image: none;
-webkit-border-vertical-spacing: 0px;
-moz-border-horizontal-spacing: 0px;
-moz-border-image: none;
-moz-border-vertical-spacing: 0px;
background-color: #F7F7F9;
border-bottom-color: #E1E1E8;
border-bottom-left-radius: 3px;
border-bottom-right-radius: 3px;
border-bottom-style: solid;
border-bottom-width: 1px;
border-collapse: separate;
border-left-color: #E1E1E8;
border-left-style: solid;
border-left-width: 1px;
border-right-color: #E1E1E8;
border-right-style: solid;
border-right-width: 1px;
border-top-color: #E1E1E8;
border-top-left-radius: 3px;
border-top-right-radius: 3px;
border-top-style: solid;
border-top-width: 1px;
display: inline;
font-family: Menlo, Monaco, Consolas, 'Courier New', monospace;
font-size: 13px;
height: auto;
line-height: 18px;
margin-bottom: 0px;
margin-left: 0px;
margin-right: 0px;
margin-top: 0px;
max-width: none;
padding-bottom: 2px;
padding-left: 4px;
padding-right: 4px;
padding-top: 2px;
text-align: left;
vertical-align: baseline;
width: auto;
}
</style>
<div class="row-fluid" style="width: 98%; margin: 0 auto;">
	<h1>Submissions</h1><hr>
	<p>
		See <code>help</code> for a description of this table and a legend of the symbols &amp; abbreviations used. You will be able to see your own source code as well as code submitted by other users who have <em>solved</em> the same problem.
	</p>
	<br>
	<div class="pagination-centered pagination">
	<ul>
		<?php echo $navstring; ?>
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
		$query = "SELECT * FROM submissions ORDER BY `sid` DESC LIMIT ".$offset.",".$numPerPage.";";
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
			$icons = canview($sub['sid']) ? '<div style="text-align:center;margin: 0px auto !important;"><p style="display:inline;margin: 0px auto !important;"><a href="status/'.$sub['sid'].'"><i class="icon-info-sign"></i></a> <a href="source/'.$sub['sid'].'"><i class="icon-file"></i></a> </p>' : 'N/A';
			if ($sub['res'] == 'AC') $classname="good_submission";
		echo ' 
		<tr>
			<td class="'.$classname.' date_submission"><p class="date_submission">'.$sub['date'].'</p><br><p class="time_submission">'.$sub['time'].'</p></td>
			<td class="'.$classname.'">'.$user['username'].'</td>
			<td class="'.$classname.'">'.$prob['name'].'</td>
			<td class="'.$classname.'">'.$sub['res'].'</td>
			<td class="'.$classname.'"><p style="display: inline;"><span class="scorecard">'.$sub['scorecard'].'</span></p></td>
			<td class="'.$classname.'">'.number_format($sub['cpu'], 3).'s</td>
			<td class="'.$classname.'">'.number_format($sub['mem'], 2).'M</td>
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