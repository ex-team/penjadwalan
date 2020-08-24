<?php

class M_Jam extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
	
	function get(){
		$rs = $this->db->query("SELECT id_jam ,range_jam ".
							   "FROM jam ".
							   "ORDER BY range_jam");
		return $rs;
	}
	
    function get_by_id($id){
		$rs = $this->db->query("SELECT id_jam ,range_jam ".
							   "FROM jam ".
							   "WHERE id_jam = $id");
		return $rs;
	}

	function cek_for_update($id_baru,$id_lama){		
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM jam ".
							   "WHERE id_jam=$id_baru AND id_jam <> $id_lama");
		return $rs->row()->cnt;
	}
	
	function cek_for_insert($id){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM jam ".
							   "WHERE id_jam=$id");
		return $rs->row()->cnt;
	}
	
	function update($id,$data){
        $this->db->where('id_jam',$id);
        $this->db->update('jam',$data);
    }
	
	function insert($data){
        $this->db->insert('jam',$data);		
    }
	
	function delete($id){
		$this->db->query("DELETE FROM jam WHERE id_jam = '$id'");
	}
	
}