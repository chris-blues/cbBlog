<?php

class Blogpost0_13 {
  protected $index;
  protected $time;
  protected $ctime;
  protected $tags;
  protected $head;
  protected $text;

  public function getdata() {
    return [
      "index" => $this->index,
      "time"  => $this->time,
      "ctime" => $this->ctime,
      "tags"  => $this->tags,
      "head"  => $this->head,
      "text"  => $this->text
      ];
  }

}

?>
