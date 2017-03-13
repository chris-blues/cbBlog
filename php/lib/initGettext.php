<?php

$locales = scandir("locale");
foreach ($locales as $key => $language) {
  if ($language == "." or $language == ".." or !is_dir($language)) unset($locales[$key]);
}

if ($config["blog"]["language"] != "") $locale = $config["blog"]["language"]; // $config["blog"]["language"] overrides everything
else {
  if (isset($_GET["lang"])) $locale = $_GET["lang"];                          // if we have some user-setting from the URI then use this
  else {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);                   // if still nothing, try browser preference
    switch ($lang) {
      case "de": $locale = "de_DE"; break;
      case "en": $locale = "en_GB"; break;
    }
  }
}
if (!isset($locale) or $locale == "") $locale = "de_DE";                      // if all fails, use "en_GB"! (actually use inline gettext strings)

if (!isset($path)) $path = "";
$directory = $path . "locale";
$textdomain = "cbBlog";
$locale .= ".utf8";

$localeString = setlocale(LC_MESSAGES, $locale) . " ";
bindtextdomain($textdomain, $directory);
textdomain($textdomain);
$localeString .= bind_textdomain_codeset($textdomain, 'UTF-8');

echo "<!-- locale: $locale -> " . $localeString . " -->\n";

?>
