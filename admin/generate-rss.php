<?php
date_default_timezone_set('Europe/Berlin');

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

/*##########################################################
####################  General RSS Feed  ####################
##########################################################*/
$query_blog = "SELECT * FROM `blog` ORDER BY `sorttime`  DESC";
$result = mysqli_query($con, $query_blog);
$counter = "0";

$rss = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
$rss .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
$rss .= "<channel>\n\n";
$rss .= "<title>Blog musicchris.de</title>\n";
$rss .= "<description>Ein Blog über Musik, OpenSource, Software und Freiheit</description>\n";
$rss .= "<link>https://musicchris.de/index.php?page=blog</link>\n\n";
$rss .= "<language>de-de</language>\n";
$rss .= "<copyright>chris@musicchris.de (chris_blues)</copyright>\n";
$rss .= "<webMaster>chris@musicchris.de (chris_blues)</webMaster>\n";
$rss .= "<pubDate>Wed, 16 Jul 2014 20:13:00 +0100</pubDate>\n";
$rss .= "<lastBuildDate>" . date("D, d M Y H:i:s O",time()) . "</lastBuildDate>\n\n";
$rss .= "<atom:link href=\"https://musicchris.de/rss-feed.xml\" rel=\"self\" type=\"application/rss+xml\" />\n\n";
$rss .= "<image>\n";
$rss .= "<url>https://musicchris.de/pics/cover_summertime_front_300x300.jpg</url>\n";
$rss .= "<title>Blog musicchris.de</title>\n";
$rss .= "<link>https://musicchris.de/index.php?page=blog</link>\n";
$rss .= "</image>\n\n";

if ($result)
  {
   while ($row = $result->fetch_assoc() and $counter < 15)
     {
      $counter++;
      $head = strip_tags($row["head"]);
      $head = str_replace("&", "&amp;", $head);
      $timehr = date("D, d M Y H:i:s O", $row["time"]);
      $rss .= "<item>\n";
      $rss .= "<title>" . $head . "</title>\n";
      $rss .= "<link>https://musicchris.de/index.php?page=blog&amp;index={$row["index"]}</link>\n";
      $rss .= "<guid>https://musicchris.de/index.php?page=blog&amp;index={$row["index"]}</guid>\n";
      $rss .= "<author>chris@musicchris.de (chris_blues)</author>\n";
      $rss .= "<pubDate>$timehr</pubDate>\n";
      $rss .= "<description><![CDATA[\n" . $row["text"] . "\n]]></description>\n";
      $rss .= "<comments>https://musicchris.de/index.php?page=blog&amp;index={$row["index"]}#linkshowcomments</comments>\n";
      $rss .= "</item>\n\n";
     }
  }

mysqli_free_result($result);
$rss .= "</channel>\n\n";
$rss .= "</rss>\n";

//echo "<pre>$rss</pre>";
file_put_contents("../../rss-feed.xml", $rss);


/*##########################################################
##################  Open Source RSS Feed  ##################
##########################################################*/
$query_blog = "SELECT * FROM `blog` WHERE `tags` LIKE '%opensource%' ORDER BY `sorttime`  DESC";
$result = mysqli_query($con, $query_blog);
$counter = "0";

$rss = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
$rss .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
$rss .= "<channel>\n\n";
$rss .= "<title>Blog musicchris.de - Open Source Themen</title>\n";
$rss .= "<description>Ein Blog über Musik, OpenSource, Software und Freiheit - Abschnitt Open Source</description>\n";
$rss .= "<link>https://musicchris.de/index.php?page=blog&amp;filter=opensource</link>\n\n";
$rss .= "<language>de-de</language>\n";
$rss .= "<copyright>chris@musicchris.de (chris_blues)</copyright>\n";
$rss .= "<webMaster>chris@musicchris.de (chris_blues)</webMaster>\n";
$rss .= "<pubDate>Wed, 16 Jul 2014 20:13:00 +0100</pubDate>\n";
$rss .= "<lastBuildDate>" . date("D, d M Y H:i:s O",time()) . "</lastBuildDate>\n\n";
$rss .= "<atom:link href=\"https://musicchris.de/rss-feed-opensource.xml\" rel=\"self\" type=\"application/rss+xml\" />\n\n";
$rss .= "<image>\n";
$rss .= "<url>https://musicchris.de/pics/cover_summertime_front_300x300.jpg</url>\n";
$rss .= "<title>Blog musicchris.de - Open Source Themen</title>\n";
$rss .= "<link>https://musicchris.de/index.php?page=blog&amp;filter=opensource</link>\n";
$rss .= "</image>\n\n";


if ($result)
  {
   while ($row = $result->fetch_assoc() and $counter < 15)
     {
      $counter++;
      $head = strip_tags($row["head"]);
      $head = str_replace("&", "&amp;", $head);
      $timehr = date("D, d M Y H:i:s O", $row["time"]);
      $rss .= "<item>\n";
      $rss .= "<title>" . $head . "</title>\n";
      $rss .= "<link>https://musicchris.de/index.php?page=blog&amp;index={$row["index"]}</link>\n";
      $rss .= "<guid>https://musicchris.de/index.php?page=blog&amp;index={$row["index"]}</guid>\n";
      $rss .= "<author>chris@musicchris.de (chris_blues)</author>\n";
      $rss .= "<pubDate>$timehr</pubDate>\n";
      $rss .= "<description><![CDATA[\n" . $row["text"] . "\n]]></description>\n";
      $rss .= "<comments>https://musicchris.de/index.php?page=blog&amp;index={$row["index"]}#linkshowcomments</comments>\n";
      $rss .= "</item>\n\n";
     }
  }

mysqli_free_result($result);
$rss .= "</channel>\n\n";
$rss .= "</rss>\n";

//echo "<pre>$rss</pre>";
file_put_contents("../../rss-feed-opensource.xml", $rss);
?>
