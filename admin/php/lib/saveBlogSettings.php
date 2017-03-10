<?php

if (!isset($_POST["standalone"])) $_POST["standalone"] = false;
else $_POST["standalone"] = true;

if (!isset($_POST["showProcessingTime"])) $_POST["showProcessingTime"] = false;
else $_POST["showProcessingTime"] = true;

$oldSettings = require("../php/config/blog.php");
foreach ($oldSettings as $key => $value) {
  if (!isset($_POST[$key])) $error["saveBlog_{$key}"] = true;
}

if (!isset($error)) {
  $handle = fopen("../php/config/blog.php", "w");

  fwrite($handle, "<?php\n");
  fwrite($handle, "\n");
  fwrite($handle, "return [\n");

  foreach ($_POST as $key => $value) {
    if ($key == "permalinkIgnore") {
      fwrite($handle, "  \"permalinkIgnore\" => [\n");
      foreach ($_POST["permalinkIgnore"] as $value2) {
        if ($value2 == "") continue;
        fwrite($handle, "    \"{$value2}\",\n");
      }
      fwrite($handle, "  ],\n");
    }
    else {
      if (is_bool($value)) {
        $bool = $value ? "true" : "false";
        fwrite($handle, "  \"{$key}\" => {$bool},\n");
      }
      else fwrite($handle, "  \"{$key}\" => \"{$value}\",\n");
    }
  }
}

fwrite($handle, "];\n");
fwrite($handle, "\n");
fwrite($handle, "?>\n");
fwrite($handle, "\n");

fclose($handle);

?>
