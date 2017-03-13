<?php

class Connection { // returns database object

  public static function make($config) {

    try {
      return new PDO(
        $config["driver"] . ":host=" . $config["host"] . ";dbname=" . $config["name"] . ";charset=UTF8MB4",
        $config["user"],
        $config["pass"],
        $config["options"]
      );
    } catch(PDOException $e) {
      return $e->getMessage();
//       die($e->getMessage());
    }
  }
}

?>
