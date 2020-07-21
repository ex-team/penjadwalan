 <div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h4 class="text-center">Tambah Guru</h4>
      </div>
      
      <div class="card-body">
          <form method="post">
            <div class="form-group">
        
              <label>Id Guru</label>
              <input type="text" class="form-control" name="id_guru">
              <label>Nama Guru</label>
              <input type="text" class="form-control" name="nama_guru">
              <label>Password Guru</label>
              <input type="text" class="form-control" name="password_guru">
              <label>NIP Guru</label>
              <input type="text" class="form-control" name="NIP">
              <label>Bidang</label>
              <input type="text" class="form-control" name="Bidang">
              <label>Id Mapel</label>
              <input type="text" class="form-control" name="id_mapel">
            </div>
            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Data Ini Sudah Benar?')">Simpan</button>
            <a href="<?php echo base_url('data_guru') ?>" class="btn btn-danger">Cancel</a>
          </form>
        </div>
      </div>
  </div>
</div>