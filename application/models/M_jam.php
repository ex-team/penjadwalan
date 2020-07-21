<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jam extends CI_Model {
	function tampil_jam()
 	{
 		//select * from atribut
 		

 		//$this->db menggunakan library database

 		$ambil = $this->db->get("jam");

 		// output $ambil (data dari tabel atribut)

 		$semuadata = $ambil->result_array();

 		return $semuadata;
 	}

 	function simpan_jam($inputan)
 	{
 		//inputan (id_atribut, nama_atribut)
 		$this->db->insert("jam", $inputan);
 	}

 	function detail_jam($id_jam)
	{
		$this->db->where('id_jam', $id_jam);

		$ambil = $this->db->get("jam");
		$dataspesifik = $ambil->row_array();
		return $dataspesifik;

		// return $this->db->get('atribut')->row_array();
	}

 	function ubah_jam($inputan, $id_jam)
 	{
 		//query update data
 		$this->db->where("id_jam", $id_jam);
 		$this->db->update("jam", $inputan);
 		
 	}
}

/* End of file Matribut.php */
/* Location: ./application/models/Matribut.php */