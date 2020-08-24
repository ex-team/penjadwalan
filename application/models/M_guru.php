<?php

class M_Guru extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
    
    
    function get(){
      /*
      
      "SELECT id_guru," +
                "       NIP as NIP," +
                "       nama_guru as Nama," +
                "       alamat as Alamat," +
                "       telp as Telp " +
                "FROM guru " +
                "ORDER BY id_guru");
      */
      $rs = $this->db->query("SELECT id_guru, ".
							 "		 NIP,".
							 "		 nama_guru,".
							 "		 alamat, ".
							 "		 telp ".
							 
                             "FROM guru ".
							 "ORDER BY $this->sort $this->order ".
							 "LIMIT $this->offset,$this->limit");
							 
      return $rs;		
    }
	
	function get_all(){
     
      $rs = $this->db->query("SELECT id_guru, ".
							 "		 NIP,".
							 "		 nama_guru,".
							 "		 alamat, ".
							 "		 telp ".
							 
                             "FROM guru ".
							 "ORDER BY nama_guru");
							 
      return $rs;		
    }
	
	function get_by_id($id){
		$rs = $this->db->query(	"SELECT id_guru, ".
								"		 NIP,".
								"		 nama_guru,".
								"		 alamat, ".
								"		 telp ".
								
								"FROM guru ".
								"WHERE id_guru = $id");
		return $rs;		
	}
	
	function get_search($search){
		$rs = $this->db->query(	"SELECT id_guru, ".
								"		 NIP,".
								"		 nama_guru,".
								"		 alamat, ".
								"		 telp ".
								
								"FROM guru ".
								"WHERE nama_guru LIKE '%$search%'");
		return $rs;		
	}
    
    function num_page(){
    	
    	$result = $this->db->from('guru')
                           ->count_all_results();
        return $result;
    }
    
    function insert($data){
        $this->db->insert('guru',$data);		
    }
    
    function update($id,$data){
        $this->db->where('id_guru',$id);
        $this->db->update('guru',$data);
    }
    
    function delete($id){
        $this->db->query("DELETE FROM guru WHERE id_guru='$id'");
    }
	
	function cek_for_insert($nama){
		/*
		
		var check = string.Format("SELECT CAST(COUNT(*) AS CHAR(1)) " +
                                          "FROM guru " +
                                          "WHERE id_guru={0} OR NIP='{1}'",
                                          int.Parse(txtKode.Text), txtNIDN.Text);
                var i = int.Parse(_dbConnect.ExecuteScalar(check));
		*/
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM guru ".
							   "WHERE nama_guru='$nama'");
		return $rs->row()->cnt;
	}
	
	function cek_for_update($id_baru,$NIP,$id_lama){
		$rs = $this->db->query("SELECT CAST(COUNT(*) AS CHAR(1)) as cnt ".
							   "FROM guru ".
							   "WHERE (id_guru=$id_baru OR NIP='$NIP') AND id_guru <> $id_lama");
		return $rs->row()->cnt;
	}
}