<!DOCTYPE html>
<?php date_default_timezone_set('Europe/Berlin'); ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="robots" content="noindex,nofollow">

    <link rel="stylesheet" href="../css/blog_fonts.css" type="text/css">
    <link rel="stylesheet" href="../css/blog.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <script type="text/javascript" src="../js/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="../js/styles/androidstudio.css">
    <script src="../js/highlight.pack.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
    <script type="text/javascript" src="js/init.js"></script>

    <title><?php echo $_SERVER["SERVER_NAME"]; ?> - admin-area</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="page-topic" content="<?php echo $_SERVER["SERVER_NAME"]; ?> - admin-area">
    <meta name="description" content="<?php echo $_SERVER["SERVER_NAME"]; ?> - admin-area">

    <?php if (isset($reload) and $reload == true) { ?>
    <meta http-equiv="refresh" content="0;<?php echo $target; ?>">
    <?php } ?>

  </head>
