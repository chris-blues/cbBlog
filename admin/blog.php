<!-- begin blog.php -->
<?php
$debug = "FALSE"; //set to true to see some debug information...
include('head.php');
require('../../phpinclude/dbconnect.php');
include("blog-create-tables.php");
?>
<frameset cols="*">
<frame src="showblog.php" name="contentblog">
</frameset>
</html>
<!-- end blog.php -->
