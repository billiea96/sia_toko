<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PelunasanPiutang extends CI_Controller {

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
        $this->load->model('Supplier_model');
        $this->load->model('Bank_model');
        $this->load->model('NotaJual_model');
        $this->load->model('Pembelian_model');
        $this->load->model('Jurnal_model');
        $this->load->model('JurnalHasAkun_model');
        $this->load->model('PelunasanPiutang_model');
        $this->load->helper('url_helper');
        $this->load->helper('form');
    	$this->load->library('form_validation');
    	$this->load->library('cart');
        $this->load->library('session');
    }
	public function index()
	{
        $data['NoNotaJual'] = $this->NotaJual_model->pembayaran_kredit();
		$data['barang'] = $this->Barang_model->get_barang();
		$data['supplier'] = $this->Supplier_model->get_supplier();
		$data['bank'] = $this->Bank_model->get_bank();

		$this->load->view('layout/header');
		$this->load->view('pelunasan/pelunasanJual', $data);
		$this->load->view('layout/footer');
	}

	public function detail_nota(){
		$NotaJual = $this->NotaJual_model->get_nota($_POST['noNota']);

		$sisaBayar = ($NotaJual['Total']-(($NotaJual['Total']*$NotaJual['Diskon'])/100)) - $NotaJual['Bayar'];
		//$totalBayar = $NotaJual['Total'] - ($NotaJual['Total'] * ($NotaJual['DiskonPelunasan']/100));
		$totalBayar = $sisaBayar - (($sisaBayar) * ($NotaJual['DiskonPelunasan']/100));
		
		echo json_encode(array('sisaBayar' => $sisaBayar,'diskon'=>$NotaJual['DiskonPelunasan'], 'total'=>$NotaJual['Total'], 'totalBayar' => $totalBayar, 'tgl'=>$NotaJual['TanggalBatasDiskon']));
	}

	public function simpan(){
		$nota = $this->input->post('noNota');
		$tgl = $this->input->post('tgl');
		$nominal = $this->input->post('nominal');
		$disc = $this->input->post('discPelunasan');
		$bayar = $this->input->post('totalBayar');
		$jp = $this->input->post('jPembayaran');
		$nocek = $this->input->post('noCek');

		$temp = $this->PelunasanPiutang_model->get_last_pelunasan();
		$idPelunasan = $temp['NoPelunasan']+1; 	

		$temp2 = $this->Jurnal_model->get_last_jurnal();
		$IDJurnal = $temp2['IDJurnal']+1;

		$dataSimpan = array(
			'NoPelunasan' => $idPelunasan,
			'Tanggal' => $tgl,
			'NominalSeharusnya' => $nominal,
			'DiskonPelunasan' => $disc,
			'Bayar' => $bayar,
			'JenisPembayaran' => $jp,
			'NoNotaJual' => $nota,
			'NoCek' => $nocek,
		);

		if($this->PelunasanPiutang_model->add_pelunasan_piutang($dataSimpan)){
			$notajual = $this->NotaJual_model->get_nota($nota);
			$keterangan ='Pelunasan transaksi penjualan tanggal '.$notajual['Tanggal'];
			$akun ="";
			if($jp=="T"){
				$keterangan.=' Secara tunai';
				$akun="101";
			}
			else if($jp=="TR"){
				$keterangan.=' dengan transfer bank';
				if($idbank=="1")
					$akun="102";
				else
					$akun="103";	
			}
			else{
				$keterangan.=' dengan cek';		
				$akun="203";
			}


			$dataJurnal = array(
				'IDJurnal' =>$IDJurnal,
				'Tanggal' => date('Y-m-d'),
				'Keterangan'=> $keterangan,
				'NoBukti'=> $idPelunasan,
				'JenisJurnal' => 'JU',
				'IDPeriode' => '20172');

			//insert jurnal 
			if($this->Jurnal_model->add_jurnal($dataJurnal)){
				if($disc>0){
					$totalDiskon=($nominal*$disc)/100;
					$tempNominal = $nominal-$totalDiskon;
					//insert akun pembayaran entah itu kas,rekening, atau cek
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>1,
						'NominalDebet' => $tempNominal,
						'NominalKredit' =>0, 
					);
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					$data = array(
					'IDJurnal' =>$IDJurnal,
					'NoAkun' => '104',
					'Urutan' =>2,
					'NominalDebet' => 0,
					'NominalKredit' =>$nominal, 
					);
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					$data = array(
					'IDJurnal' =>$IDJurnal,
					'NoAkun' => '402',
					'Urutan' =>3,
					'NominalDebet' => $totalDiskon,
					'NominalKredit' => 0, 
					);
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
				}//Jika Tidak ada diskon
				else{
					//insert akun pembayaran entah itu kas,rekening, atau cek
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>1,
						'NominalDebet' => $nominal,
						'NominalKredit' => 0, 
					);
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					$data = array(
					'IDJurnal' =>$IDJurnal,
					'NoAkun' => '104',
					'Urutan' =>2,
					'NominalDebet' => 0,
					'NominalKredit' =>$nominal, 
					);
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
				}
			}
			header("Location: ".site_url('PelunasanPiutang/index'));
		}
	}

}
