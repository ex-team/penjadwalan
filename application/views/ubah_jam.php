<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
          <h4 class="text-center">Ubah Jam</h4>
      </div>


          <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>Id Jam</label>
                        <input type="text" class="form-control" name="id_jam" value="<?php echo $data_jam['id_jam'] ?>">
                    </div>

                        <div class="form-group">
                            <label>Nama Jam</label>
                            <input type="text" class="form-control" name="nama_jam" value="<?php echo $data_jam['nama_jam'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" value="<?php echo $data_jam['keterangan'] ?>">
                        </div>

                            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Untuk Mengubah Data Ini?')">Simpan</button>
                           <a href="<?php echo base_url('data_jam') ?>" class="btn btn-danger">Cancel</a>
                </form>
             </div>
          </div>
</div>
</div>
