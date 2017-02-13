
  <div class="shadow blogentryoverview">
    <p class="notes blogdate">
      <?php echo date("d.M.Y H:i",$row['ctime']); ?> - <?php echo gettext("last update"); ?>: <?php echo date("d.M.Y H:i",$row['mtime']); ?>
    </p>

    <?php $num_comments = convertnumbers($row["num_comments"], $lang); ?>
    <p class="notes commentslink">
      <a href="<?php echo $_SERVER["PHP_SELF"] . assembleGetString(array("id" => $row["id"], "filter" => $filter)); ?>#linkshowcomments">
        <?php
          if ($row["num_comments"] > 0) {
            echo $num_comments . " ";
            if ($row["num_comments"] == 1) echo gettext("comment");
            else echo gettext("comments");
          }
          else {
            echo gettext("no comments");
          }
        ?>
      </a>
    </p>

    <?php $head = strip_tags($row["head"]); ?>
    <h1 class="bloghead">
      <a href="<?php echo $_SERVER["PHP_SELF"] . assembleGetString(array("id" => $row["id"], "filter" => $filter)); ?>">
        <?php echo $head; ?>
      </a>
    </h1>

    <?php
    if ($row["text"] != "" or !isset($row["text"])) {
      $text = preg_replace('/\s+/', ' ', strip_tags($row["text"], '<p>'));
      if (strlen($text) > 250) { $shorttext = substr($text,0,250) . "..."; }
      else { $shorttext = $text; }
    ?>
    <p class="clear blogshorttext">
      <?php echo $shorttext; ?>
    </p>
    <p class="notes inline">
      <?php echo $row["tags"] ?>
    </p>
    <?php } ?>
  </div>
