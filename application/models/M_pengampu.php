<?php

class M_Pengampu extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
	
	function get(){
	
		$rs = $this->db->query("SELECT a.id_pengampu as id_pengampu,".
				"       b.id_mapel as `id_mapel`,".
				"       b.nama_mapel as `nama_mapel`,".
				"       c.id_guru as `id_guru`,".
				"       c.nama_guru as  `nama_guru`,".
				"       a.kelas as kelas ".
				"FROM pengampu a ".
				"LEFT JOIN mapel b ".
				"ON a.id_mapel = b.id_mapel ".
				"LEFT JOIN guru c ".
				"ON a.id_guru = c.id_guru ".
				               
				"ORDER BY b.nama_mapel,a.kelas");

		
		return $rs;
	}
	
	function get_by_id($id){
		
		$sql  = "SELECT a.id_pengampu as id_pengampu,".
				"       b.id_mapel as `id_mapel`,".
				"       b.nama_mapel as `nama_mapel`,".
				"       c.id_guru as `id_guru`,".
				"       c.nama_guru as  `nama_guru`,".
				"       a.kelas as kelas".
				"FROM pengampu a ".
				"LEFT JOIN mapel b ".
				"ON a.id_mapel = b.id_mapel ".
				"LEFT JOIN guru c ".
				"ON a.id_guru = c.id_guru ".
				"WHERE a.id_pengampu = $id";
		
		$rs = $this->db->query($sql);
		return $rs;
		
	}
	
	function get_search($search_query){
	
		$rs = $this->db->query(
							    "SELECT a.id_pengampu as id_pengampu,".
								"       b.id_mapel as `id_mapel`,".
								"       b.nama_mapel as `nama_mapel`,".
								"       c.id_guru as `id_guru`,".
								"       c.nama_guru as  `nama_guru`,".
								"       a.kelas as kelas ".
								"FROM pengampu a ".
								"LEFT JOIN mapel b ".
								"ON a.id_mapel = b.id_mapel ".
								"LEFT JOIN guru c ".
								"ON a.id_guru = c.id_guru ".
								"WHERE  c.nama_guru LIKE '%$search_query%' OR b.nama_mapel LIKE '%$search_query%' ".                
								"ORDER BY b.nama_mapel,a.kelas");
		return $rs;
	}
	
function num_page(){
		$rs = $this->db->query(	
								
								"SELECT CAST(COUNT(*) AS CHAR(4)) as cnt ".
								
								"FROM pengampu a ".
								"LEFT JOIN mapel b ".
								"ON a.id_mapel = b.id_mapel ".
								"LEFT JOIN guru c ".
								"ON a.id_guru = c.id_guru ".               
								"ORDER BY b.nama_mapel,a.kelas");

		return $rs->row()->cnt;
		
	}
	
	function delete_by_id_guru($id_guru){
        $this->db->query("DELETE FROM pengampu WHERE id_guru='$id_guru'");
    }
	
	function delete_by_mapel($id_mapel){
		$this->db->query("DELETE FROM pengampu WHERE id_mapel = '$id_mapel'");
	}
	
	function delete($id){
		$this->db->query("DELETE FROM pengampu WHERE id_pengampu = '$id'");		
	}
	
	function cek_for_update($id_mapel,$id_guru,$kelas,$id){		
		$rs = $this->db->query(
							   "SELECT CAST(COUNT(*) AS CHAR(1)) as cnt".
                               "FROM pengampu ".
							   "WHERE id_mapel='$id_mapel' AND ".
                               "      id_guru=$id_guru AND ".
                               "      kelas = '$kelas' AND ".
                               "      AND id_pengampu <> $id");
		return $rs->row()->cnt;
	}
	
	function cek_for_insert($id_guru,$kelas,$id){		
		$rs = $this->db->query(
								"SELECT CAST(COUNT(*) AS CHAR(1)) as cnt".
								"FROM pengampu ".
								"WHERE id_mapel='$id' AND ".
								"   	id_guru=$id_guru AND ".
								"      kelas = '$kelas' AND ".
								"      AND id_pengampu <> $id");
		return $rs->row()->cnt;
	}
	
	function update($id,$data){
		$this->db->where('id_pengampu',$id);
        $this->db->update('pengampu',$data);
	}
	
	function insert($data){

        $this->db->insert('pengampu',$data);
		return $this->db->insert_id();
    }
}