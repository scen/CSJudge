<?php
$_ACTIVE = "help";
define('__ROOT__', "/var/www/judge/");
require_once __ROOT__ . "/php/head.php";
?>
<h1>Help</h1><hr>
<div class="row-fluid">
	<div class="span3">
		<ul class="nav nav-tabs nav-stacked">
			<li><a href="#overview">Overview</a></li>
			<li><a href="#submitting">Submitting Source Code</a></li>
			<li><a href="#languages">Languages and Compiler Flags</a></li>
			<li><a href="#output">Grader Output and Status Codes</a></li>
			<li><a href="#scorecards">Scorecards</a></li>
	</ul>
</div>
</div>
<h3 id="overview">Overview</h3>
<hr>
<p>
	On the <code>problems</code> page, you'll find an archive of problems where you can submit solutions to. Each problem has a unique alphanumeric code which will be used on the <code>submit</code> page.<br><br>
	Each problem has it's own submit button; clicking this will take you to the <code>submit</code> page and automatically fill in the problem code for you. You should then pick a language and either upload a file containing your code, or paste your code into the box. Your code must weigh less than <code>1 megabyte</code> before compilation. <br><br>
	Once you submit your code, your code will be compiled (if applicable), executed in a jail (chrooted to a single directory), and evaluated against multiple test cases. You will be taken to a page summarizing your results after this process finishes. <br><br>
	When you submit code, results, date, and a copy of your source code will be stored on the server. To view your post submissions, click your name in the top right corner and click <code>My Submissions</code>, <em>OR</em> navigate to the global submissions page and click <code>My Submissions</code> there. You will be able to see other peoples' source code if and only if you've solved the same problem.
	After you submit, you will have full access to the test data. Just click the <code>analysis</code> button on the problem page.
</p><br>
<h3 id="submitting">Submitting Source Code</h3><hr>
<p>
	A few guidelines on submitting code:
	<ul>
		<li>Input/Output should be from/to <code>stdin</code> and <code>stdout</code> respectively. Please do not flush your output after each line - this will make your program run slower. Yes, that means no using <code>std::endl</code>!</li>
		<li><strong>Do not</strong> prompt for input. The entire input file will be provided at runtime; if you prompt using stuff like <code>getchar()</code>, your program will <em>not</em> terminate.</li>
		<li>Your output must be in the <strong>exact</strong> same format as described in the problem. Extra newlines <strong>do</strong> matter, as well as spaces and punctuation.</li>
	</ul>
</p>
<p>
	What your program <strong>should not</strong> and <strong>cannot</strong> do (e.g. forbidden actions):
	<ul>
		<li>Modify the file system in any way unless otherwise noted by the problem</li>
		<li>Do anything related to networking (opening a socket, connecting to the internet)</li>
		<li>Interact with, examine, or enumerate other processes (other than your own, which you call kill)</li>
		<li>Read, Write, or Execute memory not within the address space allocated for your program</li>
		<li>Execute another program</li>
		<li>Use any system calls</li>
		<li>Dynamically load any shared modules not automatically loaded by the runtime and/or environment</li>
		<li>Attempt to circumvent the jail and/or protections</li>
	</ul>
</p>
	<h5>Sample C++ Submission</h5><br>
	<pre class="prettyprint linenums">
#include &lt;cstdio&gt;

int N;

int main(int argc, char** argv)
{
scanf("%d", &amp;N);
printf("%d", N);
return 0;
}</pre>
<h3 id="languages">Languages and Compiler Flags</h3><hr>
<p>
	<table class="table table-striped table-bordered">
		<thead>
			<th>Language</th>
			<th>Compiler Name and Version</th>
			<th>Compiler Flags</th>
		</thead>
		<tbody>
			<tr>
				<td>C++</td>
				<td>g++ 4.6.3</td>
				<td>g++-4.6 -m32 -static -O2 -DONLINE_JUDGE</td>
			</tr>
			<tr>
				<td>C</td>
				<td>gcc 4.6.3</td>
				<td>gcc-4.6 -m32 -static -O2 -DONLINE_JUDGE</td>
			</tr>
		</tbody>
	</table>
</p>
<h3 id="output">Grader Output and Status Codes</h3>
<hr>
<p>
	If you look at the main <code>submissions</code> page, you will see a column called <code>result</code>. This will have one of the following status codes:
	<ul>
		<li><strong>AC</strong>: All Correct; your submission was successful in solving all the test cases.</li>
		<li><strong>WA</strong>: Wrong answer; one or more test cases was solved incorrectly.</li>
		<li><strong>CE</strong>: Compile error.</li>
		<li><strong>TLE</strong>: Time limit exceeded.</li>
		<li><strong>MLE</strong>: Memory limit exceeded.</li>
		<li><strong>RE</strong>: Runtime error, additional info <em>may</em> contain the following:
			<ul>
				<li><strong>SIGSEGV</strong>: Invalid memory access (out of bounds, etc)</li>
				<li><strong>SIGFPE</strong>: Floating point error, e.g. dividing by zero, square roots of negative numbers</li>
				<li><strong>SIGABRT</strong>: Uncaught exceptions, failed assertions.</li>
			</ul>
		</li>
	</ul>
	If the problem has extra debug information turned on (mostly for practice problems, contests have this disabled), you will get additional info which includes the status code that happened on each test case (as well as memory usage and runtime on each one). Click the information icon
	at the <code>submissions</code> page to view this.
</p>
<h3 id="scorecards">Scorecards</h3><hr>
<p>
	Scorecards are just a quick way to display the results of your solution on <strong>all</strong> the test data (if the problem has the output enabled).
	<br>Example: <code>***x* *ts*x</code><br>
	<ul>
		<li><strong>*</strong>: AC</li>
		<li><strong>x</strong>: WA</li>
		<li><strong>t</strong>: TLE</li>
		<li><strong>s</strong>: RE, signal error, MLE</li>
	</ul>
<?php
require_once __ROOT__ . "/php/foot.php";
?>