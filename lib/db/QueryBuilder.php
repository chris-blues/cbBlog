<?php

class QueryBuilder {

  protected $DB;

  public function __construct($DB) {
    $this->DB = $DB;
  }

  public function selectAllBlogposts($table, $intoClass) {
    //$statement = $this->DB->prepare($query);
    $statement = $this->DB->prepare("SELECT * FROM `{$table}` ;");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, $intoClass);
  }

  public function selectBlogpost($table, $id, $intoClass) {
    $statement = $this->DB->prepare("SELECT * FROM `{$table}` WHERE `id`='{$id}' ;");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_CLASS, $intoClass);
    return $statement->fetch();
  }
}

?>
