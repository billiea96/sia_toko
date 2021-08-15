<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TutupPeriode extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Laporan_model');
        $this->load->model('Supplier_model');
        $this->load->model('Bank_model');
        $this->load->model('NotaJual_model');
        $this->load->model('TutupPeriode_model');
        $this->load->model('Jurnal_model');
        $this->load->model('JurnalHasAkun_model');
        $this->load->model('Pembelian_model');
        $this->load->model('PelunasanPiutang_model');
        $this->load->model('Laporan_model');
        $this->load->helper('url_helper');
        $this->load->helper('form');
    	$this->load->library('form_validation');
    	$this->load->library('cart');
        $this->load->library('session');
       $this->load->library('pdf');

    }
	public function index()
	{
		// $data['vsaldoakhir']=$this->Laporan_model->get_saldoAkhir();
 		$this->load->view('layout/header');
		$this->load->view('tutup/tutupPeriode');
		$this->load->view('layout/footer');
	}

	public function simpan()
	{

		$temp = $this->Jurnal_model->get_last_jurnal();
		$IDJurnal = $temp['IDJurnal']+1; 
		$penjualan = $this->TutupPeriode_model->get_penjualan();
		$totalTotalPendapatan = 0;
		$totalTotalBiaya = 0;

		$keterangan = "Penutupan-pendapatan";
		$dataJurnal = array(
				'IDJurnal' =>$IDJurnal,
				'Tanggal' => date('Y-m-d'),
				'Keterangan'=> $keterangan,
				'NoBukti'=> 'JT0001',
				'JenisJurnal' => 'JT',
				'IDPeriode' => '20172');

			//insert jurnal penutupan-pendapatan
			if($this->Jurnal_model->add_jurnal($dataJurnal)){
				$tempakun = 0;
				
				$data = array(
					'IDJurnal' =>$IDJurnal,
					'NoAkun' => '401',
					'Urutan' =>1,
					'NominalDebet' => $penjualan['NominalKredit'],
					'NominalKredit' =>0, 
				);
				$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

				$tempakun += $penjualan['NominalKredit'];

				$diskon = $this->TutupPeriode_model->get_diskonPenjualan();
				$data = array(
					'IDJurnal' =>$IDJurnal,
					'NoAkun' => '402',
					'Urutan' =>2,
					'NominalDebet' => 0,
					'NominalKredit' =>$diskon['NominalDebet'], 
				);
				$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

				$tempakun -= $diskon['NominalDebet'];
				

				$totalTotalPendapatan = $penjualan['NominalKredit'];
				$totalTotalPendapatan -= $diskon['NominalDebet'];

				$penjualan = $this->TutupPeriode_model->get_pendapatanLain();
				$totalTotalPendapatan += $penjualan['NominalKredit'];
				$data = array(
					'IDJurnal' =>$IDJurnal,
					'NoAkun' => '403',
					'Urutan' =>3,
					'NominalDebet' => $penjualan['NominalKredit'],
					'NominalKredit' => 0, 
				);
				$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

				$tempakun += $penjualan['NominalKredit'];

				//akun temp
				$data = array(
					'IDJurnal' =>$IDJurnal,
					'NoAkun' => '000',
					'Urutan' =>4,
					'NominalDebet' => 0,
					'NominalKredit' =>$tempakun, 
				);
				$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

			}

		$temp = $this->Jurnal_model->get_last_jurnal();
		$IDJurnal = $temp['IDJurnal']+1; 


		$keterangan = "Penutupan-Biaya";
		$dataJurnal = array(
				'IDJurnal' =>$IDJurnal,
				'Tanggal' => date('Y-m-d'),
				'Keterangan'=> $keterangan,
				'NoBukti'=> 'JT0002',
				'JenisJurnal' => 'JT',
				'IDPeriode' => '20172');
		//insert jurnal penutupan biaya
		if($this->Jurnal_model->add_jurnal($dataJurnal))
		{
			$hpp = $this->TutupPeriode_model->get_hpp();
			$biayaGaji = $this->TutupPeriode_model->get_biayaGaji();
			$biayaSediaan = $this->TutupPeriode_model->get_biayaSediaan();
			$biayaDep = $this->TutupPeriode_model->get_biayaDepresiasi();
			$biayaListrik = $this->TutupPeriode_model->get_biayaListrik();
			$rugiPenj = $this->TutupPeriode_model->get_rugiPenjualan();

			$tempakun = $hpp['NominalDebet']+$biayaGaji['NominalDebet']+$biayaSediaan['NominalDebet']+$biayaDep['NominalDebet']+$biayaListrik['NominalDebet']+$rugiPenj['NominalDebet'];

			$totalTotalBiaya = $tempakun;
			//akun temp
			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '000',
				'Urutan' =>1,
				'NominalDebet' => $tempakun,
				'NominalKredit'=>0, 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

			
			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '501',
				'Urutan' =>2,
				'NominalDebet' => 0,
				'NominalKredit'=>$hpp['NominalDebet'], 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

			
			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '506',
				'Urutan' =>3,
				'NominalDebet' => 0,
				'NominalKredit'=>$biayaGaji['NominalDebet'], 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);


			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '507',
				'Urutan' =>4,
				'NominalDebet' => 0,
				'NominalKredit'=>$biayaSediaan['NominalDebet'], 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);


			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '508',
				'Urutan' =>5,
				'NominalDebet' => 0,
				'NominalKredit'=>$biayaDep['NominalDebet'], 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);


			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '509',
				'Urutan' =>6,
				'NominalDebet' => 0,
				'NominalKredit'=>$biayaListrik['NominalDebet'], 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '515',
				'Urutan' =>7,
				'NominalDebet' => 0,
				'NominalKredit'=>$rugiPenj['NominalDebet'], 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
		}

		$temp = $this->Jurnal_model->get_last_jurnal();
		$IDJurnal = $temp['IDJurnal']+1; 


		$keterangan = "Penutupan-Modal dan Laba Rugi";
		$dataJurnal = array(
				'IDJurnal' =>$IDJurnal,
				'Tanggal' => date('Y-m-d'),
				'Keterangan'=> $keterangan,
				'NoBukti'=> 'JT0003',
				'JenisJurnal' => 'JT',
				'IDPeriode' => '20172');

		//insert jurnal penutupan modal
		if($this->Jurnal_model->add_jurnal($dataJurnal))
		{
			$totalPend = $this->TutupPeriode_model->get_totalPendapatan();
			$totalBiaya = $this->TutupPeriode_model->get_totalBiaya();
			$labaRugi = $totalTotalPendapatan - $totalTotalBiaya;
			
			//akun temp
			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '000',
				'Urutan' =>1,
				'NominalDebet' => $labaRugi,
				'NominalKredit'=> 0, 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '301',
				'Urutan' =>2,
				'NominalDebet' => 0,
				'NominalKredit'=> $labaRugi, 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
		}

		$temp = $this->Jurnal_model->get_last_jurnal();
		$IDJurnal = $temp['IDJurnal']+1; 


		$keterangan = "Penutupan-Modal prive";
		$dataJurnal = array(
				'IDJurnal' =>$IDJurnal,
				'Tanggal' => date('Y-m-d'),
				'Keterangan'=> $keterangan,
				'NoBukti'=> 'JT0004',
				'JenisJurnal' => 'JT',
				'IDPeriode' => '20172');

		//insert jurnal penutupan prive
		if($this->Jurnal_model->add_jurnal($dataJurnal))
		{
			$totalprive = $this->TutupPeriode_model->get_prive();
			
			//akun temp
			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '301',
				'Urutan' =>1,
				'NominalDebet' => $totalprive['NominalDebet'],
				'NominalKredit'=> 0, 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

			$data = array(
				'IDJurnal' =>$IDJurnal,
				'NoAkun' => '302',
				'Urutan' =>2,
				'NominalDebet' => 0,
				'NominalKredit'=> $totalprive['NominalDebet'], 
			);
			$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
		}
		header("Location: ".site_url('TutupPeriode/index'));
	}

}