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
    public function get_saldoAkhirs($noAkun){
        $this->db->where('NoAkun',$noAkun);
        $query = $this->db->get('vsaldoakhir');
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
    public function get_labaRugiPendapatan(){
        $this->db->like('NoAkun','4');
        $query = $this->db->get('vlabarugi');
        return $query->result_array();
    }
     public function get_labaRugiBiaya(){
        $this->db->like('NoAkun','5');
        $query = $this->db->get('vlabarugi');
        return $query->result_array();
    }

    public function get_neraca_aktiva(){
        $query = $this->db->query("SELECT a.NoAkun,a.Nama, (vs.SaldoAkhir*a.SaldoNormal) as SaldoAkhir from akun a inner join vsaldoakhir vs on a.NoAkun= vs.NoAkun INNER JOIN akun_has_laporan al on a.NoAkun =al.NoAkun where al.IDLaporan ='NR' AND a.NoAkun like '1%'");
        return $query->result_array();
    }
    public function get_neraca_pasiva(){
        $query = $this->db->query("SELECT a.NoAkun,a.Nama, (vs.SaldoAkhir*a.SaldoNormal) as SaldoAkhir  from akun a inner join vsaldoakhir vs on a.NoAkun= vs.NoAkun INNER JOIN akun_has_laporan al on a.NoAkun =al.NoAkun where al.IDLaporan ='NR' AND (a.NoAkun like '2%' OR a.NoAkun like '3%') ");
        return $query->result_array();
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
    public function get_arusKas(){
        $query = $this->db->get('varuskas');
        return $query->result_array();
    }



}
