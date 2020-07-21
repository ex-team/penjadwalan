<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Ruang_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        // $this->load->database();
    }


    function get_count_ruang($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(ru_id) as total
            FROM ruang ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }


    function get_ruang($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `ru_id` AS id,
                  `ru_kode` AS kode,
                  `ru_nama` AS nama,
                  `ru_kapasitas` AS kapasitas,
                  ru_is_cadangan AS is_cad,
                  IF(ru_is_cadangan=0,"","Cadangan") AS is_cad_label
            FROM `ruang`
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }

  
    function get_ruang_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND ru_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `ru_id` AS id,
                  `ru_kode` AS kode,
                  `ru_nama` AS nama,
                  `ru_kapasitas` AS kapasitas,
                  ru_is_cadangan AS is_cad
            FROM `ruang`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_ruang($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `ruang`
            SET
                  `ru_kode` = ?,
                  `ru_nama` = ?,
                  `ru_kapasitas` = ?,
                  `ru_is_cadangan` = ?
            WHERE `ru_id` = ?
        ";

        return $this->db->query($sql, array($kode, $nama, $kapasitas, $is_cad, $id));
    }

    function add_ruang($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `ruang`
            (`ru_kode`,`ru_nama`,`ru_kapasitas`, ru_is_cadangan)
            VALUES (?,?,?,?)
        ";

        return $this->db->query($sql, array($kode, $nama, $kapasitas, $is_cad));
    }

    function del_ruang($param){
        if (is_array($param))
            extract($param);

        $sql = "
            DELETE FROM ruang
            WHERE ru_id = ?
        ";

        return $this->db->query($sql, array($id)); 
    }


    function get_count_ruang_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';

        $query = '
            SELECT count(ru_id) as total
            FROM ruang ps
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $val = $ret->result_array();

        return $val[0]['total'];
    }


    function get_ruang_prodi($filter){
        if (is_array($filter))
            extract($filter);
        $str = '';
        
        $limit = '';
        if (!empty($display)) {
            $limit = "LIMIT $start, $display";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `ru_id` AS id,
                  `ru_kode` AS kode,
                  `ru_nama` AS nama,
                  `ru_kapasitas` AS kapasitas,
                  ru_is_cadangan AS is_cad,
                  IF(ru_is_cadangan=0,"","Cadangan") AS is_cad_label
            FROM `ruang`
            --search--
            --limit--
        ';

        $query = str_replace('--search--', $str, $query);
        $query = str_replace('--limit--', $limit, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret;
    }


    function get_ruang_prodi_by_id($filter){
        if (is_array($filter))
            extract($filter);
        $str = ''; 

        if (!empty($id)) {
            $str = "AND ru_id = $id";   
        }

        $query = '
            SELECT SQL_CALC_FOUND_ROWS
                  `ru_id` AS id,
                  `ru_kode` AS kode,
                  `ru_nama` AS nama,
                  `ru_kapasitas` AS kapasitas,
                  ru_is_cadangan AS is_cad
            FROM `ruang`
            WHERE 1=1
            --search--
        ';

        $query = str_replace('--search--', $str, $query);

        $ret = $this->db->query($query);
        $ret = $ret->result_array();        

        return $ret[0];
    }

    function update_ruang_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            UPDATE `ruang`
            SET
                  `ru_kode` = ?,
                  `ru_nama` = ?,
                  `ru_kapasitas` = ?,
                  `ru_is_cadangan` = ?
            WHERE `ru_id` = ?
        ";

        return $this->db->query($sql, array($kode, $nama, $kapasitas, $is_cad, $id));
    }

    function add_ruang_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            INSERT INTO `ruang`
            (`ru_kode`,`ru_nama`,`ru_kapasitas`, ru_is_cadangan)
            VALUES (?,?,?)
        ";

        return $this->db->query($sql, array($kode, $nama, $kapasitas,  $is_cad));
    }

    function del_ruang_prodi($param){
        if (is_array($param))
            extract($param);

        $sql = "
            DELETE FROM ruang_prodi
            WHERE ru_id = ?
        ";

        return $this->db->query($sql, array($id)); 
    }

}

?>
