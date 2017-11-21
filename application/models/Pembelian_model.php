<?php
class Pembelian_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function add_pembelian($data){
        return $this->db->insert('pembelian',$data);
    }
}
