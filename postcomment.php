<!DOCTYPE html>
<?php $time = time(); ?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="0;<?php echo "../index.php?page=blog&index={$_POST["affiliation"]}{$link}#$time"; ?>">
</head>
<body>

<?php
error_reporting(1);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "/www/admin/logs/php-error.log");

date_default_timezone_set('Europe/Berlin');

$forward = "";

include_once("../phpinclude/config.php");
require_once("../phpinclude/dbconnect.php");

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

//echo "<body style=\"margin: 0px;\" onload=\"window.location.href='../../index.php?page=blog&amp;index={$_POST["affiliation"]}{$link}#$time'\">\n";
//echo "<body style=\"margin: 0px;\">\n<a href=\"../../{$_SERVER["PHP_SELF"]}?page=blog&amp;kartid={$_POST["kartid"]}&amp;lang={$_POST["lang"]}&amp;index={$_POST["affiliation"]}&amp;showcomments=TRUE#$time\">back</a><br>\n";

// Retrieve $_POST data
$posterror["switch"] = "FALSE";
if ($_POST["name"] == "") { $posterror["name"] = "TRUE"; /*$posterror["switch"] = "TRUE";*/ $_POST["name"] = "anonym"; }
if ($_POST["website"] == "") { $posterror["website"] = "TRUE"; /*$posterror["switch"] = "TRUE";*/ }
if ($_POST["email"] != "") { $posterror["email"] = "TRUE"; $posterror["switch"] = "TRUE"; }
if ($_POST["text"] == "") { $posterror["text"] = "TRUE"; $posterror["switch"] = "TRUE"; }
if ($_POST["affiliation"] == "" or !is_numeric($_POST["affiliation"])) { $posterror["affiliation"] = "TRUE"; $posterror["switch"] = "TRUE"; }

if (isset($_POST["website"]) and $_POST["website"] != "" and strncmp($_POST["website"], "http", 4) != 0) $website = "http://" . $_POST["website"];
else $website = $_POST["website"];

if ($debug == "TRUE") { echo "<h3>\$_POST:</h3><pre>"; print_r($_POST); echo "</pre><br>\n"; }

// if the hidden email-field has been filled out - most likely by a bot, they just can't resist - it's considered SPAM!
if ($posterror["email"] == "TRUE")
  {
   date_default_timezone_set('Europe/Berlin');
   $maildate = date(DATE_RFC2822);
   $header = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
   $header .= "Content-Transfer-Encoding: 8bit\r\n";
   $header .= "From: blog@{$_SERVER[SERVER_NAME]}\r\n";
   $header .= "Date: $maildate\r\n";
   $header .= "\r\n";
   $subject = "possible spam from {$_SERVER['REMOTE_ADDR']} by {$_POST["name"]} ( {$_POST["email"]} )";
   $mail = "Es gab einen neuen möglichen SPAM im Blog!\n\n\n========================================\n\n";
   $mail .= "Website:        {$website}\n";
   $mail .= "Email:          {$_POST["name"]} <{$_POST["email"]}>\n";
   $mail .= "Target:         https://{$_SERVER[SERVER_NAME]}/?page=blog&amp;index={$_POST["affiliation"]}\n";
   $mail .= "Timestamp:      $time\n";
   $mail .= "originating IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
   $mail .= "content:\n";
   $mail .= wordwrap($_POST["text"], 70);
   $mail .= "\n\n\n========================================\n\nhttps://musicchris.de/blog/checkips.php\n";
  // echo "Spam notification:<br>\n<pre>To: $email_blogadmin<br>Su: $subject<br>mail: $mail<br>header: $header<br>\n";
   mail($email_blogadmin, $subject, $mail, $header);

// log IPs identified as Spam
   $ipfile = file("ipfile.txt");
   $handle = fopen("ipfile.txt",w);
   fwrite($handle, $_SERVER['REMOTE_ADDR'] . " " . $time . "\n");
   foreach ($ipfile as $key => $value)
     {
//      if (!in_array($_SERVER['REMOTE_ADDR'], $ipfile)) { $ipfile[] = $_SERVER['REMOTE_ADDR']; }
      $value = trim($ipfile[$key]);
      fwrite($handle, $value . "\n");
     }
   fclose($handle);
  }

if ($posterror["switch"] != "FALSE")
  { // if we have some error
   echo "Error! Some data was flawd!<br>\n";
   echo "<pre>_POST-"; print_r($_POST); echo "<br>\nErrors:\n"; print_r($posterror); echo "</pre>\n";
   exit();
  }
else
  { // if everything is fine
   echo "Data looks good!<br>\n";
  }

//echo "<pre>_POST-"; print_r($_POST); echo "<br>\nErrors:\n"; print_r($posterror); echo "</pre>\n";


if ($blog_emailnotification == "TRUE")
  {
   date_default_timezone_set('Europe/Berlin');
   $maildate = date(DATE_RFC2822);
   $header = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
   $header .= "Content-Transfer-Encoding: 8bit\r\n";
   $header .= "From: blog@musicchris.de\r\n";
   $header .= "Date: $maildate\r\n";
   $header .= "\r\n";
   $subject = "Comment by {$_POST["name"]} $time";
   $mail = "Es gibt einen neuen Kommentar im Blog!\n";
   $mail .= htmlspecialchars_decode("http://musicchris.de/index.php?page=blog&amp;lang={$_POST["lang"]}&amp;index={$_POST["affiliation"]}#$time");
   $mail .= "\n\n\n{$_POST["name"]} ({$_POST["website"]}) schrieb:\n\n";
   $mail .= wordwrap($_POST["text"], 70);
   if (!mail($email_blogadmin, $subject, $mail, $header)) echo "<h3>ERROR!</h3>Failed to send mail to admin!<br>\n";
  }


$name = $_POST["name"];
if ($name == "") $name = "Anonymous";
$email = $_POST["notificationTo"];

$search = array("<", ">", "\"", "'");
$replace = array("&lt;", "&gt;", "&quot;", "&#39;");
$text = str_replace($search, $replace, $_POST["text"]);

$queryAffiliation = mysqli_real_escape_string($concom, $_POST["affiliation"]);
$queryAnswerTo = mysqli_real_escape_string($concom, $_POST["answerTo"]);
$queryTime = mysqli_real_escape_string($concom, $time);
$queryname = mysqli_real_escape_string($concom, $name);
$queryemail = mysqli_real_escape_string($concom, $email);
$querywebsite = mysqli_real_escape_string($concom, $website);
$querytext = mysqli_real_escape_string($concom, $text);

$query = "INSERT INTO `musicchris_de`.`blog-comments` (`affiliation`,`answerTo`, `time`, `name`, `email`, `website`, `comment`) VALUES ('{$queryAffiliation}', '{$queryAnswerTo}', '{$queryTime}', '{$queryname}', '{$queryemail}', '{$querywebsite}', '{$querytext}');";

echo "<pre>$query</pre><br>\n";

$result = mysqli_query($concom, $query) or die(mysqli_error($concom));

echo "query done<br>\n";
mysqli_free_result($result);


// #################################################
// ##  send email notifications for new comments  ##
// #################################################
//echo "<h2>notifications:</h2>\n";

// Get concerning blog-entry for some data (header and such)
$query_blog = "select * from `blog` WHERE (`blog`.`index` = {$_POST["affiliation"]}) ";
if ($result = mysqli_query($concom, $query_blog))
  {
   while ($row = $result->fetch_assoc())
     {
      $blog_header = $row["head"];
     }
   mysqli_free_result($result);
  }


// Send each registered adress an email

$template = file_get_contents("template_subscription.html");

$query_notifications = "SELECT * FROM `blog-comments` WHERE `affiliation` = {$_POST["affiliation"]} AND `email` > '' ORDER BY `time` ASC ";

if ($result = mysqli_query($concom, $query_notifications))
  {
   while ($row = $result->fetch_assoc())
     {
      // make sure, we only notify once!
      if (isset($sentmail["{$row["email"]}"]) and $sentmail["{$row["email"]}"] == true) continue;
      // Don't notify this poster as well!
      if ($row["email"] == $_POST["notificationTo"]) continue;

      // prepare email strings
      $email = $row["email"];

      $email_sanitizer = mailparse_rfc822_parse_addresses($email);
      $email = $email_sanitizer["address"];
      $email_name = $email_sanitizer["display"];

      $link_unsubscribe_topic = htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/blog/subscription.php?email=$email&amp;job=unsubscribe&amp;scope={$_POST["affiliation"]}");
      $link_unsubscribe_site = htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/blog/subscription.php?email=$email&amp;job=unsubscribe&amp;scope=0");

      if (strlen($email_name) > 0) $to = "$email_name <$email>";
      else $to = "$email";

      $subject = "new comment on: $blog_header @ " . $_SERVER["SERVER_NAME"];
      date_default_timezone_set('Europe/Berlin');
      $maildate = date(DATE_RFC2822);
      $header = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
      $header .= "Content-Transfer-Encoding: 8bit\r\n";
      $header .= "From: blog@musicchris.de\r\n";
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
                       htmlspecialchars_decode("https://{$_SERVER["SERVER_NAME"]}/index.php?page=blog&amp;index={$_POST["affiliation"]}#$time"),
                       wordwrap($_POST["text"], 70),
                       $link_unsubscribe_topic,
                       $link_unsubscribe_site);
      $message = str_replace($search, $replace, $template);
      if (mail($to, $subject, $message, $header)) $sentmail["{$row["email"]}"] = true;
     }
   mysqli_free_result($result);
  }

?>
</body>
</html>
