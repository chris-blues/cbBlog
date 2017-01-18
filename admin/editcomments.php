<?php
$js = "FALSE";
$reload = true;
$target = "showcomments.php?affiliation=" . $_POST["affiliation"] . "#" . $_POST["number"];
include('head.php');
echo "<!-- begin editcomments.php -->\n";

require("../../phpinclude/dbconnect.php");
/* Connect to database */
$con=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

/* change character set to utf8 */
if (!mysqli_set_charset($con, "utf8"))
  { printf("Error loading character set utf8: %s<br>\n", mysqli_error($con)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($con)); } }

// get $_POST data
echo "<pre>Post:"; print_r($_POST); echo "Get:"; print_r($_GET); echo "</pre>\n";
if (isset($_POST["number"])) $number = $_POST["number"];
if (isset($_POST["affiliation"])) $affiliation = $_POST["affiliation"];
if (isset($_POST["name"])) $name = $_POST["name"];
if (isset($_POST["website"])) $website = $_POST["website"];
if (isset($_POST["time"])) $time = $_POST["time"];
if (isset($_POST["comment"])) $comment = mysqli_real_escape_string($con, $_POST["comment"]);

if ($_GET["job"] == "delete")
  {
   $query = "DELETE FROM `blog-comments` WHERE `number` = '{$_GET["number"]}'";
   $number = $_GET["number"];
   $affiliation = $_GET["affiliation"];
  }

if ($_GET["job"] == "update")
  {
   $query = "UPDATE `blog-comments` SET `affiliation` = '$affiliation',`time` = '$time',`name` = '$name', `website` = '$website',`comment` = '$comment' WHERE `number` = '$number';";
  }

echo "<body style=\"margin: 0px; width: 100%; max-width: 100%;\" onload=\"window.location.href='showcomments.php?affiliation=$affiliation#$number'\">\n";
//echo "<body style=\"margin: 0px;\">\n";

echo $query . "<br>\n";
$result = mysqli_query($con, $query) or die(mysql_error($result));
mysqli_free_result($result);

?>
<br><a href="showcomments.php?affiliation=<?php echo $affiliation . "#" . $time; ?>">BACK!</a><br>
</body>
</html>
<!-- end editcomments.php -->
