
  <div>
    <h1><?php echo $post["id"]; ?></h1>
    Created: <code><?php echo date("d.m.Y H:i:s", $post["ctime"]); ?></code>
    Changed: <code><?php echo date("d.m.Y H:i:s", $post["mtime"]); ?></code>
    <h2><?php echo htmlspecialchars($post["head"]); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($post["text"])); ?></p>

  </div>
