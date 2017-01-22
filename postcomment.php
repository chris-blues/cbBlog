<!DOCTYPE html>
<?php
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "../admin/logs/php-error.log");

date_default_timezone_set('Europe/Berlin');

$time = time();
if (isset($_POST["preview"]) and $_POST["preview"] == "1") $preview = true;
else $preview = false;
if (!isset($link)) $link = "";
?>
<html>
<head>
<meta charset="utf-8">
<?php if (!$preview) { ?><meta http-equiv="refresh" content="0;<?php echo "../index.php?page=blog&index={$_POST["affiliation"]}{$link}#$time"; ?>"><?php }
else { ?><script type="text/javascript" src="preview.js"></script><?php } ?>
</head>
<body>

<?php

if (!$preview) echo "<p><a href=\"../index.php?page=blog&index={$_POST["affiliation"]}{$link}#$time\">Zurück zum Blog</a></p>\n";

//echo "<pre>"; print_r($_POST); echo "</pre>\n";

include_once("../phpinclude/config.php");
require_once("../phpinclude/dbconnect.php");

$debug = "FALSE";
//$debug = "TRUE";

/* Connect to comments-database */
$concom=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

if (!mysqli_set_charset($concom, "utf8"))
  { printf("Error loading character set utf8: %s<br>\n", mysqli_error($concom)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($concom)); } }


$link = "&amp;lang={$_POST["lang"]}";
if (isset($_POST["kartid"]) and $_POST["kartid"] != "") $link .= "&amp;kartid={$_POST["kartid"]}";


// Retrieve $_POST data
$posterror["switch"] = "FALSE";
if ($_POST["name"] == "") { $posterror["name"] = "TRUE"; /*$posterror["switch"] = "TRUE";*/ $_POST["name"] = "anonymous"; }
if ($_POST["website"] == "") { $posterror["website"] = "TRUE"; /*$posterror["switch"] = "TRUE";*/ }
if ($_POST["email"] != "") { $posterror["email"] = "TRUE"; $posterror["switch"] = "TRUE"; }
if ($_POST["text"] == "") { $posterror["text"] = "TRUE"; $posterror["switch"] = "TRUE"; }
if ($_POST["affiliation"] == "" or !is_numeric($_POST["affiliation"])) { $posterror["affiliation"] = "TRUE"; $posterror["switch"] = "TRUE"; }

if (isset($_POST["website"]) and $_POST["website"] != "" and strncmp($_POST["website"], "http", 4) != 0) $website = "http://" . $_POST["website"];
else $website = $_POST["website"];


// if the hidden email-field has been filled out - most likely by a bot, they just can't resist - it's considered SPAM!
if (isset($posterror["email"]) and $posterror["email"] == "TRUE")
  {
   date_default_timezone_set('Europe/Berlin');
   $maildate = date(DATE_RFC2822);
   $header = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
   $header .= "Content-Transfer-Encoding: 8bit\r\n";
   $header .= "From: notify@{$_SERVER["SERVER_NAME"]}\r\n";
   $header .= "Date: $maildate\r\n";
   $header .= "\r\n";
   $subject = "possible spam from {$_SERVER['REMOTE_ADDR']} by {$_POST["name"]} ( {$_POST["email"]} )";
   $mail = "Es gab einen neuen möglichen SPAM im Blog!\n\n\n========================================\n\n";
   $mail .= "Website:        {$website}\n";
   $mail .= "Email:          {$_POST["name"]} <{$_POST["email"]}>\n";
   $mail .= "Target:         https://{$_SERVER["SERVER_NAME"]}/?page=blog&amp;index={$_POST["affiliation"]}\n";
   $mail .= "Timestamp:      $time\n";
   $mail .= "originating IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
   $mail .= "content:\n";
   $mail .= wordwrap($_POST["text"], 70);
   $mail .= "\n\n\n========================================\n\nhttps://{$_SERVER["SERVER_NAME"]}/blog/checkips.php\n";
   mail($email_blogadmin, $subject, $mail, $header);

// log IPs identified as Spam
   $ipfile = file("ipfile.txt");
   $handle = fopen("ipfile.txt",w);
   fwrite($handle, $_SERVER['REMOTE_ADDR'] . " " . $time . "\n");
   foreach ($ipfile as $key => $value)
     {
      $value = trim($ipfile[$key]);
      fwrite($handle, $value . "\n");
     }
   fclose($handle);
  }

if ($posterror["switch"] != "FALSE")
  { // if we have some error
   //echo "Error! Some data was flawd!<br>\n";
   //echo "<pre>_POST-"; print_r($_POST); echo "<br>\nErrors:\n"; print_r($posterror); echo "</pre>\n";
   exit();
  }
else
  { // if everything is fine
//   echo "Data looks good!<br>\n";
  }



$name = $_POST["name"];
if ($name == "") $name = "Anonymous";
$email = $_POST["notificationTo"];




// Check, if this email is already registered
if ($email != "" and !$preview)
  {
   $queryEmail = mysqli_real_escape_string($concom, $email);
   $queryAffiliation = mysqli_real_escape_string($concom, $_POST["affiliation"]);
   $query = "SELECT * FROM `blog-comments` WHERE `affiliation` = $queryAffiliation AND `email` = '$queryEmail'";
   $result = mysqli_query($concom, $query);
   if (mysqli_num_rows($result) < 1) // aka first post
     {
      $firstPost = true;
      $email = hash('sha256', $_SERVER["SERVER_NAME"] . $_POST["notificationTo"] . $_POST["affiliation"]);
     }
   else
     {
      $firstPost = false;
     }
   mysqli_free_result($result);
  }




//$text = htmlentities($_POST["text"], ENT_QUOTES | ENT_HTML5, "UTF-8");
// $search = array("<", ">", "\"", "'", "\r\n", "\r", "\\");
// $replace = array("&lt;", "&gt;", "&quot;", "&#39;", "\n", "\n", "&#92;");
// $text = str_replace($search, $replace, $_POST["text"]);


$search = array("\r\n", "\r");
$replace = array("\n", "\n");
$text = str_replace($search, $replace, $_POST["text"]);





// source: https://stackoverflow.com/questions/16685416/split-full-email-addresses-into-name-and-email
$sPattern = '/([\w\s\'\"]+[\s]+)?(<)?(([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/';
if (!$firstPost and !$preview)
  {
   preg_match($sPattern, $email, $aMatch);
   // $aMatch[0] = name - if empty it becomes $aMatch[3]
   // $aMatch[3] = email
   $email = $aMatch[3];
  }
// source: https://stackoverflow.com/questions/16685416/split-full-email-addresses-into-name-and-email

if (!isset($_POST["answerTo"]) or $_POST["answerTo"] == "") $answerTo = 0;
else $answerTo = $_POST["answerTo"];

$queryAffiliation = mysqli_real_escape_string($concom, $_POST["affiliation"]);
$queryAnswerTo = mysqli_real_escape_string($concom, $answerTo);
$queryTime = mysqli_real_escape_string($concom, $time);
$queryname = mysqli_real_escape_string($concom, $name);
$queryemail = mysqli_real_escape_string($concom, $email);
$querywebsite = mysqli_real_escape_string($concom, $website);
$querytext = mysqli_real_escape_string($concom, $text);

if (!$preview)
  {
   $query = "INSERT INTO `musicchris_de`.`blog-comments` (`affiliation`,`answerTo`, `time`, `name`, `email`, `website`, `comment`) VALUES ('{$queryAffiliation}', '{$queryAnswerTo}', '{$queryTime}', '{$queryname}', '{$queryemail}', '{$querywebsite}', '{$querytext}');";

   if ($result = mysqli_query($concom, $query))
     {
      //mysqli_free_result($result);
     }
   else die(mysqli_error($concom));
  }

if ($preview)
  { ?>
   <form action="../index.php?page=blog&amp;index=<?php echo $_POST["affiliation"] . $link . "#commentForm"; ?>" accept-charset="UTF-8" method="POST" id="previewForwarder">
     <input type="hidden" name="name" value="<?php echo htmlspecialchars($queryname, ENT_QUOTES | ENT_HTML5, "UTF-8"); ?>">
     <input type="hidden" name="notificationTo" value="<?php echo htmlspecialchars($queryemail, ENT_QUOTES | ENT_HTML5, "UTF-8"); ?>">
     <input type="hidden" name="website" value="<?php echo htmlspecialchars($querywebsite, ENT_QUOTES | ENT_HTML5, "UTF-8"); ?>">
     <input type="hidden" name="answerTo" value="<?php echo htmlspecialchars($queryAnswerTo, ENT_QUOTES | ENT_HTML5, "UTF-8"); ?>">
     <input type="hidden" name="text" value="<?php echo htmlspecialchars($querytext, ENT_QUOTES | ENT_HTML5, "UTF-8"); ?>">
     <input type="hidden" name="previewRequested" value="1">
   </form>
  </body>
</html><?php
   exit;
  }


// send email to admin!
if ($blog_emailnotification == "TRUE" and !$preview)
  {
   date_default_timezone_set('Europe/Berlin');
   $maildate = date(DATE_RFC2822);
   $header = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
   $header .= "Content-Transfer-Encoding: 8bit\r\n";
   $header .= "From: notify@{$_SERVER["SERVER_NAME"]}\r\n";
   $header .= "Date: $maildate\r\n";
   $header .= "\r\n";
   $subject = "Comment by {$_POST["name"]} <{$_POST["notificationTo"]}> $time";
   $mail = "Es gibt einen neuen Kommentar im Blog!\r\n";
   $mail .= "\r\n";
   $mail .= htmlspecialchars_decode("http://{$_SERVER["SERVER_NAME"]}/index.php?page=blog&amp;lang={$_POST["lang"]}&amp;index={$_POST["affiliation"]}#$time");
   $mail .= "\r\n\r\n\r\nHash: $email\r\n\r\n{$_POST["name"]} <{$_POST["notificationTo"]}> ({$_POST["website"]}) schrieb:\n\n";
   $mail .= wordwrap($_POST["text"], 70);
   if (!mail($email_blogadmin, $subject, $mail, $header))
     {
      //echo "<h3>ERROR!</h3>Failed to send mail to admin!<br>\n";
     }
  }



// #################################################
// ##  send email notifications for new comments  ##
// #################################################

// Get concerning blog-entry for some data (header and such)
$queryAffiliation = mysqli_real_escape_string($concom, $_POST["affiliation"]);
$query_blog = "select * from `blog` WHERE (`blog`.`index` = '$queryAffiliation') ";
if ($result = mysqli_query($concom, $query_blog) and !$preview)
  {
   while ($row = $result->fetch_assoc())
     {
      $blog_header = $row["head"];
     }
   //mysqli_free_result($result);
  }


// Send each registered adress an email

$template = file_get_contents("template_notification.html");

$query_notifications = "SELECT * FROM `blog-comments` WHERE `affiliation` = '$queryAffiliation' AND `email` > '' ORDER BY `time` ASC ";

if ($result = mysqli_query($concom, $query_notifications) and !$preview)
  {
   while ($row = $result->fetch_assoc())
     {
      // make sure, we only notify once!
      if (isset($sentmail["{$row["email"]}"]) and $sentmail["{$row["email"]}"] == true) { continue; }
      // Don't notify this poster as well!
      if ($row["email"] == $_POST["notificationTo"]) { continue; }
      // and don't notify unverified addresses!
      if (!filter_var($row["email"], FILTER_VALIDATE_EMAIL)) { continue; }
      // and skip this, if no email is set
      if (!isset($row["email"]) or strlen($row["email"]) < 2) { continue; }

      // prepare email strings
      $email = $row["email"];

      $sPattern = '/([\w\s\'\"]+[\s]+)?(<)?(([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/';
      preg_match($sPattern, $email, $aMatch);
      $email = $aMatch[3];
      $email_name = $aMatch[0];

      $link_unsubscribe_topic = htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/blog/subscription.php?email=$email&amp;job=unsubscribe&amp;scope={$_POST["affiliation"]}");
      $link_unsubscribe_site = htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/blog/subscription.php?email=$email&amp;job=unsubscribe&amp;scope=0");

      if (strlen($email_name) > 0) $to = $row["name"] . " <$email>";
      else $to = "$email";

      $subject = "new comment on: $blog_header @ " . $_SERVER["SERVER_NAME"];
      date_default_timezone_set('Europe/Berlin');
      $maildate = date(DATE_RFC2822);
      $header = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
      $header .= "Content-Transfer-Encoding: 8bit\r\n";
      $header .= "From: notify@{$_SERVER["SERVER_NAME"]}\r\n";
      $header .= "Date: $maildate\r\n";
      $header .= "\r\n";

      $search = array("\n",
                      "<br>",
                      "<hr>",
                      "{name}",
                      "{email}",
                      "{server}",
                      "{poster}",
                      "{link_topic}",
                      "{comment}",
                      "{link_unsubscribe_topic}",
                      "{link_unsubscribe_site}");
      $replace = array("",
                       "\r\n",
                       "---------------------------------------------------\r\n",
                       $row["name"],
                       $email,
                       $_SERVER["SERVER_NAME"],
                       $_POST["name"],
                       htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/index.php?page=blog&amp;index={$_POST["affiliation"]}"),
                       wordwrap($_POST["text"], 70),
                       $link_unsubscribe_topic,
                       $link_unsubscribe_site);
      $message = str_replace($search, $replace, $template);
      if (mail($to, $subject, $message, $header)) $sentmail["{$row["email"]}"] = true;
      else
        {
         $mailbody = str_replace("\r\n", "\n", $header . "\r\nSubject: " . $subject . "\r\nTo: " . $email . "\r\n\r\n" . $message);
         logMailError($row["name"], $row["email"], $_POST["affiliation"], $email, $mailbody);
        }
     }
   mysqli_free_result($result);
  }


// ################################################
// ##  send verification email to new subscriber ##
// ################################################
if (isset($_POST["notificationTo"]) and $_POST["notificationTo"] != "" and $firstPost and !$preview)
  {
   //echo "<p>Detected new subscriber!</p>\n";
   $email = $_POST["notificationTo"];
   $sPattern = '/([\w\s\'\"]+[\s]+)?(<)?(([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/';
   preg_match($sPattern, $email, $aMatch);
   $email = $aMatch[3];
   $email_name = $aMatch[0];

   $template = file_get_contents("template_subscription.html");
   $hash = hash('sha256', $_SERVER["SERVER_NAME"] . $email . $_POST["affiliation"]);
   $link_verify = "https://" . $_SERVER["SERVER_NAME"] . "/blog/subscription.php?job=verify&scope={$_POST["affiliation"]}&email=$email&hash=$hash";

   $subject = "Verify your subscription for: $blog_header @ " . $_SERVER["SERVER_NAME"];

   $link_unsubscribe_topic = htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/blog/subscription.php?email=$email&job=unsubscribe&scope={$_POST["affiliation"]}");
   $link_unsubscribe_site = htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/blog/subscription.php?email=$email&job=unsubscribe&scope=0");

   // $header should still be in memory!
   $search = array("\n",
                   "<br>",
                   "<hr>",
                   "{name}",
                   "{email}",
                   "{server}",
                   "{link_topic}",
                   "{comment}",
                   "{link_unsubscribe_topic}",
                   "{link_unsubscribe_site}",
                   "{verificationLink}");
   $replace = array("",
                    "\r\n",
                    "---------------------------------------------------\r\n",
                    $_POST["name"],
                    $email,
                    $_SERVER["SERVER_NAME"],
                    htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/index.php?page=blog&amp;index={$_POST["affiliation"]}"),
                    wordwrap($_POST["text"], 70),
                    $link_unsubscribe_topic,
                    $link_unsubscribe_site,
                    $link_verify);
   $message = str_replace($search, $replace, $template);
   //echo "<h2>Verification mail</h2>\n<pre>$header\nTo: $email\nSubject: $subject\nbody:\n$message</pre>\n";
   if (mail($email, $subject, $message, $header))
     {
      // echo "Verification mail sent!<br>\n";
     }
   else 
     {
      $mailbody = str_replace("\r\n", "\n", $header . "\r\nSubject: " . $subject . "\r\nTo: " . $email . "\r\n\r\n" . $message);
      logMailError($name, $email, $_POST["affiliation"], $hash, $mailbody);
     }

   // update DB entry
   $queryHash = mysqli_real_escape_string($concom, $hash);
   $queryemail = mysqli_real_escape_string($concom, $email);
   $queryAffiliation = mysqli_real_escape_string($concom, $_POST["affiliation"]);
   $query = "UPDATE `blog-comments` SET `email`='$queryHash' WHERE (`email` = '$queryemail' AND `affiliation`='$queryAffiliation');";
   if ($result = mysqli_query($concom, $query_notifications))
     {
      //echo "Hash entered into database - awaiting verification.<br>\n";
     }
   mysqli_free_result($result);
  }
?>
</body>
</html>
