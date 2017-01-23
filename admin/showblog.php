<?php include('head.php'); ?>
<!-- BEGIN showblog.php -->
<?php
$debug = "FALSE"; //set to true to see some debug information...
require("../../phpinclude/dbconnect.php");
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

if ($debug == "TRUE") echo "dbconnect has been executed.<br>\n<h1>Start showblog...</h1>\nget number of comments to each blog-entry<br>\n";
// get number of comments to each blog-entry
$querynumbercomments = "SELECT * FROM `blog-comments` ORDER BY `affiliation` ASC";
$result = mysqli_query($con, $querynumbercomments, MYSQLI_USE_RESULT);
   while ($row = $result->fetch_assoc())
     {
      $commentindex = $row["affiliation"];
      $commentlist[$commentindex]++;
     }
   mysqli_free_result($result);
   if ($debug == "TRUE") { echo "<pre class=\"debug\">"; print_r($commentlist); echo "</pre>\n"; }

if ($debug == "TRUE") echo "look up tags<br>\n";
// look up tags
$query_tags = "select * from `blog-tags` ORDER BY `tag` ASC";
$result = mysqli_query($con, $query_tags, MYSQLI_USE_RESULT);
   while ($row = $result->fetch_assoc())
     {
      $taglist[] = trim($row["tag"]);
     }
   mysqli_free_result($result);
if ($debug == "TRUE") { echo "<pre class=\"debug\">"; print_r($taglist); echo "</pre>\n"; }

if ($debug == "TRUE") echo "look up blog<br>\n";
// look up blog
if (!isset($_GET["filter"]) or $_GET["filter"] == "")
  { $query_blog = "SELECT * FROM `blog` ORDER BY `blog`.`sorttime`  DESC"; }
else
  {
   $queryFilter = mysqli_real_escape_string($con, $_GET["filter"]);
   $query_blog = "SELECT * FROM `blog` WHERE `tags` LIKE '%$queryFilter%' ORDER BY `blog`.`sorttime`  DESC";
  }
$result = mysqli_query($con, $query_blog);
$totalRows_blog = mysqli_num_rows($result);

//if ($result = mysqli_query($con, $query_blog))
//  { printf("Select returned %d rows.\n", mysqli_num_rows($result)); }
?>

<body id="adminShowBlog">
<form action="editblog.php" method="get" accept-charset="UTF-8" target="contentblog" class="inline">
<input type="hidden" name="job" value="edit">
<input type="hidden" name="index" value="new">
<input type="submit" value="   Neuen Eintrag erstellen   ">
</form>

<?php
echo "<p class=\"inline smallFont\"><a href=\"showblog.php\">all</a></p>\n";
foreach ($taglist as $key => $tag)
  { 
   if (!isset($taglist[$key]) or $taglist[$key] == "") continue 1;
   ?>
   <p class="inline smallFont">
<? echo "<a href=\"?filter=$tag\">$tag</a>"; ?>
   </p>
<? }
?>

<table>
<tr><td></td><td><b>index</b></td><td><b>time</b></td><!--td><b>sorttime</b></td--><td><b>tags</b></td><td><b>head</b></td><td><b>Optionen</b></td></tr>

<?php
      $counter = "0";
      $index = "0";
      if (!isset($commentlist[$index]) or $commentlist[$index] == "") $commentlist[$index] = "0";
      echo "<tr class=\"blogEntries\">";
      echo "<td>$counter/$totalRows_blog</td><td>0</td>";
      echo "<td>====</td>";
      echo "<td>====</td>";
      echo "<td>OVERVIEW PAGE</td>";
      echo "<td>";
      if ($commentlist[$index] != "0") echo "<a href=\"showcomments.php?affiliation=0\">Kommentare</a> ({$commentlist[$index]})\n";
      echo "</td></tr>\n";
if ($result)
  {
   while ($row = $result->fetch_assoc())
     {
      $counter++;
      $index = $row["index"];
      if (!isset($commentlist[$index]) or $commentlist[$index] == "") $commentlist[$index] = "0";
      echo "<tr class=\"blogEntries\">";
      echo "<td>$counter/$totalRows_blog</td><td>" . $row['index'] . "</td>";
      echo "<td>" . date("Y-m-d H:i",$row['time']) . "</td>";
      echo "<td class=\"notes\">" . $row['tags'] . "</td>";
      echo "<td title=\"{$row['head']}\">"; if (strlen($row['head']) > 70) { echo substr($row['head'],0,70) . "...</td>"; } else echo $row['head'];
      echo "<td>";
      echo "<a href=\"editblog.php?job=edit&amp;index={$row['index']}\" target=\"contentblog\">ändern</a> \n";
      echo "<a href=\"editblog.php?job=delete&amp;index=" . $row['index'] . "\" target=\"contentblog\">löschen</a> \n";
      if ($commentlist[$index] != "0") echo "<a href=\"showcomments.php?affiliation={$row["index"]}\">Kommentare</a> ({$commentlist[$index]})\n";
      echo "</td></tr>\n";
     }
  }
else
  {
   echo "ERROR! No data retrieved.";
  }
mysqli_free_result($result);
?>

</table>
</body>
</html>
<!-- END showblog.php -->
