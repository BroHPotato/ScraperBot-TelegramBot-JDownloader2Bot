<?php
  function init() {
    $config = parse_ini_file("config.ini");
    echo "Searching for \"".$config["htmlTags"]."\"\n";
    if (!array_key_exists("regexTitle", $config)) {
      $config["regexTitle"] = "/.*/";
    }
    echo "Filtering with ".$config["regexTitle"]."\n";
    if (!array_key_exists("regexNumber", $config)) {
      $config["regexNumber"] = "/.*/";
    }
    echo "Sub-filtering with ".$config["regexNumber"]."\n";
    if (!array_key_exists("passwordDownload", $config)) {
      $config["passwordDownload"] = "null";
      echo "No password for download\n";
    }
    if (!array_key_exists("telegram", $config) || !$config["telegram"]) {
      $config["telegram"] = false;
      echo "Bot telegram disable\n";
    } else {
      echo "Bot telegram enable\n";
    }
    if (!array_key_exists("jdownloader", $config) || !$config["jdownloader"]) {
      $config["jdownloader"] = false;
      echo "jdownloader file creation disable\n";
    } else {
      echo "jdownloader file creation enable\n";
    }
    return $config;
  }

  function get_token($config)  {
    $data = array("apikey" => $config["tvdbApiKey"], "userkey" => $config["tvdbUsrKey"], "username" => $config["tvdbUsr"]);
    $data_string = json_encode($data);

    $ch = curl_init('https://api.thetvdb.com/login');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
    $result = curl_exec($ch);
    curl_close($ch);
    $tvdb_token = json_decode($result, true)["token"];
    return $tvdb_token;
  }
