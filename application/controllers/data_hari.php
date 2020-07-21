<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_hari extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('M_hari');
  }

  public function index()
  {
    $data['hari'] = $this->M_hari->tampil_hari();

    $this->load->view('v_header');
    $this->load->view('tampil_hari', $data);
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
      $this->M_hari->simpan_mapel($inputan);

      //set flashdata sebagai pesan berhasil simpan
      $this->session->set_flashdata('pesan', 'Data hari berhasil ditambahkan');

      // c. meredirect tampilan
      redirect('data_hari', 'refresh');
    }

    //menyajikan formulir tambah atribut
    $this->load->view('v_header');
    $this->load->view('tambah_hari');
    $this->load->view('v_footer');
  }

  function ubah($id_hari)
  {
    $data['data_hari']= $this->M_hari->detail_har($id_hari);

    //menerima inputan formulir
    $inputan = $this->input->post();

    //jika di simpan
    if ($inputan)
    {
      //model Matribut menjalakan fungsi ubah_atribut
      $this->M_hari->ubah_hari($inputan , $id_hari);

      //set flashdata untuk pesan berhasil ubah
      $this->session->set_flashdata('pesan', 'Data hari berhasil diubah');

      //direct tampil atribut
      redirect('data_hari', 'refresh');
    }
    
    //6. menyajikan formulir ubah atribut
    $this->load->view('v_header');
    $this->load->view('ubah_hari', $data);
    $this->load->view('v_footer');
  }

}

/* End of file Atribut.php */
/* Location: ./application/controllers/Atribut.php */