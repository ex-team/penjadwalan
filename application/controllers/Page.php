<?php
class Page extends CI_Controller{
  function __construct(){
    parent::__construct();
    //validasi jika user belum login
    if($this->session->userdata('masuk') != TRUE){
            $url=base_url();
            redirect($url);
        }
  }
 
  function index(){
    $this->load->view('v_header');
    $this->load->view('v_dashboard');
    $this->load->view('v_footer');
  }
//   function data_guru(){
//     // function ini hanya boleh diakses oleh admin dan dosen
//     if($this->session->userdata('akses')=='1' || $this->session->userdata('akses')=='2'){
//       $this->load->view('v_guru');
//     }else{
//       echo "Anda tidak berhak mengakses halaman ini";
//     }
//  
//   }
//  
//  function data_kelas(){
//     // function ini hanya boleh diakses oleh admin 
//     if($this->session->userdata('akses')=='1' || $this->session->userdata('akses')=='2'){
//       $this->load->view('v_kelas');
//     }else{
//       echo "Anda tidak berhak mengakses halaman ini";
//     }
//  
//   }
// function data_mapel(){
//     // function ini hanya boleh diakses oleh admin 
//     if($this->session->userdata('akses')=='1' || $this->session->userdata('akses')=='2'){
//       $this->load->view('v_mapel');
//     }else{
//       echo "Anda tidak berhak mengakses halaman ini";
//     }
//  
//   }
// function data_ruang(){
//     // function ini hanya boleh diakses oleh admin
//     if($this->session->userdata('akses')=='1' || $this->session->userdata('akses')=='2'){
//       $this->load->view('v_ruang');
//     }else{
//       echo "Anda tidak berhak mengakses halaman ini";
//     }
//  
//   }
// function data_hari(){
//     // function ini hanya boleh diakses oleh admin 
//     if($this->session->userdata('akses')=='1' || $this->session->userdata('akses')=='2'){
//       $this->load->view('v_hari');
//     }else{
//       echo "Anda tidak berhak mengakses halaman ini";
//     }
//  
//   }
// function data_jam(){
//     // function ini hanya boleh diakses oleh admin 
//     if($this->session->userdata('akses')=='1' || $this->session->userdata('akses')=='2'){
//       $this->load->view('v_jam');
//     }else{
//       echo "Anda tidak berhak mengakses halaman ini";
//     }
//  
//   }
// function jadwal(){
//     // function ini hanya boleh diakses oleh admin dan guru
//     if($this->session->userdata('akses')=='1' || $this->session->userdata('akses')=='3'){
//       $this->load->view('v_jadwal');
//     }else{
//       echo "Anda tidak berhak mengakses halaman ini";
//     }
//  
//   }

}