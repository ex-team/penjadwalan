<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ruang extends CI_Model {
	function tampil_ruang()
 	{
 		//select * from atribut
 		

 		//$this->db menggunakan library database

 		$ambil = $this->db->get("ruang");

 		// output $ambil (data dari tabel atribut)

 		$semuadata = $ambil->result_array();

 		return $semuadata;
 	}

 	function simpan_ruang($inputan)
 	{
 		//inputan (id_atribut, nama_atribut)
 		$this->db->insert("ruang", $inputan);
 	}

 	function detail_ruang($id_ruang)
	{
		$this->db->where('id_ruang', $id_ruang);

		$ambil = $this->db->get("ruang");
		$dataspesifik = $ambil->row_array();
		return $dataspesifik;

		// return $this->db->get('atribut')->row_array();
	}

 	function ubah_ruang($inputan, $id_ruang)
 	{
 		//query update data
 		$this->db->where("id_ruang", $id_ruang);
 		$this->db->update("ruang", $inputan);
 		
 	}
}

/* End of file Matribut.php */
/* Location: ./application/models/Matribut.php */