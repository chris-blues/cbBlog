
    <div id="navigation">
      <div id="navLinks"
           data-backLink="<?php echo assembleGetString("string", array("job"=>"overview", "id"=>"")); ?>"
           data-editLink="<?php echo assembleGetString("string", array("job"=>"editBlog", "id"=>$_GET["id"])); ?>"
           data-viewLink="<?php echo assembleGetString("string", array("job"=>"viewBlog", "id"=>$_GET["id"])); ?>"
      >
      </div>
<?php
// ====================[ special buttons ]====================
if ($_GET["job"] == "overview") { ?>
      <button type="button" id="buttonNewBlogpost"><?php echo gettext("new blogpost"); ?></button>
<?php }
if ($_GET["job"] == "viewBlog") { ?>
      <button type="button" id="buttonEditBlogpost"><?php echo gettext("edit blogpost"); ?></button>
<?php }
if ($_GET["job"] == "editBlog") { ?>
      <button type="button" id="buttonViewBlogpost"><?php echo gettext("view blogpost"); ?></button>
<?php }
// ====================[ don't display buttonBack in overview ]====================
if ($_GET["job"] != "overview") { ?>
      <button type="button" id="buttonBack"><?php echo gettext("back"); ?></button>
<?php } ?>
      <button type="button" id="buttonUp">🔝</button>
    </div>
