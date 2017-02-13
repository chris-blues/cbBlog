<?php

class Blogpost0_13 {
  protected $id;
  protected $ctime;
  protected $mtime;
  protected $tags;
  protected $head;
  protected $text;

   public function getdata() {
     return [
       "id"    => $this->id,
       "ctime" => $this->ctime,
       "mtime" => $this->mtime,
       "tags"  => $this->tags,
       "head"  => $this->head,
       "text"  => $this->text
       ];
  }
}

?>
