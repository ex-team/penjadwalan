<?php foreach($rs_pengampu->result() as $pengampu) {} ?>

<div class="content">
   <div class="header">
      <h1 class="page-title"><?php echo $page_title;?></h1>
   </div>
   <ul class="breadcrumb">
      <li><a href="<?php echo base_url();?>">Beranda</a> <span class="divider">/</span></li>
      <li><a href="<?php echo base_url();?>web/pengampu">Modul Pengampu</a> <span class="divider">/</span></li>
      <li class="active">Ubah Data</li>
   </ul>
   
   <div class="container-fluid">
      <div class="row-fluid">
        <?php if(isset($msg)) { ?>                        
              <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">�</button>                
                <?php echo $msg;?>
              </div>  
        <?php } ?>   

        <form id="tab" method="POST" >
        

            <label>Mapel</label>
            <select name="id_mapel" class="input-xlarge" id="option_mapel">
              <?php foreach($rs_mk->result() as $mapel) { ?>
                <option value="<?php echo $mapel->id_mapel;?>" <?php echo $mapel->id_mapel === $pengampu->id_mapel ? 'selected':'' ;?> /> <?php echo $mapel->nama_mapel;?>
              <?php } ?>            
            </select>
            
            <label>Guru</label>
            <select name="id_guru" class="input-xlarge">
              <?php foreach($rs_guru->result() as $guru) { ?>
                <option value="<?php echo $guru->id_guru;?>" <?php echo $guru->id_guru === $pengampu->id_guru ? 'selected':'' ;?> /> <?php echo $guru->nama_guru;?>
              <?php } ?>
            </select>
            
            <label>Kelas</label>
            <input id="kelas" type="text" value="<?php echo $pengampu->kelas;?>" name="kelas" class="input-xsmall" />
            
            
            
			
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save</button>
              <a href="<?php echo base_url() .'web/pengampu'; ?>"><button type="button" class="btn">Cancel</button></a>
            </div>
        </form>

        <footer>
          <hr />
          <p class="pull-right">Design by <a href="http://www.portnine.com" target="_blank">Portnine</a></p>
          <p>&copy; 2012 <a href="http://www.portnine.com" target="_blank">Portnine</a></p>
        </footer>

      </div>
   </div>
</div>      