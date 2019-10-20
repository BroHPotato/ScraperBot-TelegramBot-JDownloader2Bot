<?php
function get_links($config)  {
  $svfile = fopen($config["linksFile"], "r") or die("Unable to open the file");
  $links = false;
  while (!feof($svfile)) {
    $string = fgets($svfile);
    if ($string) {
      $exploded = explode($config["delimiter"],$string);
      $title = explode("/",$exploded[3])[0];
      $links[] = array( "Title" => $title,
                        "Poster" => $exploded[1],
                        "imdbId" => $exploded[2],
                        "Link" => $exploded[0],
                        "SaveFolder" => $exploded[3]);
    }
  }
  fclose($svfile);
  return $links;
}
function check_exist_files($searchlink, $config) {
  global $logfile;
  $fol=substr_replace($config["savePath"].$searchlink["SaveFolder"] ,"", -1);
  fwrite($logfile,"SEARCH IN :".$fol."\n");
  $dir = scandir($fol);
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
    fwrite($logfile,"NEW DOWNLOAD FOUND\n");
  } else {
    fwrite($logfile,"NO NEW DOWNLOAD FOUND\n");
  }
  return $toDownload;
}
function get_list_present($fileList, $config) {
  global $logfile;
  $presentList = false;
  foreach ($fileList as $value) {
    preg_match($config["regexTitle"], $value, $episode);
    preg_match($config["regexNumber"], $episode[0], $numEpisode);
    $presentList[$numEpisode[0]] = $value; // n episodio => titolo completo presenti
  }
  if ($presentList) {
    fwrite($logfile,"SAVED EPISODE FOUND\n");
  }
  return $presentList;
}
function create_new_download($toDownload, $config)  {
  global $logfile;
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
    fwrite($logfile,"CRAWLJOB CREATED IN ".$config["crawljobPath"]."\nCON NOME ".sha1($toDownload["Title"])."\n");
  }
}
