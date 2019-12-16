<form action="<?php echo "".($_GET["a"]=="modify") ? "modify.php?id=".$_GET["id"] : "add.php"; ?>" method="post">
  <div class="row">
    <img id="previewimg" class="col img-thumbnail" src="<?php echo "".($_GET["a"]=="modify") ? $series[$_GET["id"]]->image : "_general/film-poster-placeholder.png"; ?>" alt="">
    <div class="col imgposter">
      <div class="form-group row">
        <label for="previewitem" class="col-form-label">Poster</label>
        <div class="col">
          <input id="previewitem"class="form-control" required placeholder="es. http://image.com/your/img.jpg" type="text" name="Poster" onchange="printimg()" value="<?php echo "".($_GET["a"]=="modify") ? $series[$_GET["id"]]->image : ""; ?>">
        </div>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="title" class="col-form-label">Title</label>
    <div class="col">
      <input id="title" class="form-control" required placeholder="es. Title" type="text" name="Title" value="<?php echo "".($_GET["a"]=="modify") ? $series[$_GET["id"]]->name : ""; ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="downloadlink" class="col-form-label">Download link</label>
    <div class="col">
      <input id="downloadlink"class="form-control" required placeholder="es. http://link.of.download.com/series" type="text" name="Link" value="<?php echo "".($_GET["a"]=="modify") ? $series[$_GET["id"]]->link_downloads : ""; ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="savefolder" class="col-form-label">Check Path</label>
    <div class="col">
      <input id="savefolder"class="form-control" required placeholder="es. Name/of/the Series/season 01/" type="text" name="SaveFolder" pattern="(([A-Z]:\\|\/)?[a-zA-Z0-9 \W]+(\/|\\))+" value="<?php echo "".($_GET["a"]=="modify") ? $series[$_GET["id"]]->save_folder : ""; ?>">
      <small class="form-text text-muted">Where the downloaded files can be found. Must end with &frasl; or \</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="downfolder" class="col-form-label">Save Path</label>
    <div class="col">
      <input id="downfolder"class="form-control" required placeholder="es. Name/of/the Series/season 01/" type="text" name="DownFolder" pattern="(([A-Z]:\\|\/)?[a-zA-Z0-9 \W]+(\/|\\))+" value="<?php echo "".($_GET["a"]=="modify") ? $series[$_GET["id"]]->save_folder : ""; ?>">
      <small class="form-text text-muted">Where download the files. Must end with &frasl; or \</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="thetvdbId" class="col-form-label"><a href="https://www.thetvdb.com/">The TV Database</a> ID</label>
    <div class="col">
      <input id="thetvdbId"class="form-control" required placeholder="es. 123456" type="text" name="thetvdbId" value="<?php echo "".($_GET["a"]=="modify") ? $series[$_GET["id"]]->the_tvdb_id : ""; ?>">
    </div>
  </div>
  <a href="index.php?e=001" class="btn btn-warning"><span class="fas fa-times"></span>Cancel</a>
  <button type="reset" value="reset" class="btn btn-secondary"><span class="fas fa-undo"></span>Reset</button>
  <button type="submit" class="btn btn-success"><?php echo "".($_GET["a"]=="modify") ? "<span class=\"fas fa-pen\"></span>Modify" : "<span class=\"fas fa-plus\"></span>Add"; ?></button>
</form>
