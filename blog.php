<?php
// Ugly workaround for old cbBlog databases
if (!isset($_GET["id"]) and isset($_GET["index"]) and $_GET["index"] != "") { $_GET["id"] = $_GET["index"]; unset($_GET["index"]); }
require_once("bootstrap.php");
if ($config["blog"]["showProcessingTime"]) $startTime = microtime(true);

if ($_GET["id"] == "0") unset($_GET["id"]);
date_default_timezone_set('Europe/Berlin');

// ====================[ cleanup $_GET["filter"] ]====================
if (!isset($_GET["filter"]) or $_GET["filter"] == "") $filter = "";
else $filter = $_GET["filter"];

// ====================[ select query ]====================
if (isset($_GET["id"]) and $_GET["id"] != "") {
  $blogposts[$_GET["id"]] = $query->selectBlogpostsById($_GET["id"]);
}
else {
  $blogposts = $query->selectAllBlogposts($filter);
}

// ====================[ print taglist ]====================
if (!isset($_GET["id"]) or $_GET["id"] == "" or $_GET["id"] == "0") {
  $tags = $query->selectAllTags();
  Filters::display($tags);
  foreach ($tags as $key => $Tag) {
    $tagname = $Tag->getdata();
    $taglist[$key] = $tagname["tag"];
  }
  echo "<div id=\"wrapper\">\n";
}

// ====================[ display ]====================
if ($blogposts)
  {
   foreach ($blogposts as $id => $Post)
     {
      $row = $Post->getdata();
      $row["head"] = str_replace('$link', $link, $row["head"]);
      $row["text"] = str_replace('$link', $link, $row["text"]);
      $row["tags"] = $query->getTagsOfBlogpost($row["id"]);

      $comments = $query->selectComments($row["id"]);
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

// ====================[ display comments ]====================
if (isset($_GET["id"]) and $_GET["id"] != "")
  {
   require_once("templates/view.comments-section.php");
  }
?>

</div>

<?php require_once("templates/view.foot.php"); ?>
