<?php

class M_Mapel extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
	
	/*
	function get_by_semester($semester){
		$rs = $this->db->query(
								"SELECT * ".
								"FROM mapel ".
								"WHERE semester%2=$semester ".
								"ORDER BY nama_mapel");
		return $rs;
	}
	*/
	
	
	
	function get(){
		$rs = $this->db->query(
							   "SELECT  *".
								"FROM mapel ".
								"ORDER BY $this->sort $this->order ".
								"LIMIT $this->offset,$this->limit");
		return $rs;		
	}
	
	function get_all(){
		$rs = $this->db->query(
							   "SELECT id_mapel,".
								"       nama_mapel,".
								"       semester,".								
								"       aktif, ".
								"       sks".
								"FROM mapel ");
					
		return $rs;		
	}
	
	function get_by_semester($semester){
		$rs = $this->db->query(
							   "SELECT id_mapel,".								
								"       nama_mapel ".								
								"FROM mapel ".
								"WHERE semester%2=$semester ORDER BY nama_mapel");
		return $rs;		
	}
	
	function get_by_id($id){
		$rs = $this->db->query(
							   "SELECT id_mapel,".
								"       nama_mapel,".
								"       semester,".								
								"       akitf, ".
								"       sks".
								"FROM mapel ".
								"WHERE id_mapel= $id");
		return $rs;		
	}
	
	function get_search($search){
		$rs = $this->db->query(	"SELECT id_mapel,".
								"       nama,".
								"       semester,".								
								"       aktif,".
								"       sks".
								"FROM mapel ".								
								"WHERE nama_mapel LIKE '%$search%'");
		return $rs;		
	}
	
	function num_page(){
    	
    	$result = $this->db->from('mapel')
                           ->count_all_results();
        return $result;
    }
	
	function cek_for_update($nama,$id){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM mapel ".
							   "WHERE (id_mapel=$id OR nama_mapel='$nama') AND id_mapel <> $id");
		return $rs->row()->cnt;
	}
	
	function cek_for_insert($id,$nama){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM mapel ".
							   "WHERE id_mapel=$id OR nama_mapel='$nama'");
		return $rs->row()->cnt;
	}
	
	function update($id,$data){
        $this->db->where('id_mapel',$id);
        $this->db->update('mapel',$data);
    }
	
	function insert($data){
        $this->db->insert('mapel',$data);		
    }
	
	function delete($id){
		$this->db->query("DELETE FROM mapel WHERE id_mapel = '$id'");
	}
	
}