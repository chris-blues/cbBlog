<?php

return [
  "standalone" => true,
  "showProcessingTime" => true,
  "language" => "de_DE",
  "permalinkIgnore" => [
    "kartid",
    "lang",
    "accessibility",
    "filter",
    "index",
    "job",
    "operation",
  ],
  "options" => [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
  ]
];

?>

