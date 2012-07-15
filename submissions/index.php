<?php
$_ACTIVE = "submissions";
define('__ROOT__', "/var/www/judge/");
require_once __ROOT__ . "/php/head.php";
?>
<div class="row-fluid" style="width: 98%; margin: 0 auto;">
	<h1>Submissions</h1><hr>
	<p>
		See <code>help</code> for a description of this table and a legend of the symbols &amp; abbreviations used. You will be able to see your own source code as well as code submitted by other users who have <em>solved</em> the same problem.
	</p>
	<br>
	<div class="pagination-centered pagination">
	<ul>
		<li><a href="#">First</a></li>
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
		<tr>
			<td class="good_submission date_submission"><p class="date_submission">July 3rd, 2012</p><br><p class="time_submission">12:14:43pm</p></td>
			<td class="good_submission">stanleyc</td>
			<td class="good_submission">A plus B</td>
			<td class="good_submission">AC</td>
			<td class="good_submission"><p style="display: inline;">***** ***** **</p></td>
			<td class="good_submission">0.10s</td>
			<td class="good_submission">0.24M</td>
			<td class="good_submission">C++</td>
			<td class="good_submission"><div style="text-align:center;margin: 0px auto !important;"><p style="display:inline;margin: 0px auto !important;"><a href="#"><i class="icon-file"></i></a>  <a href="#"><i class="icon-info-sign"></i></a></p></div></td>
		</tr>
		<tr>
			<td class="bad_submission date_submission"><p class="date_submission">July 3rd, 2012</p><br><p class="time_submission">12:14:43pm</p></td>
			<td class="bad_submission">stanleyc</td>
			<td class="bad_submission">A plus B</td>
			<td class="bad_submission">WA</td>
			<td class="bad_submission"><p style="display: inline;">***** *x*** **</p></td>
			<td class="bad_submission">0.10s</td>
			<td class="bad_submission">0.24M</td>
			<td class="bad_submission">C++</td>
			<td class="bad_submission"><div style="text-align:center;margin: 0px auto !important;"><p style="display:inline;margin: 0px auto !important;"><a href="#"><i class="icon-file"></i></a>  <a href="#"><i class="icon-info-sign"></i></a></p></div></td>
		</tr>
	</tbody>
	</table>
</div>
<?php
require_once __ROOT__ . "/php/foot.php";
?>