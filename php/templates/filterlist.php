<div id="filter" class="shadow">

  <div id="feeds" class="right">
    <a href="<?php echo $GLOBALS["relativePath"]; ?>/rss-feed.xml" class="feeds">
      <img src="pics/rss.png"
           alt="RSS Feed"
           title="RSS-Feed <?php echo gettext("by") . " " . $_SERVER["HTTP_HOST"]; ?>"
           height="32">
    </a>

    <a href="https://osbn.de" target="_blank" class="feeds">
    <img src="pics/osbn-button-orange.png"
         alt="Das Open-Source-Blog-Netzwerk"
         title="Open Source Blog Netzwerk - Eine Sammlung von deutschsprachigen Blogs mit dem Thema OpenSource"
         height="32">
    </a>
  </div>

  <h3>Filter:</h3>
  <ul id="tags">

<?php foreach ($filter as $id => $listitem) echo "    " . $listitem; ?>

  </ul>
</div>
