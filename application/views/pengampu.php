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
              <button type="button" class="close" data-dismiss="alert">x</button>                
              <?php echo $this->session->flashdata('msg');?>
            </div>  
        <?php } ?>  

      <div class="row-fluid">
        <a href="<?php echo base_url() . 'web/pengampu_add';?>"> <button class="btn btn-primary pull-right"><i class="icon-plus"></i> Konten Baru</button></a>     

        <form class="form" method="POST" action="<?php echo base_url() . 'web/pengampu_search'?>">
          
          
   
          <label>Guru / Mapel</label>  
          <input type="text" name="search_query" value="<?php echo isset($search_query) ? $search_query : '' ;?>">  
          
          <div class="form">
            <button type="submit" class="btn">Cari</button>
            <a href="<?php echo base_url() . 'web/pengampu';?>"><button type="button" class="btn">Clear</button> </a>
          </div>
        </form>
		
		<?php if($rs_pengampu->num_rows() === 0):?>
		<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">�</button>             
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
					   <th>ID</th>
                       <th>Mapel</th>
                       <th>Guru</th>
                       <th>Kelas</th>

                       <th style="width: 65px;"></th>
                    </tr>
                 </thead>
                 <tbody>
  
                 <?php 
                   $i =  intval($start_number) + 1;
                   foreach ($rs_pengampu->result() as $pengampu) { ?>
                   <tr<?php echo ' id="row_'.$pengampu->id_pengampu . '"';?>>
                 <td><?php echo str_pad((int)$i,3,0,STR_PAD_LEFT);?></td> 
                 <td><?php echo $pengampu->id_pengampu;?></td>  
                      <td><?php echo $pengampu->nama_mapel;?></td>                    
                      <td><?php echo $pengampu->nama_guru;?></td>
                      <td><?php echo $pengampu->kelas;?></td>
                      
                      
                      <td>
                        <a href="<?php echo base_url() . 'web/pengampu_edit/' .$pengampu->id_pengampu;?>" class="btn btn-small"><i class="icon-pencil"></i></a>
                        <a class="btn btn-small" onClick="delete_row('web/pengampu_delete/', <?php echo $pengampu->id_pengampu ?>)" ><i class="icon-trash"></i></a>
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