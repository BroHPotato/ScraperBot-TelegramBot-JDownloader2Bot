<form action="option.php" method="post">
  <div class="contenttable table-responsive-lg">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Options</th>
          <th>Values</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($config as $key => $value): ?>
          <tr>
            <td><?php echo $key; ?></td>
            <td><input type="text" name="<?php echo $key; ?>" value="<?php echo "".($value) ? $value : 0; ?>"></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <a href="index.php?e=001" class="btn btn-warning"><span class="fas fa-times"></span>Cancel</a>
  <button type="reset" value="reset" class="btn btn-secondary"><span class="fas fa-undo"></span>Reset</button>
  <button type="submit" class="btn btn-success"><span class="fas fa-pen"></span>Modify</button>
</form>
