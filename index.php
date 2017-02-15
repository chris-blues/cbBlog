<?php

require_once("bootstrap.php");

if (isset($_POST["job"]) and $_POST["job"] == "addComment") {
  require_once("lib/prepareComment.php");
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
if ($blogposts) {
  foreach ($blogposts as $id => $Post) {
    $row = $Post->getdata();
    $row["head"] = str_replace('$link', $link, $row["head"]);
    $row["text"] = str_replace('$link', $link, $row["text"]);
    $row["tags"] = $query->getTagsOfBlogpost($row["id"]);

    $comments = $query->selectComments($row["id"]);
    $row["num_comments"] = count($comments);

    if (isset($_GET["id"]) and $_GET["id"] != "") {
      $row["comments"] = $comments;
      require("templates/view.blogpost.php");
    } else {
      require("templates/view.overview.php");
    }
  }
//   echo "</div>\n";
}
else {
  echo "ERROR! No data retrieved.";
}

// ====================[ display comments ]====================
if (isset($_GET["id"]) and $_GET["id"] != "") {
  require_once("templates/view.comments-section.php");
}
?>

</div>

<?php require_once("templates/view.foot.php"); ?>
