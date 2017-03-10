<?php

require_once("../php/lib/functions.php");
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


// save settings, before loading them
if ($_GET["job"] == "settings" and isset($_GET["operation"]) and $_GET["operation"] != "") {
  require_once("php/lib/" . $_GET["operation"] . ".php");
}


// ###############
// ##  Configs  ##
// ###############
$config["database"] = require("../php/config/db.php");
$config["blog"]     = require("../php/config/blog.php");
$insertTags = require_once("../php/config/bbtags.php");

require_once('templates/head.php');


// ######################
// ##  Init gettext()  ##
// ######################
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

$directory = "../locale";
$textdomain = "cbBlog";
$locale .= ".utf8";

$localeString = setlocale(LC_MESSAGES, $locale) . " ";
bindtextdomain($textdomain, $directory);
textdomain($textdomain);
$localeString .= bind_textdomain_codeset($textdomain, 'UTF-8');

echo "<!-- locale: $locale -> " . $localeString . " -->\n";


// ###########################
// ##  Connect to database  ##
// ###########################
require_once("../php/lib/db/Connection.php");
require_once("../php/lib/db/QueryBuilder.php");
require_once("php/lib/db/QueryBuilder.php");

$query = new QueryBuilder(
  Connection::make($config["database"])
);
$adminQuery = new AdminQueryBuilder(
  Connection::make($config["database"])
);

// ########################
// ##  Blog dataclasses  ##
// ########################
require_once("../php/lib/Blogpost.php");
require_once("../php/lib/Tags.php");
require_once("../php/lib/Filters.php");
require_once("../php/lib/Comment.php");


// ====================[ cleanup/get $_GET["filter"] ]====================
if (!isset($_GET["filter"]) or $_GET["filter"] == "") $filter = "";
else $filter = $_GET["filter"];

?>
