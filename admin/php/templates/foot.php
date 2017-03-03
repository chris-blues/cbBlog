    <div class="center notes">
      <?php
        if ($config["blog"]["showProcessingTime"]) echo procTime($startTime, microtime(true));
      ?>
    </div>
    <?php if (isset($error)) { ?>
      <div id="errors" class="notes remark">
        <h2><?php echo gettext("The following errors have occured"); ?></h2>
        <ol>
          <?php if (isset($error)) displayErrors($error); ?>
        </ol>
      </div>
    <?php } ?>
  </body>
</html>
