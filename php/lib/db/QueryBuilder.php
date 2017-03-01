<?php

class QueryBuilder {

  protected $Database;

  public function __construct(PDO $Database) {
    $this->Database = $Database;
  }

  public function selectAllBlogposts($filter) {
    if (!isset($filter) or $filter == "") {
      $statement = $this->Database->prepare(
        "SELECT * FROM `blog` ORDER BY `blog`.`ctime` DESC ;"
      );
    } else {
      $statement = $this->Database->prepare(
        "SELECT blog.* FROM blog, blog_tags, blog_tags_relations
         WHERE blog_tags.tag = :filter
           AND blog.id = blog_tags_relations.blog
           AND blog_tags_relations.tag = blog_tags.id
         ORDER BY blog.ctime DESC ; "
      );
      $statement->bindParam(':filter', $filter);
    }
    try {
      $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $statement->fetchAll(PDO::FETCH_CLASS, "Blogpost");
  }

  public function selectBlogpostsById($id) {
    $statement = $this->Database->prepare("SELECT * FROM `blog` WHERE `id`=:id ;");
    $statement->bindParam(':id', $id);
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_CLASS, "Blogpost");
    try {
      $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $statement->fetch();
  }

  public function getTagsOfBlogpost($id) {
    $statement = $this->Database->prepare(
      "SELECT blog_tags.* FROM blog_tags, blog_tags_relations
        WHERE blog_tags_relations.blog = :id
          AND blog_tags.id = blog_tags_relations.tag
     ORDER BY blog_tags_relations.tag ASC ; "
    );
    $statement->bindParam(':id', $id);
    try {
      $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $statement->fetchAll(PDO::FETCH_CLASS, "Tags");
  }


  public function selectAllTags() {
    $statement = $this->Database->prepare("SELECT * FROM `blog_tags` ORDER BY `tag` ;");
    try {
      $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $statement->fetchAll(PDO::FETCH_CLASS, "Tags");
  }

  public function selectComments($affiliation) {
    $statement = $this->Database->prepare("SELECT * FROM `blog_comments` WHERE `affiliation`=:affiliation ORDER BY `blog_comments`.`time` ASC ;");
    $statement->bindParam(':affiliation', $affiliation);
    try {
      $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $statement->fetchAll(PDO::FETCH_CLASS, "Comment");
  }

  public function insertComment($Comment) {
    $comment = $Comment->getdata();

    $statement = $this->Database->prepare("INSERT INTO `blog_comments` (affiliation, answerTo, time, name, email, website, comment)
       VALUES (:affiliation, :answerTo, :time, :name, :email, :website, :comment);"
      );
    $statement->bindParam(':affiliation', $comment["affiliation"]);
    $statement->bindParam(':answerTo', $comment["answerTo"]);
    $statement->bindParam(':time', $comment["time"]);
    $statement->bindParam(':name', $comment["name"]);
    $statement->bindParam(':email', $comment["email"]);
    $statement->bindParam(':website', $comment["website"]);
    $statement->bindParam(':comment', $comment["comment"]);
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $result;
  }

  public function selectSubscribers($affiliation) {
    $statement = $this->Database->prepare("SELECT name, email FROM `blog_comments` WHERE `affiliation`=:affiliation AND `email` > \"\" ;");
    $statement->bindParam(':affiliation', $affiliation);
    try {
      $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectThisEmail($affiliation, $email) {
    $statement = $this->Database->prepare("SELECT email FROM `blog_comments` WHERE `affiliation`=:affiliation AND `email`=:email ;");
    $statement->bindParam(':affiliation', $affiliation);
    $statement->bindParam(':email', $email);
    try {
      $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $statement->fetchAll(PDO::FETCH_COLUMN);
  }

  public function selectThisHash($affiliation, $hash) {
    $statement = $this->Database->prepare("SELECT affiliation, email FROM `blog_comments` WHERE `affiliation`=:affiliation AND `email`=:hash ;");
    $statement->bindParam(':affiliation', $affiliation);
    $statement->bindParam(':hash', $hash);
    try {
      $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function verifyEmail($affiliation, $email, $hash) {
    $statement = $this->Database->prepare("UPDATE `blog_comments` SET `email`=:email WHERE (`email` = :hash AND `affiliation` = :affiliation);");
    $statement->bindParam(':affiliation', $affiliation);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':hash', $hash);
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $result;
  }

  public function deleteThisSubscription($affiliation, $email) {
    $statement = $this->Database->prepare("UPDATE `blog_comments` SET `email`='' WHERE (`email` = :email AND `affiliation` = :affiliation);");
    $statement->bindParam(':affiliation', $affiliation);
    $statement->bindParam(':email', $email);
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $result;
  }

  public function deleteAllSubscriptions($email) {
    $statement = $this->Database->prepare("UPDATE `blog_comments` SET `email`='' WHERE (`email` = :email);");
    $statement->bindParam(':email', $email);
    try {
      $result = $statement->execute();
    } catch(PDOException $e) {
      die($e->getMessage());
    }
    return $result;
  }
}

?>
