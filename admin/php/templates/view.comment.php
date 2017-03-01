
<?php
// clean up linebreaks and PDO escapings
$search = array("\\r\\n", "\r\n", "\\0", "\\");
$replace = array("\n", "\n", "0", "");
$post = str_replace($search, $replace, $row["comment"]);
$post = htmlspecialchars($post, ENT_QUOTES | ENT_HTML5, "UTF-8", false);
$text = parse(nl2br($post, false));
?>

<div id="wrapper">
  <div class="comments shadow" id="<?php echo $row["id"]; ?>" data-text="<?php echo $post; ?>">
    <button class="buttonDeleteComment" type="button" data-id="<?php echo $row["id"]; ?>"><?php echo gettext("delete comment"); ?></button>
    <h3 class="commentsHead">
      <a href="<?php echo "#" . $row["id"] ?>"><?php echo $counter; ?></a>)
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
    <div class="clear"></div>
    <div class="commentText" data-id="<?php echo $row["id"]; ?>">
      <?php echo $text; ?>
    </div>
    <div id="wrapper_<?php echo $row["id"]; ?>"></div>
  </div>
  <?php unset($post); ?>
</div>
