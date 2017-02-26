<?php

require_once("php/bootstrap.php");

if (isset($_POST["job"])) {
  if ($_POST["job"] == "addComment") require_once("php/lib/prepareComment.php");
}
if (isset($_GET["job"])) {
  switch($_GET["job"]) {
    case "verify": require_once("php/lib/verifyEmail.php"); break;
    case "unsubscribe": require_once("php/lib/unsubscribe.php"); break;
  }
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
      require("php/templates/view.blogpost.php");
    } else {
      require("php/templates/view.overview.php");
    }
  }
}
else {
  echo "ERROR! No data retrieved.";
}

// ====================[ display comments ]====================
if (isset($_GET["id"]) and $_GET["id"] != "") {
  require_once("php/templates/view.comments-section.php");
}
?>

</div>

<?php require_once("php/templates/view.foot.php"); ?>
