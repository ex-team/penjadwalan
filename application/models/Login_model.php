<?php
class Login_model extends CI_Model{
    //cek nip dan password dosen
    function auth_admin($username,$password){
        $query=$this->db->query("SELECT * FROM admin WHERE id_admin='$username' AND password_admin='$password' LIMIT 1");
        return $query;
    }
 
    //cek nim dan password guru
    function auth_guru($username,$password){
        $query=$this->db->query("SELECT * FROM guru WHERE id_guru='$username' AND password_guru='$password' LIMIT 1");
        return $query;
    }
 
}