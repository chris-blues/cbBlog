        <button type="button" id="smileyButton">Smileys ☺</button>
          <div class="smileys" id="smileys">
            <?php
//               if (!isset($GLOBALS["path"])) $GLOBALS["path"] = "";
              require($locale_path . "/php/config/smileys.php");
              foreach ($smileyFile as $key => $value) {
                $smiley = trim($value);
            ?><span class="smiley" data-id="<?php echo $smiley; ?>"><?php echo $smiley; ?></span><?php } ?>
        </div>
