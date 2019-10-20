<?php
require_once "resorces.php";
$config = init();
if (save_ini($_POST, $config)) {
  $newconfig = init();
  $links = get_links($config);
  unlink($newconfig["linksFile"]);
  foreach ($links as $key => $value) {
    $line = $value["Link"].$newconfig["delimiter"].$value["Poster"].$newconfig["delimiter"].$value["thetvdbId"].$newconfig["delimiter"].$value["SaveFolder"];
    add_line($line, $newconfig);
  }
  header("location: index.php?e=000");
} else {
  header("location: index.php?e=040");
}
?>
