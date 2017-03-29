<?php

if (!isset($_POST["standalone"])) $_POST["standalone"] = false;
else $_POST["standalone"] = true;

if (!isset($_POST["showProcessingTime"])) $_POST["showProcessingTime"] = false;
else $_POST["showProcessingTime"] = true;

if (!isset($_POST["show_debug"])) $_POST["show_debug"] = false;
else $_POST["show_debug"] = true;

if (!isset($_POST["log_debug"])) $_POST["log_debug"] = false;
else $_POST["log_debug"] = true;

$oldSettings = require($GLOBALS["path"] . "/../php/config/blog.php");
foreach ($oldSettings as $key => $value) {
  if (!isset($_POST[$key])) $error["saveBlog_{$key}"] = "$key could not be found in old config!";
}

if (!isset($error)) {
  $handle = fopen("../php/config/blog.php", "w");

  fwrite($handle, "<?php\n");
  fwrite($handle, "\n");
  fwrite($handle, "return [\n");

  foreach ($_POST as $key => $value) {
    if ($key == "permalinkIgnore") {
      fwrite($handle, "  \"$key\" => [\n");
      foreach ($_POST["$key"] as $value2) {
        if ($value2 == "") continue;
        fwrite($handle, "    \"{$value2}\",\n");
      }
      fwrite($handle, "  ],\n");
    }
    elseif ($key == "feeds") {
      fwrite($handle, "  \"$key\" => [\n");
      foreach ($value as $key2 => $value2) {
        fwrite($handle, "    \"$key2\" => [\n");
        foreach ($value2 as $key3 => $value3) {
          fwrite($handle, "      \"$key3\" => \"{$value3}\",\n");
        }
        fwrite($handle, "    ],\n");
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
