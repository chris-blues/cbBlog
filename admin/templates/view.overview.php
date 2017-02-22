
    <form action="index.php" method="get" accept-charset="UTF-8" id="formComments">
    <?php
      $getString = assembleGetString("array", array("job" => "showComments"));
      foreach ($getString as $key => $value) { ?>
      <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
      <?php } ?>
      <input type="hidden" id="formCommentsId" name="id" value="">
    </form>

    <form id="formEditBlog" action="index.php" method="get" accept-charset="UTF-8">
      <?php
        $getString = assembleGetString("array", array("job" => "editBlog"));
        foreach ($getString as $key => $value) { ?>
      <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
      <?php } ?>
      <input type="hidden" id="formBlogId" name="id" value="">
    </form>

    <div id="wrapper">
  <?php
    foreach($blogposts as $key => $Post) {
      $row = $Post->getdata();
      $comments = $query->selectComments($row["id"]);
      $row["num_comments"] = count($comments);
      ?>

      <div class="shadow overview" data-id="<?php echo $row["id"]; ?>">
        <div class="button_wrapper notes">
          <?php
            echo "<span class=\"notes\">" . convertnumbers($row["num_comments"]) . " ";
            if ($row["num_comments"] <= 1) echo gettext("comment");
            else echo gettext("comments");
            echo "</span>";

            if ($row["num_comments"] > 0) { ?>
          <button type="button" class="buttonComments" data-id="<?php echo $row["id"]; ?>"><?php echo gettext("show comments"); ?></button>
            <?php } ?>
          <br>
          <button type="button" class="editBlog" data-id="<?php echo $row["id"]; ?>"><?php echo gettext("edit post"); ?></button>
        </div>
        <div class="blogHeader">
        <?php echo $row["id"] . ") " .
                   "<b>" . $row["head"] . "</b><br>" .
                   date("Y-m-d H:i:s", $row["ctime"]) . " " .
                   "<span class=\"notes\">(" . date("Y-m-d H:i:s", $row["mtime"]) . ")</span>\n";

              $tags = $query->getTagsOfBlogpost($row["id"]);
              foreach ($tags as $key => $value) {
              $tag = $value->getdata();
            ?>
            <a class="blogpost_taglist shadow notes<?php if ($_GET["filter"] == $tag["tag"]) echo " active"; ?>"
               href="<?php echo $_SERVER["PHP_SELF"] . assembleGetString("string", array("filter" => $tag["tag"])); ?>">
              <?php echo $tag["tag"]; ?>
            </a>
            <?php
            }
            ?>
        </div>
      </div>

      <?php
    }
  ?>
    </div>
