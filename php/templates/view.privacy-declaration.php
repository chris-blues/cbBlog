  <div class="privacyDeclaration shadow notes">

    <h3><?php echo gettext("privacy declaration"); ?></h3>

    <p>
      <?php echo gettext("Your IP-address, useragent-string etc are not stored by this blog-software. Still, it is possible that the hoster of this website may store data like this. But that is beyond the scope of this blog-software. Check out this website's privacy declaration to find out more about that!"); ?>
    </p>

    <p>
      <?php
        echo gettext(
          "This blog-software generally doesn't store any information about you. Only if you post a comment, some data will have to be stored. You don't have to input any personal information here. Except for the comment itself, all fields are optional!<br>
          If you don't want to tells us your name, that's fine. It will be shown as 'anonymous'.<br>
          If you want to receive notifications on following comments, naturally you'll have to give us your email address. It will be stored and not be shared with anybody. If you don't want to be notified, just leave the notifications field empty.<br>
          If you want your name to be linked to your website, you'll have to give us your site's address. Otherwise leave this field empty."
        );
      ?>
    </p>

    <p>
      <?php echo gettext("This data will be stored in case you decide to post a comment here:"); ?>
    </p>

    <ul>
      <li><?php echo gettext("time and date of your post"); ?></li>
      <li><?php echo gettext("your name (if supplied)"); ?></li>
      <li><?php echo gettext("your email (if supplied)"); ?></li>
      <li><?php echo gettext("your website (if supplied)"); ?></li>
      <li><?php echo gettext("your comment"); ?></li>
      <li><?php echo gettext("some technical information unrelated to you, like which blogpost this comment belongs to and a unique id for this comment"); ?></li>
    </ul>

  </div>
