<?php foreach($rs_hari->result() as $hari) {} ?>
<div class="content">
   <div class="header">
      <h1 class="page-title"><?php echo $page_title;?></h1>
   </div>
   <ul class="breadcrumb">
      <li><a href="<?php echo base_url();?>">Beranda</a> <span class="divider">/</span></li>
      <li><a href="<?php echo base_url();?>web/hari">Modul Hari</a> <span class="divider">/</span></li>
      <li class="active">Edit Data</li>
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
        <label>ID Hari</label>
            <input id="id_hari" type="text" value="<?php echo $hari->id_hari;?>" name="id_hari" class="input-xsmall" />                     
            <label>Nama Hari</label>
            <input id="nama_hari" type="text" value="<?php echo $hari->nama_hari;?>" name="nama_hari" class="input-xsmall" />
            
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save</button>
              <a href="<?php echo base_url() .'web/hari'; ?>"><button type="button" class="btn">Cancel</button></a>
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