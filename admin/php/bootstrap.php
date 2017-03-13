<?php

$GLOBALS["version"] = trim(file_get_contents("../VERSION"));

require_once("../php/lib/functions.php");
$link = assembleGetString("string");
require_once("lib/functions.php");


// ##########################
// ##  Debugging settings  ##
// ##########################
switch($config["blog"]["debug_level"]) {
  case "full": error_reporting(E_ALL); break;
  case "warn": error_reporting(E_ALL & ~E_NOTICE); break;
  case "none": error_reporting(0); break;
}

if ($config["blog"]["show_debug"]) ini_set("display_errors", 1);
  else                             ini_set("display_errors", 0);
if ($config["blog"]["log_debug"])  ini_set("log_errors", 1);
  else                             ini_set("log_errors", 0);
ini_set("error_log", "logs/php-error.log");


// save settings, before loading them
if ($_GET["job"] == "settings" and isset($_GET["operation"]) and $_GET["operation"] != "") {
  require_once("php/lib/" . $_GET["operation"] . ".php");
}


// ###############
// ##  Configs  ##
// ###############
$config["database"] = require("../php/config/db.php");
$config["blog"]     = require("../php/config/blog.php");
$config["feeds"]    = require("../php/config/feeds.php");
$insertTags = require_once("../php/config/bbtags.php");

require_once('templates/head.php');
?>

  <body>

<?php


// ######################
// ##  Init gettext()  ##
// ######################
$path = "../";
require_once("../php/lib/initGettext.php");


// ###########################
// ##  Connect to database  ##
// ###########################
require_once("../php/lib/db/Connection.php");
require_once("../php/lib/db/QueryBuilder.php");
require_once("php/lib/db/QueryBuilder.php");

$connect = Connection::make($config["database"]);

if (is_object($connect)) {
  $query = new QueryBuilder($connect);
}
else $GLOBALS["DBdisconnected"] = true;

if (is_object($connect)) {
  $adminQuery = new AdminQueryBuilder($connect);
}

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
