<?php
  // clean up linebreaks and PDO escapeings
  $search = array("\\r\\n", "\r\n", "\\0", "\\");
  $replace = array("\n", "\n", "0", "");
  $post = str_replace($search, $replace, $row["comment"]);
  $post = htmlspecialchars($post, ENT_QUOTES | ENT_HTML5, "UTF-8", false);
  $post = parse(nl2br($post, false));
?>

  <div class="comments shadow" id="<?php echo $row["time"]; ?>">
    <h3 class="commentsHead">
      <a href="<?php echo "#" . $row["time"] ?>"><?php echo $counter; ?></a>)
        <?php
        if ($row["website"] != "") {
          $externalUrl = true;
          if (strncmp($row["website"], "http://" . $_SERVER["HTTP_HOST"], strlen("http://" . $_SERVER["HTTP_HOST"])) == 0) $externalUrl = false;
          if (strncmp($row["website"], "https://" . $_SERVER["HTTP_HOST"], strlen("https://" . $_SERVER["HTTP_HOST"])) == 0) $externalUrl = false;

          if ($externalUrl) $linkWebsite = "<a href=\"{$row["website"]}\" target=\"_blank\">{$row["name"]}</a>";
          else              $linkWebsite = "<a href=\"{$row["website"]}\">{$row["name"]}</a>";
          echo $linkWebsite;
        }
        else echo $row["name"];
        ?>
    </h3>
    <p class="notes inline"><?php echo date("d.M.Y H:i",$row["time"]); ?></p>
    <?php if (isset($GLOBALS["ot"]) and $GLOBALS["ot"] === true) { ?>
    <button type="button" class="otswitch inline"><?php echo gettext("show off-topic comments"); ?></button>
    <?php }
      unset ($GLOBALS["ot"]); ?>
    <div class="clear"></div>
    <div class="commentText"><?php echo $post; ?></div>
  </div>
  <?php unset($post); ?>
