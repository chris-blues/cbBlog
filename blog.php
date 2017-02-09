<!-- begin blog.php -->

<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "admin/logs/php-error.log");

include_once("blog/functions.php");
include_once("lang.php");

if ($_GET["index"] == "0") unset($_GET["index"]);
date_default_timezone_set('Europe/Berlin');
require_once("phpinclude/dbconnect.php");

//$debug = "TRUE";

/* Connect to database */
$con=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

/* change character set to utf8mb4 */
if (!mysqli_set_charset($con, "utf8mb4"))
  { printf("Error loading character set utf8mb4: %s<br>\n", mysqli_error($con)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($con)); } }

/* Connect to comments-database */
$concom=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

if (!mysqli_set_charset($concom, "utf8mb4"))
  { printf("Error loading character set utf8mb4: %s<br>\n", mysqli_error($concom)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($concom)); } }


// ################
// ##  tag list  ##
// ################
$query_tags = "select * from `blog-tags` ORDER BY `tag` ASC ";
$result = mysqli_query($con, $query_tags);
$totalRows_blogtags = mysqli_num_rows($result);
   while ($row = $result->fetch_assoc())
     {
      if ($row["tag"] != "saved") $taglist[] = trim($row["tag"]);
     }
   mysqli_free_result($result);

// #####################
// ##  overview mode  ##
// #####################
if (!isset($_GET["index"]) or $_GET["index"] == "")
  {
   // list each tag as a filter
   echo "<div id=\"filter\" class=\"shadow\">";
   include("phpinclude/feeds.php");
   echo "<h3>Filter:</h3>\n<ul id=\"tags\">\n";
   if (!isset($_GET["filter"]))
     echo "<li><a class=\"notes italic tags green\">$all</a></li>\n";
   else
     echo "<li><a href=\"{$_SERVER["PHP_SELF"]}?page={$current_page}$link\" class=\"notes tags\">$all</a></li>\n";
   foreach ($taglist as $key => $tag)
     {
      if (strcmp($tag,"") == 0) continue 1;
      if (strcmp($tag,"copypaste") == 0) $cptitle = " title=\"copied and pasted\"";
      else $cptitle = "";

      if (strcmp($_GET["filter"],$tag) == 0)
        { echo "<li><a class=\"notes italic tags green\"$cptitle>" . $tag . "</a></li>\n"; }
      else
        { echo "<li><a href=\"{$_SERVER["PHP_SELF"]}?page={$current_page}$link&amp;filter=$tag\"$cptitle class=\"notes tags\">" . $tag . "</a></li>\n"; }
     }
   echo "</ul>\n</div>\n";
   echo "<div id=\"wrapper\">\n";

   if (!isset($_GET["filter"]) or $_GET["filter"] == "")
     {
      $query_blog = "SELECT * FROM `blog` WHERE `tags` NOT LIKE '%saved%' ORDER BY `blog`.`time` DESC ";
     }
   else
     {
      $query_blog = "SELECT * FROM `blog` WHERE `tags` LIKE '%{$_GET["filter"]}%' AND `tags` NOT LIKE '%saved%' ORDER BY `time` DESC";
     }
  }

else
  {
   $query_blog = "select * from `blog` WHERE (`blog`.`index` = {$_GET['index']}) ";
  }

$result = mysqli_query($con, $query_blog);
$totalRows_blog = mysqli_num_rows($result);

if (!isset($_GET["filter"]) or $_GET["filter"] == "") $filter = "";
else $filter = "&amp;filter=" . $_GET["filter"];

if ($result)
  {
   while ($row = $result->fetch_assoc())
     {
      $row["head"] = str_replace('$link', $link, $row["head"]);
      $row["text"] = str_replace('$link', $link, $row["text"]);
// #####################
// ##  Blogpost mode  ##
// #####################
      if (isset($_GET["index"]) or $_GET["index"] != "")
        {
         echo "<div class=\"shadow blogentryfull\">\n";
         echo "<form action=\"{$_SERVER["PHP_SELF"]}?page={$current_page}{$link}{$filter}\" method=\"post\" accept-charset=\"UTF-8\" class=\"inline\">\n";
         echo "<input type=\"submit\" value=\" &lt;&lt;&lt; $back \">\n</form>\n";
         include("phpinclude/feeds.php");
         echo "<div id=\"{$row["index"]}\" class=\"clear\">\n";
         echo "<p class=\"notes inline\">tags: ";
         $actualtags = explode(" ", $row["tags"]);
         foreach ($actualtags as $key => $tag)
           {
            if (strcmp($tag,"copypaste") == 0) $cptitle = " title=\"copied and pasted\"";
            else $cptitle = "";
            echo "<a href=\"{$_SERVER["PHP_SELF"]}?page={$current_page}$link&amp;filter=$tag\"$cptitle>$tag</a> ";
           }
         echo "</p>\n";
         // create permalink (without kartid, lang and accessibility)
         $search = explode("&",$_SERVER["QUERY_STRING"]);
         $switch = "0";
         foreach ($search as $key => $value)
           { // _GET-Variables not to show in Permalink
            if (strncmp($value, "kartid", 6) == 0) continue 1;
            if (strncmp($value, "lang", 4) == 0) continue 1;
            if (strncmp($value, "accessibility", 13) == 0) continue 1;
            if (strncmp($value, "showcomments", 12) == 0) continue 1;
            if (strncmp($value, "filter", 6) == 0) continue 1;
            if ($switch == "0")
              {
               $querystring = "$value";
               $switch = "1";
              }
            else
              {
               $querystring .= "&amp;$value";
              }
           }
         echo "<p class=\"notes right\" id=\"permaLink\">Permalink: <a href=\"https://{$_SERVER["HTTP_HOST"]}{$_SERVER["PHP_SELF"]}?$querystring\">https://{$_SERVER["HTTP_HOST"]}{$_SERVER["PHP_SELF"]}?$querystring</a></p>\n";
         echo "<hr class=\"clear\">\n";
         echo "<div class=\"notes blogfulldate\">" . date("d.M.Y H:i", $row['time']);
           if ($row["time"] != $row["ctime"]) echo " - last update: " . date("d.M.Y H:i", $row["ctime"]);
         echo "</div>\n";

         $query_comments = "SELECT * FROM `musicchris_de`.`blog-comments` WHERE `affiliation` = {$row["index"]} ORDER BY `time` ASC ";
         $resultcomments = mysqli_query($concom, $query_comments);
         $totalRows_comments = mysqli_num_rows($resultcomments);
         if ($totalRows_comments)
           {
            if ($totalRows_comments == "1") $comments = $lang_comment;
              else $comments = $lang_comments;
            $total_comments = convertnumbers($totalRows_comments, $lang);
            echo "<p class=\"notes commentslink\"><a href=\"#linkshowcomments\">$total_comments $comments</a></p>\n";
           }
         else
           {
            echo "<p class=\"notes commentslink\">$zero_totalRows_comments $lang_comments</p>\n";
           }
         mysqli_free_result($resultcomments);

         echo "<article>\n<header>\n<h1>{$row["head"]}</h1>\n</header>";
         echo "{$row["text"]}\n</article>\n";
         echo "</div>\n";
         echo "<hr>\n";
         echo "<div class=\"notes blogfulldate\">" . date("d.M.Y H:i", $row['time']);
           if ($row["time"] != $row["ctime"]) echo " - last update: " . date("d.M.Y H:i", $row["ctime"]); ?></div>
         <div class="notes centered">
           <a rel="license" href="https://creativecommons.org/licenses/by/4.0/" target="_blank"><img alt="Creative Commons Lizenzvertrag" id="cc" src="pics/cc-by.png"></a><br>
           Dieses Werk ist lizenziert unter einer<br>
           <a rel="license" href="https://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons Namensnennung 4.0 International Lizenz</a>.
         </div>
   <?php
        }
// ##############################
// or we want to see the overview
// ##############################
      else
        {
         echo "<div class=\"shadow blogentryoverview\">\n";
         echo "<p class=\"notes blogdate\">" . date("d.M.Y H:i",$row['time']) . " -  updated: " . date("d.M.Y H:i",$row['ctime']) . "</p>\n";

         $query_comments = "SELECT * FROM `musicchris_de`.`blog-comments` WHERE `affiliation` = {$row["index"]} ORDER BY `time` ASC ";
         $resultcomments = mysqli_query($concom, $query_comments);
         $totalRows_comments = mysqli_num_rows($resultcomments);
         if ($totalRows_comments)
           {
            if ($totalRows_comments == "1") $comments = $lang_comment;
              else $comments = $lang_comments;
            $total_comments = convertnumbers($totalRows_comments, $lang);
            echo "<p class=\"notes commentslink\"><a href=\"index.php?page=blog&amp;index={$row["index"]}{$link}#linkshowcomments\">$total_comments $comments</a></p>\n";
           }
         else
           {
            echo "<p class=\"notes commentslink\">$lang_no_comments</p>\n";
           }
         mysqli_free_result($resultcomments);

         $head = strip_tags($row["head"]);
         echo "<h1 class=\"bloghead\"><a href=\"{$_SERVER["PHP_SELF"]}?page={$current_page}$link&amp;index={$row["index"]}{$filter}\">$head</a></h1>\n";
         if ($row["text"] != "" or !isset($row["text"]))
           {
            $text = preg_replace('/\s+/', ' ', strip_tags($row["text"], '<p>'));
            if (strlen($text) > 250) { $shorttext = substr($text,0,250) . "..."; }
            else { $shorttext = $text; }
            echo "<p class=\"clear blogshorttext\">" . $shorttext . "</p>\n";
            echo "<p class=\"notes inline\">{$row["tags"]}</p>\n";
           }
         echo "</div>\n";
        }
     }
  }
else
  {
   echo "ERROR! No data retrieved.";
  }
//mysqli_free_result($result);

if (!isset($_GET["index"]) or $_GET["index"] == "") $_GET["index"] = "0";


//  ##################################################
// ##  only show comments section if not inoverview  ##
//  ##################################################
if (isset($_GET["index"]) and $_GET["index"] != "" and $_GET["index"] != "0")
  {
   echo "</div>\n<div class=\"clear\"></div>\n";
   echo "<div class=\"comments_wrapper\">\n<div class=\"centered\" id=\"linkshowcomments\">\n";
   if ($lang == "en") echo "<h2>Comments</h2>\n"; else echo "<h2>Kommentare</h2>\n";
   echo "</div>\n";


// ############################
// show comments for this entry
// ############################

   switch ($lang)
     {
      case "de": { $switchOTon = "zeige themenfremde Beiträge"; $switchOToff = "verstecke themenfremde Beiträge"; break; }
      case "en":
      default:        { $switchOTon = "show off-topic comments"; $switchOToff = "hide off-topic comments"; break; }
     }
   echo "<div id=\"langOT\" class=\"hidden\"
              data-OTon=\"$switchOTon\"
              data-OToff=\"$switchOToff\"></div>\n";

   $query_comments = "SELECT * FROM `blog-comments` WHERE `affiliation` = {$_GET["index"]} ORDER BY `time` ASC ";
   $result = mysqli_query($con, $query_comments);
   $totalRows_comments = mysqli_num_rows($result);
   if ($result)
     {
      $counter = "0";
      while ($row = $result->fetch_assoc())
        {
         $counter++;
         echo "<div class=\"comments shadow\" id=\"{$row["time"]}\">\n";
         echo "<h3 class=\"commentsHead\"><a href=\"{$_SERVER["PHP_SELF"]}?{$_SERVER["QUERY_STRING"]}#{$row["time"]}\">$counter</a>) ";

         if ($row["website"] != "")
           {
            $externalUrl = true;
            if (strncmp($row["website"], "http://" . $_SERVER["HTTP_HOST"], strlen("http://" . $_SERVER["HTTP_HOST"])) == 0) $externalUrl = false;
            if (strncmp($row["website"], "https://" . $_SERVER["HTTP_HOST"], strlen("https://" . $_SERVER["HTTP_HOST"])) == 0) $externalUrl = false;

            if ($externalUrl) $linkWebsite = "<a href=\"{$row["website"]}\" target=\"_blank\">{$row["name"]}</a>";
            else              $linkWebsite = "<a href=\"{$row["website"]}\">{$row["name"]}</a>";
            echo $linkWebsite;
           }
         else echo $row["name"];

         echo "</h3>\n  <p class=\"notes inline\">" . date("d.M.Y H:i",$row["time"]) . "</p>";
         echo "<button type=\"button\" class=\"otswitch inline\">$switchOTon</button>\n";

         $search = array("\\r\\n", "\r\n", "\\0", "\\");
         $replace = array("\n", "\n", "0", "");
         $post = str_replace($search, $replace, $row["comment"]);
         $post = htmlspecialchars($post, ENT_QUOTES | ENT_HTML5, "UTF-8", false);
         echo "<div class=\"clear\"></div>\n<div class=\"commentText\">" . parse(nl2br($post, false)) . "</div>\n";
         echo "</div>\n";
        }
      unset($post);
     }
   mysqli_free_result($result);



   $insertTags["b"]["open"]      = "[b]";
   $insertTags["b"]["close"]     = "[/b]";
   $insertTags["u"]["open"]      = "[u]";
   $insertTags["u"]["close"]     = "[/u]";
   $insertTags["s"]["open"]      = "[s]";
   $insertTags["s"]["close"]     = "[/s]";
   $insertTags["i"]["open"]      = "[i]";
   $insertTags["i"]["close"]     = "[/i]";
   $insertTags["url"]["open"]    = "[url]";
   $insertTags["url"]["close"]   = "[/url]";
   $insertTags["code"]["open"]   = "[code]";
   $insertTags["code"]["close"]  = "[/code]";
   $insertTags["tt"]["open"]     = "[tt]";
   $insertTags["tt"]["close"]    = "[/tt]";
   $insertTags["quote"]["open"]  = "[quote]";
   $insertTags["quote"]["close"] = "[/quote]";
   $insertTags["ot"]["open"]     = "[ot]";
   $insertTags["ot"]["close"]    = "[/ot]";
   $insertTags["done"]["open"]   = "[done]";
   $insertTags["done"]["close"]  = "";


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
   echo "  <input type=\"hidden\" name=\"affiliation\" value=\"{$_GET["index"]}\">\n";
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
   $smileyFile = file("blog/smileys.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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

<!-- end blog.php -->