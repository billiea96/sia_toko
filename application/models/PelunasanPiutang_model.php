<?php
class PelunasanPiutang_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function add_pelunasan_piutang($arr_data) {
        return $this->db->insert('pelunasan_piutang', $arr_data);
    }
}
