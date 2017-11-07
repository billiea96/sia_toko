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
        $this->load->helper('url_helper');
        $this->load->helper('form');
    	$this->load->library('form_validation');
        $this->load->library('session');
    }
	public function index()
	{
		$data['barang'] = $this->Barang_model->get_barang();
		$data['pelanggan'] = $this->Pelanggan_model->get_pelanggan();

		$this->load->view('layout/header');
		$this->load->view('penjualan/penjualan',$data);
		$this->load->view('layout/footer');
	}
}
