<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Web extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
		$this->load->model(array('m_guru',
								 'm_mapel',
								 'm_ruang',
								 'm_jam',
								 'm_hari',
								 'm_pengampu',
								 'm_waktu_tidak_bersedia',
								 'm_jadwal'));
		include_once("Genetik.php");
		define('IS_TEST','FALSE');
    }

	function render_view($data)
	{
      $this->load->view('page',$data);
	}

	function index()
	{
		$data = array();	
		$data['page_name'] = 'home';
		$data['page_title'] = 'Welcome';
		
		$this->render_view($data);
	}
	
	
	
/*********************************************************************************************/
	function guru(){
		
		$data = array();
		
		$data['page_title'] = 'Modul Guru';		
		$url = base_url() . 'web/guru/';
        $res = $this->m_guru->num_page();
        $per_page = 20;

        $config = admin_paginate($url,$res,$per_page,3);
        $this->pagination->initialize($config);

        $this->m_guru->limit = $per_page;

        if($this->uri->segment(3) == TRUE){
            $this->m_guru->offset = $this->uri->segment(3);
        }else{
            $this->m_guru->offset = 0;
        }	
        
		$data['start_number'] = $this->m_guru->offset;
        $this->m_guru->sort = 'nama_guru';
        $this->m_guru->order = 'ASC';
        $data['rs_guru'] = $this->m_guru->get();
		
				
		if ($this->input->post('ajax')) {			
			$this->load->view('guru_ajax',$data);			
		}else{			
			$data['page_name'] = 'guru';
			$this->render_view($data);			
		}		
		
	}
	
	function guru_add(){
		$data = array();
		
		if(!empty($_POST)){

			$this->form_validation->set_rules('NIP','NIP','xss_clean');
			$this->form_validation->set_rules('nama_guru','Nama','xss_clean|required|is_unique[guru.nama]');
			$this->form_validation->set_rules('alamat','Alamat','xss_clean');			
			$this->form_validation->set_rules('telp','Telephon','xss_clean');			

			if($this->form_validation->run() == TRUE)
			{
				$data['NIP'] = $this->input->post('NIP');
				$data['nama_guru'] = $this->input->post('nama_guru');
				$data['alamat'] = $this->input->post('alamat');
				$data['telp'] = $this->input->post('telp');				
				
				if(IS_TEST === 'FALSE'){
					$this->m_guru->insert($data);					
					$data['msg'] = 'Data Telah Berhasil Ditambahkan';
					$data['clear_text_box'] = 'TRUE';	
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}


		$data['page_name'] = 'guru_add';
		$data['page_title'] = 'Modul Guru Add';	
		
		$this->render_view($data);	
	}
	
	
	function guru_edit($id){
		$data = array();
		
		if(!empty($_POST)){

			$this->form_validation->set_rules('NIP','NIP','xss_clean|required');
			$this->form_validation->set_rules('nama_guru','Nama','xss_clean|required');
			$this->form_validation->set_rules('alamat','Alamat','xss_clean');			
			$this->form_validation->set_rules('telp','Telephon','xss_clean');			

			if($this->form_validation->run() == TRUE)
			{
				$data['NIP'] = $this->input->post('NIP');
				$data['nama_guru'] = $this->input->post('nama_guru');
				$data['alamat'] = $this->input->post('alamat');
				$data['telp'] = $this->input->post('telp');				
				
				if(IS_TEST === 'FALSE'){
					$this->m_guru->update($id,$data);								
					$data['msg'] = 'Data telah berhasil dirubah';	
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}

			}else{
				$data['msg'] = validation_errors();
			}
		}


		$data['page_name'] = 'guru_edit';
		$data['page_title'] = 'Modul Guru Edit';	
		$data['rs_guru'] = $this->m_guru->get_by_kode($id);
		$this->render_view($data);	
	}
	
	function guru_delete($id){
		
		if(IS_TEST === 'FALSE'){
			$this->m_guru->delete($id);
			$this->m_pengampu->delete_by_kode_guru($id);
			$this->m_waktu_tidak_bersedia->delete_by_guru($id);
			$this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
		}else{
			$this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
		}
		
		
		redirect(base_url() . 'web/guru','reload');
	}
	
	
	function guru_search(){

		$search_query = $this->input->post('search_query');

		$data['rs_guru'] = $this->m_guru->get_search($search_query);
		$data['page_title'] = 'Cari Guru';
		$data['page_name'] = 'guru';	
		$data['search_query'] = $search_query;
		$data['start_number'] = 0;

		$this->render_view($data);
	}
	
/*********************************************************************************************/

	function mapel(){
		$data = array();
		
		$data['page_title'] = 'Modul Mapel';		
		$url = base_url() . 'web/mapel';
        $res = $this->m_mapel->num_page();
        $per_page = 20;

        $config = admin_paginate($url,$res,$per_page,3);
        $this->pagination->initialize($config);

        $this->m_mapel->limit = $per_page;

        if($this->uri->segment(3) == TRUE){
            $this->m_mapel->offset = $this->uri->segment(3);
        }else{
            $this->m_mapel->offset = 0;
        }	
        
		$data['start_number'] = $this->m_mapel->offset;
        $this->m_mapel->sort = 'nama_mapel';
        $this->m_mapel->order = 'ASC';
        $data['rs_mapel'] = $this->m_mapel->get();
		
				
		if ($this->input->post('ajax')) {			
			$this->load->view('mapel_ajax',$data);			
		}else{			
			$data['page_name'] = 'mapel';
			$this->render_view($data);			
		}	
	}
	
	function mapel_add(){
		$data = array();
		
		if(!empty($_POST)){

			
			$this->form_validation->set_rules('nama_mapel','Nama','xss_clean|required|is_unique[mapel.nama]');			
			$this->form_validation->set_rules('semester','Semester','xss_clean|required|integer');
			$this->form_validation->set_rules('aktif','Aktif','xss_clean|required');
			$this->form_validation->set_rules('sks','SKS','xss_clean|required|integer');

			if($this->form_validation->run() == TRUE)
			{
				
				$data['nama_mapel'] = $this->input->post('nama_mapel');
				$data['semester'] = $this->input->post('semester');
				$data['aktif'] = $this->input->post('aktif');	
				$data['sks'] = $this->input->post('sks');	
				
				if(IS_TEST === 'FALSE'){
					$this->m_mapel->insert($data);
					$data['msg'] = 'Data Telah Berhasil Ditambahkan';
					$data['clear_text_box'] = 'TRUE';
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}


		$data['page_name'] = 'mapel_add';
		$data['page_title'] = 'Modul Tambah Mapel';	
		
		$this->render_view($data);	
	}
	
	function mapel_edit($id){
		$data = array();
		
		if(!empty($_POST)){

			$this->form_validation->set_rules('nama_mapel','Nama','xss_clean|required');				
			$this->form_validation->set_rules('semester','Semester','xss_clean|required|integer');
			$this->form_validation->set_rules('aktif','Aktif','xss_clean|required');
			$this->form_validation->set_rules('sks','SKS','xss_clean|required|integer');
			

			if($this->form_validation->run() == TRUE)
			{
				
				$data['nama_mapel'] = $this->input->post('nama_mapel');
				$data['semester'] = $this->input->post('semester');
				$data['aktif'] = $this->input->post('aktif');	
				$data['sks'] = $this->input->post('sks');		
				
				if(IS_TEST === 'FALSE'){
					$this->m_mapel->update($id,$data);								
					$data['msg'] = 'Data telah berhasil dirubah';				
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}


		$data['page_name'] = 'mapel_edit';
		$data['page_title'] = 'Modul Mapel Edit';	
		$data['rs_mapel'] = $this->m_mapel->get_by_id($id);
		$this->render_view($data);	
	}
	
	function mapel_delete($id){
		
		if(IS_TEST === 'FALSE'){
			$this->m_mapel->delete($id);
			$this->m_pengampu->delete_by_mapel($id);		
			$this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
		}else{
			$this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
		}
		
		
		redirect(base_url() . 'web/mapel','reload');
	}
	
	function mapel_search(){
		$search_query = $this->input->post('search_query');

		$data['rs_mapel'] = $this->m_mapel->get_search($search_query);
		$data['page_title'] = 'Cari Mapel';
		$data['page_name'] = 'mapel';	
		$data['search_query'] = $search_query;
		$data['start_number'] = 0;

		$this->render_view($data);
	}
	
	function option_mapel_ajax($mapel){
		$data['rs_mapel'] = $this->m_mapel->get_by_semester($mapel);
		$this->load->view('option_mapel_ajax',$data);	
	}
	
	
/***********************************************************************************************/
	
	function ruang(){
		
		$data = array();
		
		$data['page_title'] = 'Modul Ruang';		
        $data['rs_ruang'] = $this->m_ruang->get();
		$data['page_name'] = 'ruang';
		$this->render_view($data);
		
	}
	
	function ruang_add(){
		/*kode,nama,kapasitas,jenis*/
		
		$data = array();
		if(!empty($_POST)){

			//$this->form_validation->set_rules('kode','Kode MK','xss_clean');
			$this->form_validation->set_rules('nama_ruang','Nama','xss_clean|required|is_unique[ruang.nama]');
			$this->form_validation->set_rules('kapasitas','Kapasitas','xss_clean|integer');						
			$this->form_validation->set_rules('jenis','Jenis','xss_clean|required');

			if($this->form_validation->run() == TRUE)
			{
				
				$data['nama_ruang'] = $this->input->post('nama_ruang');
				$data['kapasitas'] = $this->input->post('kapasitas');				
				$data['jenis'] = $this->input->post('jenis');		
				
				if(IS_TEST === 'FALSE'){
					$this->m_ruang->insert($data);
					$data['msg'] = 'Data Telah Berhasil Ditambahkan';
					$data['clear_text_box'] = 'TRUE';	
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}


		$data['page_name'] = 'ruang_add';
		$data['page_title'] = 'Modul Tambah Ruang';	
		
		$this->render_view($data);	
	}
	
	function ruang_edit($id){
		/*kode,nama,kapasitas,jenis*/
		$data = array();
		if(!empty($_POST)){

			//$this->form_validation->set_rules('kode','Kode MK','xss_clean');
			$this->form_validation->set_rules('nama','Nama','xss_clean|required');
			$this->form_validation->set_rules('kapasitas','Kapasitas','xss_clean|integer');			
			$this->form_validation->set_rules('jenis','Jenis','xss_clean|required');

			if($this->form_validation->run() == TRUE)
			{
				
				$data['nama_ruang'] = $this->input->post('nama_ruang');
				$data['kapasitas'] = $this->input->post('kapasitas');				
				$data['jenis'] = $this->input->post('jenis');
				
				/*kode,nama,kapasitas,jenis*/
				
				if(IS_TEST === 'FALSE'){
					$this->m_ruang->update($id,$data);				
					$data['msg'] = 'Data telah berhasil dirubah';	
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}


		$data['page_name'] = 'ruang_edit';
		$data['page_title'] = 'Modul Edit Ruang';	
		$data['rs_ruang'] = $this->m_ruang->get_by_id($id);
		$this->render_view($data);	
	}
	
	function ruang_delete($id){
		
		if(IS_TEST === 'FALSE'){
			$this->m_ruang->delete($id);
			$this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
		}else{
			$this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
		}
		
		
		redirect(base_url() . 'web/ruang','reload');
	}
	
	function ruang_search(){
		$search_query = $this->input->post('search_query');

		$data['rs_ruang'] = $this->m_ruang->get_search($search_query);
		$data['page_title'] = 'Cari Ruangan';
		$data['page_name'] = 'ruang';	
		$data['search_query'] = $search_query;
		//$data['start_number'] = 0;

		$this->render_view($data);
	}
	
/*************************************************************************************************/	
	
	function jam(){
		$data = array();
		
		$data['page_title'] = 'Modul Jam';		
        $data['rs_jam'] = $this->m_jam->get();
		$data['page_name'] = 'jam';
		$this->render_view($data);
	}
	
	function jam_add(){
		$data = array();
		
		if(!empty($_POST)){

			$this->form_validation->set_rules('range_jam','Range Jam','xss_clean|required|is_unique[jam.range_jam]');
			
			if($this->form_validation->run() == TRUE)
			{
				$data['range_jam'] = $this->input->post('range_jam');				
				
				if(IS_TEST === 'FALSE'){
					$this->m_jam->insert($data);
					$data['msg'] = 'Data Telah Berhasil Ditambahkan';
					$data['clear_text_box'] = 'TRUE';	
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}
		
		$data['page_name'] = 'jam_add';
		$data['page_title'] = 'Modul Tambah Range Jam';	
		
		$this->render_view($data);	
	}
	
	function jam_edit($id){
		$data = array();
		
		if(!empty($_POST)){

			$this->form_validation->set_rules('range_jam','Range Jam','xss_clean|required');
			
			if($this->form_validation->run() == TRUE)
			{
				$data['range_jam'] = $this->input->post('range_jam');
				
				if(IS_TEST === 'FALSE'){
					$this->m_jam->update($id,$data);				
					$data['msg'] = 'Data telah berhasil dirubah';				
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}
		
		$data['page_name'] = 'jam_edit';
		$data['page_title'] = 'Modul Edit Range Jam';
		$data['rs_jam'] = $this->m_jam->get_by_kode($id);
		
		$this->render_view($data);			
	}
	
	function jam_delete($id){
		
		if(IS_TEST === 'FALSE'){
			$this->m_jam->delete($id);		
			$this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
		}else{
			$this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
		}
		
		redirect(base_url() . 'web/jam','reload');
	}
	
	function jam_search(){
		$search_query = $this->input->post('search_query');

		$data['rs_jam'] = $this->m_jam->get_search($search_query);
		$data['page_title'] = 'Cari Range Jam';
		$data['page_name'] = 'jam';	
		$data['search_query'] = $search_query;
		//$data['start_number'] = 0;

		$this->render_view($data);
	}
/**************************************************************************************************/
	
	
	
	function hari(){
		$data = array();
		
		$data['page_title'] = 'Modul Hari';		
        $data['rs_hari'] = $this->m_hari->get();
		$data['page_name'] = 'hari';
		$this->render_view($data);
	}
	
	function hari_add(){
		$data = array();
		
		if(!empty($_POST)){

			$this->form_validation->set_rules('nama_hari','Nama Hari','xss_clean|required|is_unique[hari.nama]');
			
			if($this->form_validation->run() == TRUE)
			{
				$data['nama_hari'] = $this->input->post('nama_hari');				
				
				
				if(IS_TEST === 'FALSE'){
					$this->m_hari->insert($data);
					$data['msg'] = 'Data Telah Berhasil Ditambahkan';
					$data['clear_text_box'] = 'TRUE';	
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}
		
		$data['page_name'] = 'hari_add';
		$data['page_title'] = 'Modul Tambah Hari';	
		
		$this->render_view($data);	
	}
	
	function hari_edit($id){
		$data = array();
		
		if(!empty($_POST)){

			$this->form_validation->set_rules('nama_hari','Nama Hari','xss_clean|required');
			
			if($this->form_validation->run() == TRUE)
			{
				$data['nama_hari'] = $this->input->post('nama_hari');
				
				if(IS_TEST === 'FALSE'){
					$this->m_hari->update($id,$data);				
					$data['msg'] = 'Data telah berhasil dirubah';				
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}
		
		$data['page_name'] = 'hari_edit';
		$data['page_title'] = 'Modul Edit Hari';
		$data['rs_hari'] = $this->m_hari->get_by_kode($id);
		
		$this->render_view($data);			
	}
	
	function hari_delete($id){
		
		if(IS_TEST === 'FALSE'){
			$this->m_hari->delete($id);		
			$this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
		}else{
			$this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
		}
		
		redirect(base_url() . 'web/hari','reload');
	}
	
	function hari_search(){
		$search_query = $this->input->post('search_query');

		$data['rs_hari'] = $this->m_hari->get_search($search_query);
		$data['page_title'] = 'Cari Hari';
		$data['page_name'] = 'hari';	
		$data['search_query'] = $search_query;
		//$data['start_number'] = 0;

		$this->render_view($data);
	}
	

	
/**************************************************************************/
	function pengampu(){
		
		$data = array();
		
		/*
			jika null maka
				jika session ada maka gunakan session
				jika session null maka default
			else
				ubah session
		*/
		
		//echo $this->session->userdata('pengampu_semester');
		
		
	
		
		
		
		$data['page_title'] = 'Modul Pengampu';		
		$url = base_url() . 'web/pengampu' ;
        $res = $this->m_pengampu->num_page();
        $per_page = 20;

        $config = admin_paginate($url,$res,$per_page,5);
        $this->pagination->initialize($config);

        $this->m_pengampu->limit = $per_page;

        if($this->uri->segment(5) == TRUE){
            $this->m_pengampu->offset = $this->uri->segment(5);
        }else{
            $this->m_pengampu->offset = 0;
        }	
        
		$data['start_number'] = $this->m_pengampu->offset;
		//	"ORDER BY b.nama_mapel,a.kelas";
        $this->m_pengampu->sort = 'b.nama_mapel,a.kelas';
        $this->m_pengampu->order = 'ASC';
        $data['rs_pengampu'] = $this->m_pengampu->get();
		
		//$data['semester'] = $semester;
		
				
		if ($this->input->post('ajax')) {			
			$this->load->view('pengampu_ajax',$data);			
		}else{			
			$data['page_name'] = 'pengampu';
			$this->render_view($data);			
		}	
	}
	
	function pengampu_add($id){
		
		$data = array();
		//$data['semester'] = $semester;
		
		if(!empty($_POST)){

		    
			$this->form_validation->set_rules('id_mapel','Mapel','xss_clean|required');
			$this->form_validation->set_rules('id_guru','Guru','xss_clean|required');
			$this->form_validation->set_rules('kelas','Kelas','xss_clean|required');
			
			
			if($this->form_validation->run() == TRUE)
			{
				$data['id_mapel'] = $this->input->post('id_mapel');
				$data['id_guru'] = $this->input->post('id_guru');
								
				
				if(IS_TEST === 'FALSE'){
					$kelas = $this->input->post('kelas');
					if(strlen($kelas) == 1){
						$data['kelas'] = $this->input->post('kelas');
						$this->m_pengampu->insert($data);	
					}else{
						$arrKelas = explode(',',$kelas);
						foreach($arrKelas as $kls){
							$data['kelas'] = $kls;
							$this->m_pengampu->insert($data);	
						}
					}
					
					$data['msg'] = 'Data Telah Berhasil Ditambahkan';
					$data['clear_text_box'] = 'TRUE';
					
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}
		
		$data['page_name'] = 'pengampu_add';
		$data['page_title'] = 'Modul Tambah Pengampu';
		
		
		$data['rs_mapel'] = $this->m_mapel->get_by_id($id);
		$data['rs_guru'] = $this->m_guru->get_all();
		$this->render_view($data);	
	}
	
	function pengampu_edit($id){
		$data = array();
		
		if(!empty($_POST)){
			
			$this->form_validation->set_rules('id_mapel','Mapel','xss_clean|required');
			$this->form_validation->set_rules('id_guru','Guru','xss_clean|required');
			$this->form_validation->set_rules('kelas','Kelas','xss_clean|required');
			
			
			if($this->form_validation->run() == TRUE)
			{				
				$data['id_mapel'] = $this->input->post('id_mapel');
				$data['id_guru'] = $this->input->post('id_guru');
				$data['kelas'] = $this->input->post('kelas');
				
				
				if(IS_TEST === 'FALSE'){
					$this->m_pengampu->update($id,$data);				
					$data['msg'] = 'Data telah berhasil dirubah';				
				}else{
					$data['msg'] = 'WARNING: READ ONLY !';
				}
				
				

			}else{
				$data['msg'] = validation_errors();
			}
		}
		
		$data['page_name'] = 'pengampu_edit';
		$data['page_title'] = 'Modul Edit Pengampu';
		$data['rs_pengampu'] = $this->m_pengampu->get_by_id($id);
		
		$data['rs_mapel'] = $this->m_mapel->get_all();
		$data['rs_guru'] = $this->m_guru->get_all();		
		
		$this->render_view($data);			
	}
	
	function pengampu_delete($id){
		
		if(IS_TEST === 'FALSE'){
			$this->m_pengampu->delete($id);		
			//$this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
		}else{
			//$this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
		}
		
		
		//redirect($url,'reload');
		echo "OK";
	}
	
	function pengampu_search(){
		$search_query = $this->input->post('search_query');
		
		

		$data['rs_pengampu'] = $this->m_pengampu->get_search($search_query);
		$data['page_title'] = 'Cari Pengampu';
		$data['page_name'] = 'pengampu';	
		$data['search_query'] = $search_query;
		

		$data['start_number'] = 0;

		$this->render_view($data);
	}
	
	
/***************************************************************************/	
	function waktu_tidak_bersedia($id_guru = NULL){
		
		$data = array();
		
		if($id_guru == NULL){
			$id_guru = $this->db->query("SELECT id_guru FROM guru ORDER BY nama_guru LIMIT 1")->row()->id_guru;
		}
		
		if (array_key_exists('arr_tidak_bersedia', $_POST) && !empty($_POST['arr_tidak_bersedia'])){
			
			
			if(IS_TEST === 'FALSE'){
				$this->db->query("DELETE FROM waktu_tidak_bersedia WHERE id_guru = $id_guru");
				
				foreach($_POST['arr_tidak_bersedia'] as $tidak_bersedia) {				
					
					$waktu_tidak_bersedia = explode('-',$tidak_bersedia);				
					$this->db->query("INSERT INTO waktu_tidak_bersedia(id_guru,id_hari,id_jam) VALUES($waktu_tidak_bersedia[0],$waktu_tidak_bersedia[1],$waktu_tidak_bersedia[2])");
				}						
				
				$data['msg'] = 'Data telah berhasil diupdate';			
			}else{
				$data['msg'] = 'WARNING: READ ONLY !';
			}
		}elseif(!empty($_POST['hide_me']) && empty($_POST['arr_tidak_bersedia'])){
			$this->db->query("DELETE FROM waktu_tidak_bersedia WHERE kode_dosen = $id_guru");
			$data['msg'] = 'Data telah berhasil diupdate';					
		}
		
		
		
		$data['rs_guru'] = $this->m_guru->get_all();
		$data['rs_waktu_tidak_bersedia'] = $this->m_waktu_tidak_bersedia->get_by_guru($id_guru);
		$data['rs_hari']  =$this->m_hari->get();
		$data['rs_jam'] = $this->m_jam->get();
		
		$data['page_title'] = 'Waktu Tidak Bersedia';
		$data['page_name'] = 'waktu_tidak_bersedia';
		$data['id_guru'] = $id_guru;
		$this->render_view($data);
	}
	
	//function 
	
	function penjadwalan(){
		
		$data = array();
		
		
		
		if(!empty($_POST)){
			$this->form_validation->set_rules('semester','Semester','xss_clean|required');
			$this->form_validation->set_rules('jumlah_populasi','Jumlah Populiasi','xss_clean|required');
			$this->form_validation->set_rules('probabilitas_crossover','Probabilitas CrossOver','xss_clean|required');
			$this->form_validation->set_rules('probabilitas_mutasi','Probabilitas Mutasi','xss_clean|required');
			$this->form_validation->set_rules('jumlah_generasi','Jumlah Generasi','xss_clean|required');
			
			if($this->form_validation->run() == TRUE)
			{				
				//tempat keajaiban dimulai. SEMANGAAAAAATTTTTTT BANZAIIIIIIIIIIIII !
				
				$semester = $this->input->post('semester');
				$jumlah_populasi = $this->input->post('jumlah_populasi');
				$crossOver = $this->input->post('probabilitas_crossover');
				$mutasi = $this->input->post('probabilitas_mutasi');
				$jumlah_generasi = $this->input->post('jumlah_generasi');
				
				$data['semester_tipe'] = $semester;
				$data['jumlah_populasi'] = $jumlah_populasi;
				$data['probabilitas_crossover'] = $crossOver;
				$data['probabilitas_mutasi'] = $mutasi;
				$data['jumlah_generasi'] = $jumlah_generasi;
				
			    $rs_data = $this->db->query("SELECT   a.id_pengampu,"
                                    . "       b.sks,"
                                    . "       a.id_guru "
                                    . "FROM pengampu a "
                                    . "LEFT JOIN mapel b "
                                    . "ON a.id_mapel = b.id_mapel "
                                    . "WHERE b.semester%2 = $semester");
				
				if($rs_data->num_rows() == 0){
					
					$data['msg'] = 'Tidak Ada Data dengan Semester ini <br>Data yang tampil dibawah adalah data dari proses sebelumnya';
					
					//redirect(base_url() . 'web/penjadwalan','reload');
				}else{
					$genetik = new genetik($semester,
										   $jumlah_populasi,
										   $crossOver,
										   $mutasi,
										   //~~~~~~BUG!~~~~~~~
										   /*										   
											1 senin 5
											2 selasa 4
										    3 rabu 3
										    4 kamis 2
										    5 jumat 1										    
										   */
										   5,//id hari jumat										   
										   '4-5-6', //kode jam jumat
										   //jam dhuhur tidak dipake untuk sementara
										   6); //id jam dhuhur
					$genetik->AmbilData();
					$genetik->Inisialisai();
					
					
					
					$found = false;
					
					for($i = 0;$i < $jumlah_generasi;$i++ ){
						$fitness = $genetik->HitungFitness();
						
						//if($i == 100){
						//	var_dump($fitness);
						//	exit();
						//}
						
						$genetik->Seleksi($fitness);
						$genetik->StartCrossOver();
						
						$fitnessAfterMutation = $genetik->Mutasi();
						
						for ($j = 0; $j < count($fitnessAfterMutation); $j++){
							//test here
							if($fitnessAfterMutation[$j] == 1){
								
								$this->db->query("TRUNCATE TABLE jadwal");
								
								$jadwal = array(array());
								$jadwal = $genetik->GetIndividu($j);
								
								
								
								for($k = 0; $k < count($jadwal);$k++){
									
									$id_pengampu = intval($jadwal[$k][0]);
									$id_jam = intval($jadwal[$k][1]);
									$id_hari = intval($jadwal[$k][2]);
									
									$this->db->query("INSERT INTO jadwal(id_pengampu,id_jam,id_hari) ".
													 "VALUES($id_pengampu,$id_jam,$id_hari)");
									
									
								}
								
								//var_dump($jadwal);
								//exit();
								
								$found = true;								
							}
							
							if($found){break;}
						}
						
						if($found){break;}
					}
					
					if(!$found){
						$data['msg'] = 'Tidak Ditemukan Solusi Optimal';
					}
					
				}
			}else{
				$data['msg'] = validation_errors();
			}
		}
		
		
		$data['page_name'] = 'penjadwalan';
		$data['page_title'] = 'Penjadwalan';
		$data['rs_jadwal'] = $this->m_jadwal->get();
		$this->render_view($data);
	}
	
	
	function excel_report(){
		$query = $this->m_jadwal->get();
		if(!$query)
            return false;
		
		// Starting the PHPExcel library
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
		
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
 
        $objPHPExcel->setActiveSheetIndex(0);
		 // Field names in the first row
        $fields = $query->list_fields();
        $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
		
		// Fetching the table data
        $row = 2;
        foreach($query->result() as $data)
        {
            $col = 0;
            foreach ($fields as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                $col++;
            }
 
            $row++;
        }
		
		$objPHPExcel->setActiveSheetIndex(0);
 
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
 
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Products_'.date('dMy').'.xls"');
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');
	}
}