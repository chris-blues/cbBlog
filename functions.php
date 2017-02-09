<?php
function dump_var($var) {
  global $debug;
  if ($debug) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>\n";
  }
}

function dump_array($var) {
  global $debug;
  if ($debug) {
    echo "<pre>";
    print_r($var);
    echo "</pre>\n";
  }
}

function procTime($startTime, $endTime) {
  return round($endTime - $startTime, 3) . " seconds";
}




function convertnumbers($number, $lang)
  {
   if ($lang == "en")
     {
      $words = array("no", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
      $words_ten = array("ten", "eleven", "twelve");
     }
   else
     {
      $words = array("kein", "ein", "zwei", "drei", "vier", "fünf", "sechs", "sieben", "acht", "neun");
      $words_ten = array("zehn", "elf", "zwölf");
     }
   if ($number > 9)
     {
      $search = array("10", "11", "12");
      $replace = $words_ten;
     }
   else
     {
      $search = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
      $replace = $words;
     }
   if ($number < 13) $word = str_replace($search, $replace, $number);
   else $word = $number;
   return $word;
  }

function parse($string) {
  $string = bb_parse($string);
  return($string);
 }

function bb_parse($string) {
// https://secure.php.net/manual/en/function.bbcode-create.php#93349
  $tags = 'b|u|s|i|quote|url|code|tt|ot|done';
  while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`s', $string, $matches)) foreach ($matches[0] as $key => $match) {
    list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
    switch ($tag) {
      case 'b': $replacement = "<b>$innertext</b>"; break;
      case 'u': $replacement = "<u>$innertext</u>"; break;
      case 's': $replacement = "<s>$innertext</s>"; break;
      case 'i': $replacement = "<i>$innertext</i>"; break;
      case 'quote': $replacement = "<div class=\"quote comments\"><blockquote>$innertext</blockquote></div>"; break;
      case 'code': $replacement = "<pre><code>$innertext</code></pre>"; break;
      case 'tt': $replacement = "<code>$innertext</code>"; break;
      case 'ot': $replacement = "<span class=\"offtopic\">$innertext</span>"; break;
      case 'url':
        {
         if (stristr($param, "javascript:", true)) $param = "##";

         // add http:// if missing
         if (!isset($param) or $param == "") { if (strncmp($innertext, "http", 4) != 0) $innertext = "http://" . $innertext; }
         else { if (strncmp($param, "http", 4) != 0) $param = "http://" . $param; }

         // add target="_blank" to external URLs
         $externalUrl = true;
         if (strncmp($param, 'http://' . $_SERVER["HTTP_HOST"], strlen('http://' . $_SERVER["HTTP_HOST"])) == 0) $externalUrl = false;
         if (strncmp($param, 'https://' . $_SERVER["HTTP_HOST"], strlen('https://' . $_SERVER["HTTP_HOST"])) == 0) $externalUrl = false;
         if (strncmp($innertext, 'http://' . $_SERVER["HTTP_HOST"], strlen('http://' . $_SERVER["HTTP_HOST"])) == 0) $externalUrl = false;
         if (strncmp($innertext, 'https://' . $_SERVER["HTTP_HOST"], strlen('https://' . $_SERVER["HTTP_HOST"])) == 0) $externalUrl = false;
         if ($externalUrl)
              { $replacement = '<a href="' . ($param? $param : $innertext) . '" target="_blank">' . $innertext . '</a>'; }
         else { $replacement = '<a href="' . ($param? $param : $innertext) . '">' . $innertext . '</a>'; }
         unset($externalUrl);
         break;
        }
     }
    $string = str_replace($match, $replacement, $string);
   }
// additional "custom" tags
  $search = array("[done]");
  $replace = array("<span class=\"checkmark\">&#10004;</span>");
  $string = str_replace($search, $replace, $string);

  return $string;
 }

function logMailError($name, $email, $index, $hash, $mailbody)
  {
   $handle = fopen("logs/mailerror.log","a");
     fwrite ($handle, date("Y-m-d H:i:s") . " - error sending notification mail to " . trim($name) . " <" . trim($email) . ">\n");
     fwrite ($handle, date("Y-m-d H:i:s") . " - index: " . trim($index) . " . Hash: " . trim($hash) . "\n");
     fwrite ($handle, "Mailbody:\n" . $mailbody . "\n__________END OF LOG " . date("Y-m-d H:i:s") . "__________\n");
   fclose($handle);
  }

function logErrors($error)
  {
   foreach ($error as $key => $value)
     {
      $handle = fopen("logs/error.log","a");
        fwrite ($handle, date("Y-m-d H:i:s") . " - $key\n");
      fclose ($handle);
     }
  }
?>
