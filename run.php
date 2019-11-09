<?php
  require_once "resorces.php";
  $config = init(false);
  _log("Started manual run", $config);
  shell_exec("./scraperbot.sh");
  header("location: ../index.php");
?>
