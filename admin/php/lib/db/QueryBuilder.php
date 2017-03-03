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
      // die($e->getMessage());
    }
    if (isset("error")) return $error;
    else return $result;
  }

  public function updateComment($id, $comment) {
    $statement = $this->Database->prepare("UPDATE `blog_comments` SET `comment` = :comment WHERE `id` = :id ;");
    $statement->bindParam(':comment', $comment);
    $statement->bindParam(':id', $id);
    if ($result !== true) $error["updateComment"] = true;

    if (isset("error")) return $error;}
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

  private function removeTags($blogId, $tags) {
    foreach ($tags as $tagname => $tagId) {
      $statement = $this->Database->prepare("DELETE FROM `blog_tags_relations` WHERE `blog` = :blog AND `tag` = :tag ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      $result = $this->callExecution($statement);
      if ($result !== true) $error["removeTags"][$tagname] = true;

      if (isset("error")) return $error;
      else return true;
    }
  }

  private function addTags($blogId, $tags) {
    foreach ($tags as $tagname => $tagId) {
      if ($tagId == NULL) continue;
      $statement = $this->Database->prepare("INSERT INTO `blog_tags_relations` (`blog`, `tag`) VALUES (:blog, :tag) ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      $result = $this->callExecution($statement);
      if ($result !== true) $error["addTags"][$tagname] = true;
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
      else $error["insert_into_blog_tags"][$tagname] = true;

      $statement = $this->Database->prepare("INSERT INTO `blog_tags_relations` (`blog`, `tag`) VALUES (:blog, :tag) ;");
      $statement->bindParam(':blog', $blogId);
      $statement->bindParam(':tag', $tagId);
      if (!$this->callExecution($statement)) $error["query_insert_blog_tags_relations"][$tagname] = true;
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
    // echo '<div id="top_spacer"></div>';

    if(!is_array($tags)) {
      $error["checkoutTags"]["tags_isNotArray"] = true;
      if (isset($tags) and $tags != "") {
        $error["checkoutTags"]["tags_isNotArray"] = $tags;
        $tmp = $tags;
        $tags = array($tmp);
      } else {
        $tags = array();
      }
    }

    // get the tags of this request
    foreach ($tags as $id => $tag) {
      if ($tag == "") {
        unset($tags[$id]);
        continue;
      }
      $sentTags[$tag] = $this->getTagId($tag);
    }
    if (!is_array($sentTags)) {
      $error["checkoutTags"]["sentTags_isNotArray"] = true;
      $sentTags = array();
    }

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
    if(!is_array($oldTags)) {
      $error["checkoutTags"]["oldTags_isNotArray"] = true;
      $oldTags = array();
    }

    // compare the arrays and invoke appropriate action
    // check for removed tags
    $removedTags = array_diff($oldTags, $sentTags);
    if (isset($removedTags) and count($removedTags) > 0) {
      if (!is_array($removedTags)) {
        $error["checkoutTags"]["removedTags_isNotArray"] = true;
        $removedTags = array();
      }
      $result = $this->removeTags($blogId, $removedTags);
      if ($result !== true) {
        $error["checkoutTags"]["removeTags"] = $result;
      }
      $result = $this->cleanupOrphanedTags($removedTags)
      if ($result !== true) {
        $error["checkoutTags"]["cleanupOrphanedTags"] = $error;
      }
    }

    // check for added tags
    $newTags = array_diff($sentTags, $oldTags);
    if (isset($newTags) and count($newTags) > 0) {
      $result = $this->addTags($blogId, $newTags);
      if ($result !== true) $error["checkoutTags"]["addTags"] = $result;
    }

    // check for entirely new tags
    $newTags = array_diff($sentTags, $allTags);
    if (isset($newTags) and count($newTags) > 0) {
      $result = $this->insertTags($blogId, $newTags);
      if ($result !== true) $error["checkoutTags"]["insertTags"] = $result;
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
    if ($result !== true) $error["updateBlog"]["checkoutTags"] = true;

    if (isset($error)) return $error;
    else return true;
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
    }
    else {
      return $error["insertBlog"]["query"] = true;
    }

    $result = $this->checkoutTags($result, $post["tags"]);
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
}

?>
