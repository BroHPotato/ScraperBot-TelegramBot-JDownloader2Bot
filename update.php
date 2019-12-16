<?php
include_once 'Resources'.DIRECTORY_SEPARATOR.'resources.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'Series.php';

$config = init();
require_once 'Model/index.php';

$series[$_GET["id"]]->update_downloaded_episode();
$series[$_GET["id"]]->save();
header("location: index.php?e=000");

?>
