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
?>