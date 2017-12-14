<?php
class Laporan_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    public function get_laporan($slug = FALSE) {
        if ($slug === FALSE) {
            $query = $this->db->get('laporan');
            return $query->result_array();
        }

        $query = $this->db->get_where('laporan', array('IDLaporan' => $slug));
        return $query->row_array();
	}
         public function get_laporanJurnal(){
       
        $query = $this->db->get('vlaporanjurnal');
        return $query->result_array();
    }
    public function get_bukuBesar($noAkun){
        $this->db->where('NoAkun',$noAkun);
        $query = $this->db->get('vbukubesar');
        return $query->result_array();
    }
     public function get_saldoAkhir(){
        $query = $this->db->get('vsaldoakhir');
        return $query->result_array();
    }
     public function get_perubahanEkuitas(){
        $query = $this->db->get('vperubahanekuitas');
        return $query->result_array();
    }
    public function get_labaRugi(){
        $query = $this->db->get('vlabarugi');
        return $query->result_array();
    }
    public function get_neraca(){
        $query = $this->db->get('vneraca');
        return $query->result_array();
    }
    public function get_arusKas(){
        $query = $this->db->get('varuskas');
        return $query->result_array();
    }



}
