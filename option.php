<?php
include_once 'Resources'.DIRECTORY_SEPARATOR.'resources.php';
$log_enable = true;
if (save_ini($_POST))
  header("location: index.php?e=000");
else
  header("location: index.php?e=040");
?>
