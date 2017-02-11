<?php

class QueryBuilder {

  protected $Database;

  public function __construct($Database) {
    $this->Database = $Database;
  }

  public function selectAll($table, $intoClass) {
    $statement = $this->Database->prepare("SELECT * FROM `{$table}` ;");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, $intoClass);
  }

  public function selectById($table, $id, $intoClass) {
    $statement = $this->Database->prepare("SELECT * FROM `{$table}` WHERE `id`='{$id}' ;");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_CLASS, $intoClass);
    return $statement->fetch();
  }
}

?>
