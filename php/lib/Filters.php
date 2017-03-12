<?php

class Filters {

  public static function display($tags) {

    if (!isset($_GET["filter"]))
      $filter[0] = "<li><a class=\"notes italic tags green\">" . gettext("all") . "</a></li>\n";
    else
      $filter[0] = "<li><a href=\"{$_SERVER["PHP_SELF"]}" . assembleGetString("string", array("filter" => "")) . "\" class=\"notes tags\">" . gettext("all") . "</a></li>\n";

    foreach ($tags as $id => $Tag) {
      $tagname = $Tag->getdata();
      $taglist[$id] = $tagname["tag"];

      if (strcmp($tagname["tag"], "") == 0) continue;
      if (strcmp($tagname["tag"], "unreleased") == 0) continue;

      if (isset($_GET["filter"]) and strcmp($_GET["filter"],$tagname["tag"]) == 0) {
        $filter[$tagname["id"]] = "<li><a class=\"notes italic tags green\">" . $tagname["tag"] . "</a></li>\n";
      } else {
        $filter[$tagname["id"]] = "<li><a href=\"{$_SERVER["PHP_SELF"]}" . assembleGetString("string", array("filter" => $tagname["tag"])) . "\" class=\"notes tags\">" . $tagname["tag"] . "</a></li>\n";
      }
    }
  require("php/templates/filterlist.php");
  }

}
?>
