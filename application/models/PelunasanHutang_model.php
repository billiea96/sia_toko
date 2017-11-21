<?php
class PelunasanHutang_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function add_pelunasan_hutang($arr_data) {
        return $this->db->insert('pelunasan_hutang', $arr_data);
    }
}
