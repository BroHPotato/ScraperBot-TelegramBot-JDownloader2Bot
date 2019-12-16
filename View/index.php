<div class="activeservices">
  <table class="table table-sm table-striped table-hover">
    <thead>
      <tr>
        <th colspan="3">Active Options</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><span class="fab fa-telegram"></span>Telegram<?php echo "".($config["telegram"]) ?  "<span class=\"badge badge-success\">Enable</span>" : "<span class=\"badge badge-danger\">Disable</span>"; ?></td>
        <td><span class="fas fa-tv"></span>TheTVDB<?php echo "".($config["thetvdb"]) ? "<span class=\"badge badge-success\">Enable</span>" : "<span class=\"badge badge-danger\">Disable</span>"; ?></td>
        <td><span class="fas fa-download"></span>JDownloader 2<?php echo "".($config["jdownloader"]) ? "<span class=\"badge badge-success\">Enable</span>" : "<span class=\"badge badge-danger\">Disable</span>"; ?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="submenu">
  <a href="index.php?a=add" class="btn btn-primary"><span class="fas fa-plus"></span>Add</a>
  <a href="run.php" class="btn btn-info"><span class="fas fa-terminal"></span>Run now</a>
</div>
<div class="contenttable table-responsive-lg">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Poster</th>
        <th>Name Series</th>
        <th>Dowloaded Episode</th>
        <th>Download Link</th>
        <th>TheTVDB ID</th>
        <th>Check Path</th>
        <th>Save Path</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($series as $key => $value) { ?>
        <tr>
          <td class="poster"><img src="<?php echo $value->image ?>" alt="" class="img-thumbnail"></td>
          <td><?php echo $value->name ?></td>
          <td><?php echo count($value->downloaded_episode) ?></td>
          <td><?php echo $value->link_downloads ?></td>
          <td><?php echo $value->the_tvdb_id ?></td>
          <td><?php echo $value->save_folder ?></td>
          <td><?php echo $value->download_folder ?></td>
          <td class="button-triplet">
            <a href="update.php?id=<?php echo $key ?>" class="btn btn-primary d-inline"><span class="fas fa-redo d-inline"></span>Update</a>
            <a href="index.php?a=modify&id=<?php echo $key ?>" class="btn btn-info d-inline"><span class="fas fa-pen d-inline"></span>Modify</a>
            <a href="index.php?a=delete&id=<?php echo $key ?>" class="btn btn-danger d-inline" onclick="return confirm('Are you sure you want to delete <?php echo $value->name ?>?')"><span class="fas fa-times d-inline"></span>Delete</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
