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

  public function __construct($name, $to, $poster, $from, $anchor, $comment) {

    $this->name = $name;
    $this->to = $to;
    $this->from = $from;
    $this->poster = $poster;
    $this->comment = $comment;
    $this->anchor = $anchor;

    $this->header  = $this->assembleHeader(date(DATE_RFC2822));
    $this->subject = $this->assembleSubject($from);

    $this->message = wordwrap($this->assembleMessage(), 70) . "\r\n";

    dump_array($this);
  }

  public function assembleHeader($date) {
    $header  = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
    $header .= "Content-Transfer-Encoding: 8bit\r\n";
    $header .= "From: " . $this->from . "\r\n";
    $header .= "Date: $date\r\n";
    $header .= "\r\n";
    return $header;
  }

  public function assembleSubject($poster){
    $subject = gettext("New comment by") . " " . $this->poster;
    return $subject;
  }

  public function assembleMessage() {
    $template = file_get_contents("templates/email_notification.html");
    $search = array("\n",
                    "<br>",
                    "<hr>",
                    "{name}",
                    "{email}",
                    "{server}",
                    "{poster}",
                    "{link_topic}",
                    "{comment}",
                    "{link_unsubscribe_topic}",
                    "{link_unsubscribe_site}");
    $replace = array("",
                     "\r\n",
                     "---------------------------------------------------\r\n",
                     $this->name,
                     $this->to,
                     $_SERVER["SERVER_NAME"],
                     $this->poster,
                     htmlspecialchars_decode($_ENV["HTTP_REFERER"] . "#" . $this->anchor),
                     $this->comment,
                     $link_unsubscribe_topic,
                     $link_unsubscribe_site);
    $message = str_replace($search, $replace, $template);
    return $message;
  }

  public function send() {
    if (!mail($this->to, $this->subject, $this->message, $this->header))
      {
       $error["mail_admin"] = true;
       logErrors($error);
       $mailbody = $header . "To: " . $this->to . "\r\nSubject: " . $subject . "\r\n\r\n" . $this->message;
       logMailError($_POST["name"], $_POST["notificationTo"], $_POST["affiliation"], $address, $mailbody);
      }
    return $error;
  }
}

?>
