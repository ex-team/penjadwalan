<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {
        parent::__construct();
    }


	public function index(){
		$this->load->library('bantu');
		
		$param['judul_halaman'] = "Halaman home";

		$template_konten = 'home_view';
		$data['parse_template_konten'] = $this->load->view($template_konten, $param, true);

		$data['parse_breadcrumbs'] = $this->bantu->getBreadcrumbs();
		$data['parse_uri_root'] = $this->bantu->getRootAddress();
		$this->load->view('template_view', $data);
	}
}