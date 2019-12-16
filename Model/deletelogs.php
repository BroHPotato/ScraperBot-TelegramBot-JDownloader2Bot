<?php
$log_enable = true;
   if(unlink(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Logs".DIRECTORY_SEPARATOR.$_GET['id']) or _log("Can not delete Logs".DIRECTORY_SEPARATOR.$_GET['id'])){
     _log("Deleted Logs".DIRECTORY_SEPARATOR.$_GET['id']);
     header("location: index.php?e=000");
   } else {
     header("location: index.php?e=050");
   }
?>
