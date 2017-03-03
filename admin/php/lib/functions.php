<?php

function displayErrors($error, $branch = "error") {
  foreach ($error as $key => $value) {
    if (is_array($value)) {
      $branch .= "[" . $key . "]";
      displayErrors($value, $branch);
      $branch = str_replace("[" . $key . "]", "", $branch);
    }
    else {
      if (is_bool($value)) {
        if ($value == true) $value = "true";
        if ($value == false) $value ="false";
      }
      echo "<li>{$branch}[{$key}] =&gt; ({$value})</li>\n";
    }
  }
}

?>
