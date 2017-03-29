<?php

$comments = $query->selectComments($row["id"]);
$row["num_comments"] = count($comments);

require_once($path . "../php/templates/view.blogpost.php");

require_once($path . "php/templates/view.comments.php");

?>
