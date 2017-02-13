<?php

class QueryBuilder {

  protected $Database;

  public function __construct($Database) {
    $this->Database = $Database;
  }

  public function selectAllBlogposts($filter, $intoClass) {
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
    return $statement->fetchAll(PDO::FETCH_CLASS, $intoClass);
  }

  public function selectBlogpostsById($filter, $id, $intoClass) {
    $statement = $this->Database->prepare("SELECT * FROM `blog` WHERE `id`='{$id}' ;");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_CLASS, $intoClass);
    return $statement->fetch();
  }

//   SELECT blog.blog, blog.title FROM blog, tag, blog_tag WHERE tag.text = "foo" AND blog_tag.tag = tag.tag AND blog.blog = blog_tag.blog



  public function selectAllTags($intoClass) {
    $statement = $this->Database->prepare("SELECT * FROM `blog_tags` ;");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, $intoClass);
  }

  public function selectComments($affiliation, $intoClass) {
    $statement = $this->Database->prepare("SELECT * FROM `blog_comments` WHERE `affiliation`='{$affiliation}' ORDER BY `blog_comments`.`time` ASC ;");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, $intoClass);
  }
}

?>
