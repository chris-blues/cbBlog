
  <div class="clear"></div>
  <div class="comments_wrapper">
    <div class="center" id="linkshowcomments">
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
  require($GLOBALS["path"] . "/php/templates/view.comment.php");
}

if (isset($_GET["job"]) and $_GET["job"] == "verify" and $verified === true) { ?>
    <div class="notice shadow">
      <?php echo $verificationMessage; ?>
    </div>
<?php
}

require_once($GLOBALS["path"] . "/php/templates/view.postform.php");
