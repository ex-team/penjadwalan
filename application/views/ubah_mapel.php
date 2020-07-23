<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card card-primary card-outline">
      <div class="card-header">
          <h4 class="text-center">Ubah Mapel</h4>
      </div>


          <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>Id Mapel</label>
                        <input type="text" class="form-control" name="id_mapel" value="<?php echo $data_mapel['id_mapel'] ?>">
                    </div>

                        <div class="form-group">
                            <label>Nama Mapel</label>
                            <input type="text" class="form-control" name="nama_mapel" value="<?php echo $data_mapel['nama_mapel'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Semester</label>
                            <input type="text" class="form-control" name="semester" value="<?php echo $data_mapel['semester'] ?>">
                        </div>

                            <button class="btn btn-primary" onclick="return confirm('Apakah Anda Yakin Untuk Mengubah Data Ini?')">Simpan</button>
                           <a href="<?php echo base_url('data_mapel') ?>" class="btn btn-danger">Cancel</a>
                </form>
             </div>
          </div>
</div>
</div>
