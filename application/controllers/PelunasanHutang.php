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
		$data['NoNotaBeli'] = $this->NotaBeli_model->pembayaran_kredit();
		$data['barang'] = $this->Barang_model->get_barang();
		$data['supplier'] = $this->Supplier_model->get_supplier();
		$data['bank'] = $this->Bank_model->get_bank();

		$this->load->view('layout/header');
		$this->load->view('pelunasan/pelunasanBeli', $data);
		$this->load->view('layout/footer');
	}

	public function simpan(){
		$nota = $this->input->post('noNota');
		$tgl = $this->input->post('tgl');
		$jp = $this->input->post('jPembayaran');
		$nominal = $this->input->post('nominal');
		$disc = $this->input->post('discPelunasan');
		$bayar = $this->input->post('totalBayar');
		$idbank = $this->input->post('bank');
		$norek = $this->input->post('noRek');
		$pemelikBank = $this->input->post('namaPemilik');

		$dataSimpan = array(
			'Tanggal' => $tgl,
			'NominalSeharusnya' => $nominal,
			'DiskonPelunasan' => $disc,
			'Bayar' => $bayar,
			'JenisPembayaran' => $jp,
			'NoNotaBeli' => $nota,
			'IdBank' => $idbank,
			'NoRekening' => $norek,
			'PemilikRekening' => $pemilikBank
		);

		if($this->PelunasanHutang_model->add_pelunasan_hutang($dataSimpan)){

			
			header("Location: ".site_url('PelunasanHutang/index'));
		}
	}

	public function detail_nota(){
		$NotaBeli = $this->NotaBeli_model->get_nota($_POST['noNota']);

		$sisaBayar = $NotaBeli['Total']-($NotaBeli['Total']*($NotaBeli['Diskon']/100))- $NotaBeli['Bayar'];
		//$totalBayar = $NotaBeli['Total'] - ($NotaBeli['Total'] * ($NotaBeli['DiskonPelunasan']/100));
		$totalBayar = $sisaBayar - (($sisaBayar) * ($NotaBeli['DiskonPelunasan']/100));
		
		echo json_encode(array('sisaBayar' => $sisaBayar,'diskon'=>$NotaBeli['DiskonPelunasan'], 'total'=>$NotaBeli['Total'], 'totalBayar' => $totalBayar, 'tgl'=>$NotaBeli['TanggalBatasDiskon']));
	}


}
