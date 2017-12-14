<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPerubahanEkuitas extends CI_Controller {

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
		$data['vperubahanekuitas']=$this->Laporan_model->get_perubahanEkuitas();
		$data['totalPendapatan']=$this->Laporan_model->get_totalPendapatan();
		$data['totalBiaya']=$this->Laporan_model->get_totalBiaya();

		$data['labarugi'] = $data['totalPendapatan']['TotalPendapatan']-$data['totalBiaya']['TotalBiaya'];
 		$this->load->view('layout/header');
		$this->load->view('laporan/LaporanPerubahanEkuitas', $data);
		$this->load->view('layout/footer');
	}
	
	
}
