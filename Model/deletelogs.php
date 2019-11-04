<?php
   if(unlink($config["logPath"].$_GET['id'])){
     header("location: index.php?e=000");
   } else {
     header("location: index.php?e=050");
   }
?>
