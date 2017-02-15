<?php

require_once("bootstrap.php");

// ====================[ get blogpost(s) ]====================
if (isset($_GET["id"]) and $_GET["id"] != "") {
  $blogposts[$_GET["id"]] = $query->selectBlogpostsById($_GET["id"]);
}
else {
  $blogposts = $query->selectAllBlogposts($filter);
}

// ====================[ get the comments for each blogpost ]====================

// ====================[ get all tags ]====================
$tags = $query->selectAllTags();
if ($blogposts)
  {
   foreach ($blogposts as $id => $Post)
     {
      $row = $Post->getdata();
}

?>

  <body>

<?php

dump_array($tags);
dump_array($blogposts);

?>
<?php require("templates/foot.php"); ?>
