<?php

foreach ($blogposts as $key => $Post) {
  $post = $Post->getdata();
}

switch($_GET["job"]) {
  case "editBlog": $job = "updateBlog"; break;
  case "addBlog":  $job = "insertBlog"; break;
}

?>

    <form action="index.php?job=<?php echo $job; ?>&amp;id=<?php echo $_GET["id"]; ?>&amp;filter=<?php echo $_GET["filter"]; ?>"
          method="post"
          accept-charset="UTF-8">
      <?php echo gettext("created on") . " <code>" . date("Y-m-d H:i", $post["ctime"]); ?></code><br>
      <?php echo gettext("modified on") . " <code>" . date("Y-m-d H:i", $post["mtime"]); ?></code><br>
      <label><?php echo gettext("heading"); ?>:<br>
        <input id="adminEditBlogTextareaHead" type="text" name="head" value="<?php echo $post["head"]; ?>">
      </label><br>
      <label><?php echo gettext("article"); ?>:<br>
        <textarea id="adminEditBlogTextareaText" name="text"><?php echo htmlspecialchars($post["text"], ENT_COMPAT | ENT_HTML5, "UTF-8"); ?></textarea>
      </label>
    </form>

