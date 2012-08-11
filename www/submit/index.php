<?php
define('__ROOT__', "/var/www/judge/");
$_ACTIVE = "submit";
require_once __ROOT__ . "/php/head.php";

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

	function evaluate($f, $l, $code)
	{
		if ($l == "C++")
		{
			$output = $f . ".exe";
			$compileStatus = 0;
			$cmd = "g++-4.6 -O2 -DONLINE_JUDGE ".$f . " -o " . $output;
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
			error_log($err);
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
			if ($compileStatus != 2)
			{
					chroot("/var/www/");
					echo getcwd();
			}
		}
	}


	$fn = $_SUBMISSIONROOT . $code . "_" . $uname . "_" . time() . rand(1, 100) . $languageSuffix; 
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
	$("#paste_source_code").click(function() {
		event.preventDefault();
		$("#upload_source_code").removeClass("active");
		$(this).addClass("active");
		$("#isUpload").val("0");
		$("#upload_file").fadeOut(100, function() {
			$("#source_code").fadeIn();
		});
		$("#upload_file").val("");
	});
	$("#upload_source_code").click(function() {
		event.preventDefault();
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
		      							<option disabled="disabled">C</option>
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
		      					<button class="btn btn-info" onclick="">Submit</button>
								<button class="btn" id="reset_btn" type="button">Reset</button>
		      				</div>
	      				</fieldset>
      				</form>
      			</div>
        	</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>