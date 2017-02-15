<?php

require_once("../lib/functions.php");
$link = assembleGetString("string");
require_once("lib/functions.php");


// ##########################
// ##  Debugging settings  ##
// ##########################
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "logs/php-error.log");
$debug = true;


// ###############
// ##  Configs  ##
// ###############
$config["database"] = require_once("../config/db.php");
$config["blog"]     = require_once("../config/blog.php");

if ($config["blog"]["showProcessingTime"]) $startTime = microtime(true);

require_once('templates/head.php');


// ######################
// ##  Init gettext()  ##
// ######################
if ($config["blog"]["language"] != "") $locale = $config["blog"]["language"]; // $config["blog"]["language"] overrides everything
else {
  if (isset($_GET["lang"])) $locale = $_GET["lang"];                          // if we have some user-setting from the URI then use this
  else $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);              // if still nothing, try browser preference
}
if (!isset($locale) or $locale == "") $locale = "en_GB";                      // if all fails, use "en_GB"! (actually use inline gettext strings)

$directory = "../locale";
$textdomain = "cbBlog";
$locale .= ".utf8";

$localeString = setlocale(LC_MESSAGES, $locale) . " ";
bindtextdomain($textdomain, $directory);
textdomain($textdomain);
$localeString .= bind_textdomain_codeset($textdomain, 'UTF-8');

echo "<!-- locale: " . $localeString . " -->\n";


// ###########################
// ##  Connect to database  ##
// ###########################
require_once("../lib/db/Connection.php");
require_once("../lib/db/QueryBuilder.php");

$query = new QueryBuilder(
  Connection::make($config["database"])
);


// ########################
// ##  Blog dataclasses  ##
// ########################
require_once("../lib/Blogpost.php");
require_once("../lib/Tags.php");
require_once("../lib/Filters.php");
require_once("../lib/Comment.php");


// ====================[ cleanup/get $_GET["filter"] ]====================
if (!isset($_GET["filter"]) or $_GET["filter"] == "") $filter = "";
else $filter = $_GET["filter"];

?>
