<?php
include_once "simple_html_dom.php";
include_once "web_elaboration.php";
include_once "file_elaboration.php";
include_once __DIR__ . "/../resorces.php";

$config = init(true, true);
_log("SEARCHING------------------------------------", $config);
$links = get_links($config);
foreach ($links as $searchlink) {
  _log("ELABORATION OF ".$searchlink["Title"], $config);
  $response = get_page($searchlink["Link"], $config);
  $html = new simple_html_dom();
  $html->load($response);
  $searchlink["Episode"] = filter_download($html, $config);

  if ($searchlink["Episode"]) {
    $creatred = false;
    $searchlink["MissingEpisode"] = check_exist_files($searchlink, $config);
    if ($searchlink["MissingEpisode"] && $config["jdownloader"]) {
      $creatred = create_new_download($searchlink, $config);
    }
  if ($searchlink["MissingEpisode"] && $config["telegram"] && (($config["jdownloader"] && $creatred) || (!$config["jdownloader"] && !$creatred))) { //if telegram is active and (jd is active and the download is created or jd is not active and the download is not created)
      $tvdb_token = get_token($config);
      $searchlink["Details"] = get_details($searchlink, $tvdb_token, $config);
      send_telegram_message($searchlink, $config);
    }
  }
  _log("------------------------------------", $config);
}
_log("END\n\n", $config);
