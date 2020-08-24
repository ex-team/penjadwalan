<?php

class M_Waktu_Tidak_Bersedia extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
    
    function get_by_guru($id_guru){
      $rs = $this->db->query("SELECT id_hari,id_jam ".
                             "FROM waktu_tidak_bersedia ".
                             "WHERE id_guru = $id_guru");
      return $rs;
    }
    
    function update($id,$data){
      /*
      string.Format(
                        "UPDATE waktu_tidak_bersedia " +
                        "SET id_guru = {0} " +
                        "WHERE id_guru = {1}",
                        txtKode.Text,
                        _selectedkode);
      */
      
        $this->db->where('id_guru',$id);
        $this->db->update('waktu_tidak_bersedia',$data);
    }
    
    
    function delete_by_guru($id_guru){
        $this->db->query("DELETE FROM waktu_tidak_bersedia ".
                         "WHERE id_guru = $id_guru");       
      
    }
    
    
}