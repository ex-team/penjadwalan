<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
          <h4 class="text-center">Ubah Guru</h4>
      </div>


          <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>Id Guru</label>
                        <input type="text" class="form-control" name="id_guru" value="<?php echo $data_guru['id_guru'] ?>">
                    </div>

                        <div class="form-group">
                            <label>Nama Guru</label>
                            <input type="text" class="form-control" name="nama_guru" value="<?php echo $data_guru['nama_guru'] ?>">
                        </div>

                        <div class="form-group">
                            <label>Password Guru</label>
                            <input type="text" class="form-control" name="password_guru" value="<?php echo $data_guru['password_guru'] ?>">
                        </div>

                        <div class="form-group">
                            <label>NIP</label>
                            <input type="text" class="form-control" name="NIP" value="<?php echo $data_guru['NIP'] ?>">
                        </div>

                        <div class="form-group">
                            <label>Bidang</label>
                            <input type="text" class="form-control" name="Bidang" value="<?php echo $data_guru['Bidang'] ?>">
                        </div>
                            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Untuk Mengubah Data Ini?')">Simpan</button>
                           <a href="<?php echo base_url('data_guru') ?>" class="btn btn-danger">Cancel</a>
                </form>
             </div>
          </div>
</div>
</div>
