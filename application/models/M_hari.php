<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hari extends CI_Model {
	function tampil_hari()
 	{
 		//select * from atribut
 		

 		//$this->db menggunakan library database

 		$ambil = $this->db->get("hari");

 		// output $ambil (data dari tabel atribut)

 		$semuadata = $ambil->result_array();

 		return $semuadata;
 	}

 	function simpan_hari($inputan)
 	{
 		//inputan (id_atribut, nama_atribut)
 		$this->db->insert("hari", $inputan);
 	}

 	function detail_hari($id_hari)
	{
		$this->db->where('id_hari', $id_hari);

		$ambil = $this->db->get("hari");
		$dataspesifik = $ambil->row_array();
		return $dataspesifik;

		// return $this->db->get('atribut')->row_array();
	}

 	function ubah_hari($inputan, $id_hari)
 	{
 		//query update data
 		$this->db->where("id_hari", $id_hari);
 		$this->db->update("hari", $inputan);
 		
 	}
}

/* End of file Matribut.php */
/* Location: ./application/models/Matribut.php */