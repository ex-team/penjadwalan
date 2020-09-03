<div class="content">
   <div class="header">
      <h1 class="page-title"><?php echo $page_title;?></h1>
   </div>
   <ul class="breadcrumb">
      <li><a href="<?php echo base_url();?>">Beranda</a> <span class="divider">/</span></li>
      <li><a href="<?php echo base_url();?>web/matapelajaran">Modul Mata Pelajaran</a> <span class="divider">/</span></li>
      <li class="active">Tambah Data</li>
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
            <label>Kode Mata Pelajaran</label>
            <input id="kode_mp" type="text" value="<?php echo set_value('kode_mp');?>" name="kode_mp" class="input-xlarge" />
            
            <label>Nama</label>
            <input id="nama" type="text" value="<?php echo set_value('nama');?>" name="nama" class="input-xlarge" />
            
            <label>Category</label>
            <select name="jenis" class="input-xlarge">            
              <option value="TEORI" <?php echo set_select('jenis','TEORI');?> /> TEORI
              <option value="PRAKTIKUM" <?php echo set_select('jenis','PRAKTIKUM');?> /> PRAKTIKUM            
            </select>
            
            <label>Beban Pelajaran</label>
            <input id="beban" type="text" value="<?php echo set_value('beban');?>" name="beban" class="input-xsmall" />
            
            <label>Semester</label>
            <input id="semester" type="text" value="<?php echo set_value('semester');?>" name="semester" class="input-xsmall" />       
            
			
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save</button>
              <a href="<?php echo base_url() .'web/matapelajaran'; ?>"><button type="button" class="btn">Cancel</button></a>
            </div>
        </form>

         

      </div>
   </div>
</div>      