<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <h3>Jadwal</h3>
                    </div>
                    <div class="col-md-8 text-right">
                        <a href="<?php echo base_url("data_jadwal/tambah") ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Jadwal</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="datatables" class="datatables table table-striped table-bordered">
                    <thead class="background_atribut">
                        <th class="text-center">Id Jadwal</th>
                        <th class="text-center">Id Guru</th>
                        <th class="text-center">Id Mapel</th>
                        <th class="text-center">Id Kelas</th>
                        <th class="text-center">Id Ruang</th>
                        <th class="text-center">Id Hari</th>
                        <th class="text-center">Id Jam</th>
                        <th class="text-center">action</th>
            
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal_tb as $key => $value): ?>
                            <tr>
                                <td class="text-center"><?php echo $key+1; ?> </td>
                                <td class="text-center"><?php echo $value["id_guru"]; ?></td>
                                <td class="text-center"><?php echo $value["id_mapel"]; ?></td>
                                <td class="text-center"><?php echo $value["id_kelas"]; ?></td>
                                <td class="text-center"><?php echo $value["id_ruang"]; ?></td>
                                <td class="text-center"><?php echo $value["id_hari"]; ?></td>
                                <td class="text-center"><?php echo $value["id_jam"]; ?></td>

                                <td class="text-center">
                                    <a href="<?php echo base_url("data_jadwal/ubah/".$value['id_jadwal'])?>" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?php echo base_url("data_jadwal/hapus/".$value['id_jadwal'])?>" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>

                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?> 
                    </tbody> 
                </table>
            </div>        
</div>
 

            <script>
                    $(document).ready(function() {
                        var flashdata = '<?php echo $this->session->flashdata('pesan'); ?>';

                        if (flashdata != "")
                        {
                            Swal.fire({
                                title: 'Data Guru',
                                text: flashdata,
                                icon: flashdata.includes("berhasil") ? 'success' : 'error',
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false
                            });
                        }

                        $('.btn-danger').on('click', function(e) {
                            e.preventDefault();

                            var href = $(this).attr('href');

                            Swal.fire({
                                title: 'Apakah Anda Yakin?',
                                text: 'Data Guru akan dihapus',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonClass: 'btn btn-primary btn-sm',
                                cancelButtonClass: 'btn btn-danger btn-sm',
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak',
                                buttonsStyling: false
                            }).then((result) => {
                                if (result.value) {
                                    document.location.href = href;
                                }
                            })
                        });

                        $(".btnubah").on("click", function(){
                            var id = $(this).attr("idnya");
                            $.ajax({
                                type:'POST',
                                url:'<?php echo base_url("M_guru/detail_guru") ?>',
                                data: 'id_guru='+id,
                                dataType: 'json',
                                success:function(hasil)
                                {
                                    $("#modalubah input[name=nama_guru").val(hasil.nama_guru);
                                    $("#modalubah form").attr("action", "<?php echo base_url("data_guru/ubah/") ?>"+id);
                                }
                            })
                        })
                    })
              </script>