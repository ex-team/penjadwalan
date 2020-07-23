<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kelas extends CI_Model {
	function tampil_kelas()
 	{
 		//select * from atribut
 		

 		//$this->db menggunakan library database

 		$ambil = $this->db->get("kelas");

 		// output $ambil (data dari tabel atribut)

 		$semuadata = $ambil->result_array();

 		return $semuadata;
 	}

 	function simpan_kelas($inputan)
 	{
 		//inputan (id_atribut, nama_atribut)
 		$this->db->insert("kelas", $inputan);
 	}

 	function detail_kelas($inputan)
	{
		$this->db->where('id_kelas', $inputan);

		$ambil = $this->db->get("kelas");
		$dataspesifik = $ambil->row_array();
		return $dataspesifik;

		// return $this->db->get('atribut')->row_array();
	}
	

 	function ubah_kelas($inputan, $id_kelas)
 	{
 		//query update data
 		$this->db->where("id_kelas", $id_kelas);
 		$this->db->update("kelas", $inputan);
 		
 	}

}

/* End of file Matribut.php */
/* Location: ./application/models/Matribut.php */