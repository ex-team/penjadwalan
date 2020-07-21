<div class="jumbotron text-center">
  <img class="img-fluid" src="<?php echo base_url('assets/img/logo.jpg') ?>" alt="User profile picture">
  <h2> SELAMAT DATANG </h2>
  <h4> Di Sistem Penjadwalan Mata Pelajaran</h4>
  <h4> SMA N 1 Candiroto</h4>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="img-thumbnail">
          <img class="img-fluid" src="<?php echo base_url('assets/img/sma.jpg') ?>" alt="User profile picture">
        </div>
        <br>
        <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>
        <br>
        <div class="list-group-item">
          <strong>Bacalah Petunjuk Penggunaan Terlebih Dahulu !</strong>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3>Petunjuk Penggunaan</h3>
      </div>
      <div class="card-body">
        <p>Berikut ini adalah cara penggunaan aplikasi ini :</p>
        <ol>
            <li>
              <p>Menu 'ATRIBUT', pada menu ini anda dapat melihat atribut apa saja yang di gunakan untuk menentukan penerima bantuan, anda juga dapat menambah atribut jika di perlukan.</p>
            </li>
            <li>
              <p>Menu 'NILAI ATRIBUT', pada menu ini anda dapata melihat nilai atribut apa saja yang menjadi parameter penentu, anda juga dapat menambah nilai atribut jika di perlukan.</p>
            </li>
            <li>
              <p>Menu 'DATA TRAINING', pada menu ini anda dapat memasukkan data training atau data latih.</p>
            </li>
            <li>
              <p>Menu 'DATA TESTING', pada menu ini anda dapat melakukang testing dengan memasukkan data - data lalu lakukan prediksi. Aplikasi akan menampilkan hasilnya, LAYAK atau TIDAK LAYAK.</p>
            </li>
            <li>
              <p>Menu 'POHON KEPUTUSAN', pada menu in anda datap melihat rule yang terbentuk setelah data training atau data latih dimasukkan.</p>
            </li>
        </ol>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        var flashdata = '<?php echo $this->session->flashdata('pesan'); ?>';
        
        if (flashdata != "")
        {
            Swal.fire({
                title: 'Login',
                text: flashdata,
                icon: flashdata.includes("berhasil") ? 'success' : 'error',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false
            });
        }
    })
</script>