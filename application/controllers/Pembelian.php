<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {

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
        $this->load->model('Jurnal_model');
        $this->load->model('JurnalHasAkun_model');
        $this->load->model('Periode_model');
        $this->load->model('NotaBeli_model');
        $this->load->model('Pembelian_model');
        $this->load->helper('url_helper');
        $this->load->helper('form');
    	$this->load->library('form_validation');
    	$this->load->library('cart');
        $this->load->library('session');
    }
	public function index()
	{
		$angka = '';
        if($this->NotaBeli_model->get_last_nota()) {
            $temp = $this->NotaBeli_model->get_last_nota();
            $angka = intval(substr($temp['NoNotaBeli'], 7, 4)) + 1;
            $tempAngka = '';
            for($i = 0; $i < (4 - strlen($angka)); $i++) {
                $tempAngka .= '0';
            }
            $angka = $tempAngka.$angka;
        } else {
            $angka = '0001';
        }

        $data['NoNotaBeli'] = date('Y').'/NB'.$angka;
		$data['barang'] = $this->Barang_model->get_barang();
		$data['supplier'] = $this->Supplier_model->get_supplier();
		$data['bank'] = $this->Bank_model->get_bank();

		$this->cart->destroy();

		$this->load->view('layout/header');
		$this->load->view('pembelian/pembelian',$data);
		$this->load->view('layout/footer');
	}
	public function create_nota(){

		$keterangan = "";
		$NoNotaBeli = $this->input->post('NoNotaBeli');
		$tgl = $this->input->post('tgl');
		$customer = $this->input->post('supplier');	
		$temp = $this->Jurnal_model->get_last_jurnal();
		$IDJurnal = $temp['IDJurnal']+1; 
		$dataNotaBeli = array(
			'NoNotaBeli' => $NoNotaBeli,
			'Tanggal' => date('Y-m-d'),
			'KodeSupplier' => $customer,
			'StatusKirim' => 1,
		);

		//Jika ada pengiriman
		if($this->input->post('kirim')=='true'){
			$biayaKirim = $this->input->post('biayaKirim');
			$fob = $this->input->post('fob');

			$dataNotaBeli['OngkosKirim'] = $biayaKirim;
			$dataNotaBeli['FOB'] = $fob;
		}
		

		if($this->input->post('jPembayaran')=='K'){
			$jenisPembayaran = $this->input->post('jPembayaran');
			$tanggalJatuhTempo = $this->input->post('jt');
			$discPelunasan = $this->input->post('discPelunasan');
			$batasPelunasan = $this->input->post('batasPelunasan');

			$dataNotaBeli['JenisPembayaran'] = $jenisPembayaran;
			$dataNotaBeli['DiskonPelunasan'] = $discPelunasan;
			$dataNotaBeli['TanggalBatasDiskon'] = $batasPelunasan;
			$dataNotaBeli['TanggalJatuhTempo'] = $tanggalJatuhTempo;
			$keterangan .= 'Transaksi pembelian kredit ';
		}
		else{
			$jenisPembayaran = $this->input->post('jPembayaran');

			$dataNotaBeli['JenisPembayaran'] = $jenisPembayaran;
			
		}

		if($this->input->post('jPembayaran')=='TR')
		{
			$keterangan .= 'Transaksi pembelian transfer ';
		}
		else if ($this->input->post('jPembayaran')=='T')
		{
			$keterangan .= 'Transaksi pembelian tunai ';
		}
		else if ($this->input->post('jPembayaran')=='C')
		{
			$keterangan .= 'Transaksi pembelian cek ';
		}

		if($this->input->post('disc')>0){
			$disc = $this->input->post('disc');

			$dataNotaBeli['Diskon'] = $disc;
			
			$keterangan .= 'dengan diskon pembayaran ';
		}
		if($this->input->post('bank')!=''){
			$bank = $this->input->post('bank');

			$dataNotaBeli['IdBank'] = $bank;
			$keterangan .= 'ke bank '.$this->input->post('bank')." ";
		}

		$total = $this->input->post('total');
		$bayar = $this->input->post('bayar');

		$dataNotaBeli['Total'] = $total;
		$dataNotaBeli['Bayar'] = $bayar;
		
		$totalJenis1=0;
		$totalJenis2=0;
		if($this->NotaBeli_model->add_nota_beli($dataNotaBeli)){
			//Untuk mengisi data pada table pembelian
			foreach($this->cart->contents() as $item){
				if($item['NoJenis']==1){
					$totalJenis1+=$item['price']*$item['qty'];
				}else{
					$totalJenis2+=$item['price']*$item['qty'];
				}
				$datapembelian = array(
					'NoNotaBeli' => $NoNotaBeli,
					'KodeBarang' => $item['id'],
					'Harga' => $item['price'],
					'Jumlah' => $item['qty'],
				);

				$this->Pembelian_model->add_pembelian($datapembelian);
			}
			$dataJurnal = array(
				'IDJurnal' =>$IDJurnal,
				'Tanggal' => date('Y-m-d'),
				'Keterangan'=> $keterangan,
				'NoBukti'=> $NoNotaBeli,
				'JenisJurnal' => 'JU',
				'IDPeriode' => '20172');

			//insert jurnal 

			if($this->Jurnal_model->add_jurnal($dataJurnal))
			{
				//sediaan barang 1
				if($totalJenis1>0){
					$akun = "106";
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>1,
						'NominalDebet' => $totalJenis1,
						'NominalKredit' =>0, 
					);

					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					if($this->input->post('jPembayaran')=='TR')
					{
						if($this->input->post('bank')==1)
							$akun='102';
						else
							$akun='103';
					}
					else if ($this->input->post('jPembayaran')=='T')
					{
						$akun='101';
					}
					else if ($this->input->post('jPembayaran')=='C')
					{
						$akun='203';
					}
					else{
						$akun='201';
					}

					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>2,
						'NominalDebet' => 0,
						'NominalKredit' =>$totalJenis1, 
					);

					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					if($totalJenis2>0){
						$akun = "107";
						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => $akun,
							'Urutan' =>3,
							'NominalDebet' => $totalJenis2,
							'NominalKredit' =>0, 
						);

						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);


						if($this->input->post('jPembayaran')=='TR')
						{
							if($this->input->post('bank')==1)
								$akun='102';
							else
								$akun='103';
						}
						else if ($this->input->post('jPembayaran')=='T')
						{
							$akun='101';
						}
						else if ($this->input->post('jPembayaran')=='C')
						{
							$akun='203';
						}
						else{
							$akun='201';
						}

						$data = array(
							'IDJurnal' =>$IDJurnal,
							'NoAkun' => $akun,
							'Urutan' =>4,
							'NominalDebet' => 0,
							'NominalKredit' =>$totalJenis2, 
						);

						$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

						if($this->input->post('kirim')=='true'){
							
							$fob = $this->input->post('fob');
							$biayaKirim = 0;
							if($fob == "FOB Shipping Point")
							{
								$biayaKirim = $this->input->post('biayaKirim');
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => 106,
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
					else{
						if($this->input->post('kirim')=='true'){
							
							$fob = $this->input->post('fob');
							$biayaKirim = 0;
							if($fob == "FOB Shipping Point")
							{
								$biayaKirim = $this->input->post('biayaKirim');
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => 106,
									'Urutan' =>3,
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
									'Urutan' =>4,
									'NominalDebet' => 0,
									'NominalKredit' =>$biayaKirim, 
								);
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
							}
						}
					}

				}else{
					$akun = "107";
					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>1,
						'NominalDebet' => $totalJenis2,
						'NominalKredit' =>0, 
					);

					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					if($this->input->post('jPembayaran')=='TR')
					{
						if($this->input->post('bank')==1)
							$akun='102';
						else
							$akun='103';
					}
					else if ($this->input->post('jPembayaran')=='T')
					{
						$akun='101';
					}
					else if ($this->input->post('jPembayaran')=='C')
					{
						$akun='203';
					}
					else{
						$akun='201';
					}

					$data = array(
						'IDJurnal' =>$IDJurnal,
						'NoAkun' => $akun,
						'Urutan' =>2,
						'NominalDebet' => 0,
						'NominalKredit' =>$totalJenis2, 
					);

					$this->JurnalHasAkun_model->add_jurnalHasAkun($data);

					if($this->input->post('kirim')=='true'){
							
							$fob = $this->input->post('fob');
							$biayaKirim = 0;
							if($fob == "FOB Shipping Point")
							{
								$biayaKirim = $this->input->post('biayaKirim');
								$data = array(
									'IDJurnal' =>$IDJurnal,
									'NoAkun' => 106,
									'Urutan' =>3,
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
									'Urutan' =>4,
									'NominalDebet' => 0,
									'NominalKredit' =>$biayaKirim, 
								);
								$this->JurnalHasAkun_model->add_jurnalHasAkun($data);
							}
						}
				}

			}
			header("Location: ".site_url('Pembelian/index'));
		}
		
		echo $dataNotaBeli['Tanggal'];

	}

	//untuk mengisi keranjang belanja
	public function add_cart(){
		$barang = $this->Barang_model->get_barang($_POST['id_barang']);
		$data = array(
			'id' => $barang['KodeBarang'],
			'qty' => $_POST['jumlah'],
			'price' => $_POST['harga'],
			'name' => $barang['Nama'],
			'NoJenis' => $barang['NoJenisBarang'],
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
			<input type="hidden" form="form_pembelian" id="idTotal" name="total" value="'.$this->cart->total().'">
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
