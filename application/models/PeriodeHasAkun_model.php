<?php
class PeriodeHasAkun_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_PeriodeHasAkun($slug = FALSE, $slug2 = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('periode_has_akun');
            return $query->result_array();
        }

        $query = $this->db->get_where('periode_has_akun', array('IDJurnal' => $slug, 'NoAkun' => $slug2));
        return $query->row_array();
	}

    public function add_PeriodeHasAkun($arr_data) {
        return $this->db->insert('periode_has_akun', $arr_data);
    }
}
