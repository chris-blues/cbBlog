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
  $maxPosts = 10;

  $cbBlog_link = $config["blog"]["blog_call"];
  $tmp = explode("?", $config["blog"]["blog_call"]);
  $temp = explode("&", $tmp[1]);
  foreach ($temp as $key => $value) {
    $tmp = explode("=", $value);
    $getComponents[$tmp[0]] = $tmp[1];
  }
  foreach ($config["blog"]["permalinkIgnore"] as $key => $value) {
    $getComponents[$key] = "";
  }

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

    $cbBlog_linkComponents = $getComponents;
    $cbBlog_linkComponents["id"] = $row["id"];
    ?>

    <li>
      <a href="<?php echo assembleGetString("string", $cbBlog_linkComponents); ?>"><?php echo $head; ?></a>
      <?php

        if (count($row["num_comments"]) > 0) {
          $total_comments = convertnumbers($row["num_comments"]);
          ?>

          <p class="notes">

            <?php
              if ($row["num_comments"] > 0) { ?>
                (<a href="<?php echo assembleGetString("string", $cbBlog_linkComponents); ?>#linkshowcomments"><?php echo $total_comments . " ";
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
