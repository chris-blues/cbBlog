<?php
$startTime = microtime(true);
// ====================[ some default settings ]====================
if ($_GET["id"] == "0") unset($_GET["id"]);
date_default_timezone_set('Europe/Berlin');

// Ugly workaround for old cbBlog databases
if (!isset($_GET["id"]) and isset($_GET["index"]) and $_GET["index"] != "") { $_GET["id"] = $_GET["index"]; unset($_GET["index"]); }

require_once("lib/functions.php");
$link = assembleGetString("string");


// ##########################
// ##  Debugging settings  ##
// ##########################
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "admin/logs/php-error.log");
$debug = true;


// ###############
// ##  Configs  ##
// ###############
$config["database"] = require_once("config/db.php");
$config["blog"] = require_once("config/blog.php");
$insertTags = require_once("config/bbtags.php");

if ($config["blog"]["standalone"]) require_once("templates/view.head.php");


// ######################
// ##  Init gettext()  ##
// ######################
require_once("lib/initGettext.php");


// ###########################
// ##  Connect to database  ##
// ###########################
require_once("lib/db/Connection.php");
require_once("lib/db/QueryBuilder.php");

$query = new QueryBuilder(
  Connection::make($config["database"])
);


// ########################
// ##  Blog dataclasses  ##
// ########################
require_once("lib/Blogpost.php");
require_once("lib/Tags.php");
require_once("lib/Filters.php");
require_once("lib/Comment.php");


// ====================[ cleanup $_GET["filter"] ]====================
if (!isset($_GET["filter"]) or $_GET["filter"] == "") $filter = "";
else $filter = $_GET["filter"];

// ====================[ select query ]====================
if (isset($_GET["id"]) and $_GET["id"] != "") {
  $blogposts[$_GET["id"]] = $query->selectBlogpostsById($_GET["id"]);
}
else {
  $blogposts = $query->selectAllBlogposts($filter);
}

?>
