<?php
if ($_GET["job"] == "edit") $reload = false;
else $reload = true;
$target = "showblog.php";
include('head.php');
echo "<!-- begin editblog.php -->\n";
date_default_timezone_set('Europe/Berlin');

echo "<body id=\"adminEditBlog\">\n";

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

// look up tags
$query_tags = "select * from `blog-tags` ORDER BY `tag` ASC ";

$result = mysqli_query($con, $query_tags);
$totalRows_blogtags = mysqli_num_rows($result);
   while ($row = $result->fetch_assoc())
     {
      $taglist[] = trim($row["tag"]);
     }
   mysqli_free_result($result);

// get $_POST data
if (isset($_POST["time"])) $time = $_POST["time"]; else $datamissing = "TRUE";
if (isset($_POST["sorttime"])) $sorttime = $_POST["sorttime"];
if (isset($_POST["index"])) $index = $_POST["index"];
if (!isset($index) or $index == "") $index = $_GET["index"];
if (isset($_POST["tags"])) $tags = trim($_POST["tags"]);
if (isset($_POST["head"])) $head = $_POST["head"];
if (isset($_POST["text"])) $text = $_POST["text"];

// Read a certain data row, if we have an index
if ($datamissing == "TRUE" and $index != "new")
  {
   $query_blog = "select * from `blog` WHERE (`blog`.`index` = $index) ";
   $result = mysqli_query($con, $query_blog);
   $totalRows_blog = mysqli_num_rows($result);
   while ($row = $result->fetch_assoc())
     {
      $time = $row["time"];
      $sorttime = $row["sorttime"];
      $tags = $row["tags"];
      $head = $row["head"];
      $text = $row["text"];
     }
   mysqli_free_result($result);
  }

$querytags = mysqli_real_escape_string($con, $tags);
$queryhead = mysqli_real_escape_string($con, $head);
$querytext = mysqli_real_escape_string($con, $text);
$querytime = mysqli_real_escape_string($con, $time);
$querysorttime = mysqli_real_escape_string($con, $sorttime);


if ($_GET["job"] == "add")
  {
   $query = "INSERT INTO `musicchris_de`.`blog` (`time`, `sorttime`,`ctime`, `index`, `tags`, `head`, `text`) VALUES ('$querytime', '$querysorttime', '$querytime', NULL, '$querytags', '$queryhead', '$querytext');";
   $actualtags = explode(" ", $tags);
   foreach($actualtags as $tagindex => $actualtag)
     {
      $actualtag = trim($actualtag);
      echo "$actualtag : ";
      $tagfound = "FALSE";
      foreach ($taglist as $key => $value)
        {
         $value = trim($value);
         if (strcasecmp($value, $actualtag) == 0) { $tagfound = "TRUE"; }
        }

      if ($tagfound == "TRUE")
        {
         echo "skipped - already registered.<br>\n";
         continue 1;
        }
      else
        {
         $queryactualtag = mysqli_real_escape_string($con, $actualtag);
         $tagquery = "INSERT INTO `musicchris_de`.`blog-tags` (`index`, `tag`) VALUES (NULL, '" . $actualtag . "');";
         if (mysqli_query($con, $tagquery)) echo "Written!<br>\n"; else die(mysql_error($con));
        }
     }
  }


if ($_GET["job"] == "delete")
  {
   $index = mysqli_real_escape_string($con, $_GET["index"]);
   $query = "DELETE FROM `musicchris_de`.`blog` WHERE `blog`.`index` = '$index';";
  }


if ($_GET["job"] == "edit")
  { ?>
  Edit blog:<br>
<br>

<?php
echo "<p class=\"notes\">";
foreach ($taglist as $key => $tag)
  {
//   echo "<script type=\"text/javascript\">var $tag = document.createTextNode(' $tag');</script>\n";
   echo "<a id=\"tag_$tag\" class=\"linkTags\" href=\"#\" data-id=\"$tag\" onclick=\"javascript:document.getElementById('tags').value += ' $tag';\">$tag</a> \n";
  }
echo "</p>\n";
      if($index != "new")
        { ?>
         <form name="addblog" action="editblog.php?job=update" method="post" accept-charset="UTF-8" target="contentblog" class="smallFont">
         <?php
        }
      else
        {
         ?>
         <form name="addblog" action="editblog.php?job=add" method="post" accept-charset="UTF-8" target="contentblog" class="smallFont">
         <?php
         $index = "";
        }
      if (!isset($time) or $time == "") $time = time();
      if (!isset($sorttime) or $sorttime == "") $sorttime = date("YmdHi", $time);
      ?>

<div class="left">
Time: <?php echo date("Y-m-d H:i", $time); ?><br>
<textarea name="time" rows="1" cols="50"><?php echo $time; ?></textarea></div>

<div class="left">
Sorttime:<br>
<textarea name="sorttime" rows="1" cols="50"><?php echo $sorttime; ?></textarea></div>

<div class="left">
Tags:<br>
<textarea name="tags" id="tags" rows="1" cols="50"><?php echo $tags; ?></textarea></div>

<div class="clear">
Head:<br>
<textarea name="head" rows="1" id="adminEditBlogTextareaHead"><?php echo $head; ?></textarea></div>

Text:<br>
<textarea name="text" id="adminEditBlogTextareaText"><?php echo htmlspecialchars($text, ENT_COMPAT | ENT_HTML5, "UTF-8"); ?></textarea><br>

<input type="hidden" name="index" value="<?php echo $index; ?>">
<input type="button" value="           Back            " onclick="window.location.href='showblog.php'" target="contentblog">
<input type="submit" value="         Add blog!         ">
</form>
<?  }


if ($_GET["job"] == "update")
  {
   if ($time == "" or strncmp($time, "0", 1) == 0)
     {
      $timeconv = date_create_from_format("Y-m-d H:i",$date);
      $time = date_format($timeconv, "U");
     }
   $ctime = time();
   $tagscheck = explode(" ", $querytags);
   foreach ($tagscheck as $key => $value)
     {
      if (in_array($value, $tags_new)) continue 1;
      else $tags_new[] = $value;
     }
   $querytags = implode(" ", $tags_new);
   $querytags = mysqli_real_escape_string($con, $querytags);
   $queryindex = mysqli_real_escape_string($con, $index);
   $queryhead = mysqli_real_escape_string($con, $head);
   $querytext = mysqli_real_escape_string($con, $text);
   $querytime = mysqli_real_escape_string($con, $time);
   $queryctime = mysqli_real_escape_string($con, $ctime);
   $querysorttime = mysqli_real_escape_string($con, $sorttime);
   $query = "UPDATE `musicchris_de`.`blog` SET `time` = '$querytime',`sorttime` = '$querysorttime',`ctime` = '$queryctime',`index` = '$queryindex',`tags` = '$querytags',`head` = '$queryhead',`text` = '$querytext' WHERE `blog`.`index` = '$queryindex';";
   $actualtags = explode(" ", $tags);
   foreach($actualtags as $tagindex => $actualtag)
     {
      $actualtag = trim($actualtag);
      echo "$actualtag : ";
      $tagfound = "FALSE";
      foreach ($taglist as $key => $value)
        {
         $value = trim($value);
         if (strcasecmp($value, $actualtag) == 0) { $tagfound = "TRUE"; }
        }

      if ($tagfound == "TRUE")
        {
         echo "skipped - already registered.<br>\n";
         continue 1;
        }
      else
        {
         $queryactualtags = mysqli_real_escape_string($con, $actualtags);
         $tagquery = "INSERT INTO `musicchris_de`.`blog-tags` (`index`, `tag`) VALUES (NULL, '" . $queryactualtag . "');";
         if (mysqli_query($con, $tagquery)) echo "Written!<br>\n"; else die(mysql_error($con));
        }
     }
  }

//echo "\$query: " . $query . "<br>\n";
if ($query != "")
  {
   $result = mysqli_query($con, $query) or die(mysql_error($result));
   mysqli_free_result($result);
  }

include("generate-rss.php");

?>
<br><a href="showblog.php">BACK!</a><br>
</body>
</html>
<!-- end editblog.php -->
