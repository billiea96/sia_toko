<?php
class TutupPeriode_model extends CI_Model {

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
    public function get_bukuBesar(){
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

    public function get_penjualan() {

        $query = $this->db->query('SELECT SUM(NominalKredit) AS NominalKredit FROM jurnal_has_akun WHERE NoAkun = "401"');
        return $query->row_array();

    }

    public function get_pendapatanLain() {
        $query = $this->db->query('SELECT SUM(NominalKredit) AS NominalKredit FROM jurnal_has_akun WHERE NoAkun = "403"');
        return $query->row_array();
    }

    public function get_diskonPenjualan() {
        $query = $this->db->query('SELECT SUM(NominalDebet) AS NominalDebet FROM jurnal_has_akun WHERE NoAkun = "402"');
        return $query->row_array();
    }

    public function get_hpp() {
        $query = $this->db->query('SELECT SUM(NominalDebet) AS NominalDebet FROM jurnal_has_akun WHERE NoAkun = "501"');
        return $query->row_array();
    }
    public function get_biayaGaji() {
        $query = $this->db->query('SELECT SUM(NominalDebet) AS NominalDebet FROM jurnal_has_akun WHERE NoAkun = "506"');
        return $query->row_array();
    }
    public function get_biayaSediaan() {
        $query = $this->db->query('SELECT SUM(NominalDebet) AS NominalDebet FROM jurnal_has_akun WHERE NoAkun = "507"');
        return $query->row_array();
    }
    public function get_biayaDepresiasi() {
        $query = $this->db->query('SELECT SUM(NominalDebet) AS NominalDebet FROM jurnal_has_akun WHERE NoAkun = "508"');
        return $query->row_array();
    }
    public function get_biayaListrik() {
        $query = $this->db->query('SELECT SUM(NominalDebet) AS NominalDebet FROM jurnal_has_akun WHERE NoAkun = "509"');
        return $query->row_array();
    }
    public function get_rugiPenjualan() {
        $query = $this->db->query('SELECT SUM(NominalDebet) AS NominalDebet FROM jurnal_has_akun WHERE NoAkun = "515"');
        return $query->row_array();
    }

    public function get_totalPendapatan()
    {
        $query = $this->db->query('SELECT SUM(V.SaldoAkhir*A.SaldoNormal)*-1 AS TotalPendapatan
            FROM vlabarugi V INNER JOIN akun A ON V.NoAkun = A.NoAkun
            WHERE V.NoAkun LIKE "4%"');
        return $query->row_array();
        
    }
    public function get_totalBiaya()
    {
        $query = $this->db->query('SELECT SUM(V.SaldoAkhir*A.SaldoNormal) AS TotalBiaya
            FROM vlabarugi V INNER JOIN akun A ON V.NoAkun = A.NoAkun
            WHERE V.NoAkun LIKE "5%"');
        return $query->row_array();
    }
    public function get_prive()
    {
        $query = $this->db->query('SELECT SUM(NominalDebet) AS NominalDebet FROM jurnal_has_akun WHERE NoAkun = "302"');
        return $query->row_array();
    }
}
