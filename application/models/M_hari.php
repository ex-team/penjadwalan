<?php

class M_Hari extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
	
	function get(){
		$rs = $this->db->query("SELECT * FROM hari");
		return $rs;
	}
	
	function get_by_kode($id){
		$rs = $this->db->query("SELECT id_hari ,nama_hari ".
							   "FROM hari ".
							   "WHERE id_hari = $id");
		return $rs;
	}
	
	function cek_for_update($id_baru,$nama,$id_lama){
		/*
		var check = string.Format("SELECT CAST(COUNT(*) AS CHAR(1)) " +
                                          "FROM hari " +
                                          "WHERE (id_hari='{0}' OR nama='{1}') AND id_hari <> {2}",
                                          txtKodeHari.Text, txtNamaHari.Text, _selectedkodeHr);
                var i = int.Parse(_dbConnect.ExecuteScalar(check));
		*/
		
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM hari WHERE (id_hari=$id_baru OR nama_hari='$nama') AND id_hri <> $id_lama");
		return $rs->row()->cnt;
	}
	
	function cek_for_insert($id,$nama){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM hari WHERE id_hari=$id OR nama_hari='$nama'");
		return $rs->row()->cnt;
	}
	
	function update($id,$data){
        $this->db->where('id_hari',$id);
        $this->db->update('hari',$data);
    }
	
	function insert($data){
        $this->db->insert('hari',$data);		
    }
	
	function delete($id){
		$this->db->query("DELETE FROM hari WHERE id_hari = '$id'");
	}
	
	
}