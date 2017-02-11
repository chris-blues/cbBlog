<?php
require_once("lib/functions.php");
$link = assembleGetString();
// ##########################
// ##  Debugging settings  ##
// ##########################
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "admin/logs/php-error.log");
$debug = true;

// ######################
// ##  Init gettext()  ##
// ######################
switch($lang)
  {
   case 'de': { $locale = "de_DE"; break; }
   case 'en': { $locale = "en_GB"; break; }
   case 'fr': { $locale = "fr_FR"; break; }
   case 'pt': { $locale = "pt_PT"; break; }
   default:   { $locale = "de_DE"; break; }
  }
$directory = "locale";
$textdomain = "cbBlog";
$locale .= ".utf8";

$localeString = setlocale(LC_MESSAGES, $locale);
bindtextdomain($textdomain, $directory);
textdomain($textdomain);
$localeString .= " ";
$localeString .= bind_textdomain_codeset($textdomain, 'UTF-8');

echo "<!-- locale: " . $localeString . " -->\n";

// ###########################
// ##  Connect to database  ##
// ###########################
require_once("lib/db/Connection.php");
require_once("lib/db/QueryBuilder.php");

$config["database"] = require_once("config/db.php");
$config["blog"] = require_once("config/blog.php");

if ($config["blog"]["standalone"]) require_once("templates/view.head.php");


$query = new QueryBuilder(
  Connection::make($config["database"])
);


// ########################
// ##  Blog dataclasses  ##
// ########################
require_once("lib/Blogpost.php");
require_once("lib/Tags.php");
require_once("lib/Filters.php");
?>
