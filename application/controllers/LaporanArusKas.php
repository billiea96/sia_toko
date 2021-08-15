<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LaporanArusKas extends CI_Controller {

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
        $this->load->model('Laporan_model');
        $this->load->model('NotaJual_model');
        $this->load->model('Pembelian_model');
        $this->load->model('PelunasanPiutang_model');
        $this->load->helper('url_helper');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('cart');
        $this->load->library('session');
        $this->load->library('pdf');

    }
	public function index()
	{
		$data['kas']=$this->Laporan_model->get_bukuBesar('101');
		$data['kasAkhir']=$this->Laporan_model->get_saldoAkhirs('101');
		$data['rekeningBacaBaca']=$this->Laporan_model->get_bukuBesar('102');
		$data['rekeningBacaBacaAkhir']=$this->Laporan_model->get_saldoAkhirs('102');
		$data['rekeningSukaSendiri']=$this->Laporan_model->get_bukuBesar('103');
		$data['rekeningSukaSendiriAkhir']=$this->Laporan_model->get_saldoAkhirs('103');
 		$this->load->view('layout/header');
		$this->load->view('laporan/laporanArusKas', $data);
		$this->load->view('layout/footer');
        


	}
	
	
	
}
