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

/**
  *scans the directory
  *returns an array on success, false otherwise
**/
function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');
    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir.DIRECTORY_SEPARATOR.$file);
    }
    arsort($files);
    $files = array_keys($files);
    return ($files) ? $files : false;
}

/**
  *scans the file "config.ini"
  *returns an array on success, false otherwise
**/
function init() {
  _log("Starting the configuration");
  $config = parse_ini_file(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.ini") or _log("Can not parse config.ini");
  if ($config) {
    if (!array_key_exists("regexTitle", $config))
    $config["regexTitle"] = "/.*/";
    if (!array_key_exists("regexNumber", $config))
    $config["regexNumber"] = "/.*/";
    if (!array_key_exists("regexExcludeHost", $config))
    $config["regexExcludeHost"] = "//";
    if (!array_key_exists("passwordDownload", $config))
    $config["passwordDownload"] = "null";
    if (!array_key_exists("telegram", $config) || !$config["telegram"])
    $config["telegram"] = false;
    if (!array_key_exists("thetvdb", $config) || !$config["thetvdb"])
    $config["thetvdb"] = false;
    if (!array_key_exists("jdownloader", $config) || !$config["jdownloader"])
    $config["jdownloader"] = false;
    _log("Configuration:");
    foreach ($config as $key => $value) {
      _log("\t".$key." : ".$value);
    }
  }
  return $config;
}

/**
  *check if the log file exists and place the pointer before EOF
  *otherwise create the file
  *write the line
  *close the file
**/
function _log($line) {
  global $log_enable;
  $logname = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Logs".DIRECTORY_SEPARATOR."Log".date("H").".txt";
  if ($log_enable){
    if (file_exists($logname))
      if(date('d M Y', filemtime($logname)) < date('d M Y'))
        $logfile = fopen($logname, "w");
      else
        $logfile = fopen($logname, "a");
    else
      $logfile = fopen($logname, "w");
    fwrite($logfile, date(" d M Y H:i:s")."\t".$line."\n");
    chmod($logname, 0666);
    fclose($logfile);
  }
}

/**
  *returns true if url is reachable, false otherwise
**/
function check_url($url)  {
  $headers = @get_headers($url);
  if(strpos($headers[0],'200')===false)
    return false;
  return true;
}

/**
  *Saves the configuration file in .ini format
  *returns true on success, false otherwise
**/
function save_ini($newconfig){
  _log("Saving configuration");
  $config = fopen(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.ini", "w") or _log("Can not open config.ini");
  $check = false;
  foreach ($newconfig as $key => $value) {
    if($key=="telegram" || $key=="jdownloader" || $key=="thetvdb")
      $check = fwrite($config, $key."=".$value."\n");
    else
      $check = fwrite($config, $key."=\"".$value."\"\n");
  }
  fclose($config);
  if ($check) {
    _log("Configuration changed");
    init();
  } else
    _log("Can not open config.ini");
  return $check;
}
