<?php

require_once("bootstrap.php");
if ($config["blog"]["showProcessingTime"]) $startTime = microtime(true);

if ($_GET["id"] == "0") unset($_GET["id"]);
date_default_timezone_set('Europe/Berlin');

// fall back to overview if $_GET["id"] is missing or empty
if (isset($_GET["id"]) and $_GET["id"] != "") {
  $blogposts[$_GET["id"]] = $query->selectById("blog", $_GET["id"], "Blogpost");
}
else {
  $blogposts = $query->selectAll("blog", "Blogpost");
}

if (!isset($_GET["id"]) or $_GET["id"] == "" or $_GET["id"] == "0") {
  $tags = $query->selectAllTags("blog_tags", "Tags");
  Filters::display($tags);
  foreach ($tags as $key => $Tag) { $tagname = $Tag->getdata(); $taglist[$key] = $tagname["tag"]; }
  echo "<div id=\"wrapper\">\n";
}

if (!isset($_GET["filter"]) or $_GET["filter"] == "") $filter = "";
else $filter = $_GET["filter"];

// ====================[ display ]====================
if ($blogposts)
  {
   foreach ($blogposts as $id => $Post)
     {
      $row = $Post->getdata();
      $row["head"] = str_replace('$link', $link, $row["head"]);
      $row["text"] = str_replace('$link', $link, $row["text"]);

      $comments = $query->selectComments($row["id"], "Comment");
      $row["num_comments"] = count($comments);

      if (isset($_GET["id"]) and $_GET["id"] != "")
        {
         $row["comments"] = $comments;
         require("templates/view.blogpost.php");
        } else {
         require("templates/view.overview.php");
        }
     }
  echo "</div>\n";
  }
else
  {
   echo "ERROR! No data retrieved.";
  }

if (!isset($_GET["id"]) or $_GET["id"] == "") $_GET["id"] = "0";

if (isset($_GET["id"]) and $_GET["id"] != "" and $_GET["id"] != "0")
  {
   require_once("templates/view.comments-section.php");
  }
?>

</div>

<?php require_once("templates/view.foot.php"); ?>
