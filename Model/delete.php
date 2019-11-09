<?php
if (delete_line($_GET["id"], $config) === true) {
  header("location: index.php?e=000");
} else {
  header("location: index.php?e=050");
}

?>
