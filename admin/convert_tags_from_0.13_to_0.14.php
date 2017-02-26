<?php
$startTime = microtime(true);
require_once("../templates/view.head.php");
require_once("functions.php");

// ##########################
// ##  Debugging settings  ##
// ##########################
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "admin/logs/php-error.log");
$debug = true;

$config["database"] = require_once("../php/config/db.php");
$config["blog"] = require_once("../php/config/blog.php");
require_once("Blogpost0_13.php");
require_once("Tags.php");

require_once("../lib/db/Connection.php");
require_once("QueryBuilder.php");

// =============[ Connect to DB ]=============
$DB = new QueryBuilder(
  Connection::make($config["database"])
);

// =============[ read DB ]=============
$blogposts0_13 = $DB->selectOldBlogposts();
// now we have all blogs in one array $query
// eg: $query[0] => "object Blogpost0_13->tags"

$Tags0_13 = $DB->selectOldTags();

// =============[ action ]=============

foreach ($Tags0_13 as $key => $Tags) {
  $row = $Tags->getdata();
  $tags[$row["id"]] = $row["tag"];
}
// dump_array($tags);

foreach ($blogposts0_13 as $key => $Post) {
  $row = $Post->getdata();
  $posts[$row["id"]] = explode(" ", $row["tags"]);
}
// dump_array($posts);
?>

  <?php
    $DBqueryString = "INSERT INTO `blog_tags_relations`\n (`blog`, `tag`) VALUES\n ";
    $counter = 0;
    foreach ($posts as $postID => $post) {
      foreach ($post as $tagname) {
        $tagID = array_search($tagname, $tags);
        if ($counter == 0) $DBqueryString .= "('{$postID}', '{$tagID}')";
        else               $DBqueryString .= ",\n ('{$postID}', '{$tagID}')";
        $counter++;
      }
    }
    $DBqueryString .= " ; ";
//     dump_var($DBqueryString);
    $result = $DB->insertOldTags($DBqueryString);

    if ($result) {
      echo "Blog-Tags-Relations successfuly written! Removing Column `tags` from table `blog`... ";
      $result = $DB->dropTags();
      if ($result) { echo "[ ok ]<br>\n"; }
      else { echo "<br>\nError! Could not drop column tags...\n"; }
    }
    else {
      echo "Error! Could not write table `blog_tags_relations`! Exiting.<br>\n";
    }


  ?>


<?php require_once("../templates/view.foot.php"); ?>
