<?php
  function init() {
    global $logfile;
    $config = parse_ini_file("config.ini");
    fwrite($logfile,"Searching for \"".$config["htmlTags"]."\"\n");
    if (!array_key_exists("regexTitle", $config)) {
      $config["regexTitle"] = "/.*/";
    }
    fwrite($logfile,"Filtering with ".$config["regexTitle"]."\n");
    if (!array_key_exists("regexNumber", $config)) {
      $config["regexNumber"] = "/.*/";
    }
    fwrite($logfile,"Sub-filtering with ".$config["regexNumber"]."\n");
    if (!array_key_exists("passwordDownload", $config)) {
      $config["passwordDownload"] = "null";
      fwrite($logfile,"No password for download\n");
    }
    if (!array_key_exists("telegram", $config) || !$config["telegram"]) {
      $config["telegram"] = false;
      fwrite($logfile,"Bot telegram disable\n");
    } else {
      fwrite($logfile,"Bot telegram enable\n");
    }
    if (!array_key_exists("jdownloader", $config) || !$config["jdownloader"]) {
      $config["jdownloader"] = false;
      fwrite($logfile,"jdownloader file creation disable\n");
    } else {
      fwrite($logfile,"jdownloader file creation disable\n");
    }
    return $config;
  }

  function get_token($config)  {
    global $logfile;
    $data = array("apikey" => $config["tvdbApiKey"], "userkey" => $config["tvdbUsrKey"], "username" => $config["tvdbUsr"]);
    $data_string = json_encode($data);
    fwrite($logfile,"Getting the tvdb token...\n");
    $ch = curl_init('https://api.thetvdb.com/login');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
    $result = curl_exec($ch);
    curl_close($ch);
    $tvdb_token = json_decode($result, true)["token"];
    fwrite($logfile,"Token recived\n");
    return $tvdb_token;
  }
