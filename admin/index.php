<?php

if (!isset($_GET["job"]) or $_GET["job"] == "") $_GET["job"] = "overview";

require_once("php/bootstrap.php");

?>

  <body>
    <div id="navigation">
      <div id="navLinks"
           data-backLink="<?php echo assembleGetString("string", array("job"=>"overview", "id"=>"")); ?>"
           data-editLink="<?php echo assembleGetString("string", array("job"=>"editBlog", "id"=>$_GET["id"])); ?>"
           data-viewLink="<?php echo assembleGetString("string", array("job"=>"viewBlog", "id"=>$_GET["id"])); ?>"
      >
      </div>

<?php

if ($_GET["job"] != "overview") { ?>
      <button type="button" id="buttonBack"><?php echo gettext("back"); ?></button>
<?php }
if ($_GET["job"] == "viewBlog") { ?>
      <button type="button" id="buttonEditBlogpost"><?php echo gettext("edit blogpost"); ?></button>
<?php }
if ($_GET["job"] == "editBlog") { ?>
      <button type="button" id="buttonViewBlogpost"><?php echo gettext("view blogpost"); ?></button>
<?php } ?>
    </div>

<?php
// ====================[ get blogpost(s) ]====================
if (isset($_GET["id"]) and $_GET["id"] != "") {
  $blogposts[$_GET["id"]] = $query->selectBlogpostsById($_GET["id"]);
}
else {
  $blogposts = $query->selectAllBlogposts($filter);
}

// ====================[ display filterlist ]====================
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
    $row["tags"] = $query->getTagsOfBlogpost($row["id"]);
  }
}

// ====================[ very simple routing ]====================
switch($_GET["job"]) {
  case "showComments": require_once("php/templates/view.comments.php"); break;
  case "editBlog":     require_once("php/templates/view.editblog.php"); break;
  case "viewBlog":     require_once("php/templates/view.viewblog.php"); break;
  default:             require_once("php/templates/view.overview.php"); break;
}

require("php/templates/foot.php");

?>
