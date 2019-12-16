<?php
include_once 'Resources'.DIRECTORY_SEPARATOR.'resources.php';
  $log_enable = true;
  _log("Started manual run");
  shell_exec("./scraperbot.sh");
  header("location: ../index.php");
?>
