<?php
include_once __DIR__ . "/../resorces.php";

  function get_page($value, $config)  {
    _log("Getting the page...", $config);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $value);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    _log("Page recived", $config);
    return $response;
  }

  function filter_download($html,$config)  {
    _log("Filtering the page...", $config);
    $filteredLinks = false;
    if ($html)	{
      $links = $html->find($config["htmlTags"]);
      foreach ($links as $key => $value) {
        if(preg_match($config["regexTitle"], $value->plaintext, $episode) && !preg_match("/prossimamente/", $value->href)){
          preg_match($config["regexNumber"], $episode[0], $numEpisode);
          $filteredLinks[$numEpisode[0]] = $links[$key]->href;//n episodio => download
        }
      }
      if ($filteredLinks) {
        _log("Links found", $config);
      }else{
        _log("No links found", $config);
      }
    }
    return $filteredLinks;
  }

  function get_details($link, $tvdb_token, $config)  {
    _log("Getting the details...", $config);
    $ch = curl_init('https://api.thetvdb.com/series/'.$link["thetvdbId"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', "Authorization: Bearer ".$tvdb_token));
    $result = curl_exec($ch);
    curl_close($ch);
    $return = json_decode($result, true)["data"];
    if ($return) {
      _log("Details recived", $config);
    } else {
      _log("Details not recived", $config);
    }
    return $return;
  }

  function send_telegram_message($link, $config)  {
    _log("Using the Telegram bot...", $config);
    $to = "https://api.telegram.org/bot".$config["telegramToken"]."/sendPhoto?chat_id=".$config["chatId"];
    $photo = $link["Poster"];
    $caption = urlencode("Hey, é uscito un episodio di <b>".$link["Details"]["seriesName"]."</b>!\n\nDi: <b>".$link["Details"]["network"]."</b>\n\n".$link["Details"]["overview"]."\n\nVai a darci un'occhiata su <a href=\"https://app.plex.tv/desktop\">Plex</a>");
    $ch = curl_init($to."&photo=".$photo."&caption=".$caption."&parse_mode=HTML");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    $return = curl_exec($ch);
    curl_close($ch);
    if ($return) {
      _log("Message sended", $config);
    } else {
      _log("Message not sended", $config);
    }
    return $return;
  }
  function get_token($config)  {
    $data = array("apikey" => $config["tvdbApiKey"], "userkey" => $config["tvdbUsrKey"], "username" => $config["tvdbUsr"]);
    $data_string = json_encode($data);
    _log("Getting the tvdb token...", $config);
    $ch = curl_init('https://api.thetvdb.com/login');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
    $result = curl_exec($ch);
    curl_close($ch);
    $tvdb_token = json_decode($result, true)["token"];
    _log("Token recived", $config);
    return $tvdb_token;
  }
