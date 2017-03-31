<?php

$GLOBALS["path"] = realpath(dirname(__FILE__));
$locale_path = "";

$startTime = microtime(true);

$GLOBALS["displayMode"] = "full";
require_once($GLOBALS["path"] . "/php/bootstrap.php");

if (isset($_POST["job"])) {
  if ($_POST["job"] == "addComment") require_once($GLOBALS["path"] . "/php/lib/prepareComment.php");
}
if (isset($_GET["job"])) {
  switch($_GET["job"]) {
    case "verify": require_once($GLOBALS["path"] . "/php/lib/verifyEmail.php"); break;
    case "unsubscribe": require_once($GLOBALS["path"] . "/php/lib/unsubscribe.php"); break;
  }
}

if (!$GLOBALS["DBdisconnected"]) {
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
}


// ====================[ display blogposts ]====================
if (isset($blogposts)) {
  if (count($blogposts) < 1) {
    $error["Database"]["no_content_found"] = gettext("We were not able to find anything. Either there's nothing posted yet, of there's a problem with the database connection.");
  }

  foreach ($blogposts as $id => $Post) {
    $row = $Post->getdata();
    $row["head"] = str_replace('$link', $link, $row["head"]);
    $row["text"] = str_replace('$link', $link, $row["text"]);
    $row["tags"] = $query->getTagsOfBlogpost($row["id"]);
    foreach ($row["tags"] as $Tag) {
      $tmp = $Tag->getdata();
      $tempArray[$tmp["id"]] = $tmp["tag"];
      unset($tmp);
    }
    if (in_array("unreleased", $tempArray)) {
      unset($blogposts[$id], $tempArray);
      continue;
    }
    unset($tempArray);

    $comments = $query->selectComments($row["id"]);
    $row["num_comments"] = count($comments);

    if (isset($_GET["id"]) and $_GET["id"] != "") {
      $row["comments"] = $comments;
      require($GLOBALS["path"] . "/php/templates/view.blogpost.php");
    } else {
      require($GLOBALS["path"] . "/php/templates/view.overview.php");
    }
  }
}

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

// ====================[ display comments ]====================
if (isset($_GET["id"]) and $_GET["id"] != "") {
  require_once($GLOBALS["path"] . "/php/templates/view.comments-section.php");
}
?>

</div>

<?php if ($config["blog"]["standalone"]) require_once($GLOBALS["path"] . "/php/templates/view.foot.php"); ?>
