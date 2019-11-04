<?php
$_err = array(
  "000" => "Operation successful",
  "001" => "Operation successfully canceled",
  "010" => "An error occurred while deleting the file, please check the save file before continuing!",
  "020" => "An error occurred while adding the file, please check the save file before continuing!",
  "030" => "An error occurred while updating the file, please check the save file before continuing!",
  "040" => "An CRITICAL error occurred while updating the configuration file, YOU MUST CHEK THE config.ini FILE and THE Links.txt FILE BEFORE CONTINUING!",
  "050" => "An error occurred while deleting the log, try again or report this event to the administrator"
);

$inipath = "/home/pi/ScraperBot/config.ini";

/**
  *scans the file "config.ini"
  *returns an array on success, false otherwise
**/
function init($logenable=true, $start=false) {
  global $inipath;
  $config = parse_ini_file($inipath);
  if ($logenable && $start) {
      _log("START", $config);
      _log("CONFIGURATION------------------------------------", $config);
  }
  if ($logenable) {
    _log("Searching for \"".$config["htmlTags"]."\"", $config);
  }
  if (!array_key_exists("regexTitle", $config)) {
    $config["regexTitle"] = "/.*/";
  }
  if ($logenable) {
    _log("Filtering with ".$config["regexTitle"], $config);
  }
  if (!array_key_exists("regexNumber", $config)) {
    $config["regexNumber"] = "/.*/";
  }
  if ($logenable) {
    _log("Sub-filtering with ".$config["regexNumber"], $config);
  }
  if (!array_key_exists("passwordDownload", $config)) {
    $config["passwordDownload"] = "null";
    if ($logenable) {
      _log("No password for download", $config);
    }
  }
  if (!array_key_exists("telegram", $config) || !$config["telegram"]) {
    $config["telegram"] = false;
    if ($logenable) {
      _log("Bot telegram disable", $config);
    }
  } else {
    if ($logenable) {
      _log("Bot telegram enable", $config);
    }
  }
  if (!array_key_exists("jdownloader", $config) || !$config["jdownloader"]) {
    $config["jdownloader"] = false;
    if ($logenable) {
      _log("jdownloader file creation disable", $config);
    }
  } else {
    if ($logenable) {
      _log("jdownloader file creation enable", $config);
    }
  }
  return $config;
}

/**
  *scans the file "linksFile"
  *returns an array on success, false otherwise
**/
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

/**
  *delete the line number "id"
  *returns true on success, false otherwise
**/
function delete_line($line, $config){
  $svfile = fopen($config["linksFile"], "r+") or die("Unable to open the file");
  $check = true;
  while (!feof($svfile)) {
    $string[] = fgets($svfile);
  }
  $check = ftruncate($svfile, 0);
  $check = (fseek($svfile, 0)==0) ? true : false;
  if ($check) {
    foreach ($string as $key => $value) {
      if ($key!=$line) {
        fwrite($svfile, $value);
      } else {
        _log("Line deleted:\n".$value, $config);
      }
    }
  } else {
    _log("An error occured while deleting a line", $config);
  }
  fclose($svfile);
  return $check;
}

/**
  *appends "line" to the file
  *returns true on success, false otherwise
**/
function add_line($line, $config){
  $svfile = fopen($config["linksFile"], "a") or die("Unable to open the file");
  $check = (fwrite($svfile, $line)) ? true : false;
  if ($check) {
    _log("Line added:".$line, $config);
  } else {
    _log("An error occured while adding the line ".$line."\nin ".$config["linksFile"], $config);
  }
  fclose($svfile);
  return $check;
}

/**
  *replace the line number "id" with "line"
  *returns true on success, false otherwise
**/
function modify_line($id, $line, $config){
  $svfile = fopen($config["linksFile"], "r+") or die("Unable to open the file");
  $check = true;
  while (!feof($svfile)) {
    $string[] = fgets($svfile);
  }
  $check = ftruncate($svfile, 0);
  $check = (fseek($svfile, 0)==0) ? true : false;
  if ($check) {
    foreach ($string as $key => $value) {
      if ($key==$id) {
        fwrite($svfile, $line);
        _log("Line modified: ".$id."\n".$value."Changed with:\n".$line, $config);
      }else {
        fwrite($svfile, $value);
      }
    }
  } else {
    _log("An error occured while changing a line in ".$config["linksFile"], $config);
  }
  fclose($svfile);
  return $check;
}

/**
  *Saves the configuration file in .ini format
  *returns true on success, false otherwise
**/
function save_ini($newconfig){
  global $inipath;
  $config = fopen($inipath, "w") or die("Unable to open the file");
  $check = false;
  foreach ($newconfig as $key => $value) {
    if($key=="telegram" || $key=="jdownloader")
      $check = fwrite($config, $key."=".$value."\n");
    else
      $check = fwrite($config, $key."=\"".$value."\"\n");
  }
  fclose($config);
  $config = init();
  if ($check) {
    _log("Configuration changed", $config);
  } else {
    _log("An error occured while changing the configuration", $config);
  }
  return $check;
}

/**
  *scans the directory
  *returns an array on success, false otherwise
**/
function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');
    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }
    arsort($files);
    $files = array_keys($files);
    return ($files) ? $files : false;
}

/**
  *check if the log file exists and place the pointer before EOF
  *otherwise create the file
  *write the line
  *close the file
**/
function _log($line, $config) {
  $logfile = fopen($config["logPath"]."Log".date("H").".txt", "r+");
  if ($logfile) {
    fseek($logfile, 0, SEEK_END);
  } else {
    $logfile = fopen($config["logPath"]."Log".date("H").".txt", "w");
  }
  fwrite($logfile, date(" d M Y H:i:s")." ".$line."\n");
  fclose($logfile);
}

?>
