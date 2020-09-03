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
            <!--          
            <label>Semester</label>
            <select id = "semester_tipe" name="semester_tipe" class="input-xlarge" onchange="get_matapelajaran();">            
              <option value="1" <?php echo isset($semester_tipe) ? ($semester_tipe === '1' ? 'selected':'') : '' ;?> /> GANJIL
              <option value="0" <?php echo isset($semester_tipe) ? ($semester_tipe === '0' ? 'selected':'') : '' ;?> /> GENAP
            </select>
             -->           
            <label>Mata Pelajaran</label>
            <select name="kode_mp" class="input-xlarge" id="option_matapelajaran">
              <?php foreach($rs_mp->result() as $mp) { ?>
                <option value="<?php echo $mp->kode;?>" <?php echo $mp->kode === $pengampu->kode_mp ? 'selected':'' ;?> /> <?php echo $mp->nama;?>
              <?php } ?>            
            </select>
            
            <label>Guru</label>
            <select name="kode_guru" class="input-xlarge">
              <?php foreach($rs_guru->result() as $guru) { ?>
                <option value="<?php echo $guru->kode;?>" <?php echo $guru->kode === $pengampu->kode_guru ? 'selected':'' ;?> /> <?php echo $guru->nama;?>
              <?php } ?>
            </select>
            
            <label>Kelas</label>
            <input id="kelas" type="text" value="<?php echo $pengampu->kelas;?>" name="kelas" class="input-xsmall" />
            
            
            <label>Tahun Akademik</label>
            <select id="tahun_akademik" name="tahun_akademik" class="input-xlarge">
              <option value="2019-2020" <?php echo set_select('tahun_akademik','2019-2020');?> /> 2019-2020    
              <option value="2020-2021" <?php echo set_select('tahun_akademik','2020-2021');?> /> 2020-2021 
              <option value="2021-2022" <?php echo set_select('tahun_akademik','2021-2022');?> /> 2021-2022    
              <option value="2022-2023" <?php echo set_select('tahun_akademik','2022-2023');?> /> 2022-2023   
              <option value="2023-2024" <?php echo set_select('tahun_akademik','2023-2024');?> /> 2023-2024  
              <option value="2024-2025" <?php echo set_select('tahun_akademik','2024-2025');?> /> 2024-2025           
            </select>
			
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save</button>
              <a href="<?php echo base_url() .'web/pengampu'; ?>"><button type="button" class="btn">Cancel</button></a>
            </div>
        </form>

         

      </div>
   </div>
</div>      