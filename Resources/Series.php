<?php

include_once 'resources.php';
include_once 'simple_html_dom.php';

/**
 *
 */
class Series
{
  private $config;
  public $name;
  public $the_tvdb_id;
  public $image;
  public $downloaded_episode;
  public $downloadable_episode;
  private $downloadable_links;
  public $missing_episode;
  public $save_folder;
  public $download_folder;
  public $link_downloads;
  private $html;

  public function __construct($c, $jsonfile = false) {
    $this->html = new simple_html_dom();
    $this->config = $c;
    if ($jsonfile) {
      _log("Opening "."Saves".DIRECTORY_SEPARATOR.$jsonfile);
      $file = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Saves".DIRECTORY_SEPARATOR.$jsonfile) or _log("Can not open "."Saves".DIRECTORY_SEPARATOR.$jsonfile);
      $array = json_decode($file, true)  or _log("Can not decode properly "."Saves".DIRECTORY_SEPARATOR.$jsonfile);
      if (!empty($array)) {
        $this->name = $array["name"];
        $this->the_tvdb_id = $array["the_tvdb_id"];
        $this->image = $array["image"];
        $this->downloaded_episode = $array["downloaded_episode"];
        $this->save_folder = $array["save_folder"];
	      $this->download_folder = $array["download_folder"];
        $this->link_downloads = $array["link_downloads"];
        _log("Retriving the page at ".$this->link_downloads);
        if (check_url($this->link_downloads)) {
          $this->html->load_file($this->link_downloads);
        } else {
        _log("Can not retrive the page at ".$this->link_downloads);
        die();
        }
      }
    }
  }

  public function update($n, $id, $img, $save, $downf, $link){
    _log("Updatig ".$n);
    $this->name=$n;
    $this->the_tvdb_id=$id;
    $this->image=$img;
    $this->save_folder=$save;
    $this->download_folder=$downf;
    $this->link_downloads=$link;
    if(!is_dir($this->save_folder))
      mkdir($this->save_folder, 0777, true);
    if (check_url($this->link_downloads)) {
      $this->html->load_file($this->link_downloads);
      $this->downloadable_episode = null;
      $this->downloaded_episode = null;
      $this->downloadable_links = null;
      $this->missing_episode = null;
      $this->update_downloadable_episode();
      $this->update_downloaded_episode();
      $this->check_missing_episode();
    }
  }

  public function add($n, $id, $img, $save, $downf, $link){
    _log("Addatig ".$n);
    $this->name=$n;
    $this->the_tvdb_id=$id;
    $this->image=$img;
    $this->save_folder=$save;
    $this->download_folder=$downf;
    $this->link_downloads=$link;
    if(!is_dir($this->save_folder))
      mkdir($this->save_folder, 0777, true);
    $this->update_downloaded_episode();
    $this->save();
  }

  public function save() {
    _log("Saving in "."Saves".DIRECTORY_SEPARATOR.$this->name.".json");
    $array = array(
      'name' => $this->name,
      'the_tvdb_id' => $this->the_tvdb_id,
      'image' => $this->image,
      'downloaded_episode' => $this->downloaded_episode,
      'save_folder' => $this->save_folder,
      'link_downloads' => $this->link_downloads,
      'download_folder' => $this->download_folder
    );
    $file = fopen(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Saves".DIRECTORY_SEPARATOR.$this->name.".json", "w") or _log("Can not save the file ".$this->name.".json");
    chmod(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Saves".DIRECTORY_SEPARATOR.$this->name.".json", 0666);
    if ($file) {
      $encode = json_encode($array) or _log("Can not encode properly "."Saves".DIRECTORY_SEPARATOR.$this->name.".json");
      fwrite($file, $encode) or _log("Can not save the file "."Saves".DIRECTORY_SEPARATOR.$this->name.".json");
    }
    fclose($file);
  }

  public function check_missing_episode() {
    if(empty($this->downloaded_episode))
      $this->missing_episode = $this->downloadable_episode;
    else
      $this->missing_episode = array_diff($this->downloadable_episode, $this->downloaded_episode);
    if (empty($this->missing_episode))
      _log("No missing episode founded");
    else
      _log("Found a total of ".count($this->missing_episode)." missing episode");
  }

  public function update_downloaded_episode()  {
    _log("Updating downloaded episode in ".$this->save_folder);
    $this->downloaded_episode = [];
    $present = scan_dir($this->save_folder) or _log("Can not read the directory or the directory is empty ".$this->save_folder);
    if (!empty($present)) {
      foreach ($present as $value) {
        preg_match($this->config["regexTitle"], $value, $episode);
        preg_match($this->config["regexNumber"], $episode[0], $numEpisode);
        $this->downloaded_episode[] = intval($numEpisode[0]);
        _log("Found downloaded episode: ".intval($numEpisode[0]));
      }
    } else
      _log("Downloaded episodes not found");
  }

  public function update_downloadable_episode()  {
    _log("Searching for \"".$this->config["htmlTags"]."\" with \"".$this->config["regexTitle"]."\" excluding hosts like \"".$this->config["regexExcludeHost"]."\"");
    $this->downloadable_episode = [];
    $links = $this->html->find($this->config["htmlTags"]);
    foreach ($links as $key => $value) {
      if(check_url($value->href) && preg_match($this->config["regexTitle"], $value->plaintext, $episode) && !preg_match($this->config["regexExcludeHost"], $value->href)){
        preg_match($this->config["regexNumber"], $episode[0], $numEpisode);
        $this->downloadable_episode[] = intval($numEpisode[0]);
        $this->downloadable_links[intval($numEpisode[0])] = $links[$key+1]->href; //n episodio => download
        _log("Found downloadable episode: ".intval($numEpisode[0]));
      }
    }
    if (empty($this->downloadable_episode))
      _log("Downloadable episodes not found");
  }

  public function create_new_crawljob() {
    if ($this->config["jdownloader"] == true) {
      $crawljob = fopen(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Watcher".DIRECTORY_SEPARATOR.sha1($this->name).".crawljob", "w+") or _log("Can not create "."Watcher".DIRECTORY_SEPARATOR.sha1($this->name)."crawljob");
      if ($crawljob) {
        _log("Created a crawljob as "."Watcher".DIRECTORY_SEPARATOR.sha1($this->name).".crawljob");
        fwrite($crawljob, "->NEW ENTRY<-\n");
        fwrite($crawljob, "packageName=".$this->name."\n");
        fwrite($crawljob, "text=");
        foreach ($this->missing_episode as $key => $value) {
          fwrite($crawljob, $this->downloadable_links[$value]."\n");
          $this->downloaded_episode[] = $value;
        }
        fwrite($crawljob, "downloadPassword=".$this->config["passwordDownload"]."\n");
        fwrite($crawljob, "downloadFolder=".$this->download_folder."\n");
        fwrite($crawljob, "autoStart=TRUE\n");
        fclose($crawljob);
        $this->save();
      }
    } else
      _log("JDownloader 2 is DISABLE, check the configuration file");
  }

}
