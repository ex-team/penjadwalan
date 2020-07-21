<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_mapel extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('M_mapel');
  }

  public function index()
  {
    $data['mapel'] = $this->M_mapel->tampil_mapel();

    $this->load->view('v_header');
    $this->load->view('tampil_mapel', $data);
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
      $this->M_mapel->simpan_mapel($inputan);

      //set flashdata sebagai pesan berhasil simpan
      $this->session->set_flashdata('pesan', 'Data mapel berhasil ditambahkan');

      // c. meredirect tampilan
      redirect('data_mapel', 'refresh');
    }

    //menyajikan formulir tambah atribut
    $this->load->view('v_header');
    $this->load->view('tambah_mapel');
    $this->load->view('v_footer');
  }

  function ubah($id_mapel)
  {
    $data['data_mapel']= $this->M_mapel->detail_mapel($id_mapel);

    //menerima inputan formulir
    $inputan = $this->input->post();

    //jika di simpan
    if ($inputan)
    {
      //model Matribut menjalakan fungsi ubah_atribut
      $this->M_mapel->ubah_mapel($inputan , $id_mapel);

      //set flashdata untuk pesan berhasil ubah
      $this->session->set_flashdata('pesan', 'Data mapel berhasil diubah');

      //direct tampil atribut
      redirect('data_mapel', 'refresh');
    }
    
    //6. menyajikan formulir ubah atribut
    $this->load->view('v_header');
    $this->load->view('ubah_mapel', $data);
    $this->load->view('v_footer');
  }

}

/* End of file Atribut.php */
/* Location: ./application/controllers/Atribut.php */