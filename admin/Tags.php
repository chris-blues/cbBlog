<?php

class Tags {
  protected $id;
  protected $tag;

   public function getdata() {
     return [
       "id"    => $this->id,
       "tag"  => $this->tag
       ];
  }
}

?>
