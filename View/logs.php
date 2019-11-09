<?php foreach ($logs as $key => $value) {?>
  <p class="loglist">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse<?php echo $key; ?>" aria-expanded="false" aria-controls="collapse<?php echo $key; ?>">
    <?php echo $key; ?>
  </button>
  <a href="index.php?a=deletelogs&id=<?php echo $key ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the log: <?php echo $key ?> ?')" value="<?php echo $key; ?>">Delete</a>
  </p>
  <div class="collapse" id="collapse<?php echo $key; ?>">
    <div class="card card-body">
      <pre><?php echo $value; ?></pre>
    </div>
  </div>
<?php } ?>
