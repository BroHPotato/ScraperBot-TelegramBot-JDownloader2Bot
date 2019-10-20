<?php
require_once "resorces.php";
$config = init();
$line = $_POST["Link"].$config["delimiter"].$_POST["Poster"].$config["delimiter"].$_POST["thetvdbId"].$config["delimiter"].$_POST["SaveFolder"];
if (add_line($line, $config)) {
  header("location: index.php?e=000");
} else {
  header("location: index.php?e=020");
}
?>
