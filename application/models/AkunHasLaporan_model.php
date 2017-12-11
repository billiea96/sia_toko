<?php
class AkunHasLaporan extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_AkunHasLaporan($slug = FALSE, $slug2 = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('akun_has_laporan');
            return $query->result_array();
        }

        $query = $this->db->get_where('akun_has_laporan', array('NoAkun' => $slug, 'IDLaporan'=> $slug2));
        return $query->row_array();
	}

    public function add_AkunHasLaporan($arr_data) {
        return $this->db->insert('akun_has_laporan', $arr_data);
    }
}
