<!-- begin blog-create-tables.php -->
<?php
$DBconf = require_once("../config/db.php");
if ($debug == "TRUE") echo "<h1>Start blog-create-tables...</h1>\n";

/* Connect to database */
$con=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

/* change character set to utf8mb4 */
if (!mysqli_set_charset($con, "utf8mb4"))
  { printf("Error loading character set utf8mb4: %s<br>\n", mysqli_error($con)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($con)); } }


$query["create_blog"] = "CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) NOT NULL COMMENT 'creation time',
  `mtime` int(11) NOT NULL COMMENT 'last modified time',
  `head` text COLLATE utf8mb4_general_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$query["create_blog_tags"] = "CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;";

$query["create_blog_tags_relations"] = "CREATE TABLE IF NOT EXISTS `blog_tags_relations` (
  `blog` int(11) NOT NULL,
  `tag` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Which tag belongs to which blogpost';";

$query["create_blog_comments"] = "CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID for each comment - Auto-Indexed',
  `affiliation` int(11) NOT NULL COMMENT 'index of affiliated blog-entry',
  `answerTo` int(11) NOT NULL COMMENT 'Which comment this refers to',
  `time` int(11) NOT NULL COMMENT 'time of post',
  `name` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'poster\'s name',
  `email` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'poster\'s email for notifications',
  `website` text COLLATE utf8mb4_general_ci NOT NULL COMMENT 'poster\'s website',
  `comment` mediumtext COLLATE utf8mb4_general_ci NOT NULL COMMENT 'poster\'s post',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='comments for blog entries' AUTO_INCREMENT=1 ;";


$tables = array("blog" => "", "blog_comments" => "", "blog_tags" => "", "blog_tags_relations" => "");

foreach ($tables as $key => $value)
  {
   $querytables = "show tables from `" . $DBconf["name"] . "` like '$key'";
   if ($debug == "TRUE") echo "query set to: $querytables<br>\n";

   $tablelookup = mysqli_query($con,$querytables) or die(mysqli_error($con));
   if ($debug == "TRUE") echo "MySQL query has been executed!\n";

   $totalRows_tables = mysqli_num_rows($tablelookup);
   if ($debug == "TRUE") echo "MySQL query has returned $totalRows_tables results.<br>\n";

   if ($totalRows_tables == 1)
     {
      $tables[$key] = "exists";
      if ($debug == "TRUE") echo "<b>$key</b> {$tables[$key]}<br><br>\n";
     }
   else
     {
      $tables[$key] = "does not exist";
      echo "<b>$key</b> " . $tables[$key] . ". Creating...<br>\n";
      if ($debug == "TRUE") echo "1 != $totalRows_tables<br>\nCreating new table!<br>\n";

      $querycreate = $query["create_$key"];

      if (mysqli_real_query($con,$querycreate))
        { if ($debug == "TRUE") echo "Table $key created successfully " . mysqli_error($con) . "<br><br>\n"; }
      else
        { echo "Error creating table $key: " . mysqli_error($con) . "<br><br>\n"; }
     }
   $counter++;
  }
mysqli_close($con);
?>
<!-- end blog-create-tables.php -->
