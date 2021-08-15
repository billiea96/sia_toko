<?php
class Penjualan_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_penjualan($slug = FALSE) {
        $this->db->select("penjualan.*, nota_jual.*,barang.*");
        $this->db->from('penjualan');
        $this->db->join('nota_jual', 'nota_jual.NoNotaJual = penjualan.NoNotaJual');
        $this->db->join('barang', 'barang.KodeBarang = penjualan.KodeBarang');
        $this->db->order_by('penjualan.NoNotaJual');
        if ($slug === FALSE) {
            $query = $this->db->get();
            return $query->result_array();
        }
        $this->db->where("penjualan.NoNotaJual = '".$slug."'", NULL, FALSE);
        $query = $this->db->get();
        return $query->result_array();
	}
    public function add_penjualan($data){
        return $this->db->insert('penjualan',$data);
    }
}
