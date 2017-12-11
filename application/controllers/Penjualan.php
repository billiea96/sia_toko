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

		$dataNotaJual = array(
			'NoNotaJual' => $NoNotaJual,
			'Tanggal' => $tgl,
			'KodePelanggan' => $customer,
			'StatusKirim' => 1,
			'JasaPengiriman' =>$this->input->post('kurir'),
			'JenisPembayaranKirim' => $this->input->post('jPembayaranKirim')
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
		}
		else{
			$jenisPembayaran = $this->input->post('jPembayaran');

			$dataNotaJual['JenisPembayaran'] = $jenisPembayaran;
		}
		if($this->input->post('disc')!=''){
			$disc = $this->input->post('disc');

			$dataNotaJual['Diskon'] = $disc;
		}
		if($this->input->post('ppn')!=''){
			$ppn = $this->input->post('ppn');

			$dataNotaJual['PPN'] = $ppn;
		}
		if($this->input->post('bank')!=''){
			$bank = $this->input->post('bank');

			$dataNotaJual['IdBank'] = $bank;
		}

		$total = $this->input->post('total');
		$bayar = $this->input->post('bayar');

		$dataNotaJual['Total'] = $total;
		$dataNotaJual['Bayar'] = $bayar;


		if($this->NotaJual_model->add_nota_jual($dataNotaJual)){
			//Untuk mengisi data pada table penjualan
			foreach($this->cart->contents() as $item){
				$dataPenjualan = array(
					'NoNotaJual' => $NoNotaJual,
					'KodeBarang' => $item['id'],
					'Harga' => $item['price'],
					'Jumlah' => $item['qty'],
				);

				$this->Penjualan_model->add_penjualan($dataPenjualan);
			}
			header("Location: ".site_url('Penjualan/index'));
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
			'name' => $barang['Nama']
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
