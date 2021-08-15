<?php
class PelunasanPiutang_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function add_pelunasan_piutang($arr_data) {
        return $this->db->insert('pelunasan_piutang', $arr_data);
    }

    public function get_last_pelunasan() {
        $this->db->select('NoPelunasan');
        $this->db->limit(1);
        $this->db->order_by('NoPelunasan', 'DESC');
        $query = $this->db->get('pelunasan_piutang');
        return $query->row_array();
    }
}
