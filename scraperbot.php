<?php
include_once 'Resources'.DIRECTORY_SEPARATOR.'Series.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'Comunication.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'resources.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'simple_html_dom.php';
$log_enable = true;
$config = init();
_log("-----------------------------------------------------------------------");
$list = scan_dir(__DIR__.DIRECTORY_SEPARATOR."Saves");
$comunication = new Comunication($config);
foreach ($list as $key => $value) {
  $series = new Series($config, $value);
  $series->update_downloadable_episode();
  $series->check_missing_episode();
if (!empty($series->missing_episode)) {
    $series->create_new_crawljob();
    $comunication->get_details($series->the_tvdb_id);
    $comunication->send_message($series->image);
  }
  _log("-----------------------------------------------------------------------");
}
