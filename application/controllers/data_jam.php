<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_jam extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('M_jam');
  }

  public function index()
  {
    $data['jam'] = $this->M_jam->tampil_jam();

    $this->load->view('v_header');
    $this->load->view('tampil_jam', $data);
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
      $this->M_jam->simpan_mapel($inputan);

      //set flashdata sebagai pesan berhasil simpan
      $this->session->set_flashdata('pesan', 'Data Jam berhasil ditambahkan');

      // c. meredirect tampilan
      redirect('data_jam', 'refresh');
    }

    //menyajikan formulir tambah atribut
    $this->load->view('v_header');
    $this->load->view('tambah_jam');
    $this->load->view('v_footer');
  }

  function ubah($id_jam)
  {
    $data['data_jam']= $this->M_jam->detail_jam($id_jam);

    //menerima inputan formulir
    $inputan = $this->input->post();

    //jika di simpan
    if ($inputan)
    {
      //model Matribut menjalakan fungsi ubah_atribut
      $this->M_jam->ubah_jam($inputan , $id_jam);

      //set flashdata untuk pesan berhasil ubah
      $this->session->set_flashdata('pesan', 'Data Jam berhasil diubah');

      //direct tampil atribut
      redirect('data_jam', 'refresh');
    }
    
    //6. menyajikan formulir ubah atribut
    $this->load->view('v_header');
    $this->load->view('ubah_jam', $data);
    $this->load->view('v_footer');
  }

}

/* End of file Atribut.php */
/* Location: ./application/controllers/Atribut.php */