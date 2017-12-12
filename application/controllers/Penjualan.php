<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {

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
        $this->load->model('Pelanggan_model');
        $this->load->model('NotaJual_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Jurnal_model');
        $this->load->model('JurnalHasAkun_model');
        $this->load->model('Periode_model');
        $this->load->model('Bank_model');
        $this->load->helper('url_helper');
        $this->load->helper('form');
    	$this->load->library('form_validation');
    	$this->load->library('cart');
        $this->load->library('session');
    }
	public function index()
	{
		$angka = '';
        if($this->NotaJual_model->get_last_nota()) {
            $temp = $this->NotaJual_model->get_last_nota();
            $angka = intval(substr($temp['NoNotaJual'], 7, 4)) + 1;
            $tempAngka = '';
            for($i = 0; $i < (4 - strlen($angka)); $i++) {
                $tempAngka .= '0';
            }
            $angka = $tempAngka.$angka;
        } else {
            $angka = '0001';
        }

        $data['NoNotaJual'] = date('Y').'/NJ'.$angka;
		$data['barang'] = $this->Barang_model->get_barang();
		$data['pelanggan'] = $this->Pelanggan_model->get_pelanggan();
		$data['bank'] = $this->Bank_model->get_bank();

		$this->cart->destroy();

		$this->load->view('layout/header');
		$this->load->view('penjualan/penjualan',$data);
		$this->load->view('layout/footer');
	}
	public function create_nota(){
		$NoNotaJual = $this->input->post('NoNotaJual');
		$tgl = $this->input->post('tgl');
		$customer = $this->input->post('customer');
		$keterangan ="";
		$temp = $this->Jurnal_model->get_last_jurnal();
		$IDJurnal = $temp['IDJurnal']+1; 
		$dataNotaJual = array(
			'NoNotaJual' => $NoNotaJual,
			'Tanggal' => date('Y-m-d'),
			'KodePelanggan' => $customer,
			'StatusKirim' => 1,
			'JasaPengiriman' =>$this->input->post('kurir'),
			'JenisPembayaranPengiriman' => $this->input->post('jPembayaranKirim')
		);

		//Jika ada pengiriman
		if($this->input->post('kirim')=='true'){
			$biayaKirim = $this->input->post('biayaKirim');
			$fob = $this->input->post('fob');

			$dataNotaJual['OngkosKirim'] = $biayaKirim;
			$dataNotaJual['FOB'] = $fob;
		}

		if($this->input->post('jPembayaran')=='K'){
			$jenisPembayaran = $this->input->post('jPembayaran');
			$tanggalJatuhTempo = $this->input->post('jt');
			$discPelunasan = $this->input->post('discPelunasan');
			$batasPelunasan = $this->input->post('batasPelunasan');

			$dataNotaJual['JenisPembayaran'] = $jenisPembayaran;
			$dataNotaJual['DiskonPelunasan'] = $discPelunasan;
			$dataNotaJual['TanggalBatasDiskon'] = $batasPelunasan;
			$dataNotaJual['TanggalJatuhTempo'] = $tanggalJatuhTempo;

			$keterangan.="Transaksi Penjualan Kredit ";
		}
		else{
			$jenisPembayaran = $this->input->post('jPembayaran');

			$dataNotaJual['JenisPembayaran'] = $jenisPembayaran;
		}

		if($this->input->post('jPembayaran')=='TR')
		{
			$keterangan .= 'Transaksi Penjualan transfer ';
		}
		else if ($this->input->post('jPembayaran')=='T')
		{
			$keterangan .= 'Transaksi Penjualan tunai ';
		}
		else if ($this->input->post('jPembayaran')=='C')
		{
			$keterangan .= 'Transaksi Penjualan cek ';
			$dataNotaJual['NoCek']= $this->input->post('nomorCek');
		}

		if($this->input->post('ppn')!=''){
			$ppn = $this->input->post('ppn');

			$dataNotaJual['PPN'] = $ppn;

			$keterangan .= 'dengan PPN ';
		}
		if($this->input->post('disc')!=''){
			$disc = $this->input->post('disc');

			$dataNotaJual['Diskon'] = $disc;

			$keterangan .= 'dengan diskon pembayaran ';
		}
		if($this->input->post('bank')!=''){
			$bank = $this->input->post('bank');

			$dataNotaJual['IdBank'] = $bank;

			$keterangan .= 'dan di Transfer ke bank '.$this->input->post('bank')." ";
		}

		$total = $this->input->post('total');
		$bayar = $this->input->post('bayar');

		$dataNotaJual['Total'] = $total;
		$dataNotaJual['Bayar'] = $bayar;


		$totalJenis1=0;
		$totalJenis2=0;
		$totalJenis=0;
		if($this->NotaJual_model->add_nota_jual($dataNotaJual)){
			//Untuk mengisi data pada table penjualan
			//Mengambil data dari library cart
			foreach($this->cart->contents() as $item){
				if($item['NoJenis']==1){
					$totalJenis1+=$item['HargaBeli']*$item['qty'];
					$totalJenis+=$totalJenis1;
				}else{
					$totalJenis2+=$item['HargaBeli']*$item['qty'];
					$totalJenis+=$totalJenis2;
				}
				$dataPenjualan = array(
					'NoNotaJual' => $NoNotaJual,
					'KodeBarang' => $item['id'],
					'Harga' => $item['price'],
					'Jumlah' => $item['qty'],
				);

				$this->Penjualan_model->add_penjualan($dataPenjualan);

				//untul mengupdate stok barang setelah melakukan transaksi makan jumlah barang pasti berkurang
				//mengambil data barang
				$barang = $this->Barang_model->get_barang($item['id']);
				$stokBaru = $barang['Stok']-$item['qty'];
				$dataUpdate = array(
					'KodeBarang' =>$item['id'],
					'Stok' => $stokBaru,
				);
				$this->Barang_model->update_barang($dataUpdate);
			}

			$dataJurnal = array(
				'IDJurnal' =>$IDJurnal,
				'Tanggal' => date('Y-m-d'),
				'Keterangan'=> $keterangan,
				'NoBukti'=> $NoNotaJual,
				'JenisJurnal' => 'JU',
				'IDPeriode' => '20172');

			//insert jurnal 
			if($this->Jurnal_model->add_jurnal($dataJurnal)){
				//Jika Transaksi Secara Tunai
				if($this->input->post('jPembayaran')=='T'){
					//data untuk urutan pertama jika tunai
					$akun = "101";
					$totalPenjualan =$this->cart->total();
					$ppn=0;
					if($this->input->post('ppn')!=''){
						$ppn = $this->input->post('ppn');
						$ppn=$totalPenjualan*$ppn/100;
						$totalPenjualan=$totalPenjualan+$ppn;
					}
					if($this->input->post('disc')!=''){
						$disc = $this->input->post('disc');
						$totalPenjualan = $totalPenjualan+(($totalPenjualan*$disc)/100);
					}
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>1,
						'NominalDebet' => $totalPenjualan,
						'NominalKredit' =>0, 
					);
					//insert ke jurnal has akun
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					//Jika ada ppn ato tidak
					if($this->input->post('ppn')!=''){
						$totalPenjualan = $totalPenjualan-$ppn;

						//insert akun penjualan
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '401',
							'Urutan' =>2,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalPenjualan, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun Hutang PPN
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '204',
							'Urutan' =>3,
							'NominalDebet' => 0,
							'NominalKredit' =>$ppn, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun HPP
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '501',
							'Urutan' =>4,
							'NominalDebet' => $totalJenis,
							'NominalKredit' =>0, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//Jika ada barang berjenis satu
						if($totalJenis1>0){
							//insert jenis barang 1
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>5,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis1, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//insert jenis barang 2 jika ada
							if($totalJenis2>0){
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => '107',
									'Urutan' =>6,
									'NominalDebet' => 0,
									'NominalKredit' =>$totalJenis2, 
								);
								//insert ke jurnal has akun
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>7,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>8,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}							
							}//jika tidak ada barang 2
							else{
								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>6,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>7,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}
							}								
						}//barang berjenis 2 saja
						else{
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>5,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis2, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//Jika ada pengiriman
							if($this->input->post('kirim')=='true'){
							
								$fob = $this->input->post('fob');
								$biayaKirim = 0;
								if($fob == "FOB Destination Point")
								{
									$biayaKirim = $this->input->post('biayaKirim');
									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => 520,
										'Urutan' =>6,
										'NominalDebet' => $biayaKirim,
										'NominalKredit' =>0, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

									if($this->input->post('jPembayaranKirim')=='TR')
									{
										if($this->input->post('bank')==1)
											$akun='102';
										else
											$akun='103';
									}
									else if ($this->input->post('jPembayaranKirim')=='T')
									{
										$akun='101';
									}

									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => $akun,
										'Urutan' =>7,
										'NominalDebet' => 0,
										'NominalKredit' =>$biayaKirim, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
								}
							}
						}									
					}//tidak ada PPN
					else{
						//insert akun penjualan
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '401',
							'Urutan' =>2,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalPenjualan, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun HPP
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '501',
							'Urutan' =>3,
							'NominalDebet' => $totalJenis,
							'NominalKredit' =>0, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//Jika ada barang berjenis satu
						if($totalJenis1>0){
							//insert jenis barang 1
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>4,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis1, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//insert jenis barang 2 jika ada
							if($totalJenis2>0){
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => '107',
									'Urutan' =>5,
									'NominalDebet' => 0,
									'NominalKredit' =>$totalJenis2, 
								);
								//insert ke jurnal has akun
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>6,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>7,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}							
							}//jika tidak ada barang 2
							else{
								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>5,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>6,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}	
							}								
						}//barang berjenis 2 saja
						else{
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>4,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis2, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//Jika ada pengiriman
							if($this->input->post('kirim')=='true'){
							
								$fob = $this->input->post('fob');
								$biayaKirim = 0;
								if($fob == "FOB Destination Point")
								{
									$biayaKirim = $this->input->post('biayaKirim');
									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => 520,
										'Urutan' =>5,
										'NominalDebet' => $biayaKirim,
										'NominalKredit' =>0, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

									if($this->input->post('jPembayaranKirim')=='TR')
									{
										if($this->input->post('bank')==1)
											$akun='102';
										else
											$akun='103';
									}
									else if ($this->input->post('jPembayaranKirim')=='T')
									{
										$akun='101';
									}

									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => $akun,
										'Urutan' =>6,
										'NominalDebet' => 0,
										'NominalKredit' =>$biayaKirim, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
								}
							}
						}						
					}
				}
				//Jika Transaksi Secara Kredit 
				else if($this->input->post('jPembayaran')=='K'){
					//data untuk urutan pertama jika Kredit
					$akun = "104";
					$totalPenjualan =$this->cart->total();
					$ppn=0;
					if($this->input->post('ppn')!=''){
						$ppn = $this->input->post('ppn');
						$ppn=$totalPenjualan*$ppn/100;
						$totalPenjualan=$totalPenjualan+$ppn;
					}
					if($this->input->post('disc')!=''){
						$disc = $this->input->post('disc');
						$totalPenjualan = $totalPenjualan+(($totalPenjualan*$disc)/100);
					}
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>1,
						'NominalDebet' => $totalPenjualan,
						'NominalKredit' =>0, 
					);
					//insert ke jurnal has akun
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					//Jika ada ppn ato tidak
					if($this->input->post('ppn')!=''){
						$totalPenjualan = $totalPenjualan-$ppn;

						//insert akun penjualan
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '401',
							'Urutan' =>2,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalPenjualan, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun Hutang PPN
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '204',
							'Urutan' =>3,
							'NominalDebet' => 0,
							'NominalKredit' =>$ppn, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun HPP
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '501',
							'Urutan' =>4,
							'NominalDebet' => $totalJenis,
							'NominalKredit' =>0, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//Jika ada barang berjenis satu
						if($totalJenis1>0){
							//insert jenis barang 1
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>5,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis1, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//insert jenis barang 2 jika ada
							if($totalJenis2>0){
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => '107',
									'Urutan' =>6,
									'NominalDebet' => 0,
									'NominalKredit' =>$totalJenis2, 
								);
								//insert ke jurnal has akun
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>7,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>8,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}							
							}//jika tidak ada barang 2
							else{
								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>6,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>7,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}
							}								
						}//barang berjenis 2 saja
						else{
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>5,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis2, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//Jika ada pengiriman
							if($this->input->post('kirim')=='true'){
							
								$fob = $this->input->post('fob');
								$biayaKirim = 0;
								if($fob == "FOB Destination Point")
								{
									$biayaKirim = $this->input->post('biayaKirim');
									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => 520,
										'Urutan' =>6,
										'NominalDebet' => $biayaKirim,
										'NominalKredit' =>0, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

									if($this->input->post('jPembayaranKirim')=='TR')
									{
										if($this->input->post('bank')==1)
											$akun='102';
										else
											$akun='103';
									}
									else if ($this->input->post('jPembayaranKirim')=='T')
									{
										$akun='101';
									}

									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => $akun,
										'Urutan' =>7,
										'NominalDebet' => 0,
										'NominalKredit' =>$biayaKirim, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
								}
							}
						}									
					}//tidak ada PPN
					else{
						//insert akun penjualan
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '401',
							'Urutan' =>2,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalPenjualan, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun HPP
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '501',
							'Urutan' =>3,
							'NominalDebet' => $totalJenis,
							'NominalKredit' =>0, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//Jika ada barang berjenis satu
						if($totalJenis1>0){
							//insert jenis barang 1
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>4,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis1, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//insert jenis barang 2 jika ada
							if($totalJenis2>0){
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => '107',
									'Urutan' =>5,
									'NominalDebet' => 0,
									'NominalKredit' =>$totalJenis2, 
								);
								//insert ke jurnal has akun
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>6,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>7,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}							
							}//jika tidak ada barang 2
							else{
								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>5,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>6,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}	
							}								
						}//barang berjenis 2 saja
						else{
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>4,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis2, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//Jika ada pengiriman
							if($this->input->post('kirim')=='true'){
							
								$fob = $this->input->post('fob');
								$biayaKirim = 0;
								if($fob == "FOB Destination Point")
								{
									$biayaKirim = $this->input->post('biayaKirim');
									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => 520,
										'Urutan' =>5,
										'NominalDebet' => $biayaKirim,
										'NominalKredit' =>0, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

									if($this->input->post('jPembayaranKirim')=='TR')
									{
										if($this->input->post('bank')==1)
											$akun='102';
										else
											$akun='103';
									}
									else if ($this->input->post('jPembayaranKirim')=='T')
									{
										$akun='101';
									}

									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => $akun,
										'Urutan' =>6,
										'NominalDebet' => 0,
										'NominalKredit' =>$biayaKirim, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
								}
							}
						}						
					}	
				}
				//Jika Transaksi Secara Cek
				else if ($this->input->post('jPembayaran')=='C'){
					//data untuk urutan pertama jika Cek
					$akun = "105";
					$totalPenjualan =$this->cart->total();
					$ppn=0;
					if($this->input->post('ppn')!=''){
						$ppn = $this->input->post('ppn');
						$ppn=$totalPenjualan*$ppn/100;
						$totalPenjualan=$totalPenjualan+$ppn;
					}
					if($this->input->post('disc')!=''){
						$disc = $this->input->post('disc');
						$totalPenjualan = $totalPenjualan+(($totalPenjualan*$disc)/100);
					}
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>1,
						'NominalDebet' => $totalPenjualan,
						'NominalKredit' =>0, 
					);
					//insert ke jurnal has akun
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					//Jika ada ppn ato tidak
					if($this->input->post('ppn')!=''){
						$totalPenjualan = $totalPenjualan-$ppn;

						//insert akun penjualan
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '401',
							'Urutan' =>2,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalPenjualan, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun Hutang PPN
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '204',
							'Urutan' =>3,
							'NominalDebet' => 0,
							'NominalKredit' =>$ppn, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun HPP
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '501',
							'Urutan' =>4,
							'NominalDebet' => $totalJenis,
							'NominalKredit' =>0, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//Jika ada barang berjenis satu
						if($totalJenis1>0){
							//insert jenis barang 1
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>5,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis1, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//insert jenis barang 2 jika ada
							if($totalJenis2>0){
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => '107',
									'Urutan' =>6,
									'NominalDebet' => 0,
									'NominalKredit' =>$totalJenis2, 
								);
								//insert ke jurnal has akun
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>7,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>8,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}							
							}//jika tidak ada barang 2
							else{
								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>6,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>7,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}
							}								
						}//barang berjenis 2 saja
						else{
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>5,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis2, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//Jika ada pengiriman
							if($this->input->post('kirim')=='true'){
							
								$fob = $this->input->post('fob');
								$biayaKirim = 0;
								if($fob == "FOB Destination Point")
								{
									$biayaKirim = $this->input->post('biayaKirim');
									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => 520,
										'Urutan' =>6,
										'NominalDebet' => $biayaKirim,
										'NominalKredit' =>0, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

									if($this->input->post('jPembayaranKirim')=='TR')
									{
										if($this->input->post('bank')==1)
											$akun='102';
										else
											$akun='103';
									}
									else if ($this->input->post('jPembayaranKirim')=='T')
									{
										$akun='101';
									}

									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => $akun,
										'Urutan' =>7,
										'NominalDebet' => 0,
										'NominalKredit' =>$biayaKirim, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
								}
							}
						}									
					}//tidak ada PPN
					else{
						//insert akun penjualan
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '401',
							'Urutan' =>2,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalPenjualan, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun HPP
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '501',
							'Urutan' =>3,
							'NominalDebet' => $totalJenis,
							'NominalKredit' =>0, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//Jika ada barang berjenis satu
						if($totalJenis1>0){
							//insert jenis barang 1
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>4,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis1, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//insert jenis barang 2 jika ada
							if($totalJenis2>0){
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => '107',
									'Urutan' =>5,
									'NominalDebet' => 0,
									'NominalKredit' =>$totalJenis2, 
								);
								//insert ke jurnal has akun
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>6,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>7,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}							
							}//jika tidak ada barang 2
							else{
								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>5,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>6,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}	
							}								
						}//barang berjenis 2 saja
						else{
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>4,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis2, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//Jika ada pengiriman
							if($this->input->post('kirim')=='true'){
							
								$fob = $this->input->post('fob');
								$biayaKirim = 0;
								if($fob == "FOB Destination Point")
								{
									$biayaKirim = $this->input->post('biayaKirim');
									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => 520,
										'Urutan' =>5,
										'NominalDebet' => $biayaKirim,
										'NominalKredit' =>0, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

									if($this->input->post('jPembayaranKirim')=='TR')
									{
										if($this->input->post('bank')==1)
											$akun='102';
										else
											$akun='103';
									}
									else if ($this->input->post('jPembayaranKirim')=='T')
									{
										$akun='101';
									}

									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => $akun,
										'Urutan' =>6,
										'NominalDebet' => 0,
										'NominalKredit' =>$biayaKirim, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
								}
							}
						}						
					}
				}
				//Jika Transaksi Secara Transfer Bank
				else{
					//data untuk urutan pertama jika Kredit
					$akun = "";
					if($this->input->post('bank')==1)
						$akun='102';
					else
						$akun='103';
					$totalPenjualan =$this->cart->total();
					$ppn=0;
					if($this->input->post('ppn')!=''){
						$ppn = $this->input->post('ppn');
						$ppn=$totalPenjualan*$ppn/100;
						$totalPenjualan=$totalPenjualan+$ppn;
					}
					if($this->input->post('disc')!=''){
						$disc = $this->input->post('disc');
						$totalPenjualan = $totalPenjualan+(($totalPenjualan*$disc)/100);
					}
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>1,
						'NominalDebet' => $totalPenjualan,
						'NominalKredit' =>0, 
					);
					//insert ke jurnal has akun
					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					//Jika ada ppn ato tidak
					if($this->input->post('ppn')!=''){
						$totalPenjualan = $totalPenjualan-$ppn;

						//insert akun penjualan
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '401',
							'Urutan' =>2,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalPenjualan, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun Hutang PPN
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '204',
							'Urutan' =>3,
							'NominalDebet' => 0,
							'NominalKredit' =>$ppn, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun HPP
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '501',
							'Urutan' =>4,
							'NominalDebet' => $totalJenis,
							'NominalKredit' =>0, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//Jika ada barang berjenis satu
						if($totalJenis1>0){
							//insert jenis barang 1
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>5,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis1, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//insert jenis barang 2 jika ada
							if($totalJenis2>0){
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => '107',
									'Urutan' =>6,
									'NominalDebet' => 0,
									'NominalKredit' =>$totalJenis2, 
								);
								//insert ke jurnal has akun
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>7,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>8,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}							
							}//jika tidak ada barang 2
							else{
								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>6,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>7,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}
							}								
						}//barang berjenis 2 saja
						else{
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>5,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis2, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//Jika ada pengiriman
							if($this->input->post('kirim')=='true'){
							
								$fob = $this->input->post('fob');
								$biayaKirim = 0;
								if($fob == "FOB Destination Point")
								{
									$biayaKirim = $this->input->post('biayaKirim');
									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => 520,
										'Urutan' =>6,
										'NominalDebet' => $biayaKirim,
										'NominalKredit' =>0, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

									if($this->input->post('jPembayaranKirim')=='TR')
									{
										if($this->input->post('bank')==1)
											$akun='102';
										else
											$akun='103';
									}
									else if ($this->input->post('jPembayaranKirim')=='T')
									{
										$akun='101';
									}

									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => $akun,
										'Urutan' =>7,
										'NominalDebet' => 0,
										'NominalKredit' =>$biayaKirim, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
								}
							}
						}									
					}//tidak ada PPN
					else{
						//insert akun penjualan
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '401',
							'Urutan' =>2,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalPenjualan, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//insert akun HPP
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => '501',
							'Urutan' =>3,
							'NominalDebet' => $totalJenis,
							'NominalKredit' =>0, 
						);
						//insert ke jurnal has akun
						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						//Jika ada barang berjenis satu
						if($totalJenis1>0){
							//insert jenis barang 1
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '106',
								'Urutan' =>4,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis1, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//insert jenis barang 2 jika ada
							if($totalJenis2>0){
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => '107',
									'Urutan' =>5,
									'NominalDebet' => 0,
									'NominalKredit' =>$totalJenis2, 
								);
								//insert ke jurnal has akun
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>6,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>7,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}							
							}//jika tidak ada barang 2
							else{
								//Jika ada pengiriman
								if($this->input->post('kirim')=='true'){
								
									$fob = $this->input->post('fob');
									$biayaKirim = 0;
									if($fob == "FOB Destination Point")
									{
										$biayaKirim = $this->input->post('biayaKirim');
										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => 520,
											'Urutan' =>5,
											'NominalDebet' => $biayaKirim,
											'NominalKredit' =>0, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

										if($this->input->post('jPembayaranKirim')=='TR')
										{
											if($this->input->post('bank')==1)
												$akun='102';
											else
												$akun='103';
										}
										else if ($this->input->post('jPembayaranKirim')=='T')
										{
											$akun='101';
										}

										$data = array(
											'IDJurnal' =>$IDJurnal,
											'NoAkun' => $akun,
											'Urutan' =>6,
											'NominalDebet' => 0,
											'NominalKredit' =>$biayaKirim, 
										);
										$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
									}
								}	
							}								
						}//barang berjenis 2 saja
						else{
							$data = array(
								'IDJurnal' =>$IDJurnal,
								'NoAkun' => '107',
								'Urutan' =>4,
								'NominalDebet' => 0,
								'NominalKredit' =>$totalJenis2, 
							);
							//insert ke jurnal has akun
							$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

							//Jika ada pengiriman
							if($this->input->post('kirim')=='true'){
							
								$fob = $this->input->post('fob');
								$biayaKirim = 0;
								if($fob == "FOB Destination Point")
								{
									$biayaKirim = $this->input->post('biayaKirim');
									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => 520,
										'Urutan' =>5,
										'NominalDebet' => $biayaKirim,
										'NominalKredit' =>0, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

									if($this->input->post('jPembayaranKirim')=='TR')
									{
										if($this->input->post('bank')==1)
											$akun='102';
										else
											$akun='103';
									}
									else if ($this->input->post('jPembayaranKirim')=='T')
									{
										$akun='101';
									}

									$data = array(
										'IDJurnal' =>$IDJurnal,
										'NoAkun' => $akun,
										'Urutan' =>6,
										'NominalDebet' => 0,
										'NominalKredit' =>$biayaKirim, 
									);
									$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
								}
							}
						}						
					}
				}

				header("Location: ".site_url('Penjualan/index'));
			}
		}
		echo $dataNotaJual['Tanggal'];
	}

	//untuk mengisi keranjang belanja
	public function add_cart(){
		$barang = $this->Barang_model->get_barang($_POST['id_barang']);
		$data = array(
			'id' => $barang['KodeBarang'],
			'qty' => $_POST['jumlah'],
			'price' => $barang['HargaJual'],
			'name' => $barang['Nama'],
			'NoJenis' => $barang['NoJenisBarang'],
			'HargaBeli' => $barang['HargaBeli'],
		);

		$this->cart->insert($data);

		echo $this->view();
	}
	public function view(){
		$output="";
		$count =0;
		foreach ($this->cart->contents() as $value){
			$count++;
			$output.='
				<tr>
					<td>'.$count.'</td>
					<td>'.$value['name'].'</td>
					<td>'.$value['qty'].'</td>
					<td>Rp. '.$value['price'].'</td>
					<td>Rp. '.$value['subtotal'].'</td>
					<td align="center ">
						<button type="button" class="btn btn-danger btn-xs hapus-barang" name="hapus" id="'.$value['rowid'].'">hapus</button>
					</td>
				</tr>
			';
		}

		$output.='
			<tr>
				<td colspan="4">Total</td>
				<td colspan="2" align="center">Rp. '.$this->cart->total().'</td>
			</tr>
			<input type="hidden" form="form_penjualan" id="idTotal" name="total" value="'.$this->cart->total().'">
		';

		if($count==0)
			$output='';

		return $output;
	}
	public function load(){
		echo $this->view();
	}
	public function remove(){
		$rowid = $_POST['row_id'];
		$data = array(
			'rowid' => $rowid,
			'qty' =>0
		);
		$this->cart->update($data);
		echo $this->view();
	}
	public function clear_cart(){
		$this->cart->destroy();
		echo $this->view();
	}
}
