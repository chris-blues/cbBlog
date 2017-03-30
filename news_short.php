<?php

$GLOBALS["displayMode"] = "short";
$GLOBALS["path"] = realpath(dirname(__FILE__));
$locale_path = $GLOBALS["path"];
require_once($GLOBALS["path"] . "/php/bootstrap.php");

if (isset($blogposts)) {
  if (count($blogposts) < 1) {
    echo gettext("We were not able to find anything. Either there's nothing posted yet, of there's a problem with the database connection.");
    exit;
  } ?>

  <ul class="blog">

<?php
  $maxPosts = 8;
  for ($i = 0; $i < $maxPosts; $i++) {
    $row = $blogposts[$i]->getdata();

    $row["tags"] = $query->getTagsOfBlogpost($row["id"]);
    foreach ($row["tags"] as $Tag) {
      $tmp = $Tag->getdata();
      $tempArray[$tmp["id"]] = $tmp["tag"];
      unset($tmp);
    }
    if (in_array("unreleased", $tempArray)) {
      unset($blogposts[$i], $tempArray);
      $maxPosts++;
      continue;
    }
    unset($tempArray);

    $comments = $query->selectComments($row["id"]);
    $row["num_comments"] = count($comments);

    $head = strip_tags($row["head"]);
    ?>

    <li>
      <a href="index.php<?php echo assembleGetString("string", array("id"=>$row["id"])); ?>"><?php echo $head; ?></a>
      <?php

        if (count($row["num_comments"]) > 0) {
          $total_comments = convertnumbers($row["num_comments"]);
          ?>

          <p class="notes">

            <?php
              if ($row["num_comments"] > 0) { ?>
                (<a href="index.php<?php echo assembleGetString("string", array("id"=>$row["id"])); ?>#linkshowcomments"><?php echo $total_comments . " ";
                if ($row["num_comments"] == 1) echo gettext("comment");
                else echo gettext("comments") . "</a>)";
              }
            ?>
          </p>

          <?php
        }

      ?>
    </li>
  <?php } ?>

  </ul>

<?php
  }
?>
