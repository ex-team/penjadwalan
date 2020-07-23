<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <h3>Guru</h3>
                    </div>
                    <div class="col-md-8 text-right">
                        <a href="<?php echo base_url("data_guru/tambah") ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Guru</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="datatables" class="datatables table table-striped table-bordered">
                    <thead class="background_atribut">
                        <th class="text-center">Id Guru</th>
                        <th class="text-center">Nama Guru</th>
                        <th class="text-center">Password Guru</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Bidang</th>
                        <th class="text-center">Id Mapel</th>
                        <th class="text-center">Action</th>
            
                    </thead>
                    <tbody>
                        <?php foreach ($guru as $key => $value): ?>
                            <tr>
                                <td class="text-center"><?php echo $value["id_guru"]; ?></td>
                                <td class="text-center"><?php echo $value["nama_guru"]; ?></td>
                                <td class="text-center"><?php echo $value["password_guru"]; ?></td>
                                <td class="text-center"><?php echo $value["NIP"]; ?></td>
                                <td class="text-center"><?php echo $value["Bidang"]; ?></td>
                                <td class="text-center"><?php echo $value["id_mapel"]; ?></td>
                                <td class="text-center">
                                    <a href="<?php echo base_url("data_guru/ubah/".$value['id_guru'])?>" class="btn btn-warning btn-sm">
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