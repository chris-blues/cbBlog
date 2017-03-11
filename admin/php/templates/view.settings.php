    <div id="settings_wrapper">
      <h1><?php echo gettext("settings"); ?></h1>



      <div class="shadow settings" id="blogSettings">
        <h2><?php echo gettext("Blog settings"); ?></h2>
        <form id="settings" action="index.php?job=settings&amp;operation=saveBlogSettings" method ="post" accept-charset="UTF-8">

          <label for="blogSettings_standalone" title="<?php echo gettext("If you have nothing else installed on your webserver, you propably should check this. (This activates primarily the delivery of an HTML header.)"); ?>">standalone</label>
            <input id="blogSettings_standalone" type="checkbox" name="standalone" value="standalone" placeholder="standalone"<?php echo $config["blog"]["standalone"] ? " checked" : ""; ?>><br>

          <label for="blogSettings_showProcessingTime" title="<?php echo gettext("If checked, this will show the time needed for processing on each page."); ?>">showProcessingTime</label>
            <input id="blogSettings_showProcessingTime" type="checkbox" name="showProcessingTime" value="showProcessingTime" placeholder="showProcessingTime"<?php echo $config["blog"]["showProcessingTime"] ? " checked" : ""; ?>><br>

          <label for="blogSettings_language" title="<?php echo gettext("You could pass a default language. This will override the visitors settings. Use the following format: en_GB or de_DE"); ?>">language</label>
            <input id="blogSettings_language" type="text" name="language" placeholder="language" value="<?php echo $config["blog"]["language"]; ?>"><br>

            <hr>

          <label for="blogSettings_debug_level" title="<?php echo gettext("How much debug info you want to receive? (You can define how these are output below.)"); ?>">debug_level</label>
            <select id="blogSettings_debug_level" name="debug_level">
              <option value="full"<?php if ($config["blog"]["debug_level"] == "full") echo " selected"; ?>>
                <?php echo gettext("all debug messages"); ?>
              </option>
              <option value="warn"<?php if ($config["blog"]["debug_level"] == "warn") echo " selected"; ?>>
                <?php echo gettext("only warnings"); ?>
              </option>
              <option value="none"<?php if ($config["blog"]["debug_level"] == "none") echo " selected"; ?>>
                <?php echo gettext("no debug messages"); ?>
              </option>
            </select><br>

          <label for="blogSettings_show_debug" title="<?php echo gettext("Do you want these debug messages to be shown in the browser? (Might be unwanted to be shown to your visitors.)"); ?>">show_debug</label>
            <input id="blogSettings_show_debug" type="checkbox" name="show_debug" placeholder="show_debug" value="show_debug"<?php echo $config["blog"]["show_debug"] ? " checked" : ""; ?>><br>

          <label for="blogSettings_log_debug" title="<?php echo gettext("Do you want these debug messages to be logged in \$blog/admin/logs?"); ?>">log_debug</label>
            <input id="blogSettings_log_debug" type="checkbox" name="log_debug" placeholder="log_debug" value="log_debug"<?php echo $config["blog"]["log_debug"] ? " checked" : ""; ?>><br>

          <hr>

          <label title="<?php echo gettext("These GET variables will be ignored in the generation of the permalink."); ?>">permalinkIgnore</label>
        <?php
        foreach ($config["blog"]["permalinkIgnore"] as $key => $value) { ?>
            <input class="blogSettings_permalinkIgnore" id="<?php echo $key; ?>" type="text" name="permalinkIgnore[]" value="<?php echo $value; ?>" placeholder="<?php echo gettext("put new GET variables here"); ?>">
            <button type="button" id="buttonEmptyField_<?php echo $key; ?>" class="emptyField" data-id="<?php echo $key; ?>">❌</button>
            <br>
        <?php } ?>

            <input id="blogSettings_permalinkIgnore" type="text" name="permalinkIgnore[]" value="" placeholder="<?php echo gettext("put new GET variables here"); ?>"><br>

          <hr>

        <?php
        foreach ($config["blog"]["RSSinfo"] as $key => $value) { ?>
          <label for="<?php echo $key; ?>"><?php echo $key; ?></label>
            <input class="blogSettings_permalinkIgnore" id="<?php echo $key; ?>" type="text" name="RSSinfo[]" value="<?php echo $value; ?>">
            <br>
        <?php } ?>

          <div class="center">
            <button type="reset"><?php echo gettext("reset"); ?></button>
            <button type="submit"><?php echo gettext("save"); ?></button>
          </div>
        </form>
      </div>



      <div class="shadow settings" id="dbSettings">
        <h2><?php echo gettext("Database settings"); ?></h2>
        <form id="settings" action="index.php?job=settings&amp;operation=saveDBSettings" method ="post" accept-charset="UTF-8">
          <label for="dbSettings_driver" title="<?php echo gettext("The PDO driver of your database. ('mysql' should be ok in most cases.)"); ?>">driver</label>
            <input id="dbSettings_driver" type="text" name="driver" placeholder="driver" value="<?php echo $config["database"]["driver"]; ?>"><br>

          <label for="dbSettings_host" title="<?php echo gettext("The hostname for your database."); ?>">host</label>
            <input id="dbSettings_host" type="text" name="host" placeholder="host" value="<?php echo $config["database"]["host"]; ?>"><br>

          <label for="dbSettings_name" title="<?php echo gettext("Your database name."); ?>">name</label>
            <input id="dbSettings_name" type="text" name="name" placeholder="name" value="<?php echo $config["database"]["name"]; ?>"><br>

          <label for="dbSettings_user" title="<?php echo gettext("Your username for the database."); ?>">user</label>
            <input id="dbSettings_user" type="text" name="user" placeholder="user" value="<?php echo $config["database"]["user"]; ?>"><br>

          <label for="dbSettings_pass" title="<?php echo gettext("Your passwort for your database. (Make sure, that it's along one!)"); ?>">pass</label>
            <input id="dbSettings_pass" type="password" name="pass" placeholder="pass" value="<?php echo $config["database"]["pass"]; ?>"><br>

          <div class="center">
            <button type="reset"><?php echo gettext("reset"); ?></button>
            <button type="submit"><?php echo gettext("save"); ?></button>
          </div>
        </form>
      </div>


    </div>
    <div class="clear"></div>
