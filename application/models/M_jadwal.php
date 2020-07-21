<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model {
	function tampil_jadwal()
 	{
 		//select * from atribut
 		

 		//$this->db menggunakan library database

 		$ambil = $this->db->get("jadwal_tb");

 		// output $ambil (data dari tabel atribut)

 		$semuadata = $ambil->result_array();

 		return $semuadata;
 	}

 	function simpan_jadwal($inputan)
 	{
 		//inputan (id_atribut, nama_atribut)
 		$this->db->insert("jadwal_tb", $inputan);
 	}

 	function detail_jadwal($id_jadwal)
	{
		$this->db->where('id_jadwal', $id_jadwal);

		$ambil = $this->db->get("jadwal_tb");
		$dataspesifik = $ambil->row_array();
		return $dataspesifik;

		// return $this->db->get('atribut')->row_array();
	}

 	function hapus_jadwal($id_jadwal)
	{
		$this->db->where("id_jadwal", $id_jadwal);
 		$this->db->delete("jadwal_tb");
	}
	

 	function ubah_jadwal($inputan, $id_jadwal)
 	{
 		//query update data
 		$this->db->where("id_jadwal", $id_jadwal);
 		$this->db->update("jadwal_tb", $inputan);
 		
 	}
}

/* End of file Matribut.php */
/* Location: ./application/models/Matribut.php */