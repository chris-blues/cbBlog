<?php

$handle = fopen("../php/config/db.php", "w");

fwrite($handle, "<?php\n");
fwrite($handle, "\n");
fwrite($handle, "return [\n");

foreach ($_POST as $key => $value) {
  fwrite($handle, "  \"{$key}\" => \"{$value}\",\n");
}

fwrite($handle, "  \"options\" => [\n");
fwrite($handle, "    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING\n");
fwrite($handle, "  ]\n");
fwrite($handle, "];\n");
fwrite($handle, "\n");
fwrite($handle, "?>\n");
fwrite($handle, "\n");

fclose($handle);

?>
