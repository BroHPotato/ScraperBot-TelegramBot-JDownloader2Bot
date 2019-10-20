<?php
  function get_links($config)  {
    $svfile = fopen($config["linksFile"], "r") or die("Unable to open the file");
    $links = false;
    while (!feof($svfile)) {
      $string = fgets($svfile);
      if ($string) {
        $exploded = explode($config["delimiter"], substr_replace($string ,"", -1));
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
  function get_page($value)  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $value);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
  function filter_download($html,$config)  {
    $filteredLinks = false;
    if ($html)	{
      foreach ($html->find($config["htmlTags"]) as $value) {
        if(preg_match($config["regexTitle"], $value->plaintext, $episode) && !preg_match("/prossimamente/", $value->href)){
          preg_match($config["regexNumber"], $episode[0], $numEpisode);
          $filteredLinks[$numEpisode[0]] = $value->href;//n episodio => download
        }
      }
      if ($filteredLinks) {
          echo "LINKS TROVATI\n";
      }
    }
    return $filteredLinks;
  }
  function get_details($link, $tvdb_token)  {
    $ch = curl_init('https://api.thetvdb.com/search/series?imdbId='.rawurlencode($link["imdbId"]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', "Authorization: Bearer ".$tvdb_token));
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true)["data"][0];
  }

  function send_telegram_message($link, $config)  {
    $to = "https://api.telegram.org/bot".$config["telegramToken"]."/sendPhoto?chat_id=".$config["chatId"];
    $photo = $link["Poster"];
    $caption = urlencode("Hey, é uscito un episodio di <b>".$link["Details"]["seriesName"]."</b>!\n\nDi: <b>".$link["Details"]["network"]."</b>\n\n".$link["Details"]["overview"]."\n\nVai a darci un'occhiata su <a href=\"https://app.plex.tv/desktop\">Plex</a>");
    $ch = curl_init($to."&photo=".$photo."&caption=".$caption."&parse_mode=HTML");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
  }
