<?php
$list = scan_dir(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Saves");
if ($list)
  foreach ($list as $key => $value)
    $series[] = new Series($config, $value);
else
  $series = [];
?>
