<?php
include "simple_html_dom.php";
include "utility.php";
include "web_elaboration.php";
include "file_elaboration.php";

error_reporting(E_ALL);
$config = parse_ini_file("config.ini");
$logfile=fopen($config["logPath"]."Log".date("H-i").".txt", "a");
fwrite($logfile,"START\n------------------------------------\nCONFIGURATION\n");
$config = init();
fwrite($logfile,"------------------------------------\nSTART SEARCHING\n");
$links = get_links($config);
foreach ($links as $searchlink) {
  fwrite($logfile,"ELABORATION OF ".$searchlink["Title"]."\n");
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
  fwrite($logfile,"------------------------------------\n");
}
fwrite($logfile,"END\n");
fclose($logfile);
