<?php
include_once __DIR__ . "/../resorces.php";
/**
  *checks if the download folder exists
  *filters the files and checks if there is a new episode in $searchlink
  *returns an array on success, false otherwise
**/
function check_exist_files($searchlink, $config) {
  $fol=substr_replace($config["savePath"].$searchlink["SaveFolder"] ,"", -1);
  _log("Searching in ".$fol, $config);
  $fileList = scan_dir($fol);
  $toDownload = false;
  if ($fileList && !empty($fileList)) {
    $presentList = get_list_present($fileList, $config);
    foreach ($searchlink["Episode"] as $numE => $download) {
      if (!array_key_exists($numE, $presentList)) {
        $toDownload[$numE] = $download; //n episodio => download
        _log("New downloads found, episode #".$numE, $config);
      }
    }
  } else {
    $toDownload = $searchlink["Episode"];
  }
  if (!$toDownload) {
    _log("No new downloads found", $config);
  }
  return $toDownload;
}

/**
  *filters the episode number from the array $fileList
  *returns an array on success, false otherwise
**/
function get_list_present($fileList, $config) {
  $presentList = false;
  foreach ($fileList as $value) {
    preg_match($config["regexTitle"], $value, $episode);
    preg_match($config["regexNumber"], $episode[0], $numEpisode);
    $presentList[$numEpisode[0]] = $value; // n episodio => titolo completo presenti
  }
  if ($presentList) {
    _log("Saved episode found", $config);
  }
  return $presentList;
}

/**
  *checks that the link isnt in download
  *than create a .crawljob
  *returns an array on success, false otherwise
**/
function create_new_download($toDownload, $config)  {
  $fileList = scan_dir($config["crawljobPath"]."added");
  $check = false;
  $job = false;
  foreach ($toDownload["MissingEpisode"] as $numEpisode => $download) {
    $epNum = $epNum.$numEpisode;
  }
  $filename = $config["crawljobPath"].sha1($toDownload["SaveFolder"]).$epNum.".crawljob";
  foreach ($fileList as $value) {
    $value = strstr($value, '.crawljob', true);
  }
  if (!in_array(basename($filename), $fileList)) {
    array_map('unlink', glob($config["crawljobPath"].sha1($toDownload["SaveFolder"])."*.crawljob*"));
    $check = true;
    $job = fopen($filename, "w+");
  } else {
    _log("CRAWLJOB not created, already in download", $config);
  }
  if ($job && $check)  {
    write_crawljob($job, $toDownload, $config);
    _log("CRAWLJOB created in ".$config["crawljobPath"]."\ncalled ".sha1($toDownload["SaveFolder"]).$epNum.".crawljob", $config);
  }
  return $check;
}

/**
  *create a .crawljob
**/
function write_crawljob($job, $toDownload, $config) {
  fwrite($job, "->NEW ENTRY<-\n");
  fwrite($job, "packageName=".$toDownload["Title"]."\n");
  fwrite($job, "text=");
  if (count($toDownload["MissingEpisode"])=== 1){
    foreach ($toDownload["MissingEpisode"] as $numEpisode => $download) {
      fwrite($job, $download."\n");
      fwrite($job, "filename=".$toDownload["Title"]."_ep_".$numEpisode.".mp4\n");
      fwrite($job, "autoConfirm=TRUE\n");
    }
  } else {
    foreach ($toDownload["MissingEpisode"] as $numEpisode => $download) {
      fwrite($job, $download."\n");
    }
  }
  fwrite($job, "downloadPassword=".$config["passwordDownload"]."\n");
  fwrite($job, "downloadFolder=".$config["downloadFolder"].$toDownload["SaveFolder"]."\n");
  fwrite($job, "autoStart=TRUE\n");
  fclose($job);
}
