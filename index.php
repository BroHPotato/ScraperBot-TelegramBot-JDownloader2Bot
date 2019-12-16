<?php
//error_reporting(E_ALL);

include_once 'Resources'.DIRECTORY_SEPARATOR.'Series.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'Comunication.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'resources.php';
include_once 'Resources'.DIRECTORY_SEPARATOR.'simple_html_dom.php';

$config = init();

require_once "View/header.php";
if ($_GET["a"]==="add") {
  require_once "View/add_modify.php";
} elseif ($_GET["a"]==="modify") {
  require_once "Model/index.php";
  require_once "View/add_modify.php";
} elseif ($_GET["a"]==="delete") {
  require_once "Model/index.php";
  require_once "Model/delete.php";
} elseif ($_GET["a"]==="deletelogs") {
  require_once "Model/deletelogs.php";
} elseif ($_GET["a"]==="option") {
  require_once "View/option.php";
} elseif ($_GET["a"]==="logs") {
  require_once "Model/logs.php";
  require_once "View/logs.php";
} else {
  require_once "Model/index.php";
  require_once "View/index.php";
}

require_once "View/footer.php";
