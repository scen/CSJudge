<?php
$_ACTIVE = "none";
define('__ROOT__', "/var/www/judge");
require_once __ROOT__ . "/php/conf.php";

sql_init();

if (isset($_POST['submitted']) && $_POST['submitted'] == "1")
{
	$query = "SELECT * FROM categories WHERE name='" . mysql_real_escape_string($_POST["cat"]) . "';";
	$res = mysql_query($query);
	if (mysql_num_rows($res) == 1)
	{
		$row = mysql_fetch_assoc($res);
		$id = $row["cid"];
		$query = sprintf("INSERT INTO `problems`(`cid`, `level`, `code`, `date`, `solvers`, `name`, `point`, `timelimit`, `memlimit`) VALUES (%d,'%s','%s','%s',0,'%s',%d,%d,%d)", $id, mysql_real_escape_string($_POST["level"]), mysql_real_escape_string($_POST["shortname"]), mysql_real_escape_string($_POST["date"]), mysql_real_escape_string($_POST["name"]), intval($_POST["points"]), intval($_POST['timelim']), intval($_POST['memlim']));
		mysql_query($query);
		//create problem file
		$path = _PROBLEMROOT . $_POST["shortname"] . "/";
		mkdir($path);
		$fp = fopen($path."problem.txt", "w");
		fwrite($fp, $_POST["probtext"]);
		fclose($fp);
		die("success");
	}
	else die("error");
}

?>

<form action="." method="POST">
	<input type="hidden" name="submitted" id="submitted" value="1">
	<input type="text" name="cat" id="cat" placeholder="category">
	<input type="text" name="date" id="date" placeholder="date">
	<input type="text" name="level" id="level" placeholder="level">
	<input type="text" name="points" id="points" placeholder="points">
	<input type="text" name="timelim" id="timelim" placeholder="timelim">
	<input type="text" name="memlim" id="memlim" placeholder="memlim">
	<input type="text" name="name" id="name" placeholder="name">
	<input type="text" name="shortname" id="shortname" placeholder="shortname">
	<textarea placeholder="probtext" id="probtext" name="probtext" style="width: 500px; height: 500px;"></textarea>
	<input type="submit" value="submit">
</form>

<?php
require_once __ROOT__ . "/php/foot.php";
?>