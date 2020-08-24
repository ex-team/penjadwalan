<?php

class M_Ruang extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
	
	function get(){
		$rs = $this->db->query("SELECT * FROM ruang");
		return $rs;
	}
	
	function get_by_id($id){
		$rs = $this->db->query("SELECT * FROM ruang WHERE id_ruang = $id");
		return $rs;
	}
	
	function cek_for_update($nama,$id){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM ruang ".
							   "WHERE nama_ruang='$nama' AND id_ruang <> $id");
		return $rs->row()->cnt;
	}
	
	function cek_for_insert($nama){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM ruang ".
							   "WHERE nama_ruang ='$nama'");
		return $rs->row()->cnt;
	}
	
	function update($id,$data){
        $this->db->where('id_ruang',$id);
        $this->db->update('ruang',$data);
	}
	
	function insert($data){
        $this->db->insert('ruang',$data);		
    }
	
	function delete($id){
		$this->db->query("DELETE FROM ruang where id_ruang = $id");
	}
	
	function get_search($search_query){
		$rs = $this->db->query("SELECT id_ruang,nama_ruang,kapasitas,jenis FROM ruang WHERE nama_ruang LIKE '%$search_query%'");
		return $rs;
	}
	
}