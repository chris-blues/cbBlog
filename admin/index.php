<?php

$startTime = microtime(true);

// catch possible traps
if (!isset($_GET["job"]) or $_GET["job"] == "") $_GET["job"] = "overview";
if (isset($_GET["id"]) and $_GET["id"] == "") unset($_GET["id"]);

require_once("php/bootstrap.php");
?>
  <body>
    <div id="localeData"
         data-reallyDelete="<?php echo gettext("Do you really want to delete this?"); ?>"
    ></div>
<?php

// ====================[ perform DB operations before showing any content ]====================
if ($_POST["job"] == "deleteComment") {
  $result = $adminQuery->deleteComment($_POST["id"]);
  if ($result !== true) {
    $error["query_deleteComment"] = $result;
  }
}
if ($_GET["job"] == "deleteBlog") {
  $result = $adminQuery->deleteBlog($_GET["id"]);
  if ($result !== true) {
    $error["query_deleteBlog"] = $result;
  }
  $_GET["job"] = "overview";
  unset($_GET["id"]);
}
if ($_GET["job"] == "viewBlog" and $_GET["operation"] == "insertBlog") {
  $newId = $adminQuery->insertBlog($_POST);
  if (!is_numeric($newId)) {
    $error["query_insertBlog"] = $newId;
  }
  else {
    $_GET["id"] = $newId;
  }
}
if ($_GET["job"] == "viewBlog" and $_GET["operation"] == "updateBlog") {
  $result = $adminQuery->updateBlog($_POST);
  if ($result !== true) { $error["query_updateBlog"] = $result; }
}
unset($_GET["operation"]);



if (isset($error)) { ?>
      <div id="errors" class="notes remark">
        <h2><?php echo gettext("The following errors have occured"); ?></h2>
        <ol>
          <?php if (isset($error)) displayErrors($error); ?>
        </ol>
      </div>
    <?php }


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
