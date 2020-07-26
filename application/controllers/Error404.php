<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error404 extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->data['breadcrumb'] = '404 Error';
		$this->data['subtitle'] = 'Page not Found';
		$this->data['main_view'] = 'view404';
		$this->load->view('template',$this->data);
	}
}
?>