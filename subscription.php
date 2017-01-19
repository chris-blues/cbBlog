<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<h1>Manage subscriptions</h1>

<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 0);
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
if (!isset($_GET["job"]) or $_GET["job"] == "")			{ $errors = true; $error["job"]["exists"] = "missing"; }
if ($_GET["job"] != "unsubscribe" and $_GET["job"] != "verify")	{ $errors = true; $error["job"]["data"] = "faulty"; }
if ($_GET["job"] == "unsubscribe")
  { if (!isset($_GET["scope"]) or $_GET["scope"] == "")		{ $errors = true; $error["scope"]["exists"] = "missing"; } }
if (!is_numeric($_GET["scope"]))				{ $errors = true; $error["scope"]["data"] = "faulty"; }

if ($_GET["job"] == "verify")
  { if (!isset($_GET["hash"]) or $_GET["hash"] == "")		{ $errors = true; $error["hash"]["exists"] = "missing"; } }

if (!isset($_GET["email"]) or $_GET["email"] == "")		{ $errors = true; $error["email"]["exists"] = "missing"; }
if (!filter_var($_GET["email"], FILTER_VALIDATE_EMAIL))		{ $errors = true; $error["email"]["data"] = "Not a valid address"; }



// lets get going!
$job = $_GET["job"];
$email = $_GET["email"];

if ($job == "verify")
  {
   echo "<h1>Verification</h1>\n";
   if (!$errors)
     {
      $hash_verification = hash('sha256', $_SERVER["SERVER_NAME"] . $email . $_GET["scope"]);
      if (strcmp($_GET["hash"], $hash_verification) == 0)
        {
         $queryEmail = mysqli_real_escape_string($concom, $email);
         $queryScope = mysqli_real_escape_string($concom, $_GET["scope"]);
         $query = "UPDATE `blog-comments` SET `email`='$queryEmail' WHERE (`email` = '$hash_verification' AND `affiliation`='$queryScope');";
         if ($result = mysqli_query($concom, $query))
           {
            echo "<p>DB updated!</p>\n";
            //mysqli_free_result($result);
           }
         else echo "<p>ERROR! DB not updated</p>\n<p>MySQLi error: " . mysqli_error($concom) . "</p>\n";
        }
      else echo "<h1>Error!</h1>\n<p>You don't seem to be authorized to verify this address!</p>\n<p>Please check if the URL matches the one in your email! If the error persists, try to copy & paste the entire URL into your browser.</p>\n<p>{$_GET["hash"]}<br>$hash_verification</p>\n";
     }
  }


if ($job == "unsubscribe")
  {
   $scope = $_GET["scope"];
   $queryScope = mysqli_real_escape_string($concom, $_GET["scope"]);
   $queryEmail = mysqli_real_escape_string($concom, $email);
   echo "Removing you from ";
   if ($scope == "0")
     {
      $query = "UPDATE `blog-comments` SET `email`='' WHERE (`email` = '$queryEmail');";
      echo "all subscriptions on " . $_SERVER["SERVER_NAME"] . "... ";
     }
   if ($scope != "0")
     {
      $query = "UPDATE `blog-comments` SET `email`='' WHERE (`email` = '$queryEmail' AND `affiliation`='$queryScope');";
      echo "blogentry number " . $queryScope . "... ";
     }

   if ($result = mysqli_query($concom, $query))
     {
      if (mysqli_num_rows($result) == "0") { echo "[Error!]<br>\nAddress was not found!<br>\n"; $errors = true; $error["processing"]["email"] = "not found"; }
      else echo mysqli_num_rows($result) . " subscriptions removed.<br>\n";
      echo "[ done ]<br>\n";
     }
   else { echo "[Error!]<br>\nDatabase could not be accessed!<br>\n"; $errors = true; $error["processing"]["queryDB"] = "failed"; }
   //mysqli_free_result($result);
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

echo "<p><a href=\"../index.php?page=blog&index={$_GET["scope"]}\">Back to the blog</a></p>";
?>


</body>
</html>