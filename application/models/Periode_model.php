<?php
class Periode_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_periode($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('periode');
            return $query->result_array();
        }

        $query = $this->db->get_where('periode', array('IDPeriode' => $slug));
        return $query->row_array();
	}

    public function add_periode($arr_data) {
        return $this->db->insert('periode', $arr_data);
    }
}
