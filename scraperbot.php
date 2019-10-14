<?php
include "simple_html_dom.php";
include "utility.php";
include "web_elaboration.php";
include "file_elaboration.php";

error_reporting(E_ALL);
echo "START\n";
echo "------------------------------------\n";
echo "CONFIGURATION\n";
$config = init();
echo "------------------------------------\n";
echo "START SEARCHING";
$links = get_links($config);

foreach ($links as $searchlink) {
  echo "ELABORAZIONE ".$searchlink["Title"]."\n";
  $response = get_page($searchlink["Link"]);
  $html = new simple_html_dom();
  $html->load($response);
  $searchlink["Episode"] = filter_download($html, $config);

  if ($searchlink["Episode"]) {
    $searchlink["MissingEpisode"] = check_exist_files($searchlink, $config);
    if ($searchlink["MissingEpisode"] && $config["jdownloader"]) {
      create_new_download($searchlink, $config);
    }
    if ($searchlink["MissingEpisode"] && $config["telegram"]) {
      $tvdb_token = get_token($config);
      $searchlink["Details"] = get_details($searchlink, $tvdb_token);
      send_telegram_message($searchlink, $config);
    }
  }
  echo "\n------------------------------------\n";
}
echo "TERMINO\n";
