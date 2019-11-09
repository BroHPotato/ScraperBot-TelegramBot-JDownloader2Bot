<?php
require_once "resorces.php";
$config = init(false);
if (save_ini($_POST)) {
  $newconfig = init();
  $links = get_links($config);
  unlink($newconfig["linksFile"]);
  _log("Rebuilding the linksFile", $newconfig);
  foreach ($links as $key => $value) {
    $line = $value["Link"].$newconfig["delimiter"].$value["Poster"].$newconfig["delimiter"].$value["thetvdbId"].$newconfig["delimiter"].$value["SaveFolder"];
    add_line($line, $newconfig);
  }
  header("location: index.php?e=000");
} else {
  header("location: index.php?e=040");
}
?>
