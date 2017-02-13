<?php

function dump_var($var) {
  global $debug;
  if ($debug) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>\n";
  }
}

function dump_array($var) {
  global $debug;
  if ($debug) {
    echo "<pre>";
    print_r($var);
    echo "</pre>\n";
  }
}

function prettyTime($proctime) {
  $proctime = $proctime * 1000;
  return ($proctime);
}

function procTime($startTime, $endTime) {
  $proctime = $endTime - $startTime;
  $timeUnits = array("s", "ms", "µs");
  $t = 0;
  while ($proctime < 1) {
    $proctime = prettyTime($proctime);
    $t++;
  }
  return gettext("Processing needed") . " " . round($proctime, 3) . " " . $timeUnits[$t];
}

?>
