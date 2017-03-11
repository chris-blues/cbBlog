<?php

$posts = $adminQuery->selectAllBlogposts();

/*##########################################################
####################  General RSS Feed  ####################
##########################################################*/

$counter = 0;

$rss = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
$rss .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
$rss .= "<channel>\n\n";
$rss .= "<title>" . $config["blog"]["RSSinfo"]["title"] . "</title>\n";
$rss .= "<description>" . $config["blog"]["RSSinfo"]["description"] . "</description>\n";
$rss .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blog"]["RSSinfo"]["blogCall"] . "</link>\n\n";
$rss .= "<language>" . $config["blog"]["RSSinfo"]["language"] . "</language>\n";
$rss .= "<copyright>" . $config["blog"]["RSSinfo"]["author"] . "</copyright>\n";
$rss .= "<webMaster>" . $config["blog"]["RSSinfo"]["author"] . "</webMaster>\n";
$rss .= "<generator>cbBlog " . $version . "</generator>\n";
// $rss .= "<pubDate>" . date(DATE_RFC822) . "</pubDate>\n";
$rss .= "<lastBuildDate>" . date(DATE_RFC822) . "</lastBuildDate>\n\n";
$rss .= "<atom:link href=\"" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/rss-feed.xml\" rel=\"self\" type=\"application/rss+xml\" />\n\n";
// $rss .= "<image>\n";
// $rss .= "<url>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/pics/cover_summertime_front_300x300.jpg</url>\n";
// $rss .= "<title>" . $config["blog"]["RSSinfo"]["title"] . "</title>\n";
// $rss .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blog"]["RSSinfo"]["blogCall"] . "</link>\n";
// $rss .= "</image>\n\n";

if ($posts) {
  $maxPosts = 15;
  foreach ($posts as $key => $value) {
    if ($counter == $maxPosts) continue;

    $row = $posts[$key]->getdata();
    if (isset($error)) showErrors($error);
    $row["tags"] = $query->getTagsOfBlogpost($row["id"]);
    if (isset($error)) showErrors($error);
    foreach ($row["tags"] as $Tag) {
      $tmp = $Tag->getdata();
      $tempArray[$tmp["id"]] = $tmp["tag"];
      unset($tmp);
    }
    if (in_array("unreleased", $tempArray)) {
      unset($tempArray);
      $maxPosts++;
      continue;
    }
    unset($tempArray);

    $counter++;
    $head = strip_tags($row["head"]);
    $head = str_replace("&", "&amp;", $head);
    $timehr = date("D, d M Y H:i:s O", $row["mtime"]);
    $rss .= "<item>\n";
    $rss .= "<title>" . $head . "</title>\n";
    $rss .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blog"]["RSSinfo"]["blogCall"] . "&amp;id={$row["id"]}</link>\n";
    $rss .= "<guid>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blog"]["RSSinfo"]["blogCall"] . "&amp;id={$row["id"]}</guid>\n";
    $rss .= "<author>" . $config["blog"]["RSSinfo"]["author"] . "</author>\n";
    $rss .= "<pubDate>$timehr</pubDate>\n";
    $rss .= "<description><![CDATA[\n" . $row["text"] . "\n]]></description>\n";
    $rss .= "<comments>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blog"]["RSSinfo"]["blogCall"] . "&amp;id={$row["id"]}#linkshowcomments</comments>\n";
    $rss .= "</item>\n\n";
    }
}

$rss .= "</channel>\n\n";
$rss .= "</rss>\n";

// echo "<pre>$rss</pre>";
file_put_contents("../rss-feed.xml", $rss);

/*##########################################################
##################  Open Source RSS Feed  ##################
##########################################################*/

/*
$query_blog = "SELECT * FROM `blog` WHERE `tags` LIKE '%opensource%' ORDER BY `sorttime`  DESC";
$result = mysqli_query($con, $query_blog);
$counter = "0";

$rss = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
$rss .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
$rss .= "<channel>\n\n";
$rss .= "<title>Blog musicchris.de - Open Source Themen</title>\n";
$rss .= "<description>Ein Blog über Musik, OpenSource, Software und Freiheit - Abschnitt Open Source</description>\n";
$rss .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/id.php?page=blog&amp;filter=opensource</link>\n\n";
$rss .= "<language>de-de</language>\n";
$rss .= "<copyright>" . $config["blog"]["RSSinfo"]["author"] . "</copyright>\n";
$rss .= "<webMaster>" . $config["blog"]["RSSinfo"]["author"] . "</webMaster>\n";
$rss .= "<pubDate>Wed, 16 Jul 2014 20:13:00 +0100</pubDate>\n";
$rss .= "<lastBuildDate>" . date("D, d M Y H:i:s O",time()) . "</lastBuildDate>\n\n";
$rss .= "<atom:link href=\"" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/rss-feed-opensource.xml\" rel=\"self\" type=\"application/rss+xml\" />\n\n";
$rss .= "<image>\n";
$rss .= "<url>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/pics/cover_summertime_front_300x300.jpg</url>\n";
$rss .= "<title>Blog musicchris.de - Open Source Themen</title>\n";
$rss .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/id.php?page=blog&amp;filter=opensource</link>\n";
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
      $rss .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/id.php?page=blog&amp;id={$row["id"]}</link>\n";
      $rss .= "<guid>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/id.php?page=blog&amp;id={$row["id"]}</guid>\n";
      $rss .= "<author>" . $config["blog"]["RSSinfo"]["author"] . "</author>\n";
      $rss .= "<pubDate>$timehr</pubDate>\n";
      $rss .= "<description><![CDATA[\n" . $row["text"] . "\n]]></description>\n";
      $rss .= "<comments>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/id.php?page=blog&amp;id={$row["id"]}#linkshowcomments</comments>\n";
      $rss .= "</item>\n\n";
     }
  }

mysqli_free_result($result);
$rss .= "</channel>\n\n";
$rss .= "</rss>\n";

//echo "<pre>$rss</pre>";
file_put_contents("../rss-feed-opensource.xml", $rss); */
?>
