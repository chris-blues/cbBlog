<?php

class QueryBuilder {

  protected $Database;

  public function __construct($Database) {
    $this->Database = $Database;
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
