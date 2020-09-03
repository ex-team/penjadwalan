<div class="content">
   <div class="header">
      <h1 class="page-title"><?php echo $page_title;?></h1>
   </div>
   <ul class="breadcrumb">
      <li><a href="<?php echo base_url();?>">Beranda</a> <span class="divider">/</span></li>
      <li class="active"><?php echo $page_title;?></li>
   </ul>

   <div class="container-fluid">
         <?php if($this->session->flashdata('msg')) { ?>                        
            <div class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">�</button>                
              <?php echo $this->session->flashdata('msg');?>
            </div>  
        <?php } ?>  

      <div class="row-fluid">
        <a href="<?php echo base_url() . 'web/matapelajaran_add';?>"> <button class="btn btn-primary pull-right"><i class="icon-plus"></i> Data Baru</button></a>     
        
        <form class="form-inline" method="POST" action="<?php echo base_url() . 'web/matapelajaran_search'?>">
          <input type="text" placeholder="Nama" name="search_query" value="<?php echo isset($search_query) ? $search_query : '' ;?>">
          <button type="submit" class="btn">Cari</button>
          <a href="<?php echo base_url() . 'web/matapelajaran';?>"><button type="button" class="btn">Clear</button> </a>
        </form>
		
		<?php if($rs_mp->num_rows() === 0):?>
		<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">x</button>             
			Tidak ada data.
        </div>  
		<?php else: ?> 
		<div id="content_ajax">
          <div class="pagination" id="ajax_paging">
              <ul>
                  <?php echo $this->pagination->create_links();?>
              </ul>
          </div>           

          <div class="widget-content">
            
              <table class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Kode Mata Pelajaran</th>
                       <th>Nama</th>
                       <th>Beban Pelajaran</th>
                       <th>Semester</th>
                       <th>Jenis</th>                       
                       <th style="width: 65px;"></th>
                    </tr>
                 </thead>
                 <tbody>
  
                 <?php 
                   $i =  intval($start_number) + 1;
                   foreach ($rs_mp->result() as $mp) { ?>
                   <tr>
                      <td><?php echo str_pad((int)$i,2,0,STR_PAD_LEFT);?></td>    
                      <td><?php echo $mp->kode_mp;?></td>                    
                      <td><?php echo $mp->nama;?></td>
                      <td><?php echo $mp->beban;?> x 45 menit</td>
                      <td><?php echo $mp->semester;?></td>
                      <td><?php echo $mp->jenis;?></td>                      
                      
                      <td>
                        <a href="<?php echo base_url() . 'web/matapelajaran_edit/' .$mp->kode;?>" class="btn btn-small"><i class="icon-pencil"></i></a>
                        <a href="<?php echo base_url() . 'web/matapelajaran_delete/' .$mp->kode;?>" class="btn btn-small" onClick="return confirm('Anda yakin ingin menghapus data ini?')" ><i class="icon-trash"></i></a>
                      </td>
                   </tr>
                 <?php $i++;} ?>
                    
                 </tbody>
              </table>
           </div>
           
  
           <div class="pagination" id="ajax_paging">
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