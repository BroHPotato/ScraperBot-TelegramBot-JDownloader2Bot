<?php
$loglist = scan_dir(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Logs");
if ($loglist) {
  foreach ($loglist as $value) {
    $logs[$value] = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Logs".DIRECTORY_SEPARATOR.$value);
  }
}
?>
