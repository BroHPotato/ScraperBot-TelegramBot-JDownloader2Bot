<?php
require_once "resorces.php";

$config = init(false);
$line = $_POST["Link"].$config["delimiter"].$_POST["Poster"].$config["delimiter"].$_POST["thetvdbId"].$config["delimiter"].$_POST["SaveFolder"]."\n";
if (modify_line($_GET["id"], $line, $config)) {
  header("location: index.php?e=000");
} else {
  header("location: index.php?e=030");
}
?>
