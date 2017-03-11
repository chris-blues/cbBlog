<?php

class RSS {

  protected $config["blog"];
  protected $topic;
  protected $posts;

  public function __construct($config) {

    $this->config = $config;
    $this->posts = $adminQuery->selectAllBlogposts($this->config["tag"]);

    $this->buildHeader();
    $this->buildBody();
    $this->buildFooter();

  }

  protected function buildHeader() {

    $header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
    $header .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
    $header .= "<channel>\n\n";
    $header .= "<title>" . $config["title"] . "</title>\n";
    $header .= "<description>" . $config["description"] . "</description>\n";
    $header .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blogCall"] . "</link>\n\n";
    $header .= "<language>" . $config["language"] . "</language>\n";
    $header .= "<copyright>" . $config["author"] . "</copyright>\n";
    $header .= "<generator>cbBlog " . $GLOBALS["version"] . "</generator>\n";
//     $header .= "<pubDate>" . date(DATE_RFC822) . "</pubDate>\n";
    $header .= "<lastBuildDate>" . date(DATE_RFC822) . "</lastBuildDate>\n\n";
    $header .= "<atom:link href=\"" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/rss-feed.xml\" rel=\"self\" type=\"application/rss+xml\" />\n\n";
//     $header .= "<image>\n";
//     $header .= "<url>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/pics/cover_summertime_front_300x300.jpg</url>\n";
//     $header .= "<title>" . $config["title"] . "</title>\n";
//     $header .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blogCall"] . "</link>\n";
//     $header .= "</image>\n\n";

    $this->header = $header;

  }

  protected function buildBody() {

    if ($this->posts) {
      $maxPosts = 15;
      foreach ($this->posts as $key => $value) {
        if ($counter == $maxPosts) continue;

        $row = $this->posts[$key]->getdata();
        if (isset($error)) showErrors($error);
        $row["tags"] = $query->getTagsOfBlogpost($row["id"]);
        if (isset($error)) showErrors($error);
        foreach ($row["tags"] as $Tag) {
          $tmp = $Tag->getdata();
          $tempArray[$tmp["id"]] = $tmp["tag"];
          unset($tmp);
        }
        if (in_array("unreleased", $tempArray)) {
          unset($tempArray);
          $maxPosts++;
          continue;
        }
        unset($tempArray);

        $counter++;
        $head = strip_tags($row["head"]);
        $head = str_replace("&", "&amp;", $head);
        $timehr = date("D, d M Y H:i:s O", $row["mtime"]);
        $body .= "<item>\n";
        $body .= "<title>" . $head . "</title>\n";
        $body .= "<link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blogCall"] . "&amp;id={$row["id"]}</link>\n";
        $body .= "<guid>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blogCall"] . "&amp;id={$row["id"]}</guid>\n";
        $body .= "<author>" . $config["author"] . "</author>\n";
        $body .= "<pubDate>$timehr</pubDate>\n";
        $body .= "<description><![CDATA[\n" . $row["text"] . "\n]]></description>\n";
        $body .= "<comments>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $config["blogCall"] . "&amp;id={$row["id"]}#linkshowcomments</comments>\n";
        $body .= "</item>\n\n";
      }
    }

    $this->body = $body;

  }

  protected function buildFooter() {

    $footer .= "</channel>\n\n";
    $footer .= "</rss>\n";

    $this->footer = $footer;

  }

  protected function writeRSS($filename) {

    if (!isset($filename) or $filename == "") {
      $error["RSS_filenameMissing"] = true;
      continue;

    $content = $this->header;
    $content .= $this->body;
    $content .= $this->footer;

    file_put_contents("../" . $filename, $content);
    }

  }

}


?>
