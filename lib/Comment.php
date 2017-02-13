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
}

?>
