<!-- begin news_short.php -->
<?php
include("blog/functions.php");

date_default_timezone_set('Europe/Berlin');
require("phpinclude/dbconnect.php");


//$debug = "TRUE";

/* Connect to database */
$con=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

/* change character set to utf8mb4 */
if (!mysqli_set_charset($con, "utf8mb4"))
  { printf("Error loading character set utf8mb4: %s<br>\n", mysqli_error($con)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($con)); } }

/* Connect to comments-database */
$concom=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

if (!mysqli_set_charset($concom, "utf8mb4"))
  { printf("Error loading character set utf8mb4: %s<br>\n", mysqli_error($concom)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($concom)); } }



$query_blog = "SELECT * FROM `blog` WHERE `tags` LIKE '%news%' AND `tags` NOT LIKE '%saved%' ORDER BY `time` DESC LIMIT 0, 8 ";
$result = mysqli_query($con, $query_blog);

if ($result)
  {
   echo "<ul class=\"blog\">\n";
   while ($row = $result->fetch_assoc())
     {
      $head = strip_tags($row["head"]);
      echo "<li><a href=\"{$_SERVER["PHP_SELF"]}?page=blog&amp;index={$row["index"]}{$link}\">" . $head . "</a>\n";
//      echo "<p class=\"notes\">" . date("d.M.Y H:i",$row['time']) . " - last update: " . date("d.M.Y H:i",$row['ctime']) . "</p>";

      $query_comments = "SELECT * FROM `musicchris_de`.`blog-comments` WHERE `affiliation` = {$row["index"]} ORDER BY `time` ASC ";
      $resultcomments = mysqli_query($concom, $query_comments);
      $totalRows_comments = mysqli_num_rows($resultcomments);
      if ($totalRows_comments)
        {
         if ($totalRows_comments == "1") $comments = $lang_comment;
           else $comments = $lang_comments;
         $total_comments = convertnumbers($totalRows_comments, $lang);
         echo "<p class=\"notes\">(<a href=\"index.php?page=blog&amp;index={$row["index"]}{$link}#linkshowcomments\">$total_comments $comments</a>)</p>\n";
        }
      mysqli_free_result($resultcomments);
      echo "</li>\n";
     }
   echo "</ul>\n";
  }
else
  {
   echo "ERROR! No data retrieved.";
  }
mysqli_free_result($result);
?>
<!-- end news_short.php -->
