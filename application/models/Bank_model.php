<?php
class Bank_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
   public function get_bank($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('bank');
            return $query->result_array();
        }

        $query = $this->db->get_where('bank', array('IdBank' => $slug));
        return $query->row_array();
    }
}
