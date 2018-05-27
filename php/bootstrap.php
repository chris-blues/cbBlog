<?php

$version = trim(file_get_contents($GLOBALS["path"] . "/VERSION"));

// ====================[ some default settings ]====================
if (isset($_GET["id"]) and ($_GET["id"] == "0" or (!is_numeric($_GET["id"]) and intval($_GET["id"]) == 0))) unset($_GET["id"]);
date_default_timezone_set('Europe/Berlin');

// Ugly workaround for old cbBlog databases
if (!isset($_GET["id"]) and isset($_GET["index"]) and $_GET["index"] != "") { $_GET["id"] = $_GET["index"]; unset($_GET["index"]); }

require_once($GLOBALS["path"] . "/php/lib/functions.php");
$link = assembleGetString("string");


// ###############
// ##  Configs  ##
// ###############
$config["database"] = require_once($GLOBALS["path"] . "/php/config/db.php");
$config["blog"] = require_once($GLOBALS["path"] . "/php/config/blog.php");
$config["email"] = require_once($GLOBALS["path"] . "/php/config/email.php");
$insertTags = require_once($GLOBALS["path"] . "/php/config/bbtags.php");

$GLOBALS["relativePath"] = $config["blog"]["path"];

if ($config["blog"]["standalone"] and $GLOBALS["displayMode"] != "short") require_once($GLOBALS["path"] . "/php/templates/view.head.php");


// ##########################
// ##  Debugging settings  ##
// ##########################
switch($config["blog"]["debug_level"]) {
  case "full": error_reporting(E_ALL); break;
  case "warn": error_reporting(E_ALL & ~E_NOTICE); break;
  case "none": error_reporting(0); break;
}

if ($config["blog"]["show_debug"]) {
  ini_set("display_errors", 1);
  if ($config["blog"]["debug_level"] != "none") {
    $debug = true;
  }
}
else ini_set("display_errors", 0);
if ($config["blog"]["log_debug"])  ini_set("log_errors", 1);
  else                             ini_set("log_errors", 0);
ini_set("error_log", "admin/logs/php-error.log");


// ######################
// ##  Init gettext()  ##
// ######################
$locale_path = $GLOBALS["path"];
require_once($GLOBALS["path"] . "/php/lib/initGettext.php");


// ###########################
// ##  Connect to database  ##
// ###########################
require_once($GLOBALS["path"] . "/php/lib/db/Connection.php");
require_once($GLOBALS["path"] . "/php/lib/db/QueryBuilder.php");

$connect = Connection::make($config["database"]);
if (is_object($connect)) {
  $query = new QueryBuilder($connect);
  $GLOBALS["DBdisconnected"] = false;
}
else $GLOBALS["DBdisconnected"] = true;


// ########################
// ##  Blog dataclasses  ##
// ########################
require_once($GLOBALS["path"] . "/php/lib/Blogpost.php");
require_once($GLOBALS["path"] . "/php/lib/Tags.php");
require_once($GLOBALS["path"] . "/php/lib/Filters.php");
require_once($GLOBALS["path"] . "/php/lib/Comment.php");
require_once($GLOBALS["path"] . "/php/lib/Email.php");


// ====================[ cleanup $_GET["filter"] ]====================
if (!isset($_GET["filter"]) or $_GET["filter"] == "") $filter = "";
else $filter = $_GET["filter"];

// ====================[ select query ]====================
if (!$GLOBALS["DBdisconnected"]) {
  if (isset($_GET["id"]) and $_GET["id"] != "") {
    $blogposts[$_GET["id"]] = $query->selectBlogpostsById($_GET["id"]);
  }
  else {
    $blogposts = $query->selectAllBlogposts($filter);
  }
}
else $error["Database"]["connection_error"] = gettext("Not connected. Possibly wrong credentials.");

?>
