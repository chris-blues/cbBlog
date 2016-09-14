<?php
$debug = "FALSE";
if ($debug  == "TRUE")
  {
   error_reporting(E_ALL & ~E_NOTICE);
   ini_set("display_errors", 1);
   echo "<pre>\n";
  }
else
  {
   error_reporting(0);
   ini_set("display_errors", 0);
  }

include("blog.php");
?>
