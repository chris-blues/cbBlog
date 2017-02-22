<?php

if (!isset($_GET["job"]) or $_GET["job"] == "") $_GET["job"] = "overview";

require_once("bootstrap.php");

?>

  <body>

<?php

// ====================[ get blogpost(s) ]====================
if (isset($_GET["id"]) and $_GET["id"] != "") {
  $blogposts[$_GET["id"]] = $query->selectBlogpostsById($_GET["id"]);
}
else {
  $blogposts = $query->selectAllBlogposts($filter);
}

// ====================[ get the comments for each blogpost ]====================

// ====================[ get all tags ]====================
if ($_GET["job"] == "overview") {
  $tags = $query->selectAllTags();
  Filters::display($tags, "../templates");
  foreach ($tags as $key => $Tag) {
    $tagname = $Tag->getdata();
    $taglist[$key] = $tagname["tag"];
  }
}

if ($blogposts) {
  foreach ($blogposts as $id => $Post) {
    $row = $Post->getdata();
  }
}

switch($_GET["job"]) {
  case "showComments": require_once("templates/view.comments.php"); break;
  case "editBlog":     require_once("templates/view.editblog.php"); break;
  default:             require_once("templates/view.overview.php"); break;
}

require("templates/foot.php");

?>
