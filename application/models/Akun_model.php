<?php
class Akun_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_akun($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('akun');
            return $query->result_array();
        }

        $query = $this->db->get_where('akun', array('NoAkun' => $slug));
        return $query->row_array();
	}
}
