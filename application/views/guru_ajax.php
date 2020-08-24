          <div class="pagination" id="ajax_paging">
              <ul>
                  <?php echo $this->pagination->create_links();?>
              </ul>
          </div>           

          <div class="widget-content">            
              <table class="table table-striped table-bordered">
                 <thead>
                    <tr>
					   <th>ID</th>
                       <th>NIP</th>
                       <th>Nama</th>
                       <th>Telp</th>
                       <th style="width: 65px;"></th>
                    </tr>
                 </thead>
                 <tbody>
  
                 <?php 
                   $i =  intval($start_number) + 1;
                   foreach ($rs_guru->result() as $guru) { ?>
                   <tr>
					  <td><?php echo str_pad((int)$i,2,0,STR_PAD_LEFT);?></td> 
                      <td><?php echo $guru->NIP;?></td>                    
                      <td><?php echo $guru->nama_guru;?></td>
                      <td><?php echo $guru->telp;?></td>                   
                      
                      <td>
                        <a href="<?php echo base_url() . 'web/guru_edit/' .$guru->id_guru;?>" class="btn btn-small"><i class="icon-pencil"></i></a>
                        <a href="<?php echo base_url() . 'web/guru_delete/' .$guru->id_guru;?>" class="btn btn-small" onClick="return confirm('Anda yakin ingin menghapus data ini?')" ><i class="icon-trash"></i></a>
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