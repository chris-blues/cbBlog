
    <div id="navigation">
      <div id="navLinks"
           data-backLink="<?php echo assembleGetString("string", array("job"=>"overview", "id"=>"")); ?>"
           data-editLink="<?php echo assembleGetString("string", array("job"=>"editBlog", "id"=>$_GET["id"])); ?>"
           data-viewLink="<?php echo assembleGetString("string", array("job"=>"viewBlog", "id"=>$_GET["id"])); ?>"
      >
      </div>

      <button type="button" id="buttonSettings">⚙</button>
<?php
// ====================[ special buttons ]====================
if ($_GET["job"] == "overview") { ?>
      <button type="button" id="buttonNewBlogpost"><?php echo gettext("new blogpost"); ?></button>
<?php
  $released = gettext("released");
  $unreleased = gettext("unreleased");

  if (!isset($_GET["category"])) $_GET["category"] = "released";
  switch ($_GET["category"]) {
    case "released":   $switchCategory = "unreleased"; $state = "released";   break;
    case "unreleased": $switchCategory = "released";   $state = "unreleased"; break;
    default:           $switchCategory = "unreleased"; $state = "released";   break;
  } ?>
      <button type="button" id="switchCategory" data-state="<?php echo $state; ?>"><?php echo $$switchCategory; ?></button>
<?php
}
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
