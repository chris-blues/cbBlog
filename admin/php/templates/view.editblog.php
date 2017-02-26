<?php

switch($_GET["job"]) {
  case "editBlog": $job = "updateBlog"; break;
  case "addBlog":  $job = "insertBlog"; break;
}

foreach($row["tags"] as $key => $tag) {
  $tags[] = $tag->getdata();
}

?>

    <form action="index.php<?php echo assembleGetString("string", array("job"=>"viewBlog", "operation"=>$job, )); ?>"
          method="post"
          accept-charset="UTF-8">
      <div class="notes">
        <?php echo gettext("created on") . " <code>" . date("Y-m-d H:i", $row["ctime"]); ?></code>
        <?php echo gettext("modified on") . " <code>" . date("Y-m-d H:i", $row["mtime"]); ?></code>
      </div>
      Tags:
      <div id="tags">
        <?php foreach ($tags as $key => $tag) { ?>
        <a class="blogpost_taglist editor notes" id="<?php echo $tag["tag"]; ?>">
          <span class="tags"><?php echo $tag["tag"]; ?></span>
        </a>
        <?php } ?>
      </div><br>

      <label><?php echo gettext("heading"); ?>:<br>
        <input id="adminEditBlogTextareaHead" type="text" name="head" value="<?php echo $row["head"]; ?>">
      </label><br>
      <label><?php echo gettext("article"); ?>:<br>
        <textarea id="adminEditBlogTextareaText" name="text"><?php echo htmlspecialchars($row["text"], ENT_COMPAT | ENT_HTML5, "UTF-8"); ?></textarea>
      </label>
      <div class="center">
        <button type="reset"><?php echo gettext("reset"); ?></button>
        <button type="submit"><?php echo gettext("save"); ?></button>
      </div>
    </form>
