<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_guru extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('M_guru');
  }

  public function index()
  {
    $data['guru'] = $this->M_guru->tampil_guru();

    $this->load->view('v_header');
    $this->load->view('tampil_guru', $data);
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
      $this->M_guru->simpan_guru($inputan);

      //set flashdata sebagai pesan berhasil simpan
      $this->session->set_flashdata('pesan', 'Data guru berhasil ditambahkan');

      // c. meredirect tampilan
      redirect('data_guru', 'refresh');
    }

    //menyajikan formulir tambah atribut
    $this->load->view('v_header');
    $this->load->view('tambah_guru');
    $this->load->view('v_footer');
  }

  function ubah($id_guru)
  {
    $data['data_guru']= $this->M_guru->detail_guru($id_guru);

    //menerima inputan formulir
    $inputan = $this->input->post();

    //jika di simpan
    if ($inputan)
    {
      //model Matribut menjalakan fungsi ubah_atribut
      $this->M_guru->ubah_guru($inputan , $id_guru);

      //set flashdata untuk pesan berhasil ubah
      $this->session->set_flashdata('pesan', 'Data guru berhasil diubah');

      //direct tampil atribut
      redirect('data_guru', 'refresh');
    }
    
    //6. menyajikan formulir ubah atribut
    $this->load->view('v_header');
    $this->load->view('ubah_guru', $data);
    $this->load->view('v_footer');
  }

}

/* End of file Atribut.php */
/* Location: ./application/controllers/Atribut.php */