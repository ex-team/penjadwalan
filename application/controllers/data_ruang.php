<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_ruang extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('M_ruang');
  }

  public function index()
  {
    $data['ruang'] = $this->M_ruang->tampil_ruang();

    $this->load->view('v_header');
    $this->load->view('tampil_ruang', $data);
    $this->load->view('v_footer');    
  }

  function tambah()
  {
    //mendapatkan data dari formulir
    $inputan = $this->input->post();

    //jika di simpan
    if ($inputan)
    {
      //a. model Matribut menjalankan method simpan (inputan)
      $this->M_ruang->simpan_mapel($inputan);

      //set flashdata sebagai pesan berhasil simpan
      $this->session->set_flashdata('pesan', 'Data ruang berhasil ditambahkan');

      // c. meredirect tampilan
      redirect('data_ruang', 'refresh');
    }

    //menyajikan formulir tambah atribut
    $this->load->view('v_header');
    $this->load->view('tambah_ruang');
    $this->load->view('v_footer');
  }

  function ubah($id_ruang)
  {
    $data['data_ruang']= $this->M_ruang->detail_ruang($id_ruang);

    //menerima inputan formulir
    $inputan = $this->input->post();

    //jika di simpan
    if ($inputan)
    {
      //model Matribut menjalakan fungsi ubah_atribut
      $this->M_ruang->ubah_ruang($inputan , $id_ruang);

      //set flashdata untuk pesan berhasil ubah
      $this->session->set_flashdata('pesan', 'Data ruang berhasil diubah');

      //direct tampil atribut
      redirect('data_ruang', 'refresh');
    }
    
    //6. menyajikan formulir ubah atribut
    $this->load->view('v_header');
    $this->load->view('ubah_ruang', $data);
    $this->load->view('v_footer');
  }

}

/* End of file Atribut.php */
/* Location: ./application/controllers/Atribut.php */