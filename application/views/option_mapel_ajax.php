
  <?php foreach($rs_mapel->result() as $mapel) { ?>
    <option value="<?php echo $mapel->id_mapel?>" > <?php echo $mapel->nama_mapel;?> </option>
  <?php } ?>
