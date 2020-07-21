<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mapel extends CI_Model {
	function tampil_mapel()
 	{
 		//select * from atribut
 		

 		//$this->db menggunakan library database

 		$ambil = $this->db->get("mapel");

 		// output $ambil (data dari tabel atribut)

 		$semuadata = $ambil->result_array();

 		return $semuadata;
 	}

 	function simpan_mapel($inputan)
 	{
 		//inputan (id_atribut, nama_atribut)
 		$this->db->insert("mapel", $inputan);
 	}

 	function detail_mapel($id_mapel)
	{
		$this->db->where('id_mapel', $id_mapel);

		$ambil = $this->db->get("mapel");
		$dataspesifik = $ambil->row_array();
		return $dataspesifik;

		// return $this->db->get('atribut')->row_array();
	}

 	function ubah_mapel($inputan, $id_mapel)
 	{
 		//query update data
 		$this->db->where("id_mapel", $id_mapel);
 		$this->db->update("mapel", $inputan);
 		
 	}
}

/* End of file Matribut.php */
/* Location: ./application/models/Matribut.php */