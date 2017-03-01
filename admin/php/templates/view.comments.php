<?php

if ($_POST["job"] == "editComment") {
  $adminQuery->updateComment($_POST["id"], $_POST["text"]);
}

?>

  <form id="formEditComment" action="index.php<?php echo assembleGetString("string"); ?>" method="post" accept-charset="UTF-8">
    <input type="hidden" name="job" value="editComment">
    <input type="hidden" id="commentId" name="id" value="">
    <input type="hidden" id="commentText" name="text" value="">
  </form>
  <div id="smileyTarget" data-target="post_text"></div>

  <div id="editor_wrapper" class="hidden">
    <div id="editor">
      <div id="data"></div>
      <textarea class="commentTextArea" id="post_text"></textarea>
      <?php
      foreach ($insertTags as $tag => $value) { ?>
        <button type="button" class="tagButton"
                data-valueOpen="<?php echo $insertTags[$tag]["open"]; ?>"
                data-valueClose="<?php echo $insertTags[$tag]["close"]; ?>">
        <?php echo $insertTags[$tag]["open"]; ?>
        </button>
      <?php } ?>
      <?php $path = "../"; require("../php/templates/view.smileys.php"); ?><br>
      <button type="reset" id="buttonReset"><?php echo gettext("reset"); ?></button>
      <button type="button" id="buttonSave"><?php echo gettext("save"); ?></button>
    </div>
  </div>

  <div class="comments_wrapper">
    <div id="linkshowcomments"></div>

<?php
$blogpost = $query->selectBlogpostsById($_GET["id"]);
$post = $blogpost->getdata();
?>
  <h1 class="center"><?php echo $post["id"] . ") " .  $post["head"]; ?></h1>
<?php
$comments = $query->selectComments($_GET["id"]);
$counter = 0;
foreach ($comments as $Comment) {
  $counter++;
  $row = $Comment->getdata();
  require("php/templates/view.comment.php");
}

?>
  </div>
