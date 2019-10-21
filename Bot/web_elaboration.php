<?php
  function get_page($value)  {
    global $logfile;
    fwrite($logfile,"Getting the page...\n");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $value);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    fwrite($logfile,"Page recived\n");
    return $response;
  }
  function filter_download($html,$config)  {
    global $logfile;
    fwrite($logfile,"Filtering the page...\n");
    $filteredLinks = false;
    if ($html)	{
      foreach ($html->find($config["htmlTags"]) as $value) {
        if(preg_match($config["regexTitle"], $value->plaintext, $episode) && !preg_match("/prossimamente/", $value->href)){
          preg_match($config["regexNumber"], $episode[0], $numEpisode);
          $filteredLinks[$numEpisode[0]] = $value->href;//n episodio => download
        }
      }
      if ($filteredLinks) {
        fwrite($logfile,"LINKS FOUND\n");
      }else{
        fwrite($logfile,"NO LINKS FOUND\n");
      }
    }
    return $filteredLinks;
  }
  function get_details($link, $tvdb_token)  {
    global $logfile;
    fwrite($logfile,"Getting the details...\n");
    $ch = curl_init('https://api.thetvdb.com/series/'.$link["imdbId"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', "Authorization: Bearer ".$tvdb_token));
    $result = curl_exec($ch);
    curl_close($ch);
    $return = json_decode($result, true)["data"];
    if ($return) {
      fwrite($logfile,"Details recived\n");
    } else {
      fwrite($logfile,"Details not recived\n");
    }
    return $return;
  }

  function send_telegram_message($link, $config)  {
    global $logfile;
    fwrite($logfile,"Using the Telegram bot...\n");
    $to = "https://api.telegram.org/bot".$config["telegramToken"]."/sendPhoto?chat_id=".$config["chatId"];
    $photo = $link["Poster"];
    $caption = urlencode("Hey, é uscito un episodio di <b>".$link["Details"]["seriesName"]."</b>!\n\nDi: <b>".$link["Details"]["network"]."</b>\n\n".$link["Details"]["overview"]."\n\nVai a darci un'occhiata su <a href=\"https://app.plex.tv/desktop\">Plex</a>");
    $ch = curl_init($to."&photo=".$photo."&caption=".$caption."&parse_mode=HTML");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    $return = curl_exec($ch);
    curl_close($ch);
    fwrite($logfile,"Message sended\n");
    return $return;
  }
