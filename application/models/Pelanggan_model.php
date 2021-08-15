<?php
class Pelanggan_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
   public function get_pelanggan($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('pelanggan');
            return $query->result_array();
        }

        $query = $this->db->get_where('pelanggan', array('KodePelanggan' => $slug));
        return $query->row_array();
    }
}
