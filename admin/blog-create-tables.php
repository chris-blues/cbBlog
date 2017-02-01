<!-- begin blog-create-tables.php -->
<?php
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
  `time` int(11) NOT NULL,
  `sorttime` bigint(11) NOT NULL,
  `ctime` int(11) NOT NULL COMMENT 'last modified time',
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `tags` text COLLATE utf8mb4_bin NOT NULL,
  `head` text COLLATE utf8mb4_bin NOT NULL,
  `text` longtext COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`index`),
  UNIQUE KEY `index_3` (`index`),
  UNIQUE KEY `index_6` (`index`),
  KEY `index` (`index`),
  KEY `index_2` (`index`),
  KEY `sorttime` (`sorttime`),
  KEY `index_4` (`index`),
  KEY `index_5` (`index`),
  FULLTEXT KEY `tags` (`tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1 ;";

$query["create_blog-tags"] = "CREATE TABLE IF NOT EXISTS `blog-tags` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `tag` text COLLATE utf8mb4_bin NOT NULL COMMENT 'tags for news',
  PRIMARY KEY (`index`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='tags for news' AUTO_INCREMENT=1 ;";

$query["create_blog-comments"] = "CREATE TABLE IF NOT EXISTS `blog-comments` (
  `number` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID for each comment - Auto-Indexed',
  `affiliation` int(11) NOT NULL COMMENT 'index of affiliated blog-entry',
  `answerTo` int(11) NOT NULL COMMENT 'Which comment this refers to',
  `time` int(11) NOT NULL COMMENT 'time of post',
  `name` text COLLATE utf8mb4_bin NOT NULL COMMENT 'poster''s name',
  `email` text COLLATE utf8mb4_bin NOT NULL 'poster's email for notifications',
  `website` text COLLATE utf8mb4_bin NOT NULL COMMENT 'poster''s website',
  `comment` mediumtext COLLATE utf8mb4_bin NOT NULL COMMENT 'poster''s post',
  PRIMARY KEY (`number`),
  UNIQUE KEY `number` (`number`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='comments for blog entries' AUTO_INCREMENT=1 ;";


$tables = array("blog" => "", "blog-comments" => "", "blog-tags" => "");

foreach ($tables as $key => $value)
  {
   $querytables = "show tables from `musicchris_de` like '$key'";
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
