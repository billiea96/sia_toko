<?php
class Jurnal_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_jurnal($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('jurnal');
            return $query->result_array();
        }

        $query = $this->db->get_where('jurnal', array('IDJurnal' => $slug));
        return $query->row_array();
	}
    public function get_last_jurnal() {
        $this->db->select('IDJurnal');
        $this->db->limit(1);
        $this->db->order_by('IDJurnal', 'DESC');
        $query = $this->db->get('jurnal');
        return $query->row_array();
    }

    public function add_jurnal($arr_data) {
        return $this->db->insert('jurnal', $arr_data);
    }
}
