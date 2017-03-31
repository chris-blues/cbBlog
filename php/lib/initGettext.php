<?php

$locales = scandir($locale_path . "/locale");
foreach ($locales as $key => $language) {
  if ($language == "." or $language == ".." or !is_dir($language)) unset($locales[$key]);
}

if ($config["blog"]["language"] != "") $locale = $config["blog"]["language"]; // $config["blog"]["language"] overrides everything
else {
  if (isset($_GET["lang"])) $locale = $_GET["lang"];                          // if we have some user-setting from the URI then use this
  else {
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);                   // if still nothing, try browser preference
      switch ($lang) {
        case "de": $locale = "de_DE"; break;
        case "en": $locale = "en_GB"; break;
      }
    }
  }
}
if (!isset($locale) or $locale == "") $locale = "de_DE";                      // if all fails, use "en_GB"! (actually use inline gettext strings)

if ($locale == "de") $locale = "de_DE";
if ($locale == "en") $locale = "en_GB";

if (!isset($locale_path)) $locale_path = "";
$directory = $locale_path . "/locale";
$textdomain = "cbBlog";
$locale .= ".utf8";

$localeString = setlocale(LC_MESSAGES, $locale) . " ";
bindtextdomain($textdomain, $directory);
textdomain($textdomain);
$localeString .= bind_textdomain_codeset($textdomain, 'UTF-8');

echo "<!-- locale: $locale -> " . $localeString . " -->\n";

?>
