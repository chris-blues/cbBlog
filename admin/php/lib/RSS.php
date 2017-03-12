<?php

class RSS {

  protected $config;
  protected $posts;
  protected $header;
  protected $body;
  protected $footer;

  public function __construct($adminQuery, $config) {

    $this->config = $config;
    $this->posts = $adminQuery->selectAllBlogposts($this->config["tag"]);

    $this->header = $this->buildHeader();

    $result = $this->buildBody($adminQuery);
    if (is_array($result)) $error["RSS_buildBody"] = $result;
    else $this->body = $result;

    $this->footer = $this->buildFooter();

    if (isset($error)) return $error;
    else return $this;
  }

  private function buildHeader() {

    $header  = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
    $header .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
    $header .= "  <channel>\n\n";
    $header .= "    <title>" . $this->config["title"] . "</title>\n";
    $header .= "    <description>" . $this->config["description"] . "</description>\n";
    $header .= "    <link>" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $this->config["blogCall"] . "</link>\n\n";
    $header .= "    <language>" . $this->config["language"] . "</language>\n";
    $header .= "    <copyright>" . $this->config["author"] . "</copyright>\n";
    $header .= "    <generator>cbBlog " . $GLOBALS["version"] . "</generator>\n";
//     $header .= "    <pubDate>" . date(DATE_RFC822) . "</pubDate>\n";
    $header .= "    <lastBuildDate>" . date(DATE_RFC822) . "</lastBuildDate>\n\n";
    $header .= "    <atom:link href=\"" . $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/rss-feed.xml\" rel=\"self\" type=\"application/rss+xml\" />\n\n";

    return $header;

  }

  private function buildBody($adminQuery) {

    $count = count($this->posts);

    if ($count > 0) {

      $counter = 1;
      $maxPosts = 15;

      foreach ($this->posts as $key => $Post) {

        if ($counter >= $maxPosts) {
          unset($this->posts[$key]);
          continue;
        }

        $row = $Post->getdata();

        $row["tags"] = $adminQuery->getTagsOfBlogpost($row["id"]);

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

        $tmp = strip_tags($row["head"]);
        $head = str_replace("&", "&amp;", $tmp);

        $timehr = date("D, d M Y H:i:s O", $row["mtime"]);
        $link = $_ENV["HTTP_X_FORWARDED_PROTO"] . "://" . $_ENV["SERVER_NAME"] . "/" .  $this->config["blogCall"] . "&amp;id=" . $row["id"];

        $body .= "    <item>\n";
        $body .= "      <title>" . $head . "</title>\n";
        $body .= "      <link>" . $link . "</link>\n";
        $body .= "      <guid>" . $link . "</guid>\n";
        $body .= "      <author>" . $this->config["author"] . "</author>\n";
        $body .= "      <pubDate>" . $timehr . "</pubDate>\n";
        $body .= "      <description><![CDATA[\n" . $row["text"] . "\n]]></description>\n";
        $body .= "      <comments>" . $link . "#linkshowcomments</comments>\n";
        $body .= "    </item>\n\n";
      }
    }
    else $error["RSS_noPostsRetrieved"] = true;

    if (isset($error)) return $error;
    else return $body;

  }

  private function buildFooter() {

    $footer  = "  </channel>\n\n";
    $footer .= "</rss>\n";

    return $footer;

  }

  public function writeRSS($filename) {

    if (!isset($filename) or $filename == "") {
      $error["RSS_filenameMissing"] = true;
      return $error;
    }

    $content = $this->header;
    $content .= $this->body;
    $content .= $this->footer;

    $result = file_put_contents("../" . $filename, $content);
    if (!$result) $error["RSS_write_$filename"] = true;

    if (isset($error)) return $error;
    else return true;
  }

}

?>
