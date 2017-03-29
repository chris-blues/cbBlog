<?php

  include_once("php/lib/Blogpost0_13.php");
//   echo "php/lib/Blogpost0_13.php included!<br>\n";
  include_once("../php/lib/Tags.php");
//   echo "../php/lib/Tags.php included!<br>\n";

  // =============[ read DB ]=============
  $blogposts0_13 = $adminQuery->selectOldBlogposts();
  // now we have all blogs in one array $query
  // eg: $query[0] => "object Blogpost0_13->tags"
//   dump_array($blogposts0_13);

  $Tags0_13 = $adminQuery->selectOldTags();
//   dump_array($Tags0_13);

  // =============[ action ]=============

  foreach ($Tags0_13 as $key => $Tags) {
    $row = $Tags->getdata();
    $tags[$row["id"]] = $row["tag"];
  }
//   dump_array($tags);

  foreach ($blogposts0_13 as $key => $Post) {
    $row = $Post->getdata();
    $posts[$row["index"]] = explode(" ", $row["tags"]);
  }
//   dump_array($posts);

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
//   dump_var($DBqueryString);
  $result = $adminQuery->insertOldTags($DBqueryString);

  if ($result) {
    echo "Blog-Tags-Relations successfuly written!<br>Removing Column `tags` from table `blog`... ";
    $result = $adminQuery->dropTags();
    if ($result) { echo "[ OK ]<br>\n"; }
    else { echo "<br>\nError! Could not drop column tags...\n"; }
  }
  else {
    echo "Error! Could not write table `blog_tags_relations`! Exiting.<br>\n";
  }
  $result = $adminQuery->renameIndex();
  if ($result === true) echo "`blog`.`index` -&gt; `blog`.`id`<br>\n";
  else {
    echo "Error renaming `blog`.`index` -&gt; `blog`.`id`<br>\n";
  }

  // =============[ rename tables ]=============

  $tables = array("blog-comments" => "", "blog-tags" => "");

  foreach ($tables as $key => $value) {
    $result = $adminQuery->renameTable($key, str_replace("-", "_", $key));
    echo "renameTable($key): ";
    if ($result !== true) echo "ERROR!<br>\n";
    else echo "[ OK ]<br>\n";

    if ($result !== true) $error["renameTable"][$key] = $result;
  }

?>
