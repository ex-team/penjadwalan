
  <?php foreach($rs_mp->result() as $mp) { ?>
    <option value="<?php echo $mp->kode?>" > <?php echo $mp->nama;?> </option>
  <?php } ?>
