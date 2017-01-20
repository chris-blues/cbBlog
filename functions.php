<?php
function convertnumbers($number, $lang)
  {
   if ($lang == "english")
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
   $word = str_replace($search, $replace, $number);
   return $word;
  }

// https://secure.php.net/manual/en/function.bbcode-create.php#93349
function bb_parse($string) {
        $tags = 'b|i|quote|url|code';
        while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`', $string, $matches)) foreach ($matches[0] as $key => $match) {
            list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
            switch ($tag) {
                case 'b': $replacement = "<strong>$innertext</strong>"; break;
                case 'i': $replacement = "<i>$innertext</i>"; break;
                case 'quote': $replacement = "<div class=\"quote\"><blockquote>$innertext</blockquote></div>"; break;
                case 'url': $replacement = '<a href="' . ($param? $param : $innertext) . "\">$innertext</a>"; break;
                case 'code': $replacement = "<pre><code>$innertext</code></pre>"; break;
            }
         $string = str_replace($match, $replacement, $string);
        }
     return $string;
    }
// https://secure.php.net/manual/en/function.bbcode-create.php#93349

?>