<?php
$_err = array(
  "000" => "Operation successful",
  "001" => "Operation successfully canceled",
  "010" => "An error occurred while deleting the file, please check the save file before continuing!",
  "020" => "An error occurred while adding the file, please check the save file before continuing!",
  "030" => "An error occurred while updating the file, please check the save file before continuing!",
  "040" => "An CRITICAL error occurred while updating the configuration file, YOU MUST CHEK THE config.ini FILE and THE Links.txt FILE BEFORE CONTINUING!"
);

function init() {
  $config = parse_ini_file("config.ini");
  if (!array_key_exists("regexTitle", $config)) {
    $config["regexTitle"] = "/.*/";
  }
  if (!array_key_exists("regexNumber", $config)) {
    $config["regexNumber"] = "/.*/";
  }
  if (!array_key_exists("passwordDownload", $config)) {
    $config["passwordDownload"] = "null";
  }
  if (!array_key_exists("telegram", $config) || !$config["telegram"]) {
    $config["telegram"] = false;
  }
  if (!array_key_exists("jdownloader", $config) || !$config["jdownloader"]) {
    $config["jdownloader"] = false;
  }
  return $config;
}

function get_links($config)  {
  $svfile = fopen($config["linksFile"], "r") or die("Unable to open the file");
  $links = false;
  while (!feof($svfile)) {
    $string = fgets($svfile);
    if ($string) {
      $exploded = explode($config["delimiter"], $string);
      $title = explode("/",$exploded[3])[0];
      $links[] = array( "Title" => $title,
                        "Poster" => $exploded[1],
                        "thetvdbId" => $exploded[2],
                        "Link" => $exploded[0],
                        "SaveFolder" => $exploded[3]);
    }
  }
  fclose($svfile);
  return $links;
}

function delete_line($line, $config){
  $logfile=fopen($config["logPath"]."Log".date("H-i").".txt", "a");
  $svfile = fopen($config["linksFile"], "r+") or die("Unable to open the file");
  $check = true;
  while (!feof($svfile)) {
    $string[] = fgets($svfile);
  }
  $check = ftruncate($svfile, 0);
  $check = (fseek($svfile, 0)==0) ? true : false;
  foreach ($string as $key => $value) {
    if ($key!=$line) {
      fwrite($svfile, $value);
    } else {
      fwrite($logfile,"LINE DELETED:\n".$value."\n");
    }
  }
  fclose($logfile);
  fclose($svfile);
  return $check;
}

function add_line($line, $config){
  $svfile = fopen($config["linksFile"], "a") or die("Unable to open the file");
  $logfile=fopen($config["logPath"]."Log".date("H-i").".txt", "a");
  $check = (fwrite($svfile, $line)) ? true : false;
  if ($check) {
    fwrite($logfile,"LINE ADDED:\n".$line."\n");
  }
  fclose($svfile);
  fclose($logfile);
  return $check;
}
function modify_line($id, $line, $config){
  $svfile = fopen($config["linksFile"], "r+") or die("Unable to open the file");
  $logfile=fopen($config["logPath"]."Log".date("H-i").".txt", "a");
  $check = true;
  while (!feof($svfile)) {
    $string[] = fgets($svfile);
  }
  $check = ftruncate($svfile, 0);
  $check = (fseek($svfile, 0)==0) ? true : false;
  foreach ($string as $key => $value) {
    if ($key==$id) {
      fwrite($svfile, $line);
      fwrite($logfile,"LINE MODIFIED: ".$id."\n".$value." CHANGED WITH\n".$line."\n");
    }else {
      fwrite($svfile, $value);
    }
  }
  fclose($svfile);
  fclose($logfile);
  return $check;
}
function save_ini($oldconfig, $newconfig){
  $logfile=fopen($config["logPath"]."Log".date("H-i").".txt", "a");
  $config = fopen("config.ini", "w") or die("Unable to open the file");
  $check = false;
  foreach ($oldconfig as $key => $value) {
    if($key=="telegram" || $key=="jdownloader")
      $check = fwrite($config, $key."=".$value."\n");
    else
      $check = fwrite($config, $key."=\"".$value."\"\n");
  }
  fwrite($logfile,"CONFIGURATIN CHANGED\n");
  fclose($config);
  fclose($logfile);
  return $check;
}
?>
