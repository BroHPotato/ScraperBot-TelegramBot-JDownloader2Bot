<div class="activeservices">
  <table class="table table-sm table-striped table-hover">
    <thead>
      <tr>
        <th colspan="2">Active Options</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><span class="fab fa-telegram"></span>Telegram<?php echo "".($config["telegram"]) ?  "<span class=\"badge badge-success\">Enable</span>" : "<span class=\"badge badge-danger\">Disable</span>"; ?></td>
        <td><span class="fas fa-download"></span>JDownloader 2<?php echo "".($config["jdownloader"]) ? "<span class=\"badge badge-success\">Enable</span>" : "<span class=\"badge badge-danger\">Disable</span>"; ?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="submenu">
  <a href="index.php?a=add" class="btn btn-primary"><span class="fas fa-plus"></span>Add</a>
  <a href="scraperbot.php" class="btn btn-info"><span class="fas fa-terminal"></span>Run now</a>
</div>
<div class="contenttable table-responsive-lg">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Poster</th>
        <th>Name Series</th>
        <th>Download Link</th>
        <th>TheTVDB ID</th>
        <th>Save Path</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($entry as $key => $value) { ?>
        <tr>
          <td class="poster"><img src="<?php echo $value["Poster"] ?>" alt="" class="img-thumbnail"></td>
          <td><?php echo $value["Title"] ?></td>
          <td><?php echo $value["Link"] ?></td>
          <td><?php echo $value["thetvdbId"] ?></td>
          <td><?php echo $value["SaveFolder"] ?></td>
          <td><a href="index.php?a=delete&id=<?php echo $key ?>" class="btn btn-danger d-inline"><span class="fas fa-times d-inline"></span>Delete</a></td>
          <td><a href="index.php?a=modify&id=<?php echo $key ?>" class="btn btn-info d-inline"><span class="fas fa-pen d-inline"></span>Modify</a></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
