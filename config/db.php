<?php

return [
  "driver"  => "mysql",
  "host"    => "mysqlHost",
  "name"    => "databaseName",
  "user"    => "userName",
  "pass"    => "secretPassword",
  "options" => [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
  ]
];

?>
