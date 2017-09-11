<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../blog.css" type="text/css">
  </head>
<body>

<div id="checkipsWrapper">
<div id="checkipsText">
<h2>IPs identified as Spammers</h2>

<?php
// ===============
// Select timezone
// ===============
// script taken and slightly modified from : http://php.net/manual/de/timezones.europe.php#115104
// ===============

$ipfile = file("ipfile.txt");

if (!isset($_GET["sort"]) or $_GET["sort"] == "") $sort = "date";
else $sort = $_GET["sort"];

if (isset($_GET["show"]) and $_GET["show"] != "") $show = $_GET["show"];

if (isset($_GET["timezone"]) and $_GET["timezone"] != "") $timezone = urldecode($_GET["timezone"]);
else $timezone ="UTC";

date_default_timezone_set(urldecode($timezone));

// $lang = 'pt';
$lang = 'en';

if (function_exists("timezone_identifiers_list"))
{
$arr_timez_id_lst2 = timezone_identifiers_list();

$pt = 'Lista dos identificadores: <br><br>';
$en = 'Select timezone: <br><br>';
echo $$lang;

echo '<form id="timezoneselect" name="timezoneselect" method="get" action="checkips.php">  <select name="timezone" onchange="this.form.submit();">';

foreach( $arr_timez_id_lst2 as $timez2)
{
echo '    <option value="' . $timez2 . '"';
if ($timez2 == $timezone) echo " selected";
echo '>' . $timez2 . '</option>';
}
echo "  </select>\n";
if (isset($show) and $show != "") echo "  <input type=\"hidden\" name=\"show\" value=\"$show\">\n";
echo "  <input type=\"hidden\" name=\"sort\" value=\"$sort\">\n";
echo "<button type=\"submit\">OK</button>\n</form>";
}
else
{
$en = 'FUNCTION NOT IMPLEMENTED ON THIS PLATFORM!';
$pt = 'FUNÇÃO NÃO DEFINIDA PARA ESTE AMBIENTE!';
echo '<span style="color:#039;font-size:18px;">' . $$lang . '</span>';
}
// ===============
// Select timezone
// ===============
// script taken and slightly modified from : http://php.net/manual/de/timezones.europe.php#115104
// ===============
?>

<?php if (!isset($_GET["show"]) or $_GET["show"] == "") { ?><p style="display: inline; background-color: #BBBBBB;">Sorted by <b><?php echo $sort; ?></b></p><br> <?php } ?>
<br>
<?php if (!isset($_GET["show"]) or $_GET["show"] == "")
        { echo "<p><b>" . count($ipcount) . "</b> IPs detected after <b>$totalips</b> attacks so far.<br>\nLast detection: <b>" . date ("F d Y H:i:s.", filemtime("ipfile.txt")) . "</b> ($timezone)</p>\n"; } ?>
<p>These IPs have been trying to spam into my blog's comments. I collect them in order to devise a better spam detection machanism some time in the future.<br>
I'm also thinking about extracting the abuse mail address from the whois-site and forward every spam-notification to them...</p>
<h2>Notes</h2>

<ul id="checkipNotes">
<li><b>2014-10-30:</b> I added a field "time" to the table. Most fields didn't have that information till now, but I suspect that's going to change... ;-)</li>

<li><b>2014-11-12:</b> I was evil! After adding the time-field I also reported a site as spammers to it's host! Soon after that - the spamming stopped. Almost completely. As you can see for yourself, the last time is only 5 days after my report. This is now more than a month at the time of this writing. Maybe we all should log spammers IPs and report them more often?</li>

<li><b>2014-11-21:</b> After the next wave of Spam Attacks, the numbers nearly doubled. For some days, there was about 1 Spam per hour... It was from Nov 15 - 18</li>

<li><b>2015-05-20:</b> I added a sorting function to the table.</li>

<li><b>2015-09-24:</b> I added a detailed view for every IP (click on the "times registered" of the interesting IP.)</li>
</ul>
</div>

<?php

// ===================================================
// If we want only one IP with all times it registered
// ===================================================
if (isset($_GET["show"]) and $_GET["show"] != "")
  {
   //echo "<pre>"; print_r($ipfile); echo "</pre>\n";
   echo "<a href=\"?sort=$sort&timezone=" . urlencode($timezone) . "\">BACK</a><br>\n";
   foreach ($ipfile as $key => $value)
     {
      $value = trim($value);
      $value = explode(" ", $value);
      if (strcmp($_GET["show"], $value[0]) == 0)
        {
         $ip[] = $value[1];
        }
     }
   echo "<h1>Stats for: <a href=\"http://whois.com/whois/{$show}\" target=\"_blank\" title=\"look up at WhoIs.net\">{$show}</a></h1>\n";
?>
   <table id="checkipsTable" border="1">
     <tr>
       <th> number </th>
       <th> time <?php echo $_GET["show"]; ?> registered <?php echo " (" . urldecode($timezone) . ")"; ?></th>
       <th> UNIX timestamp </th>
     </tr>
<?php
   foreach ($ip as $key => $value)
     {
      if ($value != "") $time = date("Y-m-d - H:i:s", $value);
      else { $time = "N/A"; $value = "N/A"; }
      $key++;
      echo "<tr><td>$key</td><td>$time</td><td>$value</td></tr>\n";
     }
   echo "</table>\n";
  }

// ===========================================================
// If we want to see all registered IPs sorted in some fashion
// ===========================================================
else {
foreach ($ipfile as $key => $value)
  {
   $ipfile[$key] = trim($value);
   $ip = explode(" ", $ipfile[$key]);
   $ips[$key] = $ip[0];
   if (!isset($data[$ip[0]])) $data[$ip[0]] = $ip[1];
   unset($ip);
  }
$totalips = count($ipfile);
$ipcount = array_count_values($ips);
unset($key, $value);

// Sorting     if ($sort == "date") simply leave as is, the ipfile is sorted by time anyway - first entry is newest, last entry is oldest
if ($sort == "amount") arsort($ipcount);
if ($sort == "ip") ksort($ipcount, SORT_NUMERIC);

?>
<table border="1" id="checkipsTable">
  <tr>
    <th>number</th>
    <th><?php if ($sort != "amount") echo "<a href=\"?sort=amount&timezone=" . urlencode($timezone) . "\">"; ?>times registered</a></th>
    <th><?php if ($sort != "ip") echo "<a href=\"?sort=ip&timezone=" . urlencode($timezone) . "\">"; ?>IP w/ lookup</a></th>
    <th><?php if ($sort != "date") echo "<a href=\"?sort=date&timezone=" . urlencode($timezone) . "\">"; ?>last contact</a><?php echo " ($timezone)"; ?></th>
    <th> UNIX timestamp </th>
  </tr>
<?php
$counter = 0;
foreach ($ipcount as $key => $value)
  {
   $counter++;
   echo "<tr><td>$counter</td><td><a href=\"?show={$key}&sort={$sort}&timezone=" . urlencode($timezone) . "\" title=\"click here to see the times, this IP has registered\">{$value}x registered</a></td><td><a href=\"http://whois.com/whois/$key\" target=\"_blank\" title=\"look up at WhoIs.net\">$key</a></td><td>";
   if ($data[$key] != "") echo date("Y-m-d. H:i:s", $data[$key]);
   echo "</td><td>{$data[$key]}</td></tr>\n";
  }
echo "</table>\n";
}

// echo "<pre>"; print_r($data); echo "</pre>\n";
// echo "<pre>"; print_r($ipcount); echo "</pre>\n";
?>
</div>
</body>
</html>
