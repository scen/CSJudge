<?php
$_ACTIVE = "about";
define('__ROOT__', "/var/www/judge");
require_once __ROOT__ . "/php/head.php";
?>
<h1>About</h1><hr>
<p>This is a problem grader/contest framework for programming contests. This was designed and implemented by Stanley Cen, because all the open source contest frameworks didn't fit my needs,
or weren't secure enough. It uses PHP, HTML, Javascript, MySQL, CSS, and C++.</p><br/>
<p>Complete code is hosted at Github <a href="http://github.com/scen/CSJudge">here</a>.</p>
<?php
require_once __ROOT__ . "/php/foot.php";
?>