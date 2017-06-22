<?php

class Blogpost {
  protected $id;
  protected $ctime;
  protected $mtime;
  protected $head;
  protected $text;

   public function getdata() {
     return array(
       "id"    => $this->id,
       "ctime" => $this->ctime,
       "mtime" => $this->mtime,
       "head"  => $this->head,
       "text"  => $this->text
       );
  }
}

?>
