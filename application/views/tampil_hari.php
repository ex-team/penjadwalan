<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <h3>Hari</h3>
                    </div>
                    <div class="col-md-8 text-right">
                        <a href="<?php echo base_url("data_hari/tambah") ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Hari</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="datatables" class="datatables table table-striped table-bordered">
                    <thead class="background_atribut">
                        <th class="text-center">Id Hari</th>
                        <th class="text-center">Nama Hari</th>
                        
                        <th class="text-center">Action</th>
                    </thead>
                    <tbody>
                        <?php foreach ($hari as $key => $value): ?>
                            <tr>
                                <td class="text-center"><?php echo $value["id_hari"]; ?></td>
                                <td class="text-center"><?php echo $value["nama_hari"]; ?></td>
                            
                                <td class="text-center">
                                    <a href="<?php echo base_url("data_hari/ubah/".$value['id_hari'])?>" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    
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
                                title: 'Data Hari',
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
                                text: 'Data Hari akan dihapus',
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
                                url:'<?php echo base_url("M_hari/detail_hari") ?>',
                                data: 'id_mapel='+id,
                                dataType: 'json',
                                success:function(hasil)
                                {
                                    $("#modalubah input[name=nama_hari").val(hasil.nama_kelas);
                                    $("#modalubah form").attr("action", "<?php echo base_url("data_hari/ubah/") ?>"+id);
                                }
                            })
                        })
                    })
              </script>