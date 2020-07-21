<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
          <h4 class="text-center">Ubah Hari</h4>
      </div>


          <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>Id Hari</label>
                        <input type="text" class="form-control" name="id_hari" value="<?php echo $data_hari['id_hari'] ?>">
                    </div>

                        <div class="form-group">
                            <label>Nama Hari</label>
                            <input type="text" class="form-control" name="nama_hari" value="<?php echo $data_hari['nama_hari] ?>">
                        </div>
                        
                            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Untuk Mengubah Data Ini?')">Simpan</button>
                           <a href="<?php echo base_url('data_guru') ?>" class="btn btn-danger">Cancel</a>
                </form>
             </div>
          </div>
</div>
</div>
