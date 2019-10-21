<?php foreach ($logs as $key => $value) {?>
  <p class="loglist">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapse<?php echo $key; ?>" aria-expanded="false" aria-controls="collapse<?php echo $key; ?>">
    <?php echo $key; ?>
    </button>
  </p>
  <div class="collapse" id="collapse<?php echo $key; ?>">
    <div class="card card-body">
      <pre>
        <?php echo $value; ?>
      </pre>
    </div>
  </div>
<?php } ?>
