<?php

class QueryBuilder {

  protected $Database;

  public function __construct($Database) {
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
         WHERE blog_tags.tag = '{$filter}'
           AND blog.id = blog_tags_relations.blog
           AND blog_tags_relations.tag = blog_tags.id
         ORDER BY blog.ctime DESC ; "
      );
    }
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, "Blogpost");
  }

  public function selectBlogpostsById($id) {
    $statement = $this->Database->prepare("SELECT * FROM `blog` WHERE `id`='{$id}' ;");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_CLASS, "Blogpost");
    return $statement->fetch();
  }

  public function getTagsOfBlogpost($id) {
    $statement = $this->Database->prepare(
      "SELECT blog_tags.* FROM blog, blog_tags, blog_tags_relations
        WHERE blog_tags_relations.blog = '{$id}'
          AND blog.id = '{$id}'
          AND blog_tags.id = blog_tags_relations.tag
     ORDER BY blog_tags_relations.tag ASC ; "
    );
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, "Tags");
  }


  public function selectAllTags() {
    $statement = $this->Database->prepare("SELECT * FROM `blog_tags` ;");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, "Tags");
  }

  public function selectComments($affiliation) {
    $statement = $this->Database->prepare("SELECT * FROM `blog_comments` WHERE `affiliation`='{$affiliation}' ORDER BY `blog_comments`.`time` ASC ;");
    $statement->execute();
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

//     $statement = $this->Database->prepare("INSERT INTO `blog_comments` (`affiliation`, `answerTo`, `time`, `name`, `email`, `website`, `comment` )
//        VALUES ( '" . $comment["affiliation"] . "',
//                 '" . $comment["answerTo"] . "',
//                 '" . $comment["time"] . "',
//                 '" . $comment["name"] . "',
//                 '" . $comment["email"] . "',
//                 '" . $comment["website"] . "',
//                 '" . $comment["comment"] . "'
//               );"
//       );
    return $statement->execute();
  }
}

?>
