<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    	<title>ScraperBot</title>
      <meta charset="UTF-8" />
      <meta name="author" content="BroHPotato - Giuseppe Vito Bitetti" />
      <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="_general/style.css" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php"><img class="icon" src="_general/icon2.png" alt="icon">ScraperBot</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link"href="index.php"><span class="fas fa-home"></span>Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?a=option"><span class="fas fa-cogs"></span>Options</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?a=logs"><span class="fas fa-clipboard-list"></span>Logs</a>
        </li>
      </ul>
    </div>
  </nav>
  <?php if ($_GET["e"]) {
    if ($_GET["e"]=="000") {?>
      <div id="card" class="card border-success success">
        <div class="card-body">
          <p class="card-text"><?php echo $_err[$_GET["e"]]; ?></p>
          <button class="button btn btn-success success" type="button" name="okbutton" onclick="vanish()">Ok!</button>
        </div>
      </div>
    <?php } else { ?>
      <div id="card" class="card border-<?php echo "".($_GET["e"]=="001") ? "warning warning" : "danger danger"; ?>">
        <div class="card-header">Attenzione!</div>
        <div class="card-body">
          <p class="card-text"><?php echo $_err[$_GET["e"]]; ?></p>
          <button class="button btn btn-<?php echo "".($_GET["e"]=="001") ? "warning warning" : "danger danger"; ?>" type="button" name="okbutton" onclick="vanish()">Ok!</button>
        </div>
      </div>
    <?php } ?>
  <?php } ?>
<div class="content">
