		
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
                      
                       <th>Nama</th>
                       <th>SKS</th>
                       <th>Semester</th>
                                            
                       <th style="width: 65px;"></th>
                    </tr>
                 </thead>
                 <tbody>
  
                 <?php 
                   $i =  intval($start_number) + 1;
                   foreach ($rs_mapel->result() as $mapel) { ?>
                   <tr>
                      <td><?php echo str_pad((int)$i,2,0,STR_PAD_LEFT);?></td>    
                                        
                      <td><?php echo $mapel->nama_mapel;?></td>
                      <td><?php echo $mapel->sks;?></td>
                      <td><?php echo $mapel->semester;?></td>
                                          
                      
                      <td>
                        <a href="<?php echo base_url() . 'web/mapel_edit/' .$mapel->id_mapel;?>" class="btn btn-small"><i class="icon-pencil"></i></a>
                        <a href="<?php echo base_url() . 'web/mapel_delete/' .$mapel->id_mapel;?>" class="btn btn-small" onClick="return confirm('Anda yakin ingin menghapus data ini?')" ><i class="icon-trash"></i></a>
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
        
