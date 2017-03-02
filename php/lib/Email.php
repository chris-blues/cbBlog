<?php

class Email {

  protected $header;
  protected $subject;
  protected $message;
  protected $comment;
  protected $name;
  protected $poster;
  protected $to;
  protected $from;
  protected $anchor;

  public function __construct($job, $name, $to, $poster, $from, $anchor, $comment) {

    $this->name = $name;
    $this->to = $to;
    $this->from = $from;
    $this->poster = $poster;
    $this->comment = $comment;
    $this->anchor = $anchor;
    $this->header  = $this->assembleHeader(date(DATE_RFC2822));
    $this->subject = $this->assembleSubject($job, $from);
    $this->message = wordwrap($this->assembleMessage($job), 70) . "\r\n";
  }

  public function assembleHeader($date) {
    $header  = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
    $header .= "Content-Transfer-Encoding: 8bit\r\n";
    $header .= "From: " . $this->from . "\r\n";
    $header .= "Date: $date\r\n";
    $header .= "\r\n";
    return $header;
  }

  public function assembleSubject($job, $from) {
    switch($job) {
      case "notification": $subject = $this->assembleNotificationSubject($from); break;
      case "verification": $subject = $this->assembleVerificationSubject($from); break;
    }
    return $subject;
  }
  public function assembleNotificationSubject($poster) {
    return gettext("New comment by") . " " . $this->poster;
  }
  public function assembleVerificationSubject($poster) {
    return str_replace("{server}", $_SERVER["HTTP_HOST"], gettext("Verify your subscription to {server}"));
  }

  public function templateNotification() {
    $template  = gettext("Hi") . " {name},<br><br>";
    $template .= gettext("you wanted to be notified via {email} of new comments on {server}. {poster} has just commented") . ":<br><br>";
    $template .= "<hr><br><br>{comment}<br><br><br><hr><br><br>";
    $template .= gettext("Click here to see this comment on {server}") . ":<br>{link_topic}<br><br>";
    $template .= gettext("To unsubscribe from this topic click here") . ":<br><br>{link_unsubscribe_topic}<br><br><br>";
    $template .= gettext("To entirely unsubscribe from {server} click here") . ":<br>{link_unsubscribe_site}<br><br><br>";
    $template .= gettext("Best regards") . ",<br>{server}<br><br>";
    return $template;
  }

  public function templateVerification() {
    $template  = gettext("Hi") . " {name},<br><br>";
    $template .= gettext("you wanted to be notified via {email} of new comments on {link_topic}.") . "<br><br>";
    $template .= gettext("To verify your wish, to receive notifications to this topic, please click here");
    $template .= ":<br><br>{verificationLink}<br><br><br><hr><br><br>";
    $template .= gettext("If you don't want to receive notifications, or you haven't subscribed yourself, click here");
    $template .= ":<br><br>{link_unsubscribe_topic}<br><br><br>";
    $template .= gettext("To entirely unsubscribe from {server} (never receive any notifications from {server} again) click here");
    $template .= ":<br>{link_unsubscribe_site}<br><br><br>";
    $template .= gettext("Best regards") . ",<br>{server}<br><br>";
    return $template;
  }

  public function assembleMessage($job) {
    global $config;
    switch ($job) {
      case "notification": $template = $this->templateNotification(); break;
      case "verification": $template = $this->templateVerification(); break;
    }
    $hash = hash('sha256', $_SERVER["SERVER_NAME"] . $this->to);
    $link_unsubscribe_topic = htmlspecialchars_decode(
      $_SERVER["HTTP_X_FORWARDED_PROTO"] . "://" .
      $_SERVER["HTTP_HOST"] .
      $_SERVER["PHP_SELF"] .
      "?" . assemblePermaLink($config["blog"]["permalinkIgnore"]) .
      "&email=" . $this->to .
      "&job=unsubscribe&scope=" . $_POST["affiliation"] .
      "&hash=$hash"
    );
    $link_unsubscribe_site = htmlspecialchars_decode(
      $_SERVER["HTTP_X_FORWARDED_PROTO"] . "://" .
      $_SERVER["HTTP_HOST"] .
      $_SERVER["PHP_SELF"] .
      "?" . assemblePermaLink($config["blog"]["permalinkIgnore"]) .
      "&email=" . $this->to .
      "&job=unsubscribe&scope=0&hash=$hash"
    );
    $search = array(
      "\n",
      "<br>",
      "<hr>",
      "{name}",
      "{email}",
      "{server}",
      "{poster}",
      "{link_topic}",
      "{comment}",
      "{verificationLink}",
      "{link_unsubscribe_topic}",
      "{link_unsubscribe_site}"
    );
    $replace = array(
      "",
      "\r\n",
      "---------------------------------------------------\r\n",
      $this->name,
      $this->to,
      $_SERVER["SERVER_NAME"],
      $this->poster,
      htmlspecialchars_decode(
        $_SERVER["HTTP_X_FORWARDED_PROTO"] . "://" .
        $_SERVER["HTTP_HOST"] .
        $_SERVER["PHP_SELF"] .
        "?" . assemblePermaLink($config["blog"]["permalinkIgnore"]) .
        "#" . $this->anchor
      ),
      $this->comment,
      htmlspecialchars_decode(
        $_SERVER["HTTP_X_FORWARDED_PROTO"] . "://" .
        $_SERVER["HTTP_HOST"] .
        $_SERVER["PHP_SELF"] .
        "?" . assemblePermaLink($config["blog"]["permalinkIgnore"]) .
        "&job=verify&scope={$_POST["affiliation"]}&email=" . $this->to . "&hash=" . $this->comment .
        "#" . $this->anchor
      ),
      $link_unsubscribe_topic,
      $link_unsubscribe_site
    );
    return str_replace($search, $replace, $template);
  }

  public function send() {
    if (!mail($this->to, $this->subject, $this->message, $this->header)) {
      $error["mail_admin"] = true;
      logErrors($error);
      $mailbody = $header . "To: " . $this->to . "\r\nSubject: " . $subject . "\r\n\r\n" . $this->message;
      logMailError($_POST["name"], $_POST["notificationTo"], $_POST["affiliation"], $address, $mailbody);
      return $error;
    } else {
      return true;
    }
  }
}

?>
