
<?php $getString = assembleGetString("array", array("job" => "showComments")); ?>


    <form action="index.php" method="get" accept-charset="UTF-8" id="formComments">
    <?php foreach ($getString as $key => $value) { ?>
      <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
      <?php } ?>
      <input type="hidden" id="formCommentsId" name="id" value="">
    </form>

    <div id="wrapper">
  <?php
    foreach($blogposts as $key => $Post) {
      $data = $Post->getdata();
      ?>

      <div class="shadow overview" data-id="<?php echo $data["id"]; ?>">
        <?php echo $data["id"] . ") " . date("Y-m-d H:i:s", $data["ctime"]) . " " . $data["head"] . "\n"; ?>
        <div class="button_wrapper notes">
          <button type="button" class="buttonComments" data-id="<?php echo $data["id"]; ?>"><?php echo gettext("show comments"); ?></button>
        </div>
        <div class="blogentryfull" id="<?php echo $data["id"]; ?>">
          <div class="notes tags">
            <?php
              $tags = $query->getTagsOfBlogpost($data["id"]);
              foreach ($tags as $key => $value) {
              $tag = $value->getdata();
            ?>
            <a class="blogpost_taglist shadow<?php if ($_GET["filter"] == $tag["tag"]) echo " active"; ?>"
               href="<?php echo $_SERVER["PHP_SELF"] . assembleGetString("string", array("filter" => $tag["tag"])); ?>">
              <?php echo $tag["tag"]; ?>
            </a>
            <?php
            }
            ?>
          </div>
          <hr>
          <h1><?php echo $data["head"]; ?></h1>
          <?php echo $data["text"]; ?>
        </div>
      </div>

      <?php
    }
  ?>
    </div>
