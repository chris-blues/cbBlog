<?php

class Tags {
  protected $id;
  protected $tag;

   public function getdata() {
     return array(
       "id"    => $this->id,
       "tag"  => $this->tag
       );
  }
}

?>
