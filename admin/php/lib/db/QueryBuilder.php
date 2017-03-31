<?php

class AdminQueryBuilder extends QueryBuilder {

  protected $Database;

  public function __construct(PDO $Database) {
    $this->Database = $Database;
  }

  private function callExecution($statement) {
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      $error["callExecution"] = $e->getMessage();
      die($e->getMessage());
    }
//     dump_var($error);
    if (isset($error)) return $error;
    else return $result;
  }

  public function selectAllUnreleasedBlogposts($filter) {
    if (!isset($filter) or $filter == "") {
      $statement = $this->Database->prepare(
        "SELECT blog.* FROM blog, blog_tags, blog_tags_relations
         WHERE blog_tags.tag = 'unreleased'
           AND blog.id = blog_tags_relations.blog
           AND blog_tags_relations.tag = blog_tags.id
         ORDER BY blog.ctime DESC ; "
      );
    } else {
      $statement = $this->Database->prepare(
        "SELECT blog.*, COUNT(blog_tags_relations.blog) AS count
          FROM blog, blog_tags, blog_tags_relations
         WHERE blog_tags.tag IN ('unreleased', 'opensource')
           AND blog.id = blog_tags_relations.blog
           AND blog_tags_relations.tag = blog_tags.id
      GROUP BY blog_tags_relations.blog
        HAVING count = 2"
      );
      $statement->bindParam(':filter', $filter);
    }
    $this->callExecution($statement);
    return $statement->fetchAll(PDO::FETCH_CLASS, "Blogpost");
  }

  public function updateComment($id, $comment) {
    $statement = $this->Database->prepare("UPDATE `blog_comments` SET `comment` = :comment WHERE `id` = :id ;");
    $statement->bindParam(':comment', $comment);
    $statement->bindParam(':id', $id);
    $result = $this->callExecution($statement);
    if ($result !== true) $error["updateComment"] = true;
    if (isset($error)) return $error;
    else return true;
  }

  private function getTagId($tag) {
    $statement = $this->Database->prepare("SELECT `id` FROM `blog_tags` WHERE `tag` = :tag");
    $statement->bindParam(':tag', $tag);
    $this->callExecution($statement);
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $tmp = $statement->fetch();
    return $tmp["id"];
  }

  private function addTags($blogId, $tags) {

//     echo "addTags({$blogId}, {$tags})<br>\n";
//     dump_array($tags);

    foreach ($tags as $tagname => $tagId) {
      if ($tagId == NULL) continue;
//       echo "Adding $tagname ($tagId) ...";

      $statement = $this->Database->prepare("INSERT INTO `blog_tags_relations` (`blog`, `tag`) VALUES (:blog, :tag) ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      $result = $this->callExecution($statement);
      if ($result !== true) {
//         echo "FAILED!<br>\n";
        $error["addTags"][$tagname] = true;
      }
//       else echo "✔<br>\n";
    }
    if (isset($error)) return $error;
    else return true;
  }

  private function insertTags($blogId, $tags) {

//     echo "insertTags({$blogId}, {$tags})<br>\n";
//     dump_array($tags);

    foreach ($tags as $tagname => $tagId) {
      $statement = $this->Database->prepare("INSERT INTO `blog_tags` (`tag`) VALUES (:tag) ;");
      $statement->bindParam(':tag', $tagname);
      $result = $this->callExecution($statement);
      if ($result === true) {
        $statement = $this->Database->prepare("SELECT LAST_INSERT_ID();");
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $tmp = $statement->fetch();
        $tagId = $tmp["LAST_INSERT_ID()"];
      }
      else $error["insert_into_blog_tags"][$tagname] = true;

      $statement = $this->Database->prepare("INSERT INTO `blog_tags_relations` (`blog`, `tag`) VALUES (:blog, :tag) ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      if (!$this->callExecution($statement)) $error["query_insert_blog_tags_relations"][$tagname] = true;
    }
    if (isset($error)) return $error;
    else return true;
  }

  private function removeTags($blogId, $tags) {
//     echo '<div id="top_spacer"></div>';

    foreach ($tags as $tagname => $tagId) {
      $statement = $this->Database->prepare("DELETE FROM `blog_tags_relations` WHERE `blog` = :blog AND `tag` = :tag ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      $result = $this->callExecution($statement);
      if ($result !== true) {
        $error["removeTags"][$tagname] = $result;
      }
    }
  if (isset($error)) return $error;
  else return true;
  }

  private function cleanupOrphanedTags($tags) {
//     echo '<div id="top_spacer"></div>';

//     echo "cleanupOrphanedTags($tags)";
//     dump_array($tags);

    foreach ($tags as $tagname => $tagId) {
      $statement = $this->Database->prepare("SELECT * FROM `blog_tags_relations` WHERE `tag` = :tag ;");
      $statement->bindParam(':tag', $tagId);
      $this->callExecution($statement);
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);

//       echo "\$result:";
//       dump_array($result);

      if (count($result) < 1) {
        $statement = $this->Database->prepare("DELETE FROM `blog_tags` WHERE `tag` = :tag ;");
        $statement->bindParam(':tag', $tagname);
        $result = $this->callExecution($statement);
        if ($result !== true) { $error["cleanupOrphanedTags"] = true; }
      }
    }
//     showErrors($error);
    if (isset($error)) return $error;
    else return true;
  }

  private function checkoutTags($blogId, $tags) {
//     echo '<div id="top_spacer"></div>';
//     echo "<br>\ncheckoutTags($blogId, $tags);<br>\n";
//     dump_array($tags);

    if(!is_array($tags)) {
      if (isset($tags) and $tags != "") {
        $tmp = $tags;
        $tags = array($tmp);
      } else {
        $tags = array();
      }
    }

    // get the tags of this request
    foreach ($tags as $id => $tag) {
      if (strlen(trim($tag)) < 1) {
        unset($tags[$id]);
        continue;
      }
      $sentTags[$tag] = $this->getTagId($tag);
    }
    if (!is_array($sentTags)) {
      $sentTags = array();
    }
//     echo "\$sentTags:\n";
//     dump_array($sentTags);

    // get all available tags from database
    $tmp = $this->selectAllTags();
    foreach ($tmp as $key => $value) {
      $temp = $value->getdata();
      $allTags[$temp["tag"]] = $temp["id"];
    }
    if (!is_array($allTags)) {
      $allTags = array();
    }
//     echo "All available Tags:\n";
//     dump_array($allTags);

    // get the tags of this post that are already in the database
    $readTags = $this->getTagsOfBlogpost($blogId);
    foreach ($readTags as $id => $value) {
      $tmp = $value->getdata();
      $oldTags[$tmp["tag"]] = $tmp["id"];
    }
    if(!isset($oldTags) or !is_array($oldTags)) {
      $oldTags = array();
    }
//     echo "\$oldTags:\n";
//     dump_array($oldTags);

    // compare the arrays and invoke appropriate action

    // check for removed tags
//     echo "check for removed tags:<br>\n";

    $removedTags = array_diff($oldTags, $sentTags);
    if (isset($removedTags) and count($removedTags) > 0) {
      if (!is_array($removedTags)) {
        $removedTags = array();
      }
//       dump_array($removedTags);

      $result = $this->removeTags($blogId, $removedTags);
      if ($result !== true) {
        $error["checkoutTags"]["removeTags"] = $result;
      }
      $result = $this->cleanupOrphanedTags($removedTags);
      if ($result !== true) {
        $error["checkoutTags"]["cleanupOrphanedTags"] = $result;
      }
    }

    // check for entirely new tags
//     echo "check for entirely new tags<br>\n";
    $newTags = array_diff($sentTags, $allTags);
//     dump_array($newTags);

    if (isset($newTags) and count($newTags) > 0) {
      $result = $this->insertTags($blogId, $newTags);
      if ($result !== true) $error["checkoutTags"]["insertTags"] = $result;
    }

    // check for added tags
//     echo "check for added tags<br>\n";
    $newTags = array_diff($sentTags, $oldTags);
//     dump_array($newTags);

    if (isset($newTags) and count($newTags) > 0) {
      $result = $this->addTags($blogId, $newTags);
      if ($result !== true) $error["checkoutTags"]["addTags"] = $result;
    }

    if (isset($error)) return $error;
    else return true;
  }

  public function updateBlog($post) {
    $statement = $this->Database->prepare("UPDATE `blog` SET `head` = :head, `text` = :text WHERE `blog`.`id` = :id;");
    $statement->bindParam(':head', $post["head"]);
    $statement->bindParam(':text', $post["text"]);
    $statement->bindParam(':id', $post["id"]);
    $result = $this->callExecution($statement);
    if ($result !== true) $error["updateBlog"]["query"] = true;

    $result = $this->checkoutTags($post["id"], $post["tags"]);
    if ($result !== true) $error["updateBlog"]["checkoutTags"] = $result;

    if (isset($error)) return $error;
    else return true;
  }

  public function insertBlog($post) {
//     echo '<div id="top_spacer"></div>';
//     dump_array($post);

    $time = time();

    $statement = $this->Database->prepare("INSERT INTO `blog` (ctime, head, text) VALUES (:ctime, :head, :text);");
    $statement->bindParam(':ctime', $time);
    $statement->bindParam(':head', $post["head"]);
    $statement->bindParam(':text', $post["text"]);
    $result = $this->callExecution($statement);
    if ($result === true) {
      $statement = $this->Database->prepare("SELECT LAST_INSERT_ID();");
      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);
      $tmp = $statement->fetch();
    }
    else {
      return $error["insertBlog"]["query"] = true;
    }

    $result = $this->checkoutTags($tmp["LAST_INSERT_ID()"], $post["tags"]);
    if ($result !== true) {
      $error["insertBlog"]["checkoutTags"] = $result;
    }

    if (isset($error)) return $error;
    else return $tmp["LAST_INSERT_ID()"];
  }

  public function deleteBlog($id) {
    $statement = $this->Database->prepare(
      "SELECT `blog_tags`.*
      FROM `blog_tags`,`blog_tags_relations`
      WHERE `blog_tags_relations`.`blog` = :blogid
      AND `blog_tags`.`id` = `blog_tags_relations`.`tag` ;"
    );
    $statement->bindParam(':blogid', $id);
    $this->callExecution($statement);
    $tagIds = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tagIds as $key => $value) {
      $tags[$value["tag"]] = $value["id"];
    }
    if (isset($tags) and count($tags) > 0) {
      $result = $this->removeTags($id, $tags);
      if ($result !== true) {
        $error["deleteBlog"]["removeTags"] = $result;
      }
      $result = $this->cleanupOrphanedTags($tags);
      if ($result !== true) {
        $error["deleteBlog"]["cleanupOrphanedTags"] = $result;
      }
    }

    $statement = $this->Database->prepare("DELETE FROM `blog` WHERE `id` = :id ;");
    $statement->bindParam(':id', $id);
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["deleteBlog"]["removeBlog"] = true;
    }

    if (isset($error)) return $error;
    else return true;
  }

  public function deleteComment($id) {
    $statement = $this->Database->prepare("DELETE FROM `blog_comments` WHERE `id` = :id ;");
    $statement->bindParam(':id', $id);
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["deleteComment"] = true;
    }

    if (isset($error)) return $error;
    else return true;
  }

  public function createTables() {

    $query["create_blog"] = "CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  `head` text NOT NULL,
  `text` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

    $query["create_blog_tags"] = "CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

    $query["create_blog_tags_relations"] = "CREATE TABLE IF NOT EXISTS `blog_tags_relations` (
  `blog` int(11) NOT NULL,
  `tag` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

    $query["create_blog_comments"] = "CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `affiliation` int(11) NOT NULL,
  `answerTo` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `website` text NOT NULL,
  `comment` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

    $tables = array("blog" => "", "blog_comments" => "", "blog_tags" => "", "blog_tags_relations" => "");

    foreach ($tables as $key => $value) {
      $statement = $this->Database->prepare("SHOW TABLES LIKE :table ;");
      $statement->bindParam(':table', $key);
      $result = $this->callExecution($statement);

      if ($result !== true) {
        $error["createTables"]["checkForTables"] = $result;
        echo "<p class=\"remark\">Error! CheckForTables failed:<pre>" . $result . "</pre></p>";
      }

      $data = $statement->fetchAll();
//       dump_var($data);

      if (count($data) > 0) {
        $tablename = $data[0][0];
//         echo "Table {$key} (returned: " . $tablename . ") exists. Nothing to do.<br>\n";

        if ( strcmp($tablename, str_replace("-", "_", $tablename) ) != 0 ) {
          echo "<p class=\"center\">Database update needed! We have &lt; 0.13 format!</p>\n";
          $GLOBALS["DatabaseUpdateNeeded"] = 0.13;
        }
        continue;
      }
      else {
        $tables[$key] = "does not exist";
        echo "<p class=\"center\">Database table <b>$key</b> " . $tables[$key] . ". Creating... ";

        $statement = $this->Database->prepare($query["create_$key"]);
        $result = $this->callExecution($statement);
        if ($result !== true) {
          echo "FAILED!</p>\n";
          $error["createTables"]["create_{$key}"] = $result;
        }
        else {
          echo "✔</p>\n";
        }
      }
//     $counter++;
    }

    if (isset($error)) return $error;
    else return true;

  }

  public function renameTable($old, $new) {
    $statement = $this->Database->prepare("RENAME TABLE `$old` TO `$new` ;");
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["renameTable"][$old] = true;
    }

    if (isset($error)) return $error;
    else return true;
  }

  public function selectOldBlogposts() {
    $statement = $this->Database->prepare("SELECT * FROM blog;");
    $result = $this->callExecution($statement);
    if ($result) return $statement->fetchAll(PDO::FETCH_CLASS, "Blogpost0_13");
    else return false;
  }

  public function selectOldTags() {
    $statement = $this->Database->prepare("SELECT * FROM `blog-tags`;");
    $result = $this->callExecution($statement);
    if ($result) return $statement->fetchAll(PDO::FETCH_CLASS, "Tags");
    else return false;
  }

  public function insertOldTags($query) {
    $statement = $this->Database->prepare($query);
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["insertOldTags"] = true;
    }

    if (isset($error)) return $error;
    else return true;
  }

  public function dropTags() {
    $statement = $this->Database->prepare("ALTER TABLE `blog` DROP `tags`");
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["dropTags"] = true;
    }

    $statement = $this->Database->prepare("ALTER TABLE `blog` DROP `sorttime`");
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["dropSorttime"] = true;
    }

    if (isset($error)) return $error;
    else return true;
  }

  public function renameIndex() {
    $statement = $this->Database->prepare("ALTER TABLE `blog` CHANGE `index` `id` INT( 11 ) NOT NULL AUTO_INCREMENT; ");
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["renameIndex"] = true;
    }

    $statement = $this->Database->prepare("ALTER TABLE `blog-tags` CHANGE `index` `id` INT( 11 ) NOT NULL AUTO_INCREMENT; ");
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["renameIndex"] = true;
    }

    $statement = $this->Database->prepare("ALTER TABLE `blog-comments` CHANGE `number` `id` INT( 11 ) NOT NULL AUTO_INCREMENT; ");
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["renameIndex"] = true;
    }

    $statement = $this->Database->prepare("ALTER TABLE `blog` CHANGE `ctime` `mtime` INT( 11 );");
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["renameCtime"] = true;
    }

    $statement = $this->Database->prepare("ALTER TABLE `blog` CHANGE `time` `ctime` INT( 11 );");
    $result = $this->callExecution($statement);
    if ($result !== true) {
      $error["renameTime"] = true;
    }

    if (isset($error)) return $error;
    else return true;
  }
}

// echo "<p class=\"center\">admin/QueryBuilder.php</p>";

?>
