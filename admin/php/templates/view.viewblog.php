<?php

$comments = $query->selectComments($row["id"]);
$row["num_comments"] = count($comments);

require_once("../php/templates/view.blogpost.php");

require_once("php/templates/view.comments.php");

?>
