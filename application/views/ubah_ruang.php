<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
          <h4 class="text-center">Ubah Ruang</h4>
      </div>


          <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>Id Ruang</label>
                        <input type="text" class="form-control" name="id_ruang" value="<?php echo $data_ruang['id_ruang'] ?>">
                    </div>

                        <div class="form-group">
                            <label>Nama Ruang</label>
                            <input type="text" class="form-control" name="nama_ruang" value="<?php echo $data_ruang['nama_ruang'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Kapasitas</label>
                            <input type="text" class="form-control" name="kapasitas" value="<?php echo $data_ruang['kapasitas'] ?>">
                        </div>

                            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Untuk Mengubah Data Ini?')">Simpan</button>
                           <a href="<?php echo base_url('data_ruang') ?>" class="btn btn-danger">Cancel</a>
                </form>
             </div>
          </div>
</div>
</div>
