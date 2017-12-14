<?php
class NotaBeli_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_nota($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('nota_beli');
            return $query->result_array();
        }

        $query = $this->db->get_where('nota_beli', array('NoNotaBeli' => $slug));
        return $query->row_array();
	}
    public function set_lunas($no){
        $this->db->where('NoNotaBeli',$no);
        $this->db->update('nota_beli',array('Lunaskah'=>1));
    }
    public function add_nota_beli($arr_data) {
        return $this->db->insert('nota_beli', $arr_data);
    }

    public function get_last_nota() {
        $this->db->select('NoNotaBeli');
        $this->db->limit(1);
        $this->db->order_by('NoNotaBeli', 'DESC');
        $query = $this->db->get('nota_beli');
        return $query->row_array();
    }

    public function pembayaran_kredit(){
        $query = $this->db->query('SELECT * FROM nota_beli WHERE JenisPembayaran = "K" AND Lunaskah=0');
        return $query->result_array();
    }
}
