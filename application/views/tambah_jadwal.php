 <div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h4 class="text-center">Tambah Jadwal</h4>
      </div>
      
      <div class="card-body">
          <form method="post">
            <div class="form-group">
        
              <label>Id Guru</label>
              <input type="text" class="form-control" name="id_guru">
              <label>Id Mapel</label>
              <input type="text" class="form-control" name="id_mapel">
              <label>Id Kelas</label>
              <input type="text" class="form-control" name="id_kelas">
              <label>Id Ruang</label>
              <input type="text" class="form-control" name="id_ruang">
              <label>Id Hari</label>
              <input type="text" class="form-control" name="id_hari">
              <label>Id Jam</label>
              <input type="text" class="form-control" name="id_jam">
            </div>
            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Data Ini Sudah Benar?')">Simpan</button>
            <a href="<?php echo base_url('data_jadwal') ?>" class="btn btn-danger">Cancel</a>
          </form>
        </div>
      </div>
  </div>
</div>