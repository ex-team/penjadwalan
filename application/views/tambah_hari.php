 <div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h4 class="text-center">Tambah Hari</h4>
      </div>
      
      <div class="card-body">
          <form method="post">
            <div class="form-group">
        
              <label>Id Hari</label>
              <input type="text" class="form-control" name="id_hari">
              <label>Nama Hari</label>
              <input type="text" class="form-control" name="nama_hari">
              
            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Data Ini Sudah Benar?')">Simpan</button>
            <a href="<?php echo base_url('data_hari') ?>" class="btn btn-danger">Cancel</a>
          </form>
        </div>
      </div>
  </div>
</div>