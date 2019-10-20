<?php

function check_exist_files($searchlink, $config) {
  $dir = scandir($config["savePath"].$searchlink["SaveFolder"]);
  $toDownload = false;
  if ($dir) {
    $fileList = array_diff($dir, array('..', '.'));
    if (!empty($fileList)) {
      $presentList = get_list_present($fileList, $config);
      foreach ($searchlink["Episode"] as $numE => $download) {
        if (!array_key_exists($numE, $presentList)) {
          $toDownload[$numE] = $download; //n episodio => download
        }
      }
    }
  } else {
    $toDownload = $searchlink["Episode"];
  }
  if ($toDownload) {
    echo "NUOVI DOWNLOAD TROVATI\n";
  } else {
    echo "NESSUN NUOVO DOWNLOAD TROVATO\n";
  }
  return $toDownload;
}
function get_list_present($fileList, $config) {
  $presentList = false;
  foreach ($fileList as $value) {
    preg_match($config["regexTitle"], $value, $episode);
    preg_match($config["regexNumber"], $episode[0], $numEpisode);
    $presentList[$numEpisode[0]] = $value; // n episodio => titolo completo presenti
  }
  if ($presentList) {
    echo "EPISODI SALVATI TROVATI\n";
  }
  return $presentList;
}
function create_new_download($toDownload, $config)  {
  $job = fopen($config["crawljobPath"].sha1($toDownload["Title"]).".crawljob", "w+");
  if ($job)  {
    fwrite($job, "->NEW ENTRY<-\n");
    fwrite($job, "packageName=".$toDownload["Title"]."\n");
    fwrite($job, "text=");
    foreach ($toDownload["MissingEpisode"] as $numEpisode => $download) {
      fwrite($job, $download."\n");
    }
    fwrite($job, "downloadPassword=".$config["passwordDownload"]."\n");
    fwrite($job, "downloadFolder=".$config["downloadFolder"].$toDownload["SaveFolder"]."\n");
    fwrite($job, "autoStart=TRUE\n");
    fwrite($job, "autoConfirm=TRUE");
    fclose($job);
    echo "CRAWLJOB CREATO IN ".$config["crawljobPath"]."\nCON NOME ".sha1($toDownload["Title"])."\n";
  }
}
