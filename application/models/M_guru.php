<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_guru extends CI_Model {
	function tampil_guru()
 	{
 		//select * from atribut
 		

 		//$this->db menggunakan library database

 		$ambil = $this->db->get("guru");

 		// output $ambil (data dari tabel atribut)

 		$semuadata = $ambil->result_array();

 		return $semuadata;
 	}

 	function simpan_guru($inputan)
 	{
 		//inputan (id_atribut, nama_atribut)
 		$this->db->insert("guru", $inputan);
 	}

 	function detail_guru($id_guru)
	{
		$this->db->where('id_guru', $id_guru);

		$ambil = $this->db->get("guru");
		$dataspesifik = $ambil->row_array();
		return $dataspesifik;

		// return $this->db->get('atribut')->row_array();
	}

 	function ubah_guru($inputan, $id_guru)
 	{
 		//query update data
 		$this->db->where("id_guru", $id_guru);
 		$this->db->update("guru", $inputan);
 		
 	}
}

/* End of file Matribut.php */
/* Location: ./application/models/Matribut.php */