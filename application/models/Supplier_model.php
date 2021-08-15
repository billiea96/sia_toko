<?php
class Supplier_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
   public function get_supplier($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('supplier');
            return $query->result_array();
        }

        $query = $this->db->get_where('supplier', array('KodeSupplier' => $slug));
        return $query->row_array();
    }
}
