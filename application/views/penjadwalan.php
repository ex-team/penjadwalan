<style>
  .block {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 0px solid #CCCCCC;
    margin: 1em 0;
}
</style>
<div class="content">
   <div class="header">
      <h1 class="page-title"><?php echo $page_title;?></h1>
   </div>
   <ul class="breadcrumb">
      <li><a href="<?php echo base_url();?>">Beranda</a> <span class="divider">/</span></li>
      <li class="active"><?php echo $page_title;?></li>
   </ul>

   <div class="container-fluid">
         <?php if(isset($msg)) { ?>                        
            <div class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">x</button>                
              <?php echo $msg;?>
            </div>  
        <?php } ?>  

      <div class="row-fluid">
        
        <form class="form" method="POST" action="">
          <div class="block span6">
			<label>Semester</label>
			<select id = "semester" name="semester" class="input-xlarge">            
			  <option value="1" <?php echo isset($semester) ? ($semester === '1' ? 'selected':'') : '' ;?> /> GANJIL
			  <option value="0" <?php echo isset($semester) ? ($semester === '0' ? 'selected':'') : '' ;?> /> GENAP
			</select>
			  
			<label>Jumlah Populasi</label>  
			<input type="text" name="jumlah_populasi" value="<?php echo isset($jumlah_populasi) ? $jumlah_populasi : '10' ;?>">  
          </div>
          <div class="block span6">
            <label>Probabilitas Crossover</label>  
            <input type="text" name="probabilitas_crossover" value="<?php echo isset($probabilitas_crossover) ? $probabilitas_crossover: '0.80' ;?>">
            
            <label>Probabilitas Mutasi</label>  
            <input type="text" name="probabilitas_mutasi" value="<?php echo isset($probabilitas_mutasi)? $probabilitas_mutasi : '0.50' ;?>">

            <label>Jumlah Generasi</label>  
            <input type="text" name="jumlah_generasi" value="<?php echo isset($jumlah_generasi) ? $jumlah_generasi : '2000' ;?>">
          </div>
          <div class="form">
            <button type="submit" class="btn" onclick="ShowProgressAnimation();">Proses</button>
			
          </div>
        </form>
		
		<?php if($rs_jadwal->num_rows() !== 0):?>			
			<a href="<?php echo base_url();?>web/excel_report"> <button class="btn btn-primary pull-right"><i class="icon-plus"></i> Export to Excel</button></a>
			<br><br>
		<?php endif;?>
			
		<div id="loading-div-background">
		  <div id="loading-div" class="ui-corner-all">
			<img style="height:50px;width:50px;margin:30px;" src="<?php echo base_url()?>assets/images/please_wait.gif" alt="Loading.."/><br>PROCESSING<br>PLEASE WAIT
		  </div>
		</div>
		
		<?php if($rs_jadwal->num_rows() === 0):?>
		<!--
		<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">�</button>             
			Tidak ada data.
        </div>
		-->
		<?php else: ?> 
		<div id="content_ajax">
          
          <div class="pagination pull-right" id="ajax_paging">
              <ul>
                  <?php echo $this->pagination->create_links();?>
              </ul>
          </div>           

          <div class="widget-content">            
              <table class="table table-striped table-bordered">
                 <thead>
                    <tr>
					   <th>#</th>
                       <th>Hari</th>
                       <th>Sesi</th>
                       <th>Jam</th>
                       <th>Mapel</th>
                       <th>SKS</th>
                       <th>Semester</th>
                       <th>Kelas</th>
                       <th>Guru</th>
                     
                       
                    </tr>
                 </thead>
                 <tbody>
  
                 <?php 
                   $i =  1;
                   foreach ($rs_jadwal->result() as $jadwal) { ?>
                   <tr>
					  <td><?php echo $i;?></td>
                      <td><?php echo $jadwal->hari;?></td>
                      <td><?php echo $jadwal->sesi;?></td>
                      <td><?php echo $jadwal->jam_pelajaran;?></td>
                      <td><?php echo $jadwal->nama_mapel;?></td>
                      <td><?php echo $jadwal->sks;?></td>
                      <td><?php echo $jadwal->semester;?></td>
                      <td><?php echo $jadwal->kelas;?></td>
                      <td><?php echo $jadwal->guru;?></td>
                                        
                   </tr>
                 <?php $i++;} ?>
                    
                 </tbody>
              </table>
           </div>
           
          
           <div class="pagination pull-right" id="ajax_paging">
              <ul>
                  <?php echo $this->pagination->create_links();?>
              </ul>
          </div>           
        </div>
        <?php endif; ?>
         <footer>
            <hr />
            <p class="pull-right">Design by <a href="http://www.portnine.com" target="_blank">Portnine</a></p>
            <p>&copy; 2012 <a href="http://www.portnine.com" target="_blank">Portnine</a></p>
         </footer>
      </div>
   </div>
</div>