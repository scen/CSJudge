<!DOCTYPE html>
<html lang="en">
	<head>
		<title>WCS Judge</title>
		<meta name="description" content="Contest problem grader">
		<meta name="author" content="stanley cen">
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="css/main.css" rel="stylesheet">
		<link href="gcp/prettify.css" type="text/css" rel="stylesheet" />
		<script src="js/main.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap-dropdown.js" type="text/javascript"></script>
		<script type="text/javascript" src="gcp/prettify.js"></script>
		<style type="text/css">
	    .addpad {
	        padding-top: 60px;
	    }
	    </style>
		<script src="bootstrap/js/bootstrap-collapse.js" type="text/javascript"></script>
		<script type="text/javascript">
		$(document).ready(function()
		{
			window.prettyPrint && prettyPrint();
		});
		</script>
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            			<span class="icon-bar"></span>
            			<span class="icon-bar"></span>
            			<span class="icon-bar"></span>
          			</a>
			        <!--Replace this "logo" -->
			        <a class="brand" href="#">WCS Judge</a>
			        <div class="btn-group pull-right">
			        	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
			        		<i class="icon-user"></i>
			        		uberness132
			        		<span class="caret"></span>
			        	</a>
			        	<ul class="dropdown-menu">
			        		<li><a href="#">My Account</a></li>
			        		<li><a href="#">My Submissions</a></li>
				            <li class="divider"></li>
				            <li><a href="#">Sign out</a></li>
			        	</ul>
			        </div>
			        <div class="nav-collapse">
			        	<ul class="nav">
			        		<li class="active"><a href="#"><!-- <i class="icon-home icon-white"></i> -->main</a></li>
			        		<li><a href="#"><!-- <i class="icon-file icon-white"></i> -->problems</a></li>
			        		<li><a href="#"><!-- <i class="icon-folder-open icon-white"></i> -->submissions</a></li>
			        		<li><a href="#"><!-- <i class="icon-upload icon-white"></i> -->submit</a></li>
			        		<li><a href="#"><!-- <i class="icon-book icon-white"></i> -->contests</a></li>
			        		<li><a href="#"><!-- <i class="icon-user icon-white"></i> -->users</a></li>
			        		<li><a href="#"><!-- <i class="icon-comment icon-white"></i> -->help</a></li>
			        	</ul>
			        </div>
	          	</div>
	      	</div>
      	</div>
     	<div class="container-fluid addpad">
      		<div class="row-fluid" style="width: 98%; margin: 0 auto;">
      			<h1>Main Page</h1>
      			<hr>
      			<div class="">
      				<p>
      				This is another problem grader, where you can solve and test an archive of problems. Your program is compiled and ran against many test cases instantly, giving you results immediately. To use this site, pick a problem or contest via the <code>problems</code> or <code>contests</code> link, solve the problem, and use the <code>submit</code> button on each problem's page (or use the button on top).
      				<br><br>
      				See the <code>help</code> page for more info.
      				</p>
      			</div><br>
      			<h2>News</h2><hr>
      			<div class="news">
      				<h3>July 11, 2012</h3>
      				<ul>
      					<li>Login and index pages finished.</li>
      					<li>Revamped UI</li>
      				</ul>
      				<h3>July 8, 2012</h3>
      				<ul>
      					<li>Started construction on the site.</li>
      				</ul>
      			</div>
        	</div>
        	<hr>
        	<div class="copyright"><p style="margin-bottom: 0px;">Copyright 2012 &copy; Stanley Cen</p></div>
    	</div>
	</body>
</html>