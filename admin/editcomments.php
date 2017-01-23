<!-- begin editcomments.php -->
<?php
$debug = "FALSE";
$js = "FALSE";
$reload = true;

if (isset($_POST["affiliation"]) and $_POST["affiliation"] != "") $affiliation = $_POST["affiliation"];
if (!isset($affiliation) or $affiliation == "") { if (isset($_GET["affiliation"]) and $_GET["affiliation"] != "") { $affiliation = $_GET["affiliation"]; } }

if (isset($_POST["number"]) and $_POST["number"] != "") $number = $_POST["number"];
if (!isset($number) or $number == "") { if (isset($_GET["number"]) and $_GET["number"] != "") { $number = $_GET["number"]; } }

$target = "showcomments.php?affiliation=$affiliation#" . $number;

include('head.php');

require("../../phpinclude/dbconnect.php");
/* Connect to database */
$con=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    {
     echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n";
    }
  else
    {
     if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n";
    }

/* change character set to utf8mb4 */
if (!mysqli_set_charset($con, "utf8mb4"))
  { printf("Error loading character set utf8mb4: %s<br>\n", mysqli_error($con)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($con)); } }

// get $_POST data
if ($debug == "TRUE") { echo "Post: <pre>"; print_r($_POST); echo "</pre>Get: <pre>"; print_r($_GET); echo "</pre>\n"; }
if (isset($_POST["number"])) $number = $_POST["number"];
if (isset($_POST["affiliation"])) $affiliation = $_POST["affiliation"];
if (isset($_POST["name"])) $name = $_POST["name"];
if (isset($_POST["email"])) $email = $_POST["email"];
if (isset($_POST["website"])) $website = $_POST["website"];
if (isset($_POST["time"])) $time = $_POST["time"];
if (isset($_POST["comment"])) $comment = mysqli_real_escape_string($con, str_replace("\\", "&#92;", $_POST["comment"]));

if ($_GET["job"] == "delete")
  {
   $query = "DELETE FROM `blog-comments` WHERE `number` = '" . mysqli_real_escape_string($con, $_GET["number"]) . "'";
   $number = $_GET["number"];
   $affiliation = $_GET["affiliation"];
  }

if ($_GET["job"] == "update")
  {
   $queryName = mysqli_real_escape_string($con, $name);
   $queryEmail = mysqli_real_escape_string($con, $email);
   $queryWebsite = mysqli_real_escape_string($con, $website);
   $queryComment = mysqli_real_escape_string($con, $comment);
   $queryNumber = mysqli_real_escape_string($con, $number);

   $query = "UPDATE `blog-comments` SET `name` = '$queryName', `email` = '$queryEmail', `website` = '$queryWebsite',`comment` = '" . str_replace("\\r\\n", "\r\n", $queryComment) . "' WHERE `number` = '$queryNumber';";
  }

echo "<body>\n";

if ($debug == "TRUE") echo $query . "<br>\n";
$result = mysqli_query($con, $query) or die(mysql_error($result));
//mysqli_free_result($result);

?>
<br><a href="showcomments.php?affiliation=<?php echo $affiliation . "#" . $time; ?>">BACK!</a><br>
<?php if ($debug == "TRUE") { ?><pre><?php print_r($_POST); ?></pre><?php } ?>
</body>
</html>
<!-- end editcomments.php -->
