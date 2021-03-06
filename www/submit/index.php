<?php
define('__ROOT__', "/var/www/judge");
$_ACTIVE = "submit";
require_once __ROOT__ . "/php/head.php";

date_default_timezone_set("America/Los_Angeles");

function getcurdate()
{
	return date("M j, Y");
}

function getcurtime()
{
	return date("g:i:sa");
}


$error = "";

if (isset($_POST['isUpload']))
{
	$uname = $_SESSION['username'];
	sql_init();
	
	$lang = mysql_real_escape_string($_POST['prog_lang']);
	$code = mysql_real_escape_string($_POST['problem_code']);

	$query = "SELECT * FROM users WHERE username='".$uname."';";
	$res = mysql_query($query);
	$user = mysql_fetch_assoc($res);

	$query = "SELECT * FROM problems WHERE code='".$code."';";
	$res = mysql_query($query);
	if (mysql_num_rows($res) == 0)
	{
		//Error...
		die();
	}
	$prob = mysql_fetch_assoc($res);
	$pid = $prob['pid'];

	//Credits: php.net
	function make_seed()
	{
	  list($usec, $sec) = explode(' ', microtime());
	  return (float) $sec + ((float) $usec * 100000);
	}
	srand(make_seed());

	$uid = intval($user['id']);

	$languageSuffix = ".cpp";
	if ($lang == "C") $languageSuffix = ".c";

	function bypass_copy($from, $to)
	{
		//bypass the copying
		$dst = fopen($to, "w");
		$src = fopen($from, "r");
		$sz = filesize($from);
		$str = fread($src, $sz);
		fwrite($dst, $str);
		fclose($dst);
		fclose($src);
		chmod($to, 0777);
	}

	function evaluate($f, $l, $code)
	{
		global $pid;
		global $prob;
		if ($l == "C++")
		{
			$output = $f . ".exe";
			$compileStatus = 0;
			$cmd = "g++-4.6 -static -O2 -DONLINE_JUDGE ".$f . " -o " . $output;
			$desc = array(
				0 => array("pipe", "r"),
				1 => array("pipe", "w"),
				2 => array("pipe", "w")
			);
			$pipes = array();
			$res = proc_open($cmd, $desc, $pipes);
			$err = "";
			$status = proc_get_status($res);
			while ($status['running'])
			{
				$status = proc_get_status($res);
				$err .= fread($pipes[2], 1);
			}
			fclose($pipes[0]);
			fclose($pipes[1]);
			fclose($pipes[2]);
			$retval = proc_close($res);
			$compileStatus = 2; //default to an error
			$err = str_replace($f, $code . ".cpp", $err);
			if ($retval != 0)
			{
				//Compile error or warning.
				//If exe file exists it was a warning otherwise it's an error
				if (file_exists($output))
				{
					//warning
					$compileStatus = 1;
				}
				else
				{
					$compileStatus = 2;
				}
			}

			//Count testcases
			$cur = 1;
			$inputfiles = array();
			$outputfiles = array();
			for (; $cur < 100; $cur++)
			{
				if (file_exists(_PROBLEMROOT . $code . "/" . $cur . ".in") && file_exists(_PROBLEMROOT . $code . "/" . $cur . ".out"))
				{
					$inputfiles[] = _PROBLEMROOT . $code . "/" . $cur.".in";
					$outputfiles[] = _PROBLEMROOT . $code . "/" . $cur.".out";
				}
				else
					break;
			}
			for ($cur = 1; $cur < 100;$cur++)
			{
				if (file_exists(_PROBLEMROOT . $code . "/" . $code.".".$cur . ".in") && file_exists(_PROBLEMROOT . $code . "/".$code."." . $cur . ".out"))
				{
					$inputfiles[] = _PROBLEMROOT . $code . "/" . $code.".".$cur.".in";
					$outputfiles[] = _PROBLEMROOT . $code . "/" . $code.".".$cur.".out";
				}
				else
					break;
			}
			for ($cur = 1; $cur < 100;$cur++)
			{
				if (file_exists(_PROBLEMROOT . $code . "/" ."I.".$cur) && file_exists(_PROBLEMROOT . $code . "/". "O.".$cur))
				{
					$inputfiles[] = _PROBLEMROOT . $code . "/". "I.".$cur;
					$outputfiles[] = _PROBLEMROOT . $code . "/". "O.".$cur;
				}
				else
					break;
			}
			for ($cur = 1; $cur < 100;$cur++)
			{
				if (file_exists(_PROBLEMROOT . $code . "/" . $code."-".$cur . ".in") && file_exists(_PROBLEMROOT . $code . "/".$code."-" . $cur . ".out"))
				{
					$inputfiles[] = _PROBLEMROOT . $code . "/" . $code."-".$cur.".in";
					$outputfiles[] = _PROBLEMROOT . $code . "/" . $code."-".$cur.".out";
				}
				else
					break;
			}
			if ($compileStatus != 2)
			{
				//lock the jail to prevent another instance of the grader from using it
				$seph = sem_get(_SEMAPHORE_KEY);
				sem_acquire($seph);//blocks if necessary

				chmod($output, 777);
				$finalCodeResult = "AC";
				$finalScorecard = "";
				$maxTime = 0;
				$maxMem = 0;
				$all_cpu = "";
				$all_mem = "";
				$all_res = "";
				for ($i = 0; $i < count($inputfiles); $i++)
				{
					$inputf = $inputfiles[$i];
					$outputf = $outputfiles[$i];
					bypass_copy($inputf, _RUNJAIL . "input");
					bypass_copy($output, _RUNJAIL . "exe");
					$cmd = "echo '1' | sudo -S " . _SUPERVISOR . " --cpu " . $prob['timelimit'] . " --mem " . ($prob['memlimit'] * 1024) . " --inputfile input --outputfile " .  $outputf .
						" --chroot " . _GRADERJAIL . " --exec exe";
					$desc = array(
						0 => array("pipe", "r"),
						1 => array("pipe", "w"),
						2 => array("pipe", "w")
					);
					$pipes = array();
					$res = proc_open($cmd, $desc, $pipes);
					$graderResult = "";
					$status = proc_get_status($res);
					while ($status['running'])
					{
						$status = proc_get_status($res);
						$graderResult .= fread($pipes[1], 100);
						usleep(1000); //2ms
					}
					fclose($pipes[0]);
					fclose($pipes[1]);
					fclose($pipes[2]);

					$priorities = array("RE" => 1,
										"TLE" => 2,
										"MLE" => 3,
										"WA" => 4,
										"OLE" => 5);

					$retval = proc_close($res);
					list($codeResult, $elapsed, $mem, $cpu, $millis) = sscanf($graderResult, "%s %d %d %d %d");

					$maxTime = max(floatval($millis) / 1000, $maxTime);
					$maxMem = max($maxMem, floatval($mem) / 1024);

					$all_cpu .= number_format(floatval($millis) / 1000, 3) . ",";
					$all_mem .= number_format(floatval($mem) / 1024, 2) . ",";
					$all_res .= $codeResult . ",";
					if ($codeResult == "AC")
						$finalScorecard .= "*";
					else if ($codeResult == "WA")
					{
						$finalScorecard .= "x";
					}
					else if ($codeResult == "TLE")
					{
						$finalScorecard .= "t";
					}
					else if ($codeResult == "RE" || $codeResult == "MLE")
					{
						$finalScorecard .= "s";
					}
					if ($finalCodeResult == "AC" && $codeResult != "AC")
					{
						$finalCodeResult = $codeResult;
					}
					else if ($codeResult != 'AC' && $priorities[$finalCodeResult] > $priorities[$codeResult])
					{
						$finalCodeResult = $codeResult;
					}

					if ((($i + 1) % 5) == 0)
						$finalScorecard .= " ";
				}
				$query = "INSERT INTO `submissions`(`lang`, `date`, `time`, `uid`, `pid`, `res`, `pts`, `scorecard`, `compile_status`, `path_submit`, `cpu`, `mem`, `all_cpu`, `all_mem`, `all_res`) VALUES " . "('C++','".getcurdate()."','".getcurtime()."',".$_SESSION['uid'].",".$pid.","."'".$finalCodeResult."',0,"."'".$finalScorecard."',".$compileStatus.",'".$f."',".$maxTime.",".$maxMem.",'".$all_cpu."','".$all_mem."','".$all_res."');";//,'".$escerr."');";
				$res = mysql_query($query);
				$query = "SELECT LAST_INSERT_ID() as id;";
				$res2 = mysql_query($query);
				$tab = mysql_fetch_assoc($res2);
				if ($finalCodeResult == "AC")
				{
					$query = "SELECT * FROM `submissions` WHERE res='AC' AND uid=".$_SESSION['uid']." AND `pid`=".$pid.";";
					$res = mysql_query($query);
					if (mysql_num_rows($res) == 1) //just submitted one as AC
					{
						$query = "UPDATE `problems` SET solvers=solvers+1 WHERE pid=".$pid.";";
						mysql_query($query);
						//increase points
						$query = "UPDATE `users` SET points=points+".$prob['point']." WHERE id=".$_SESSION['uid'].";";
						mysql_query($query);
					}
				}
				sem_release($seph); //don't forget to release it...
				header("Location: "._ROOT."submissions/status/".$tab['id']);
				die();
			}
			else
			{
				//compile error
				$gen = "";
				for ($i = 0; $i < count($inputfiles); $i++)
				{
					$gen .= "c";
					if ((($i + 1) % 5) == 0)
						$gen .= " ";
				}
				$escerr = mysql_real_escape_string($err);
				$query = "INSERT INTO `submissions`(`lang`, `date`, `time`, `uid`, `pid`, `res`, `pts`, `scorecard`, `compile_status`, `path_submit`, `extrainfo`) VALUES " . "('C++','".getcurdate()."','".getcurtime()."',".$_SESSION['uid'].",".$pid.","."'CE',0,"."'".$gen."',".$compileStatus.",'".$f."','".$escerr."');";
				$res = mysql_query($query);
				$query = "SELECT LAST_INSERT_ID() as id;";
				$res2 = mysql_query($query);
				$tab = mysql_fetch_assoc($res2);
				header("Location: "._ROOT."submissions/status/".$tab['id']);
				die();
			}
			//remove the file
			if (file_exists($output))
				unlink($outout);
		}
	}


	$fn = _SUBMISSIONROOT . $code . "_" . $uname . "_" . time() . rand(1, 100) . $languageSuffix; 
	if ($_POST['isUpload'] == '1')
	{
		if ($_FILES['upload_file']['size'] / 1024 > 1000) //1 megajit
		{
			$error = "Your file was larger than 1 MB, please try again.";
		}
		else
		{
			if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $fn)) 
			{
				evaluate($fn, $lang, $code);
			}
			else
			{
				//some error occured
				$error = "An error occured, please try again.";
			}
		}
	}
	else
	{
		$src = $_POST['source_code'];
		$fp = fopen($fn, "w");
		if ($fp)
		{
			fwrite($fp, $src);
			fclose($fp);
			evaluate($fn, $lang, $code);
		}
		else
		{
			$error = "An error occured, please try again.";
		}
	}
}

?>
<script type="text/javascript">
$(document).ready(function()
{
	$("#paste_source_code").click(function(e) {
		e.preventDefault();
		$("#upload_source_code").removeClass("active");
		$(this).addClass("active");
		$("#isUpload").val("0");
		$("#upload_file").fadeOut(100, function() {
			$("#source_code").fadeIn();
		});
		$("#upload_file").val("");
	});
	$("#upload_source_code").click(function(e) {
		e.preventDefault();
		$("#paste_source_code").removeClass("active");
		$(this).addClass("active");
		$("#isUpload").val('1');
		$("#source_code").fadeOut(100, function() {
			$("#upload_file").fadeIn();
		});
		$("#source_code").val("");
	});
	$("#reset_btn").click(function() {
		$("#source_code").val("");
		$("#upload_file").val("");
		$("#paste_source_code").removeClass("active");
		$("#upload_source_code").addClass("active");
		$("#source_code").fadeOut(100, function() {
			$("#upload_file").fadeIn();
		});
		$("#problem_code").val("");
	});
	$("#submit_form").submit(function(e)
	{
		// e.preventDefault && e.preventDefault();
		// e.stopPropagation && e.stopPropagation();
		$("#submit_button").attr("disabled", "true");
		$.blockUI({
			css: {
				border: 'none',
				padding: '15px',
				backgroundColor: "#000",
				'-webkit-border-radius': '10px', 
	            '-moz-border-radius': '10px', 
	            opacity: .5, 
	            color: '#fff' 
			},
			message: "Please wait while your submission is queued and graded..."
		});
		// return false;
	});
});
</script>
      		<div class="row-fluid" style="width: 98%; margin: 0 auto;">
      			<h1>Submit a solution</h1>
      			<hr>
      			<div class="">
      				<p>
      				Make sure you've read and understood the <code>help</code> page before submitting any solutions.
      				</p>
      			</div><br>
      			<div id="submit_form_wrap">
      				<?php if (!empty($error)) echo'
      				<div class="alert alert-error">
      					'.$error.'
      				</div>';?>
      				<form id="submit_form" action="." method="POST" class="form-horizontal well" enctype="multipart/form-data">
      					<fieldset>
      						<div class="control-group">
		      					<label class="control-label" for="problem_code">Problem code: <abbr title="The alphanumeric code text on each problem's page."><i class="icon-question-sign"></i></abbr></label>
		      					<div class="controls">
		      						<input type="text" autocomplete="off" id="problem_code" name="problem_code" value="<?php echo $_GET['name'];?>">
		      						<input type="hidden" id="isUpload" value="1" name="isUpload">
		      					</div>
		      				</div>
		      				<div class="control-group">
		      					<label class="control-label" for="prog_lang">Language:</label>
		      					<div class="controls">
		      						<select id="prog_lang" name="prog_lang">
		      							<option selected="selected">C++</option>
		      							<!-- <option disabled="disabled">C</option> -->
		      							<!-- <option>Java</option> -->
		      						</select>
		      						<p class="help-inline">
		      							<strong>Note:</strong> See <code>help</code> for all compile flags and preprocessor defines.
		      						</p>
		      					</div>
		      				</div>
		      				<div class="control-group">
								<label class="control-label" for="">Input Source Code:</label>
		      					<div class="controls">
		      						<ul class="nav nav-pills">
										<li class="active" id="upload_source_code"><a href="#">Upload Source Code</a></li>
										<li id="paste_source_code"><a href="#">Paste Source Code</a></li>
									</ul>
									<input class="input-file" id="upload_file" type="file" name="upload_file">
									<textarea id="source_code" placeholder=" source code..." name="source_code"></textarea>
		      					</div>
		      				</div>
		      				<div class="controls">
		      					<button class="btn btn-info" id="submit_button" onclick="">Submit</button>
								<button class="btn" id="reset_btn" type="button">Reset</button>
		      				</div>
	      				</fieldset>
      				</form>
      			</div>
        	</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>