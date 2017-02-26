<?php

// ====================[ translate GET data ]====================
$affiliation = $_GET["id"];
$email = $_GET["email"];
$hash = $_GET["hash"];

$verified = array(
  "doublecheck" => false,
  "affiliation" => false,
  "email" => false,
  "hash" => false
);

$verify = $query->selectThisHash($affiliation, $hash);

if ($_GET["id"] == $_GET["scope"]) {
  $verified["doublecheck"] = true;
}

if (count($verify) > 0) {
  $verified["affiliation"] = true;
}

if ($actualemail = filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $verified["email"] = true;
}

$actualhash = hash('sha256', $_SERVER["SERVER_NAME"] . $email);
if (strcmp($hash, $actualhash) == 0) {
  $verified["hash"] = true;
}

$valid = true;
foreach ($verified as $key => $value) {
  if ($value === false) $valid = false;
}

if ($valid === true) {
  if ($query->verifyEmail($affiliation, $email, $hash)) {
    $verificationMessage = gettext("You'll now receive notifications for new comments.");
    $verified = true;
  }
}

unset($_GET["email"], $_GET["hash"], $_GET["scope"]);

?>
