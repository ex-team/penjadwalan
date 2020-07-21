<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_jadwal extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('execute');
  }

  public function index()
  {
    $data['jadwal_tb'] = $this->M_jadwal->tampil_jadwal();

    $this->load->view('v_header');
    $this->load->view('tampil_jadwal', $data);
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
      $this->M_jadwal->simpan_jadwal($inputan);

      //set flashdata sebagai pesan berhasil simpan
      $this->session->set_flashdata('pesan', 'Data guru berhasil ditambahkan');

      // c. meredirect tampilan
      redirect('data_jadwal', 'refresh');
    }

    //menyajikan formulir tambah atribut
    $this->load->view('v_header');
    $this->load->view('tambah_jadwal');
    $this->load->view('v_footer');
  }

  function ubah()
  {
    $data['data_jadwal']= $this->M_jadwal->detail_jadwal($id_jadwal);

    //menerima inputan formulir
    $inputan = $this->input->post();

    //jika di simpan
    if ($inputan)
    {
      //model Matribut menjalakan fungsi ubah_atribut
      $this->M_jadwal->ubah_jadwal($inputan , $id_jadwal);

      //set flashdata untuk pesan berhasil ubah
      $this->session->set_flashdata('pesan', 'Data guru berhasil diubah');

      //direct tampil atribut
      redirect('data_jadwal', 'refresh');
    }
    
    //6. menyajikan formulir ubah atribut
    $this->load->view('v_header');
    $this->load->view('ubah_jadwal', $data);
    $this->load->view('v_footer');
  }

  function hapus()
  {
    $this->M_jadwal->hapus_jadwal($id_jadwal);
    if ("berhasil")
    {
      $this->session->set_flashdata('pesan', 'Data guru berhasil dihapus');
    }
    else
    {
      $this->session->set_flashdata('pesan', 'Data guru gagal dihapus');
    }

    redirect('data_jadwal', 'refresh');
  }

}

/* End of file Atribut.php */
/* Location: ./application/controllers/Atribut.php */