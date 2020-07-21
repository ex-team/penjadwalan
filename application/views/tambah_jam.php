 <div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h4 class="text-center">Tambah Jam</h4>
      </div>
      
      <div class="card-body">
          <form method="post">
            <div class="form-group">
        
              <label>Id Jam</label>
              <input type="text" class="form-control" name="id_jam">
              <label>Nama Jam</label>
              <input type="text" class="form-control" name="nama_jam">
              <label>Keterangan</label>
              <input type="text" class="form-control" name="keterangan">
              
            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Data Ini Sudah Benar?')">Simpan</button>
            <a href="<?php echo base_url('data_jam') ?>" class="btn btn-danger">Cancel</a>
          </form>
        </div>
      </div>
  </div>
</div>