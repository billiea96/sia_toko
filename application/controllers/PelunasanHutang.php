<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PelunasanHutang extends CI_Controller {

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
        $this->load->model('NotaBeli_model');
        $this->load->model('Jurnal_model');
        $this->load->model('JurnalHasAkun_model');
        $this->load->model('Pembelian_model');
        $this->load->model('PelunasanHutang_model');
        $this->load->helper('url_helper');
        $this->load->helper('form');
    	$this->load->library('form_validation');
    	$this->load->library('cart');
        $this->load->library('session');
    }
	public function index()
	{
		$angka = '';
        if($this->PelunasanHutang_model->get_last_pelunasan()) {
            $temp = $this->PelunasanHutang_model->get_last_pelunasan();
            $angka = intval(substr($temp['NoPelunasan'], 2, 4)) + 1;
            $tempAngka = '';
            for($i = 0; $i < (4 - strlen($angka)); $i++) {
                $tempAngka .= '0';
            }
            $angka = $tempAngka.$angka;
        } else {
            $angka = '0001';
        }

        $data['NoPelunasan'] = 'PB'.$angka;
		$data['NoNotaBeli'] = $this->NotaBeli_model->pembayaran_kredit();
		$data['barang'] = $this->Barang_model->get_barang();
		$data['supplier'] = $this->Supplier_model->get_supplier();
		$data['bank'] = $this->Bank_model->get_bank();

		$this->load->view('layout/header');
		$this->load->view('pelunasan/pelunasanBeli', $data);
		$this->load->view('layout/footer');
	}

	public function simpan(){
		$noPelunasan = $this->input->post('noPelunasan');
		$nota = $this->input->post('noNota');
		$jp = $this->input->post('jPembayaran');
		$nominal = $this->input->post('nominal');
		$disc = $this->input->post('discPelunasan');
		$bayar = $this->input->post('totalBayar');
		$idbank = $this->input->post('bank');
		$norek = $this->input->post('noRek');
		$nocek = $this->input->post('noCek');
		$pemilikBank = $this->input->post('namaPemilik');

		$temp = $this->Jurnal_model->get_last_jurnal();
		$IDJurnal = $temp['IDJurnal']+1; 
		$keterangan ='';

		$dataSimpan = array(
			'NoPelunasan' =>$noPelunasan,
			'Tanggal' => date('Y-m-d'),
			'NominalSeharusnya' => $nominal,
			'DiskonPelunasan' => $disc,
			'Bayar' => $bayar,
			'JenisPembayaran' => $jp,
			'NoNotaBeli' => $nota,
			'IdBank' => $idbank,
			'NoRekening' => $norek,
			'PemilikRekening' => $pemilikBank,
			'NoCek' => $nocek,
		);

		if($this->PelunasanHutang_model->add_pelunasan_hutang($dataSimpan)){
			//untuk set lunas
			$this->NotaBeli_model->set_lunas($nota);

			$notabeli = $this->NotaBeli_model->get_nota($nota);
			$keterangan ='Pelunasan transaksi pembelian tanggal '.$notabeli['Tanggal'];
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
				'NoBukti'=> $noPelunasan,
				'JenisJurnal' => 'JU',
				'IDPeriode' => '20172');

			//insert jurnal 
			if($this->Jurnal_model->add_jurnal($dataJurnal)){
				//insert akung hutang dagang
				$data = array(
					'IDJurnal' =>$IDJurnal,
					'NoAkun' => '201',
					'Urutan' =>1,
					'NominalDebet' => $nominal,
					'NominalKredit' =>0, 
				);
				$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

				//Jika mendapatkan diskon pelunasan
				if($disc>=0){
					$totalDiskon=($nominal*$disc)/100;
					$nominal = $nominal-$totalDiskon;
					//insert akun pembayaran entah itu kas,rekening, atau cek
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>2,
						'NominalDebet' => 0,
						'NominalKredit' =>$nominal, 
					);
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					$urutan =2;
					$sediaan = $this->JurnalHasAkun_model->get_jurnal($nota);
					foreach ($sediaan as $key => $value) {
						$urutan++;
						if($value['NoAkun']=='106'){
							//insert sediaan barang
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>$urutan,
								'NominalDebet' => 0,
								'NominalKredit' =>(($value['NominalDebet']*$disc)/100), 
							);
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
						}else{
							//insert sediaan barang
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>$urutan,
								'NominalDebet' => 0,
								'NominalKredit' =>(($value['NominalDebet']*$disc)/100), 
							);
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
						}
					}
				}//Jika Tidak ada diskon
				else{
					//insert akun pembayaran entah itu kas,rekening, atau cek
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>2,
						'NominalDebet' => 0,
						'NominalKredit' =>$nominal, 
					);
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
				}

			}
			header("Location: ".site_url('PelunasanHutang/index'));
		}
	}

	public function detail_nota(){
		$NotaBeli = $this->NotaBeli_model->get_nota($_POST['noNota']);

		$sisaBayar = $NotaBeli['Total']-(($NotaBeli['Total']*$NotaBeli['Diskon'])/100)- $NotaBeli['Bayar'];
		//$totalBayar = $NotaBeli['Total'] - ($NotaBeli['Total'] * ($NotaBeli['DiskonPelunasan']/100));
		$totalBayar = $sisaBayar - (($sisaBayar) * ($NotaBeli['DiskonPelunasan']/100));
		
		echo json_encode(array('sisaBayar' => $sisaBayar,'diskon'=>$NotaBeli['DiskonPelunasan'], 'total'=>$NotaBeli['Total'], 'totalBayar' => $totalBayar, 'tgl'=>$NotaBeli['TanggalBatasDiskon']));
	}


}
