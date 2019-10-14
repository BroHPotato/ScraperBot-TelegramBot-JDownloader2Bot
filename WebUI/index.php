
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="title" content="Home - ScraperBot" />
    <meta name="language" content="english en" />
    <meta name="author" content="Giuseppe Vito Bitetti" />
    <link rel="stylesheet" type="text/css" href="Index.css" media="handheld, screen">
  </head>
  <?php
  require_once 'web_elaboration.php';
    $config = parse_ini_file("config.ini");
  ?>
  <body>
<?php

  $links = get_links($config);
  echo "<table>
          <thead>
            <tr>
              <th>Poster</th>
              <th>Titolo</th>
              <th>Imdb ID</th>
              <th>Download Link</th>
              <th>Save Path</th>
            </tr>
          </thead>
          <tbody>
            ";

  foreach ($links as $value) {
    echo "<tr>
            <td><img src=\"".$value["Poster"]."\" height=100 width=100></td>
            <td>".$value["Title"]."</td>
            <td>".$value["imdbId"]."</td>
            <td>".$value["Link"]."</td>
            <td>".$value["SaveFolder"]."</td>
          </tr>";
  }
  echo "</tbody></table>";
 ?>
  </body>
