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

  public function __construct($comment){
    $this->id          = $comment["id"];
    $this->affiliation = $comment["affiliation"];
    $this->answerTo    = $comment["answerTo"];
    $this->time        = $comment["time"];
    $this->name        = $comment["name"];
    $this->email       = $comment["email"];
    $this->website     = $comment["website"];
    $this->comment     = $comment["comment"];
  }

  public function getdata() {
    return [
      "id"    => $this->id,
      "affiliation" => $this->affiliation,
      "answerTo" => $this->answerTo,
      "time"  => $this->time,
      "name" => $this->name,
      "email" => $this->email,
      "website" => $this->website,
      "comment"  => $this->comment
      ];
  }

  public function addComment() {
    dump_var($this);
  }
}

?>
