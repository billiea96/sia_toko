<?php
class Barang_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_barang($slug = FALSE) {
        $this->db->select("barang.KodeBarang, barang.Nama, barang.HargaJual, barang.HargaBeliRata2 as HargaBeli ,jenis_barang.NoJenisBarang, jenis_barang.Nama as NamaJenis");
        $this->db->from('barang');
        $this->db->join('jenis_barang', 'barang.NoJenisBarang = jenis_barang.NoJenisBarang');
        $this->db->order_by('barang.KodeBarang');
        if ($slug === FALSE) {
            $query = $this->db->get();
            return $query->result_array();
        }
        $this->db->where("barang.KodeBarang = '".$slug."'", NULL, FALSE);
        $query = $this->db->get();
        return $query->row_array();
	}
}
