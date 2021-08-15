<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanBukuBesar extends CI_Controller {

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
        $this->load->model('Laporan_model');
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
		$data['rekeningBacaBaca']=$this->Laporan_model->get_bukuBesar('102');
		$data['rekeningSukaSendiri']=$this->Laporan_model->get_bukuBesar('103');
		$data['piutangDagang']=$this->Laporan_model->get_bukuBesar('104');
		$data['piutangCek']=$this->Laporan_model->get_bukuBesar('105');
		$data['sediaanBarangAlatTulis']=$this->Laporan_model->get_bukuBesar('106');
		$data['sediaanBarangRumahTangga']=$this->Laporan_model->get_bukuBesar('107');
		$data['sediaanHabisPakai']=$this->Laporan_model->get_bukuBesar('108');
		$data['kendaraan']=$this->Laporan_model->get_bukuBesar('110');
		$data['akumulasiDepresiasiKendaraan']=$this->Laporan_model->get_bukuBesar('111');
		$data['hutangDagang']=$this->Laporan_model->get_bukuBesar('201');
		$data['hutangBank']=$this->Laporan_model->get_bukuBesar('202');
		$data['hutangCek']=$this->Laporan_model->get_bukuBesar('203');
		$data['hutangPPN']=$this->Laporan_model->get_bukuBesar('204');
		$data['modalPemilik']=$this->Laporan_model->get_bukuBesar('301');
		$data['prive']=$this->Laporan_model->get_bukuBesar('302');
		$data['penjualan']=$this->Laporan_model->get_bukuBesar('401');
		$data['diskonPenjualan']=$this->Laporan_model->get_bukuBesar('402');
		$data['pendapatanLain']=$this->Laporan_model->get_bukuBesar('403');
		$data['HPP']=$this->Laporan_model->get_bukuBesar('501');
		$data['biayaGaji']=$this->Laporan_model->get_bukuBesar('506');
		$data['biayaSediaan']=$this->Laporan_model->get_bukuBesar('507');
		$data['biayaDepresiasi']=$this->Laporan_model->get_bukuBesar('508');
		$data['biayaListrikTelp']=$this->Laporan_model->get_bukuBesar('509');
		$data['rugiPenjualanAsetTetap']=$this->Laporan_model->get_bukuBesar('515');
		$data['biayaLain']=$this->Laporan_model->get_bukuBesar('520');
 		$this->load->view('layout/header');
		$this->load->view('laporan/LaporanBukuBesar', $data);
		$this->load->view('layout/footer');
	}
	
	
}