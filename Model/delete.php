<?php
$log_enable = true;
if (unlink(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Saves".DIRECTORY_SEPARATOR.$series[$_GET["id"]]->name.".json") or _log("Can not delete ".__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Saves".DIRECTORY_SEPARATOR.$series[$_GET["id"]]->name.".json")) {
  _log("Deleted ".__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Saves".DIRECTORY_SEPARATOR.$series[$_GET["id"]]->name.".json");
  header("location: index.php?e=000");
} else {
  header("location: index.php?e=050");
}
