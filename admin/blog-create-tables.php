<!-- begin blog-create-tables.php -->
<?php
if ($debug == "TRUE") echo "<h1>Start blog-create-tables...</h1>\n";

/* Connect to database */
$con=mysqli_connect($hostname, $userdb, $passworddb, $db);
  if (mysqli_connect_errno())
    { echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br>\n"; }
  else
    { if ($debug == "TRUE") echo "Successfully connected. " . mysqli_connect_error() . "<br>\n"; }

/* change character set to utf8 */
if (!mysqli_set_charset($con, "utf8"))
  { printf("Error loading character set utf8: %s<br>\n", mysqli_error($con)); }
else
  { if ($debug == "TRUE") { printf("Current character set: %s<br>\n", mysqli_character_set_name($con)); } }

$query["create_blog-comments"] = "CREATE TABLE IF NOT EXISTS `blog-comments` 
(`number` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID for each comment - Auto-Indexed',
 `affiliation` int(11) NOT NULL COMMENT 'index of affiliated blog-entry',
 `time` int(11) NOT NULL COMMENT 'time of post',
 `name` text COLLATE utf8_bin NOT NULL COMMENT 'poster''s name',
 `website` text COLLATE utf8_bin NOT NULL COMMENT 'poster''s website', 
 `comment` mediumtext COLLATE utf8_bin NOT NULL COMMENT 'poster''s post',
PRIMARY KEY (`number`)
UNIQUE KEY `number` (`number`) )
ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='comments for blog entries' AUTO_INCREMENT=1 ;";

$query["create_blog"] = "CREATE TABLE IF NOT EXISTS `blog` 
(`time` int(11) NOT NULL,
`sorttime` bigint(11) NOT NULL,
`ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'last modified time',
`index` int(11) NOT NULL AUTO_INCREMENT,
`tags` text COLLATE utf8_bin NOT NULL,
`head` text COLLATE utf8_bin NOT NULL,
`text` longtext COLLATE utf8_bin NOT NULL,
PRIMARY KEY (`index`),
KEY `index` (`index`),
KEY `index_2` (`index`),
KEY `sorttime` (`sorttime`),
FULLTEXT KEY `tags` (`tags`))
ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;";

$query["create_blog-tags"] = "CREATE TABLE IF NOT EXISTS `blog-tags` 
(`index` int(11) NOT NULL AUTO_INCREMENT,
 `tag` text COLLATE utf8_bin NOT NULL COMMENT 'tags for news',
PRIMARY KEY (`index`))
ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='tags for news' AUTO_INCREMENT=1 ;";

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
