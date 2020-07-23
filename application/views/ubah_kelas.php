<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
          <h4 class="text-center">Ubah Kelas</h4>
      </div>


          <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>Id Kelas</label>
                        <input type="text" class="form-control" name="id_kelas" value="<?php echo $data_kelas['id_kelas'] ?>">
                    </div>

                        <div class="form-group">
                            <label>Nama Kelas</label>
                            <input type="text" class="form-control" name="nama_kelas" value="<?php echo $data_kelas['nama_kelas'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Password Kelas</label>
                            <input type="text" class="form-control" name="password_kelas" value="<?php echo $data_kelas['password_kelas'] ?>">
                        </div>

                            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Untuk Mengubah Data Ini?')">Simpan</button>
                           <a href="<?php echo base_url('data_kelas') ?>" class="btn btn-danger">Cancel</a>
                </form>
             </div>
          </div>
</div>
</div>
