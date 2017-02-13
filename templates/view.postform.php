<?php

  if (isset($_POST["name"]) and $_POST["name"] != "")
    $previewName = htmlspecialchars_decode($_POST["name"], ENT_QUOTES | ENT_HTML5); else $previewName = "";
  if (isset($_POST["notificationTo"]) and $_POST["notificationTo"] != "")
    $previewNotificationTo = htmlspecialchars_decode($_POST["notificationTo"], ENT_QUOTES | ENT_HTML5); else $previewNotificationTo = "";
  if (isset($_POST["website"]) and $_POST["website"] != "")
    $previewWebsite = htmlspecialchars_decode($_POST["website"], ENT_QUOTES | ENT_HTML5); else $previewWebsite = "";
  $post = htmlspecialchars(stripcslashes($_POST["text"]), ENT_QUOTES | ENT_HTML5, "UTF-8", false);

?>

  <div class="shadow comments comment postform" name="comment" id="commentForm">

<?php
  if (isset($_POST["previewRequested"]) and $_POST["previewRequested"] == "1") {
    $time = time();
    ?>
    <div class="comments preview" id="preview">
      <h3 class="commentsHead inline"><?php echo $previewString; ?>)
        <?php
        if ($previewWebsite != "") { ?><a href="<?php echo $previewWebsite; ?>" target="_blank"><?php echo $previewName; ?></a><?php }
        else { echo "$previewName"; }
        ?>
      </h3>
      <p class="notes inline"><?php echo date("d.M.Y H:i", $time); ?></p>
      <p class="otswitch inline"><?php echo $switchOTon; ?></p>
      <div class="clear"></div>
      <?php echo parse(nl2br($post, false)); ?><br>
    </div>
    <hr>
<?php } ?>

    <form action="blog/postcomment.php" method="post" accept-charset="UTF-8">
      <?php echo gettext("name"); ?>: <span class="notes">(<?php echo gettext("optional"); ?>)</span><br>
      <input type="text" name="name" id="post_name" value="<?php echo $previewName; ?>" placeholder="<?php echo gettext("Anonymous");?>"><br>
      <p class="notes" id="email">
        <?php echo gettext("leave empty"); ?>
        <input type="email" name="email" id="post_email">
      </p>
      <?php echo gettext("notify"); ?>: <span class="notes">(<?php echo gettext("optional") . ", " . gettext("to be notified of new comments"); ?>)</span><br>
      <input type="email" name="notificationTo" id="post_notificationTo" value="<?php echo $previewNotificationTo; ?>" placeholder="you@inter.net"><br>
      <?php echo gettext("Website"); ?>: <span class="notes">(<?php echo gettext("optional"); ?>)</span><br>
      <input type="url" name="website" id="post_website" value="<?php echo $previewWebsite; ?>" placeholder="https://www.example.tld"><br>

      <?php echo gettext("Your comment"); ?>
      <button type="button" class="notes" id="switchTagHelp">
        <?php echo gettext("Notes for formatting"); ?>
      </button><br>
      <div class="hidden" id="tagHelp">
        <h4><?php echo gettext("Allowed Tags"); ?>:</h4><br>
        <table width="100%">
          <tr>
            <td width="30%">&#91;b&#93;<?php echo gettext("bold text"); ?>&#91;/b&#93;</td>
            <td><b><?php echo gettext("bold text"); ?></b></td>
          </tr>
          <tr>
            <td>&#91;u&#93;<?php echo gettext("underlined text"); ?>&#91;/u&#93;</td>
            <td><u><?php echo gettext("underlined text"); ?></u></td>
          </tr>
          <tr>
            <td>&#91;s&#93;<?php echo gettext("stroke text"); ?>&#91;/s&#93;</td>
            <td><s><?php echo gettext("stroke text"); ?></s></td>
          </tr>
          <tr>
            <td>&#91;i&#93;<?php echo gettext("italic text"); ?>&#91;/i&#93;</td>
            <td><i><?php echo gettext("italic text"); ?></i></td>
          </tr>
          <tr>
            <td>&#91;url&#93;<?php echo gettext("link"); ?>&#91;/url&#93;</td>
            <td><a href="##"><?php echo gettext("link"); ?></a></td>
          </tr>
          <tr>
            <td>&#91;code&#93;Code(\$foo);&#91;/code&#93;</td>
            <td><pre><code>Code($foo);</code></pre></td>
          </tr>
          <tr>
            <td>Text &#91;tt&#93;Code(\$foo);&#91;/tt&#93; etc</td>
            <td>Text <code>Code(\$foo);</code> etc</td>
          </tr>
          <tr>
            <td>&#91;quote&#93;<?php echo gettext("quote"); ?>&#91;/quote&#93;</td>
            <td><div class="quote"><blockquote class="inline"><?php echo gettext("quote"); ?></blockquote></div></td>
          </tr>
          <tr>
            <td>&#91;ot&#93;<?php echo gettext("off-topic"); ?>&#91;/ot&#93;</td>
            <td><span class="offtopic"><?php echo gettext("off-topic"); ?></span></td>
          </tr>
          <tr>
            <td>&#91;done&#93;</td>
            <td><span class="checkmark">&#10004;</span></td>
          </tr>
        </table>
      </div>
      <textarea name="text" id="post_text"><?php echo $post; ?></textarea>
        <input type="hidden" name="affiliation" value="<?php echo $_GET["id"]; ?>">
        <?php
        foreach ($_GET as $key => $value) {
          if ($key == "id") continue;
        ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
  <?php } ?>
        <input type="hidden" name="preview" value="0" id="switchPreview">

        <div class="button_wrapper">
        <?php
        foreach ($insertTags as $tag => $value) { ?>
          <button type="button" class="tagButton"
                  data-valueOpen="<?php echo $insertTags[$tag]["open"]; ?>"
                  data-valueClose="<?php echo $insertTags[$tag]["close"]; ?>">
            <?php echo $insertTags[$tag]["open"]; ?>
          </button>
  <?php } ?>
          <button type="button" id="smileyButton">Smileys ☺</button>
          <div class="smileys" id="smileys">
            <?php
              require_once("config/smileys.php");
              foreach ($smileyFile as $key => $value) {
                $smiley = trim($value);
            ?><span class="smiley" data-id="<?php echo $smiley; ?>"><?php echo $smiley; ?></span><?php } ?>
        </div>
      </div>
      <div class="centered">
        <button type="reset"> &lt;&lt;&lt; <?php echo gettext("Back"); ?> </button>
        <button type="button" id="buttonPreview"> <?php echo gettext("Preview"); ?> </button>
        <button type="submit" id="buttonSend"> <?php echo gettext("Send"); ?> &gt;&gt;&gt; </button>
      </div>
    </form>
  </div>
