<?php

class Filters {

  public static function display($tags) {

    if (!isset($_GET["filter"]))
      $filter[0] = "<li><a class=\"notes italic tags green\">" . gettext("all") . "</a></li>\n";
    else
      $filter[0] = "<li><a href=\"{$_SERVER["PHP_SELF"]}{$link}\" class=\"notes tags\">" . gettext("all") . "</a></li>\n";

    foreach ($tags as $id => $Tag) {
      $tagname = $Tag->getdata();
      $taglist[$key] = $tagname["tag"];

      if (strcmp($tagname["tag"],"") == 0) continue 1;

      if (strcmp($_GET["filter"],$tagname["tag"]) == 0) {
        $filter[$tagname["id"]] = "<li><a class=\"notes italic tags green\">" . $tagname["tag"] . "</a></li>\n";
      } else {
        $filter[$tagname["id"]] = "<li><a href=\"{$_SERVER["PHP_SELF"]}" . assembleGetString("string", array("filter" => $tagname["tag"])) . "\" class=\"notes tags\">" . $tagname["tag"] . "</a></li>\n";
      }
    }
  require("php/templates/filterlist.php");
  }

}
?>
