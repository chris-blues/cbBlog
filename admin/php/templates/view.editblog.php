<?php

switch($_GET["job"]) {
  case "editBlog": $job = "updateBlog"; break;
  case "addBlog":  $job = "insertBlog"; break;
}

foreach($row["tags"] as $key => $tag) {
  $tags[] = $tag->getdata();
}

$tmpTags = $query->selectAllTags();
foreach ($tmpTags as $key => $value) {
  $tmp = $value->getdata();
  $allTags[$tmp["id"]] = $tmp["tag"];
}
asort($allTags);
?>
    <div class="allTags_wrapper">
      <p><?php echo gettext("available tags"); ?>:</p>
<?php
foreach ($allTags as $id => $tag) { ?>
      <a class="blogpost_availableTags editor notes" id="<?php echo $tag; ?>"><?php echo $tag; ?></a>
<?php }

?>
    </div>
    <div id="smileyTarget" data-target="adminEditBlogTextareaText"></div>
    <form action="index.php<?php echo assembleGetString("string", array("job"=>"viewBlog", "operation"=>$job, )); ?>"
          method="post"
          accept-charset="UTF-8">
      <?php if($_GET["job"] == "editBlog") { ?>
      <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
      <?php } ?>
      <div class="notes">
        <?php echo gettext("created on") . " <code>" . date("Y-m-d H:i", $row["ctime"]); ?></code>
        <?php echo gettext("modified on") . " <code>" . date("Y-m-d H:i", $row["mtime"]); ?></code>
      </div>
      Tags:
      <div id="tags">
        <?php foreach ($tags as $key => $tag) { ?>
        <input class="tagFields" type="hidden" name="tags[]" value="<?php echo $tag["tag"]; ?>">
        <?php } ?>
        <?php foreach ($tags as $key => $tag) { ?>
        <a class="blogpost_taglist editor notes" id="<?php echo $tag["tag"]; ?>">
          <?php echo $tag["tag"]; ?>
        </a>
        <?php } ?>
      </div><br>

      <label><?php echo gettext("heading"); ?>:<br>
        <input id="adminEditBlogTextareaHead" type="text" name="head" value="<?php echo $row["head"]; ?>">
      </label><br>
      <label><?php echo gettext("article"); ?>:<br>
        <textarea id="adminEditBlogTextareaText" name="text"><?php echo htmlspecialchars($row["text"], ENT_COMPAT | ENT_HTML5, "UTF-8"); ?></textarea>
      </label>

      <?php $path = "../"; require_once("../php/templates/view.smileys.php"); ?>

      <div class="center">
        <button type="reset"><?php echo gettext("reset"); ?></button>
        <button type="submit"><?php echo gettext("save"); ?></button>
      </div>
    </form>
