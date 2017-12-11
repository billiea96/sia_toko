<?php
class JurnalHasAkun_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_jurnalHasAkun($slug = FALSE, $slug2 = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('jurnal_has_akun');
            return $query->result_array();
        }

        $query = $this->db->get_where('jurnal_has_akun', array('IDJurnal' => $slug, 'NoAkun' => $slug2));
        return $query->row_array();
	}

    public function add_jurnalHasAkun($arr_data) {
        return $this->db->insert('jurnal_has_akun', $arr_data);
    }
}
