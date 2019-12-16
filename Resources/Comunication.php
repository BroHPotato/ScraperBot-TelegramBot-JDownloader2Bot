<?php

include_once 'resources.php';

/**
 *
 */
class Comunication
{
  private $telegram_status;
  private $the_tvdb_status;
  private $telegram_token;
  private $telegram_chat_id;
  private $the_tvdb_usr_name;
  private $the_tvdb_usr_key;
  private $the_tvdb_api_key;
  private $the_tvdb_api_token;
  public $text;
  public $details;

  public function __construct($config) {
    $this->telegram_status = ($config["telegram"]==true) ? true : false;
    $this->the_tvdb_status = ($config["thetvdb"]==true) ? true : false;
    if ($this->telegram_status) {
      $this->telegram_token = $config["telegramToken"];
      $this->telegram_chat_id = $config["chatId"];
    }
    if ($this->the_tvdb_status) {
      $this->the_tvdb_usr_name = $config["tvdbUsr"];
      $this->the_tvdb_usr_key = $config["tvdbUsrKey"];
      $this->the_tvdb_api_key = $config["tvdbApiKey"];
    }
  }

  public function get_telegram_status()  {
    return $this->telegram_status;
  }

  public function get_the_tvdb_status()  {
    return $this->the_tvdb_status;
  }

  private function get_token()  {
    if ($this->get_the_tvdb_status()) {
      $array= array('apikey' => $this->the_tvdb_api_key, 'userkey' => $this->the_tvdb_usr_key, 'username' => $this->the_tvdb_usr_name);
      $data = json_encode($array);
      $ch = curl_init('https://api.thetvdb.com/login');
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
      $result = curl_exec($ch);
      curl_close($ch);
      $this->the_tvdb_api_token = json_decode($result, true)["token"];
    }
  }

  public function get_details($id)  {
    if ($this->get_the_tvdb_status()) {
      $this->get_token();
      $ch = curl_init('https://api.thetvdb.com/series/'.$id);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', "Authorization: Bearer ".$this->the_tvdb_api_token));
      $result = curl_exec($ch);
      curl_close($ch);
      $this->details = json_decode($result, true)["data"];
    }
  }

  public function send_message($img)  {
    if ($this->get_telegram_status()) {
      $to = "https://api.telegram.org/bot".$this->telegram_token."/sendPhoto?chat_id=".$this->telegram_chat_id;
      $caption = urlencode("Hey, é uscito un episodio di <b>".$this->details["seriesName"]."</b>!\n\nDi: <b>".$this->details["network"]."</b>\n\n".$this->details["overview"]."\n\nVai a darci un'occhiata su <a href=\"https://app.plex.tv/desktop\">Plex</a>");
      $ch = curl_init($to."&photo=".$img."&caption=".$caption."&parse_mode=HTML");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
      if(curl_exec($ch));
        _log("Messege sended correctly");
      curl_close($ch);
    }
  }
}
