<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<!--meta http-equiv="refresh" content="0;<?php echo "../index.php?page=blog&index={$_POST["affiliation"]}{$link}#$time"; ?>"-->
</head>
<body>
<h1>Manage subscriptions</h1>

<?php
error_reporting(1);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "/www/admin/logs/php-error.log");

date_default_timezone_set('Europe/Berlin');

include_once("../phpinclude/config.php");
require_once("../phpinclude/dbconnect.php");

/* Connect to comments-database */
$concom=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

if (!mysqli_set_charset($concom, "utf8"))
  { printf("Error loading character set utf8: %s<br>\n", mysqli_error($concom)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($concom)); } }



// catch errors
$errors = false;
if (!isset($_GET["job"]) or $_GET["job"] == "")						{ $errors = true; $error["job"]["exists"] = "missing"; }
if ($_GET["job"] != "unsubscribe")							{ $errors = true; $error["job"]["data"] = "faulty"; }
if ($_GET["job"] == "unsubscribe" and !isset($_GET["scope"]) and $_GET["scope"] == "")	{ $errors = true; $error["scope"]["exists"] = "missing"; }
if (!is_numeric($_GET["scope"]))							{ $errors = true; $error["scope"]["data"] = "faulty"; }

if (!isset($_GET["email"]) or $_GET["email"] == "")					{ $errors = true; $error["email"]["exists"] = "missing"; }
if (!filter_var($_GET["email"], FILTER_VALIDATE_EMAIL))					{ $errors = true; $error["email"]["data"] = "Not a valid address"; }



// lets get going!
$job = $_GET["job"];
$email = $_GET["email"];
$scope = $_GET["scope"];

if ($job == "unsubscribe")
  {
   echo "Removing you from ";
   if ($scope == "all")
     {
      $query = "UPDATE `blog-comments` SET `email`='' WHERE `email` = '$email';";
      echo "all subscriptions on " . $_SERVER["SERVER_NAME"] . "... ";
     }
   if ($scope != "all")
     {
      $query = "UPDATE `blog-comments` SET `email`='' WHERE (`email` = '$email' AND `affiliation`='$number');";
      echo "blogentry number " . $scope . "... ";
     }

   if ($result = mysqli_query($concom, $query))
     {
      if (mysqli_num_rows($result) < 1) { echo "[Error!]<br>\nAddress was not found!<br>\n"; $errors = true; $error["processing"]["email"] = "not found"; }
      else echo mysqli_num_rows($result) . " subscriptions removed.<br>\n";
      echo "[ done ]<br>\n";
     }
   else { echo "[Error!]<br>\nDatabase could not be accessed!<br>\n"; $errors = true; $error["processing"]["queryDB"] = "failed"; }
   mysqli_free_result($result);
  }


// display errors
if ($errors)
  {
   echo "<h1>Errors occured!</h1>\n<pre>";
   print_r($error);
   echo "</pre>\n";
   echo "<h2>\$_GET array:</h2><pre>";
   print_r($_GET);
   echo "</pre>\n";
   exit(count($error) . " errors");
  }

echo "<p><a href=\"../index.php?page=blog&index={$_POST["affiliation"]}\">Back to the blog</a></p>";
?>


</body>
</html>