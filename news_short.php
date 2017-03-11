<?php

require_once("php/bootstrap.php");

if ($blogposts) { ?>

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
            (<a href="index.php<?php echo assembleGetString("string", array("id"=>$row["id"])); ?>#linkshowcomments">
            <?php
              if ($row["num_comments"] > 0) {
                echo $total_comments . " ";
                if ($row["num_comments"] == 1) echo gettext("comment");
                else echo gettext("comments");
              }
              else {
                echo gettext("no comments");
              }
            ?>
            </a>)
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

