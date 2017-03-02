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
      die($e->getMessage());
    }
    return $result;
  }

  public function updateComment($id, $comment) {
    $statement = $this->Database->prepare("UPDATE `blog_comments` SET `comment` = :comment WHERE `id` = :id ;");
    $statement->bindParam(':comment', $comment);
    $statement->bindParam(':id', $id);
    $result = $this->callExecution($statement);
    return $result;
  }

  private function getTagId($tag) {
    $statement = $this->Database->prepare("SELECT `id` FROM `blog_tags` WHERE `tag` = :tag");
    $statement->bindParam(':tag', $tag);
    $this->callExecution($statement);
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $tmp = $statement->fetch();
    return $tmp["id"];
  }

  private function removeTags($blogId, $tags) {
    foreach ($tags as $tagname => $tagId) {
      $statement = $this->Database->prepare("DELETE FROM `blog_tags_relations` WHERE `blog` = :blog AND `tag` = :tag ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      if (!$this->callExecution($statement)) $error["query_removeTags"][$tagname] = true;
    }
  }

  private function addTags($blogId, $tags) {
    foreach ($tags as $tagname => $tagId) {
      if ($tagId == NULL) continue;
      $statement = $this->Database->prepare("INSERT INTO `blog_tags_relations` (`blog`, `tag`) VALUES (:blog, :tag) ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      if (!$this->callExecution($statement)) $error["query_addTags"][$tagname] = true;
    }
    if (isset($error)) return $error;
    else return true;
  }

  private function insertTags ($blogId, $tags) {
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

      $statement = $this->Database->prepare("INSERT INTO `blog_tags_relations` (`blog`, `tag`) VALUES (:blog, :tag) ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      if (!$this->callExecution($statement)) $error["query_addTags"][$tagname] = true;
    }
    if (isset($error)) return $error;
    else return true;
  }

  private function cleanupOrphanedTags($tags) {
    foreach ($tags as $tagname => $tagId) {
      $statement = $this->Database->prepare("SELECT * FROM `blog_tags_relations` WHERE `tag` = :tag ;");
      $statement->bindParam(':tag', $tagId);
      $this->callExecution($statement);
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);
      if (count($result) < 1) {
        $statement = $this->Database->prepare("DELETE FROM `blog_tags` WHERE `tag` = :tag ;");
        $statement->bindParam(':tag', $tagname);
        if (!$this->callExecution($statement)) $error["cleanupOrphanedTags"] = true;
      }
    }
    if (isset($error)) return $error;
    else return true;
  }

  private function checkoutTags($blogId, $tags) {
//     echo '<div id="top_spacer"></div>';
    if(!is_array($tags)) $tags = array();

    // get the tags of this request
    foreach ($tags as $id => $tag) {
      if ($tag == "") {
        unset($post[$id]);
        continue;
      }
      $sentTags[$tag] = $this->getTagId($tag);
    }
    if (!is_array($sentTags)) $sentTags = array();

    // get all available tags from database
    $tmp = $this->selectAllTags();
    foreach ($tmp as $key => $value) {
      $temp = $value->getdata();
      $allTags[$temp["tag"]] = $temp["id"];
    }

    // get the tags of this post that are already in the database
    $readTags = $this->getTagsOfBlogpost($blogId);
    foreach ($readTags as $id => $value) {
      $tmp = $value->getdata();
      $oldTags[$tmp["tag"]] = $tmp["id"];
    }
    if(!is_array($oldTags)) $oldTags = array();

    // compare the arrays and invoke appropriate action
    // check for removed tags
    $removedTags = array_diff($oldTags, $sentTags);
    if (isset($removedTags) and count($removedTags) > 0) {
      if (!is_array($removedTags)) $removedTags = array();
      $result = $this->removeTags($blogId, $removedTags);
      if ($result !== true) $error["removeTags"] = $result;
      if ($this->cleanupOrphanedTags($removedTags) !== true) {
        $error["cleanupOrphanedTags"] = $error;
      }
    }

    // check for added tags
    $newTags = array_diff($sentTags, $oldTags);
    if (isset($newTags) and count($newTags) > 0) {
      $result = $this->addTags($blogId, $newTags);
      if ($result !== true) $error["addTags"] = $result;
    }

    // check for entirely new tags
    $newTags = array_diff($sentTags, $allTags);
    if (isset($newTags) and count($newTags) > 0) {
      $result = $this->insertTags($blogId, $newTags);
      if ($result !== true) $error["insertTags"] = $result;
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
    if (!$this->checkoutTags($post["id"], $post["tags"])) {
      $error["query_checkoutTags"] = true;
    }
    return $result;
  }

  public function insertBlog($post) {
    $statement = $this->Database->prepare("INSERT INTO `blog` (ctime, head, text) VALUES (:ctime, :head, :text);");
    $statement->bindParam(':ctime', time());
    $statement->bindParam(':head', $post["head"]);
    $statement->bindParam(':text', $post["text"]);
    $result = $this->callExecution($statement);
    if ($result === true) {
      $statement = $this->Database->prepare("SELECT LAST_INSERT_ID();");
      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);
      $tmp = $statement->fetch();
      $result = $tmp["LAST_INSERT_ID()"];
    }

    if (!$this->checkoutTags($result, $post["tags"])) {
      $error["query_checkoutTags"] = true;
    }
    return $result;
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
      $this->removeTags($id, $tags);
      $this->cleanupOrphanedTags($tags);
    }

    $statement = $this->Database->prepare("DELETE FROM `blog` WHERE `id` = :id ;");
    $statement->bindParam(':id', $id);
    $result = $this->callExecution($statement);
    return $result;
  }

  public function deleteComment($id) {
    $statement = $this->Database->prepare("DELETE FROM `blog_comments` WHERE `id` = :id ;");
    $statement->bindParam(':id', $id);
    $result = $this->callExecution($statement);
    return $result;
  }
}

?>
