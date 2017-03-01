
  <div class="shadow blogentryfull">
    <form action="<?php echo $_SERVER["PHP_SELF"];?>"
          method="get"
          accept-charset="UTF-8"
          id="backForm"
          >
      <?php
        $getVars = assembleGetString("array", array("id" => "", "index" => ""));
        foreach ($getVars as $key => $value) { ?>
          <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
      <?php } ?>
      <button type="submit"> &lt;&lt;&lt; <?php echo gettext("back"); ?> </button>
    </form>

    <div id="<?php echo $row["id"]; ?>" class="clear">
      <p class="notes inline">tags:
        <?php
        foreach ($row["tags"] as $Tag) {
          $tempArray = $Tag->getdata();
          if ($tempArray["tag"] == "") continue;
          echo "<a class=\"blogpost_taglist\" href=\"{$_SERVER["PHP_SELF"]}" . assembleGetString("string", array("filter" => $tempArray["tag"])) . "\">" . $tempArray["tag"] . "</a> ";
        }
        ?>
      </p>

      <p class="notes right" id="permaLink">Permalink: <?php $querystring = assemblePermaLink($config["blog"]["permalinkIgnore"]); ?>
        <a href="<?php echo $_SERVER["HTTP_X_FORWARDED_PROTO"]; ?>://<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $querystring; ?>">
          <?php echo $_SERVER["HTTP_X_FORWARDED_PROTO"]; ?>://<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?" . $querystring; ?>
        </a>
      </p>

      <hr class="clear">
      <div class="notes blogfulldate">
        <?php echo date("d.M.Y H:i", $row['ctime']);
              if ($row["ctime"] != $row["mtime"] and $row["mtime"] != "") echo " - " . gettext("last update") . ": " . date("d.M.Y H:i", $row["mtime"]);
        ?>
      </div>

      <?php
        if ($row["num_comments"] > 0) {
          if ($row["num_comments"] == "1") $comments = gettext("comment");
          else                             $comments = gettext("comments");

          $num_comments = convertnumbers($row["num_comments"]);
          echo "<p class=\"notes commentslink\"><a href=\"#linkshowcomments\">$num_comments $comments</a></p>\n";
        }
        else {
          echo "<p class=\"notes commentslink\">" . gettext("no comments") . "</p>\n";
        }
      ?>

      <article>
        <header>
          <h1><?php echo $row["head"]; ?></h1>
        </header>
        <?php echo $row["text"]; ?>
      </article>

      <div class="notes center">
        <a rel="license" href="https://creativecommons.org/licenses/by/4.0/" target="_blank">
          <img alt="Creative Commons Lizenzvertrag" id="cc" src="pics/cc-by.png">
        </a><br>
        Dieses Werk ist lizenziert unter einer<br>
        <a rel="license" href="https://creativecommons.org/licenses/by/4.0/" target="_blank">
          Creative Commons Namensnennung 4.0 International Lizenz
        </a>.
      </div>
    </div>
  </div>
