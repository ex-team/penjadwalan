<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_kelas extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('M_kelas');
  }

  public function index()
  {
    $data['kelas'] = $this->M_kelas->tampil_kelas();

    $this->load->view('v_header');
    $this->load->view('tampil_kelas', $data);
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
      $this->M_kelas->simpan_kelas($inputan);

      //set flashdata sebagai pesan berhasil simpan
      $this->session->set_flashdata('pesan', 'Data kelas berhasil ditambahkan');

      // c. meredirect tampilan
      redirect('data_kelas', 'refresh');
    }

    //menyajikan formulir tambah atribut
    $this->load->view('v_header');
    $this->load->view('tambah_kelas');
    $this->load->view('v_footer');
  }

  function ubah($inputan)
  {
    $data['data_kelas']= $this->M_kelas->detail_kelas($inputan);

    //menerima inputan formulir
    $inputan = $this->input->post();

    //jika di simpan
    if ($inputan)
    {
      //model Matribut menjalakan fungsi ubah_atribut
      $this->M_kelas->ubah_kelas($inputan , $id_kelas);

      //set flashdata untuk pesan berhasil ubah
      $this->session->set_flashdata('pesan', 'Data kelas berhasil diubah');

      //direct tampil atribut
      redirect('data_kelas', 'refresh');
    }
    
    //6. menyajikan formulir ubah atribut
    $this->load->view('v_header');
    $this->load->view('ubah_kelas', $data);
    $this->load->view('v_footer');
  }

}

/* End of file Atribut.php */
/* Location: ./application/controllers/Atribut.php */