<?php 
$js = "FALSE";
include('head.php');
?>
<!-- begin showcomments.php -->
<body>
<center><a href="showblog.php"><button>ZURÜCK</button></a></center>

<?php
$debug = "FALSE"; //set to true to see some debug information...
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

if ($debug == "TRUE") echo "dbconnect has been executed.<br>\n<h1>Start showblog...</h1>\nlook up comments<br>\n";

// look up comments
$queryAffiliation = mysqli_real_escape_string($con, $_GET["affiliation"]);
$query_comments = "SELECT * FROM `blog-comments` WHERE `affiliation` = '$queryAffiliation' ORDER BY `time` ASC ";
$result = mysqli_query($con, $query_comments);
$totalRows_comments = mysqli_num_rows($result);
if ($result)
  {
   while ($row = $result->fetch_assoc())
     {
      echo "<br>\n<div class=\"comments shadow\" id=\"{$row["number"]}\" name=\"comments\" style=\"margin: 10px 25px;\">\n";
      echo "<a href=\"editcomments.php?job=delete&amp;number={$row["number"]}&amp;affiliation={$row["affiliation"]}\" class=\"deleteComment\">löschen</a>\n";
      echo "<form action=\"editcomments.php?job=update\" method=\"post\" accept-charset=\"UTF-8\">\n";
      echo "<input type=\"hidden\" name=\"number\" value=\"{$row["number"]}\">\n";
      echo "<input type=\"hidden\" name=\"affiliation\" value=\"{$row["affiliation"]}\">\n";
      echo "<input type=\"text\" name=\"name\" value=\"{$row["name"]}\">\n";
      echo "<input type=\"text\" name=\"email\" value=\"{$row["email"]}\">\n";
      echo "<input type=\"text\" name=\"website\" value=\"{$row["website"]}\">\n";
      echo "<input type=\"hidden\" name=\"time\" value=\"{$row["time"]}\">" . date("d.M.Y H:i",$row["time"]) . "<br>\n";
      $search = array("\\r\\n", "\r\n", "\\0", "\\", "&#92;");
      $replace = array("\n", "\n", "0", "", "\\");
      echo "<textarea name=\"comment\" class=\"commentTextarea\">" . htmlspecialchars(str_replace($search, $replace, $row["comment"]), ENT_COMPAT | ENT_HTML5, "UTF-8") . "</textarea><br>\n";
      echo "<button type=\"submit\">  Speichern  </button>\n";
      echo "</form>\n</div>\n";
     }
  }

mysqli_free_result($result);
?>

</body>
</html>
<!-- end showcomments.php -->
