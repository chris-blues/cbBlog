<?php

$startTime = microtime(true);

$GLOBALS["path"] = realpath(dirname(__FILE__));

// catch possible traps
if (!isset($_GET["job"]) or $_GET["job"] == "") $_GET["job"] = "overview";
if (isset($_GET["id"]) and $_GET["id"] == "") unset($_GET["id"]);
if (isset($_GET["category"]) and $_GET["category"] == "") unset($_GET["category"]);


require_once($GLOBALS["path"] . "/php/bootstrap.php");

if (isset($GLOBALS["DBdisconnected"]) and $GLOBALS["DBdisconnected"] === true) {
  // take the shortcut to the settings view and die.
  require_once($GLOBALS["path"] . "/php/templates/view.settings.php");
  require_once($GLOBALS["path"] . "/php/templates/foot.php");
  die;
}

?>

    <div id="localeData"
         data-reallyDelete="<?php echo gettext("Do you really want to delete this?"); ?>"
    ></div>
<?php


// ====================[ perform DB operations before showing any content ]====================

$result = $adminQuery->createTables();
if ($result !== true) {
  $error["createTables"] = $result;
}

if (isset($GLOBALS["DatabaseUpdateNeeded"]) and $GLOBALS["DatabaseUpdateNeeded"] < 0.14) {
  echo "<p class=\"remark\">Calling Database Upgrade (0.13 -&gt; 0.14)<br>\n";
  require_once($GLOBALS["path"] . "/php/lib/upgrade0.13-0.14.php");
  echo "</p>\n";
}

if ($_GET["job"] != "settings") {
  if (isset($_POST["job"]) and $_POST["job"] == "deleteComment") {
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
    $RSSupdateNeeded = true;
    $_GET["job"] = "overview";
    unset($_GET["id"]);
  }
  if ($_GET["job"] == "viewBlog" and isset($_GET["operation"]) and $_GET["operation"] == "insertBlog") {
    $newId = $adminQuery->insertBlog($_POST);
    if (!is_numeric($newId)) {
      $error["query_insertBlog"] = $newId;
    }
    else {
      $_GET["id"] = $newId;
      $RSSupdateNeeded = true;
    }
  }
  if ($_GET["job"] == "viewBlog" and isset($_GET["operation"]) and $_GET["operation"] == "updateBlog") {
    $result = $adminQuery->updateBlog($_POST);
    if ($result !== true) { $error["query_updateBlog"] = $result; }
    else $RSSupdateNeeded = true;
  }
  unset($_GET["operation"]);
}



if (isset($error)) { ?>
      <div id="errors" class="notes remark">
        <h2><?php echo gettext("The following errors have occured"); ?></h2>
        <ol>
          <?php if (isset($error)) displayErrors($error); ?>
        </ol>
      </div>
    <?php }


if ($_GET["job"] != "addBlog" and $_GET["job"] != "settings") {


  // ====================[ get blogpost(s) ]====================
  if (isset($_GET["id"]) and $_GET["id"] != "") {
    $blogposts[$_GET["id"]] = $adminQuery->selectBlogpostsById($_GET["id"]);
  }
  else {
    if (!isset($_GET["category"]) or $_GET["category"] == "" or $_GET["category"] == "released") {
      $category = "unreleased";
      $blogposts = $adminQuery->selectAllBlogposts($filter);
    }
    else {
      $category = "released";
      $blogposts = $adminQuery->selectAllUnreleasedBlogposts($filter);
    }
  }
}


// ====================[ update RSS ]====================
if (isset($RSSupdateNeeded) and $RSSupdateNeeded === true) {

  require_once($GLOBALS["path"] . "/php/lib/RSS.php");

  foreach ($config["feeds"] as $key => $feed) {

    $Feed[$key] = new RSS ($adminQuery, $feed);
    if (is_array($Feed[$key])) displayErrors($Feed[$key]);

    if ($feed["tag"] == "") $filename = "rss-feed.xml";
    else $filename = "rss-feed-" . $feed["tag"] . ".xml";

    $result = $Feed[$key]->writeRSS($filename);
    if ($result !== true) displayErrors($result);

  }

}


// ====================[ display filterlist ]====================
if ($_GET["job"] != "settings") {
  $tags = $adminQuery->selectAllTags();
  if ($_GET["job"] == "overview") {
    Filters::display($tags, "../templates");
  }
  foreach ($tags as $key => $Tag) {
    $tagname = $Tag->getdata();
    $taglist[$key] = $tagname["tag"];
  }
  unset($tags);
}


require_once($GLOBALS["path"] . "/php/templates/navigation.php");



if($_GET["job"] != "showComments") { ?>
    <div id="top_spacer"></div>
<?php }


if (isset($blogposts) and $blogposts) {
  foreach ($blogposts as $id => $Post) {
    $row = $Post->getdata();
    $row["tags"] = $adminQuery->getTagsOfBlogpost($row["id"]);
  }
}

// ====================[ very simple routing ]====================
switch($_GET["job"]) {
  case "showComments": require_once($GLOBALS["path"] . "/php/templates/view.comments.php"); break;
  case "addBlog":      require_once($GLOBALS["path"] . "/php/templates/view.editblog.php"); break;
  case "editBlog":     require_once($GLOBALS["path"] . "/php/templates/view.editblog.php"); break;
  case "viewBlog":     require_once($GLOBALS["path"] . "/php/templates/view.viewblog.php"); break;
  case "settings":     require_once($GLOBALS["path"] . "/php/templates/view.settings.php"); break;
  default:             require_once($GLOBALS["path"] . "/php/templates/view.overview.php"); break;
}

// ====================[ display errors ]====================

if (isset($error)) { ?>
      <div class="remark">
        <h1><?php echo gettext("Whoops! Something isn't right..."); ?></h1>
        <div id="errors" class="commentText">
          <p><?php echo gettext("The following errors have occured"); ?></p>
          <ol>
            <?php if (isset($error)) showErrors($error); ?>
          </ol>
        </div>
      </div>
    <?php
}

require_once($GLOBALS["path"] . "/php/templates/foot.php");

?>
