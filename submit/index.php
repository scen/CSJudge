<?php
define('__ROOT__', "/var/www/judge/");
$_ACTIVE = "submit";
require_once __ROOT__ . "/php/head.php";
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
      				<form id="submit_form" action="." method="POST" class="form-horizontal well">
      					<fieldset>
      						<div class="control-group">
		      					<label class="control-label" for="problem_code">Problem code: <abbr title="The alphanumeric code text on each problem's page."><i class="icon-question-sign"></i></abbr></label>
		      					<div class="controls">
		      						<input type="text" autocomplete="off" id="problem_code" name="problem_code">
		      						<input type="hidden" id="isUpload" value="1" name="isUpload">
		      					</div>
		      				</div>
		      				<div class="control-group">
		      					<label class="control-label" for="prog_lang">Language:</label>
		      					<div class="controls">
		      						<select id="prog_lang" name="prog_lang">
		      							<option selected="selected">C++</option>
		      							<option>C</option>
		      							<option>Java</option>
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