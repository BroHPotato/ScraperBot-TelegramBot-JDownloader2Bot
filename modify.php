<?php
include_once 'Resources'.DIRECTORY_SEPARATOR.'resources.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'Series.php';

$config = init();
$log_enable = true;
$tomod = new Series($config, $_POST["Title"].".json");
$tomod->update($_POST["Title"], $_POST["thetvdbId"], $_POST["Poster"], $_POST["SaveFolder"], $_POST["DownFolder"], $_POST["Link"]);
$tomod->save();
if (date('d M Y H:i', filemtime(__DIR__.DIRECTORY_SEPARATOR."Saves".DIRECTORY_SEPARATOR.$tomod->name.".json")) == date('d M Y H:i'))
  header("location: index.php?e=000");
else
  header("location: index.php?e=030");
?>
