<?php

class M_Jadwal extends CI_Model{

	public $limit;
	public $offset;
	public $sort;
	public $order;

	function __construct(){

		parent::__construct();

	}
	
	
	function get(){
		$rs = $this->db->query(	"SELECT  e.nama_hari as hari,".
								"          Concat_WS('-',  concat('(', g.id_jam), concat( (SELECT id_jam".  
								"                                  FROM jam ". 
								"                                  WHERE id_jam = (SELECT jm.id_jam ".
								"                                                FROM jam jm  ".
								"                                                WHERE MID(jm.range_jam,1,5) = MID(g.range_jam,1,5)) + (c.sks - 1)),')')) as sesi, ". 
								" 		  Concat_WS('-', MID(g.range_jam,1,5),".
								"                (SELECT MID(range_jam,7,5) ".
								"                 FROM jam ".
								"                 WHERE id_jam = (SELECT jm.id_jam ".
								"                               FROM jam jm ".
								"                               WHERE MID(jm.range_jam,1,5) = MID(g.range_jam,1,5)) + (c.sks - 1))) as jam_pelajaran, ".
			   
								"        c.nama_mapel as nama_mapel,".
								"        c.sks as sks,".
					
								"        b.kelas as kelas,".
								"        d.nama_guru as guru,".
								"        f.nama_ruang as ruang ".
								"FROM jadwal a ".
								"LEFT JOIN pengampu b ".
								"ON a.id_pengampu = b.id_pengampu ".
								"LEFT JOIN mapel c ".
								"ON b.id_mapel = c.id_mapel ".
								"LEFT JOIN guru d ".
								"ON b.id_guru = d.id_guru ".
								"LEFT JOIN hari e ".
								"ON a.id_hari = e.id_hari ".
								"LEFT JOIN ruang f ".
								"ON a.id_ruang = f.id_ruang ".
								"LEFT JOIN jam g ".
								"ON a.id_jam = g.id_jam ".
								"order by e.id_hari asc,jam_pelajaran asc;");
		return $rs;
	}
}