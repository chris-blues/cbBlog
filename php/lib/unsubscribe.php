<div class="notice shadow">
<?php

$affiliation = $_GET["id"];
$email = $_GET["email"];
$hash = $_GET["hash"];

$verified = array(
  "doublecheck" => false,
  "affiliation" => false,
  "email" => false,
  "hash" => false
);

if ($_GET["id"] == $_GET["scope"] or $_GET["scope"] == 0) {
  $verified["doublecheck"] = true;
} else $error["doublecheck"] = "URL seems to be broken";

$verify = $query->selectThisEmail($affiliation, $email);
if (count($verify) > 0) {
  $verified["affiliation"] = true;
} else $error["affiliation"] = "not in database with this email and id";

if ($actualemail = filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $verified["email"] = true;
} else $error["emailAddress"] = "email address invalid";

$actualhash = hash('sha256', $_SERVER["SERVER_NAME"] . $email);
if (strcmp($hash, $actualhash) === 0) {
  $verified["hash"] = true;
} else $error["hash"] = "hash is not what it should be";

$valid = true;
foreach ($verified as $key => $value) {
  if ($value === false) $valid = false;
}

if ($valid) {
  switch($_GET["scope"]) {
    case "0": {
      if ($query->deleteAllSubscriptions($email)) {
        echo gettext("You'll receive no further notifications for new comments on this entire blog.") . "<br>\n";
      }
    }
    default: {
      if ($query->deleteThisSubscription($affiliation, $email)) {
        echo gettext("You'll receive no further notifications for new comments on this topic.") . "<br>\n";
      }
    }
  }
} else {
  echo gettext("Your request could not be handled.") . "<br>\n";
  showErrors($error);
}

unset($_GET["email"], $_GET["hash"], $_GET["scope"]);

?>
</div>
