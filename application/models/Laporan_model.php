<?php
class Laporan_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_laporan($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('laporan');
            return $query->result_array();
        }

        $query = $this->db->get_where('laporan', array('IDLaporan' => $slug));
        return $query->row_array();
	}
}
