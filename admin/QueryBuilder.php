<?php

class QueryBuilder {

  protected $Database;

  public function __construct($Database) {
    $this->Database = $Database;
  }

  public function selectOldBlogposts() {
    $statement = $this->Database->prepare("SELECT `id`,`tags` FROM `blog` ORDER BY `blog`.`id` ASC ;");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, "Blogpost0_13");
  }

  public function selectOldTags() {
    $statement = $this->Database->prepare("SELECT * FROM `blog_tags` ORDER BY `blog_tags`.`id` ASC ;");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, "Tags");
  }

  public function insertOldTags($queryString) {
    $statement = $this->Database->prepare($queryString);
    return $statement->execute();
  }

  public function dropTags() {
    $statement = $this->Database->prepare("ALTER TABLE `blog` DROP COLUMN `tags` ; ");
    return $statement->execute();
  }










  public function selectAll($table, $intoClass) {
    $statement = $this->Database->prepare("SELECT * FROM `{$table}` ORDER BY `{$table}`.`ctime` DESC ;");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, $intoClass);
  }

  public function selectById($table, $id, $intoClass) {
    $statement = $this->Database->prepare("SELECT * FROM `{$table}` WHERE `id`='{$id}' ;");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_CLASS, $intoClass);
    return $statement->fetch();
  }

  public function selectAllTags($table, $intoClass) {
    $statement = $this->Database->prepare("SELECT * FROM `{$table}` ;");
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
