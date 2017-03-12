<?php

$handle = fopen("../php/config/feeds.php", "w");

fwrite($handle, "<?php\n");
fwrite($handle, "\n");
fwrite($handle, "return [\n");

foreach ($_POST["feeds"] as $key => $value) {
  if ($value["name"] == "") continue;

  if ($key != "newFeed") fwrite($handle, "  \"{$key}\" => [\n");
  else                   fwrite($handle, "  \"{$value["name"]}\" => [\n");

  foreach ($value as $key2 => $value2) {
    fwrite($handle, "    \"{$key2}\" => \"{$value2}\",\n");
  }

  fwrite($handle, "  ],\n");
}

fwrite($handle, "];\n");
fwrite($handle, "\n");
fwrite($handle, "?>\n");
fwrite($handle, "\n");

fclose($handle);

?>
