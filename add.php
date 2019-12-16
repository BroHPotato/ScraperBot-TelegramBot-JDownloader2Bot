<?php
include_once 'Resources'.DIRECTORY_SEPARATOR.'resources.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'Series.php';

$config = init();
$log_enable = true;
$toadd = new Series($config);
$toadd->add($_POST["Title"], $_POST["thetvdbId"], $_POST["Poster"], $_POST["SaveFolder"],  $_POST["DownFolder"], $_POST["Link"]);
if (file_exists(__DIR__.DIRECTORY_SEPARATOR."Saves".DIRECTORY_SEPARATOR.$toadd->name.".json"))
  header("location: index.php?e=000");
else
  header("location: index.php?e=020");
?>
