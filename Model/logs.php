<?php
$loglist = scandir($config["logPath"], SCANDIR_SORT_DESCENDING) or die("Unable to open the file");
if ($loglist) {
  $loglist = array_diff($loglist, array('..', '.'));
  foreach ($loglist as $value) {
    $file = fopen($config["logPath"].$value, "r");
    $logs[$value] = fread($file, filesize($config["logPath"].$value));
  }
}
?>
