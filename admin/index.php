<?php

// catch possible traps
if (!isset($_GET["job"]) or $_GET["job"] == "") $_GET["job"] = "overview";
if (isset($_GET["id"]) and $_GET["id"] == "") unset($_GET["id"]);

require_once("php/bootstrap.php");
?>
  <body>
<?php


// ====================[ perform DB operations before showing any content ]====================
if ($_GET["job"] == "viewBlog" and $_GET["operation"] == "insertBlog") {
  $newId = $adminQuery->insertBlog($_POST);
  if ($newId === false) { $error["query_insertBlog"] = true; }
  else { $_GET["id"] = $newId; }
}
if ($_GET["job"] == "viewBlog" and $_GET["operation"] == "updateBlog") {
  if ($adminQuery->updateBlog($_POST) === false) { $error["query_updateBlog"] = true; }
//   if ($adminQuery->updateTags($_GET["id"], $_POST["tags"], $taglist) === false) { $error["query_updateTags"] = true; }
}
unset($_GET["operation"]);


if ($_GET["job"] != "addBlog") {
  // ====================[ get blogpost(s) ]====================
  if (isset($_GET["id"]) and $_GET["id"] != "") {
    $blogposts[$_GET["id"]] = $adminQuery->selectBlogpostsById($_GET["id"]);
  }
  else {
    $blogposts = $adminQuery->selectAllBlogposts($filter);
  }
}

// ====================[ display filterlist ]====================
$tags = $adminQuery->selectAllTags();
if ($_GET["job"] == "overview") {
  Filters::display($tags, "../templates");
}
foreach ($tags as $key => $Tag) {
  $tagname = $Tag->getdata();
  $taglist[$key] = $tagname["tag"];
}
unset($tags);


require_once("php/templates/navigation.php");



if($_GET["job"] != "showComments") { ?>
    <div id="top_spacer"></div>
<?php }


if ($blogposts) {
  foreach ($blogposts as $id => $Post) {
    $row = $Post->getdata();
    $row["tags"] = $adminQuery->getTagsOfBlogpost($row["id"]);
  }
}

// ====================[ very simple routing ]====================
switch($_GET["job"]) {
  case "showComments": require_once("php/templates/view.comments.php"); break;
  case "addBlog":      require_once("php/templates/view.editblog.php"); break;
  case "editBlog":     require_once("php/templates/view.editblog.php"); break;
  case "viewBlog":     require_once("php/templates/view.viewblog.php"); break;
  default:             require_once("php/templates/view.overview.php"); break;
}

require("php/templates/foot.php");

?>
