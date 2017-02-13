
  <div class="clear"></div>
  <div class="comments_wrapper">
    <div class="centered" id="linkshowcomments">
      <h2><?php echo gettext("comments"); ?></h2>
    </div>

    <div id="langOT" class="hidden"
         data-OTon="<?php echo gettext("show off-topic comments"); ?>"
         data-OToff="<?php echo gettext("hide off-topic comments"); ?>">
    </div>

  <?php

$counter = 0;
foreach ($row["comments"] as $Comment) {
  $counter++;
  $row = $Comment->getdata();
  require("templates/view.comment.php");
}

require_once("templates/view.postform.php");
