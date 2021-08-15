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

    public function get_jurnal($nobukti) { 
        $query = $this->db->query('Select ja.* from jurnal j inner join jurnal_has_akun ja on j.IDJurnal=ja.IDJurnal where j.NoBukti = "'.$nobukti.'" and (ja.NoAkun ="106" OR ja.NoAkun="107") ORDER BY ja.Urutan LIMIT 2');
        return $query->result_array();
    }
    public function add_jurnalHasAkun($arr_data) {
        return $this->db->insert('jurnal_has_akun', $arr_data);
    }
}
