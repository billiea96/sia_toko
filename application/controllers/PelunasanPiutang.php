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
		$jp = $this->input->post('jPembayaran');
		$nominal = $this->input->post('nominal');
		$disc = $this->input->post('discPelunasan');
		$bayar = $this->input->post('totalBayar');

		$dataSimpan = array(
			'Tanggal' => $tgl,
			'NominalSeharusnya' => $nominal,
			'DiskonPelunasan' => $disc,
			'Bayar' => $bayar,
			'JenisPembayaran' => $jp,
			'NoNotaJual' => $nota
		);

		if($this->PelunasanPiutang_model->add_pelunasan_piutang($dataSimpan)){
			header("Location: ".site_url('PelunasanPiutang/index'));
		}
	}

}
