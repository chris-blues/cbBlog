<?php

// ====================[ translate POST data into shape to fit into Comment object ]====================

$comment["id"] = "";

if (strlen($_POST["email"]) > 0) {
  $error["prepareComment"]["SPAM"] = true;
}


// ====================[ check the data for errors etc ]====================

$tmp = trim($_POST["affiliation"]);
if (filter_var($tmp, FILTER_VALIDATE_INT, $options = array("min_range" => 0)) === false) {
  $error["prepareComment"]["affiliation"] = true;
} else { $comment["affiliation"] = $tmp; }



$tmp = trim($_POST["answerTo"]);
if (filter_var($tmp, FILTER_VALIDATE_INT, $options = array("min_range" => 0)) === false) {
  $error["prepareComment"]["answerTo"] = true;
} else { $comment["answerTo"] = $tmp; }



$time = time();
$tmp = trim($_POST["post_time"]);
if (filter_var($tmp, FILTER_VALIDATE_INT,
               $options = array("min_range" => $time - 3600,
                                "max_range" => $time + 3600)
  ) === false) {
  $error["prepareComment"]["time"] = true;
} else { $comment["time"] = $tmp; }



$tmp = trim($_POST["name"]);
if (strlen($tmp) < 1) { $comment["name"] = gettext("Anonymous"); }
else { $comment["name"] = strip_tags($tmp); }



$tmp = trim($_POST["notificationTo"]);
if (!filter_var($tmp, FILTER_VALIDATE_EMAIL) and strlen($tmp) < 0) {
  $sPattern = '/([\w\s\'\"]+[\s]+)?(<)?(([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/';
  preg_match($sPattern, $tmp, $aMatch);
  if (filter_var($aMatch[3], FILTER_VALIDATE_EMAIL)) {
    $comment["email"] = $aMatch[3];
  } else {
    $error["prepareComment"]["email"] = true;
  }
} else { $comment["email"] = $tmp; }

if (isset($comment["email"]) and $comment["email"] != "" and !isset($error["prepareComment"]["email"])) {
  // Lets check if this email is already verified, if not put hash into DB!
  $verified = $query->selectThisEmail($comment["affiliation"], $tmp);
  if (count($verified) == 0) {
    $comment["hash"] = hash('sha256', $_SERVER["SERVER_NAME"] . $comment["email"]);
    $firstpost = true;
  }
}



$tmp = trim($_POST["website"]);
if (!filter_var($tmp, FILTER_VALIDATE_URL) and strlen($tmp) < 0) {
  echo "<code>$tmp</code> does not validate as URL! Trying harder... ";
  if (strncmp($tmp, "https://", strlen("https://")) != 0 or strncmp($tmp, "http://", strlen("http://")) != 0) {
    $tmp = "http://" . $tmp;
    if (!filter_var($tmp, FILTER_VALIDATE_URL)) {
      echo "<code>$tmp</code> still does not validate! Giving up!<br>\n";
      $error["prepareComment"]["website"] = true;
    } else {
      echo "<code>$tmp</code> validates! [ OK ]<br>\n";
      $comment["website"] = trim($_POST["website"]);
    }
  }
}
else { $comment["website"] = $tmp; }



$tmp = trim($_POST["text"]);
if (strlen($tmp) < 1) { $error["prepareComment"]["comment"] = true; }
else { $comment["comment"] = $tmp; }


// ====================[ finally output the response! ]====================
if (isset($error) and count($error["prepareComment"]) > 0) {

  echo "Errors:\n";

  dump_array($errors);

} else {

  $newComment = new Comment();

  $newComment->addComment($comment);

  $result = $query->insertComment($newComment);

  if ($result === false) {
    echo "prepareComment.php DB query has failed!<br>\n";
    $error["query_addComment"] = true;
  }


// ====================[ send verification mail ]====================
if (isset($firstpost) and $firstpost) {
  $Verification = new Email("verification", $comment["name"], $comment["email"], $comment["name"], $config["email"]["email"], $comment["time"], $comment["hash"]);
  $Verification->send();
}

// ====================[ send email to subscribed commentors ]====================
  $subscriptions = $query->selectSubscribers($comment["affiliation"]);
  // add Admin as well!
  $newKey = count($subscriptions);
  $subscriptions[$newKey]["email"] = $config["email"]["admin"];
  $subscriptions[$newKey]["name"] = "Admin";

  foreach ($subscriptions as $key => $value) {
    $email = $value["email"];
    $name = $value["name"];
    // make sure, we only notify once!
    if (isset($sentmail[$email]) and $sentmail[$email] == true) { continue; }
    // Don't notify this poster as well!
    if ($email == $comment["email"]) { continue; }

    $Notification = new Email("notification", $name, $email, $comment["name"], $config["email"]["email"], $comment["time"], $comment["comment"]);

    if ($Notification->send() === true) { $sentmail[$email] = true; }
    unset($email, $name);
  }
}

?>
