<?php
// ##########################
// ##  Debugging settings  ##
// ##########################
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "admin/logs/php-error.log");
$debug = true;

// ###########################
// ##  Connect to database  ##
// ###########################
require_once("lib/db/Connection.php");
require_once("lib/db/QueryBuilder.php");

$config = require_once("config/db.php");
$query = new QueryBuilder(
  Connection::make($config["database"])
);

// ########################
// ##  Blog dataclasses  ##
// ########################
require_once("lib/Blogpost.php");
?>
