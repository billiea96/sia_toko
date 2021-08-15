<?php
class NotaJual_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_nota($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('nota_jual');
            return $query->result_array();
        }

        $query = $this->db->get_where('nota_jual', array('NoNotaJual' => $slug));
        return $query->row_array();
	}
    public function set_lunas($no){
        $this->db->where('NoNotaJual',$no);
        $this->db->update('nota_jual',array('Lunaskah'=>1));
    }
    public function add_nota_jual($arr_data) {
        return $this->db->insert('nota_jual', $arr_data);
    }

    public function get_last_nota() {
        $this->db->select('NoNotaJual');
        $this->db->limit(1);
        $this->db->order_by('NoNotaJual', 'DESC');
        $query = $this->db->get('nota_jual');
        return $query->row_array();
    }

    public function pembayaran_kredit(){
        $query = $this->db->query('SELECT * FROM nota_jual WHERE JenisPembayaran = "K" AND Lunaskah=0');
        return $query->result_array();
    }
}
