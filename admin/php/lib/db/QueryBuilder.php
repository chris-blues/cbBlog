<?php

class AdminQueryBuilder extends QueryBuilder {

  protected $Database;

  public function __construct(PDO $Database) {
    $this->Database = $Database;
  }

  public function updateComment($id, $comment) {
    $statement = $this->Database->prepare("UPDATE `blog_comments` SET `comment` = :comment WHERE `id` = :id ;");
    $statement->bindParam(':comment', $comment);
    $statement->bindParam(':id', $id);
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $result;
  }

  public function updateBlog($post) {
    $statement = $this->Database->prepare("UPDATE `blog` SET `head` = :head, `text` = :text WHERE `blog`.`id` = :id;");
    $statement->bindParam(':head', $post["head"]);
    $statement->bindParam(':text', $post["text"]);
    $statement->bindParam(':id', $post["id"]);
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $result;
  }

  public function insertBlog($post) {
    $statement = $this->Database->prepare("INSERT INTO `blog` (ctime, head, text) VALUES (:ctime, :head, :text);");
    $statement->bindParam(':ctime', time());
    $statement->bindParam(':head', $post["head"]);
    $statement->bindParam(':text', $post["text"]);
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    if ($result === true) {
      $statement = $this->Database->prepare("SELECT LAST_INSERT_ID();");
      $statement->execute();
      $statement->setFetchMode(PDO::FETCH_ASSOC);
      $tmp = $statement->fetch();
      $result = $tmp["LAST_INSERT_ID()"];
    }
    return $result;
  }
}

?>
