
    <form id="formComments" action="index.php" method="get" accept-charset="UTF-8">
    <?php
      $getString = assembleGetString("array", array("job" => "showComments", "category" => ""));
      foreach ($getString as $key => $value) { ?>
      <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
      <?php } ?>
      <input type="hidden" id="formCommentsId" name="id" value="">
    </form>

    <form id="formEditBlog" action="index.php" method="get" accept-charset="UTF-8">
      <?php
        $getString = assembleGetString("array", array("job" => "", "category" => ""));
        foreach ($getString as $key => $value) { ?>
      <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
      <?php } ?>
      <input type="hidden" id="formJob" name="job" value="">
      <input type="hidden" id="formBlogId" name="id" value="">
      <input type="hidden" id="category" name="category" value="">
    </form>

    <div id="wrapper">
  <?php
    foreach($blogposts as $key => $Post) {

      $row = $Post->getdata();
      $comments = $query->selectComments($row["id"]);
      $row["num_comments"] = count($comments);

      $tags = $query->getTagsOfBlogpost($row["id"]);
      foreach ($tags as $key => $value) {
        $tmp = $value->getdata();
        $temp[$tmp["id"]] = $tmp["tag"];
      }
      if (in_array($category, $temp)) {
        unset($blogposts[$key], $temp);
        continue;
      }
      unset($temp);

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
          <button type="button" class="editBlog" data-id="<?php echo $row["id"]; ?>"><?php echo gettext("edit blogpost"); ?></button>
          <button type="button" class="deleteBlog" data-id="<?php echo $row["id"]; ?>"><?php echo gettext("delete blogpost"); ?></button>
        </div>
        <div class="blogHeader">
          <?php echo $row["id"]; ?>)
          <a href="index.php<?php echo assembleGetString("string", array("job" => "viewBlog", "id" => $row["id"])); ?>">
            <b><?php echo $row["head"]; ?></b><br>
          </a>
          <?php echo date("Y-m-d H:i:s", $row["ctime"]); ?>
          <span class="notes">(<?php echo date("Y-m-d H:i:s", $row["mtime"]); ?>)</span>
            <?php
              $tags = $query->getTagsOfBlogpost($row["id"]);
              foreach ($tags as $key => $value) {
              $tag = $value->getdata();
            ?>
            <a class="blogpost_taglist shadow notes<?php if (isset($_GET["filter"]) and $_GET["filter"] == $tag["tag"]) echo " active"; if ($tag["tag"] == "unreleased") echo " unreleased"; ?>"
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
