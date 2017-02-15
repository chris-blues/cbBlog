<?php

// ====================[ translate POST data into shape to fit into Comment object ]====================

$comment["id"] = "";


// ====================[ check the data for errors etc ]====================

$tmp = trim($_POST["affiliation"]);
if (filter_var($tmp, FILTER_VALIDATE_INT, $options = array("min_range" => 0)) === false) {
  $errors["affiliation"] = true;
} else { $comment["affiliation"] = $tmp; }



$tmp = trim($_POST["answerTo"]);
if (filter_var($tmp, FILTER_VALIDATE_INT, $options = array("min_range" => 0)) === false) {
  $errors["answerTo"] = true;
} else { $comment["answerTo"] = $tmp; }



$time = time();
$tmp = trim($_POST["post_time"]);
if (filter_var($tmp, FILTER_VALIDATE_INT,
               $options = array("min_range" => $time - 3600,
                                "max_range" => $time + 3600)
  ) === false) {
  $errors["time"] = true;
} else { $comment["time"] = $tmp; }



$tmp = trim($_POST["name"]);
if (strlen($tmp) < 1) { $comment["name"] = gettext("Anonymous"); }
else { $comment["name"] = strip_tags($tmp); }



$tmp = trim($_POST["notificationTo"]);
if (!filter_var($tmp, FILTER_VALIDATE_EMAIL)) {
  $sPattern = '/([\w\s\'\"]+[\s]+)?(<)?(([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/';
  preg_match($sPattern, $tmp, $aMatch);
  if (filter_var($aMatch[3], FILTER_VALIDATE_EMAIL)) {
    $comment["email"] = $aMatch[3];
  } else {
    $errors["email"] = true;
  }
} else { $comment["email"] = $tmp; }



$tmp = trim($_POST["website"]);
if (!filter_var($tmp, FILTER_VALIDATE_URL)) { $errors["website"] = true; }
else { $comment["website"] = $tmp; }



$tmp = trim($_POST["text"]);
if (strlen($tmp) < 1) { $errors["comment"] = true; }
else { $comment["comment"] = $tmp; }


// ====================[ finally submit this comment! ]====================
if (count($errors) > 0) {
  echo "Errors:\n";
  dump_array($errors);
} else {
  $newComment = new Comment($comment);

  $newComment->addComment();
}

?>
