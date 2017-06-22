<?php

class Comment {
  protected $id;
  protected $affiliation;
  protected $answerTo;
  protected $time;
  protected $name;
  protected $email;
  protected $website;
  protected $comment;

  public function getdata() {
    return array(
      "id"    => $this->id,
      "affiliation" => $this->affiliation,
      "answerTo" => $this->answerTo,
      "time"  => $this->time,
      "name" => $this->name,
      "email" => $this->email,
      "website" => $this->website,
      "comment"  => $this->comment
      );
  }

  public function addComment($comment) {
    $this->id          = "";
    $this->affiliation = $comment["affiliation"];
    $this->answerTo    = $comment["answerTo"];
    $this->time        = $comment["time"];
    $this->name        = $comment["name"];
    $this->website     = $comment["website"];
    $this->comment     = $comment["comment"];

    if (!isset($comment["hash"])) {
      $this->email     = $comment["email"];
    } else {
      $this->email     = $comment["hash"];
    }
  }
}

?>
