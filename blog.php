<?php
$startTime = microtime(true);
require_once("bootstrap.php");

include_once("lang.php");

if ($_GET["id"] == "0") unset($_GET["id"]);
date_default_timezone_set('Europe/Berlin');

// fall back to overview if $_GET["id"] is missing or empty
if (isset($_GET["id"]) and $_GET["id"] != "") {
  $blogposts[$_GET["id"]] = $query->selectById("blog", $_GET["id"], "Blogpost");
}
else {
  $blogposts = $query->selectAll("blog", "Blogpost");
}


if (!isset($_GET["id"]) or $_GET["id"] == "" or $_GET["id"] == "0") {
  $tags = $query->selectAllTags("blog_tags", "Tags");
  Filters::display($tags);
  foreach ($tags as $key => $Tag) { $tagname = $Tag->getdata(); $taglist[$key] = $tagname["tag"]; }
  echo "<div id=\"wrapper\">\n";
}










if (!isset($_GET["filter"]) or $_GET["filter"] == "") $filter = "";
else $filter = $_GET["filter"];

if ($blogposts)
  {
   foreach ($blogposts as $id => $Post)
     {
      $row = $Post->getdata();
      $row["head"] = str_replace('$link', $link, $row["head"]);
      $row["text"] = str_replace('$link', $link, $row["text"]);
// #####################
// ##  Blogpost mode  ##
// #####################
      if (isset($_GET["id"]) and $_GET["id"] != "")
        {
         require("templates/view.blogpost.php");





//          $query_comments = "SELECT * FROM `musicchris_de`.`blog-comments` WHERE `affiliation` = {$row["id"]} ORDER BY `time` ASC ";
//          $resultcomments = mysqli_query($concom, $query_comments);
//          $totalRows_comments = mysqli_num_rows($resultcomments);
//          if ($totalRows_comments)
//            {
//             if ($totalRows_comments == "1") $comments = $lang_comment;
//               else $comments = $lang_comments;
//             $total_comments = convertnumbers($totalRows_comments, $lang);
//             echo "<p class=\"notes commentslink\"><a href=\"#linkshowcomments\">$total_comments $comments</a></p>\n";
//            }
//          else
//            {
//             echo "<p class=\"notes commentslink\">$zero_totalRows_comments $lang_comments</p>\n";
//            }
//          mysqli_free_result($resultcomments);
//

        }
// ##############################
// or we want to see the overview
// ##############################
      else
        {
         require("templates/view.overview.php");

//          $query_comments = "SELECT * FROM `musicchris_de`.`blog-comments` WHERE `affiliation` = {$row["id"]} ORDER BY `time` ASC ";
//          $resultcomments = mysqli_query($concom, $query_comments);
//          $totalRows_comments = mysqli_num_rows($resultcomments);
//          if ($totalRows_comments)
//            {
//             if ($totalRows_comments == "1") $comments = $lang_comment;
//               else $comments = $lang_comments;
//             $total_comments = convertnumbers($totalRows_comments, $lang);
//             echo "<p class=\"notes commentslink\"><a href=\"index.php?page=blog&amp;id={$row["id"]}{$link}#linkshowcomments\">$total_comments $comments</a></p>\n";
//            }
//          else
//            {
//             echo "<p class=\"notes commentslink\">$lang_no_comments</p>\n";
//            }
//          mysqli_free_result($resultcomments);

//
//
        }
     }
  echo "</div>\n";
  }
else
  {
   echo "ERROR! No data retrieved.";
  }


if (!isset($_GET["id"]) or $_GET["id"] == "") $_GET["id"] = "0";


//  ##################################################
// ##  only show comments section if not inoverview  ##
//  ##################################################
if (isset($_GET["id"]) and $_GET["id"] != "" and $_GET["id"] != "0")
  { ?>
  <div class="clear"></div>
  <div class="comments_wrapper">
    <div class="centered" id="linkshowcomments">
      <h2><?php echo gettext("comments"); ?></h2>
    </div>

    <div id="langOT" class="hidden"
         data-OTon="<?php echo gettext("show off-topic comments"); ?>"
         data-OToff="<?php echo gettext("hide off-topic comments"); ?>">
    </div>

  <?php
  $comments = $query->selectComments($_GET["id"], "Comment");
  $counter = 0;
  foreach ($comments as $Comment) {
    $counter++;
    $row = $Comment->getdata();
    require("templates/view.comment.php");
  }








   //display post form
   if (isset($_POST["name"]) and $_POST["name"] != "")
       $previewName = htmlspecialchars_decode($_POST["name"], ENT_QUOTES | ENT_HTML5); else $previewName = "";
   if (isset($_POST["notificationTo"]) and $_POST["notificationTo"] != "")
       $previewNotificationTo = htmlspecialchars_decode($_POST["notificationTo"], ENT_QUOTES | ENT_HTML5); else $previewNotificationTo = "";
   if (isset($_POST["website"]) and $_POST["website"] != "")
       $previewWebsite = htmlspecialchars_decode($_POST["website"], ENT_QUOTES | ENT_HTML5); else $previewWebsite = "";
   $post = htmlspecialchars(stripcslashes($_POST["text"]), ENT_QUOTES | ENT_HTML5, "UTF-8", false);

   echo "<div class=\"shadow comments comment postform\" name=\"comment\" id=\"commentForm\">\n";

   if (isset($_POST["previewRequested"]) and $_POST["previewRequested"] == "1")
     {
      $time = time();
      echo "<div class=\"comments preview\" id=\"preview\">\n";
      echo "<h3 class=\"commentsHead inline\">$previewString) ";
      if ($previewWebsite != "") echo "<a href=\"$previewWebsite\" target=\"_blank\">$previewName</a>";
      else echo "$previewName";
      echo "</h3>\n  <p class=\"notes inline\">" . date("d.M.Y H:i", $time) . "</p>";
      echo "<p class=\"otswitch inline\">$switchOTon</p>\n";
      echo "<div class=\"clear\"></div>\n" . parse(nl2br($post, false)) . "<br>\n";
      echo "</div>\n<hr>";
     }

   echo "  <form action=\"blog/postcomment.php\" method=\"post\" accept-charset=\"UTF-8\">\n";
   echo "  Name: <span class=\"notes\">(optional)</span><br>\n";
   echo "  <input type=\"text\" name=\"name\" id=\"post_name\" value=\"$previewName\" placeholder=\"Anonymous\"><br>\n";
   echo "  <p class=\"notes\" id=\"email\">$leaveempty<input type=\"email\" name=\"email\" id=\"post_email\"></p>\n";
   echo "  $notify: <span class=\"notes\">(optional, $emailusage)</span><br>\n";
   echo "  <input type=\"email\" name=\"notificationTo\" id=\"post_notificationTo\" value=\"$previewNotificationTo\" placeholder=\"you@inter.net\"><br>\n";
   echo "  Website: <span class=\"notes\">(optional)</span><br>\n";
   echo "  <input type=\"url\" name=\"website\" id=\"post_website\" value=\"$previewWebsite\" placeholder=\"https://www.example.tld\"><br>\n";
   echo "  $comment<br>\n<div class=\"hidden\" id=\"tagHelp\">$taghelp</div>\n";
   echo "  <textarea name=\"text\" id=\"post_text\">$post</textarea><br>\n";
   echo "  <input type=\"hidden\" name=\"affiliation\" value=\"{$_GET["id"]}\">\n";
   echo "  <input type=\"hidden\" name=\"kartid\" value=\"$kartid\">\n";
   echo "  <input type=\"hidden\" name=\"lang\" value=\"$lang\">\n";
   echo "  <input type=\"hidden\" name=\"preview\" value=\"0\" id=\"switchPreview\">\n";

   echo "  <div class=\"button_wrapper\">\n";
   foreach ($insertTags as $tag => $value)
     {
      echo "<button type=\"button\" class=\"tagButton\" data-valueOpen=\"{$insertTags[$tag]["open"]}\" data-valueClose=\"{$insertTags[$tag]["close"]}\">{$insertTags[$tag]["open"]}</button>\n";
     }
   echo "    <button type=\"button\" id=\"smileyButton\">Smileys ☺</button>\n";
   echo "    <div class=\"smileys\" id=\"smileys\">\n";

   // add Smileys
   $smileyFile = file("smileys.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   foreach ($smileyFile as $key => $value) { $smiley = trim($value); echo " <span class=\"smiley\" data-id=\"$smiley\">$smiley</span>"; }

   echo "\n    </div>\n  </div>\n";
   echo "  <button type=\"reset\"> &lt;&lt;&lt; $back </button> <button type=\"button\" id=\"buttonPreview\"> $previewString </button> <button type=\"submit\" id=\"buttonSend\"> $send &gt;&gt;&gt; </button><br>\n";
   echo "  </form>\n";
   echo "</div>\n";
  }
?>

<?php

   echo "</div>\n";
?>

<?php require_once("templates/view.foot.php"); ?>
