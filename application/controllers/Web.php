<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Web extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['m_dosen',
            'm_matakuliah',
            'm_ruang',
            'm_jam',
            'm_hari',
            'm_pengampu',
            'm_waktu_tidak_bersedia',
            'm_jadwalkuliah', ]);
        include_once 'genetik.php';
        define('IS_TEST', 'FALSE');
    }

    public function render_view($data)
    {
        $this->load->view('page', $data);
    }

    public function index()
    {
        $data = [];
        $data['page_name'] = 'home';
        $data['page_title'] = 'Welcome';

        $this->render_view($data);
    }

    /*********************************************************************************************/
    public function dosen()
    {
        $data = [];

        $data['page_title'] = 'Modul Dosen';
        $url = base_url().'web/dosen/';
        $res = $this->m_dosen->num_page();
        $per_page = 20;

        $config = admin_paginate($url, $res, $per_page, 3);
        $this->pagination->initialize($config);

        $this->m_dosen->limit = $per_page;

        if ($this->uri->segment(3) == true) {
            $this->m_dosen->offset = $this->uri->segment(3);
        } else {
            $this->m_dosen->offset = 0;
        }

        $data['start_number'] = $this->m_dosen->offset;
        $this->m_dosen->sort = 'nama';
        $this->m_dosen->order = 'ASC';
        $data['rs_dosen'] = $this->m_dosen->get();

        if ($this->input->post('ajax')) {
            $this->load->view('dosen_ajax', $data);
        } else {
            $data['page_name'] = 'dosen';
            $this->render_view($data);
        }
    }

    public function dosen_add()
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('nidn', 'NIDN', 'xss_clean');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required|is_unique[dosen.nama]');
            $this->form_validation->set_rules('alamat', 'Alamat', 'xss_clean');
            $this->form_validation->set_rules('telp', 'Telephon', 'xss_clean');

            if ($this->form_validation->run() == true) {
                $data['nidn'] = $this->input->post('nidn');
                $data['nama'] = $this->input->post('nama');
                $data['alamat'] = $this->input->post('alamat');
                $data['telp'] = $this->input->post('telp');

                if (IS_TEST === 'FALSE') {
                    $this->m_dosen->insert($data);
                    $data['msg'] = 'Data Telah Berhasil Ditambahkan';
                    $data['clear_text_box'] = 'TRUE';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'dosen_add';
        $data['page_title'] = 'Modul Dosen Add';

        $this->render_view($data);
    }

    public function dosen_edit($kode)
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('nidn', 'NIDN', 'xss_clean|required');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required');
            $this->form_validation->set_rules('alamat', 'Alamat', 'xss_clean');
            $this->form_validation->set_rules('telp', 'Telephon', 'xss_clean');

            if ($this->form_validation->run() == true) {
                $data['nidn'] = $this->input->post('nidn');
                $data['nama'] = $this->input->post('nama');
                $data['alamat'] = $this->input->post('alamat');
                $data['telp'] = $this->input->post('telp');

                if (IS_TEST === 'FALSE') {
                    $this->m_dosen->update($kode, $data);
                    $data['msg'] = 'Data telah berhasil dirubah';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'dosen_edit';
        $data['page_title'] = 'Modul Dosen Edit';
        $data['rs_dosen'] = $this->m_dosen->get_by_kode($kode);
        $this->render_view($data);
    }

    public function dosen_delete($kode)
    {
        if (IS_TEST === 'FALSE') {
            $this->m_dosen->delete($kode);
            $this->m_pengampu->delete_by_kode_dosen($kode);
            $this->m_waktu_tidak_bersedia->delete_by_dosen($kode);
            $this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
        } else {
            $this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
        }

        redirect(base_url().'web/dosen', 'reload');
    }

    public function dosen_search()
    {
        $search_query = $this->input->post('search_query');

        $data['rs_dosen'] = $this->m_dosen->get_search($search_query);
        $data['page_title'] = 'Cari Dosen';
        $data['page_name'] = 'dosen';
        $data['search_query'] = $search_query;
        $data['start_number'] = 0;

        $this->render_view($data);
    }

    /*********************************************************************************************/

    public function matakuliah()
    {
        $data = [];

        $data['page_title'] = 'Modul Matakuliah';
        $url = base_url().'web/matakuliah/';
        $res = $this->m_matakuliah->num_page();
        $per_page = 20;

        $config = admin_paginate($url, $res, $per_page, 3);
        $this->pagination->initialize($config);

        $this->m_matakuliah->limit = $per_page;

        if ($this->uri->segment(3) == true) {
            $this->m_matakuliah->offset = $this->uri->segment(3);
        } else {
            $this->m_matakuliah->offset = 0;
        }

        $data['start_number'] = $this->m_matakuliah->offset;
        $this->m_matakuliah->sort = 'jenis,nama';
        $this->m_matakuliah->order = 'ASC';
        $data['rs_mk'] = $this->m_matakuliah->get();

        if ($this->input->post('ajax')) {
            $this->load->view('matakuliah_ajax', $data);
        } else {
            $data['page_name'] = 'matakuliah';
            $this->render_view($data);
        }
    }

    public function matakuliah_add()
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('kode_mk', 'Kode MK', 'xss_clean');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required|is_unique[matakuliah.nama]');
            $this->form_validation->set_rules('sks', 'SKS', 'xss_clean|required|integer');
            $this->form_validation->set_rules('semester', 'Semester', 'xss_clean|required|integer');
            $this->form_validation->set_rules('jenis', 'Jenis', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['kode_mk'] = $this->input->post('kode_mk');
                $data['nama'] = $this->input->post('nama');
                $data['sks'] = $this->input->post('sks');
                $data['semester'] = $this->input->post('semester');
                $data['jenis'] = $this->input->post('jenis');

                if (IS_TEST === 'FALSE') {
                    $this->m_matakuliah->insert($data);
                    $data['msg'] = 'Data Telah Berhasil Ditambahkan';
                    $data['clear_text_box'] = 'TRUE';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'matakuliah_add';
        $data['page_title'] = 'Modul Tambah Matakuliah';

        $this->render_view($data);
    }

    public function matakuliah_edit($kode)
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('kode_mk', 'Kode MK', 'xss_clean');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required');
            $this->form_validation->set_rules('sks', 'SKS', 'xss_clean|required|integer');
            $this->form_validation->set_rules('semester', 'Semester', 'xss_clean|required|integer');
            $this->form_validation->set_rules('jenis', 'Jenis', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['kode_mk'] = $this->input->post('kode_mk');
                $data['nama'] = $this->input->post('nama');
                $data['sks'] = $this->input->post('sks');
                $data['semester'] = $this->input->post('semester');
                $data['jenis'] = $this->input->post('jenis');

                if (IS_TEST === 'FALSE') {
                    $this->m_matakuliah->update($kode, $data);
                    $data['msg'] = 'Data telah berhasil dirubah';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'matakuliah_edit';
        $data['page_title'] = 'Modul Matakuliah Edit';
        $data['rs_mk'] = $this->m_matakuliah->get_by_kode($kode);
        $this->render_view($data);
    }

    public function matakuliah_delete($kode)
    {
        if (IS_TEST === 'FALSE') {
            $this->m_matakuliah->delete($kode);
            $this->m_pengampu->delete_by_mk($kode);
            $this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
        } else {
            $this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
        }

        redirect(base_url().'web/matakuliah', 'reload');
    }

    public function matakuliah_search()
    {
        $search_query = $this->input->post('search_query');

        $data['rs_mk'] = $this->m_matakuliah->get_search($search_query);
        $data['page_title'] = 'Cari Matakuliah';
        $data['page_name'] = 'matakuliah';
        $data['search_query'] = $search_query;
        $data['start_number'] = 0;

        $this->render_view($data);
    }

    public function option_matakuliah_ajax($matakuliah_tipe)
    {
        $data['rs_mk'] = $this->m_matakuliah->get_by_semester($matakuliah_tipe);
        $this->load->view('option_matakuliah_ajax', $data);
    }

    /***********************************************************************************************/

    public function ruang()
    {
        $data = [];

        $data['page_title'] = 'Modul Ruang';
        $data['rs_ruang'] = $this->m_ruang->get();
        $data['page_name'] = 'ruang';
        $this->render_view($data);
    }

    public function ruang_add()
    {
        /*kode,nama,kapasitas,jenis*/

        $data = [];
        if (!empty($_POST)) {
            //$this->form_validation->set_rules('kode','Kode MK','xss_clean');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required|is_unique[ruang.nama]');
            $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'xss_clean|integer');
            $this->form_validation->set_rules('jenis', 'Jenis', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['nama'] = $this->input->post('nama');
                $data['kapasitas'] = $this->input->post('kapasitas');
                $data['jenis'] = $this->input->post('jenis');

                if (IS_TEST === 'FALSE') {
                    $this->m_ruang->insert($data);
                    $data['msg'] = 'Data Telah Berhasil Ditambahkan';
                    $data['clear_text_box'] = 'TRUE';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'ruang_add';
        $data['page_title'] = 'Modul Tambah Ruang';

        $this->render_view($data);
    }

    public function ruang_edit($kode)
    {
        /*kode,nama,kapasitas,jenis*/
        $data = [];
        if (!empty($_POST)) {
            //$this->form_validation->set_rules('kode','Kode MK','xss_clean');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required');
            $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'xss_clean|integer');

            $this->form_validation->set_rules('jenis', 'Jenis', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['nama'] = $this->input->post('nama');
                $data['kapasitas'] = $this->input->post('kapasitas');
                $data['jenis'] = $this->input->post('jenis');

                /*kode,nama,kapasitas,jenis*/

                if (IS_TEST === 'FALSE') {
                    $this->m_ruang->update($kode, $data);
                    $data['msg'] = 'Data telah berhasil dirubah';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'ruang_edit';
        $data['page_title'] = 'Modul Edit Ruang';
        $data['rs_ruang'] = $this->m_ruang->get_by_kode($kode);
        $this->render_view($data);
    }

    public function ruang_delete($kode)
    {
        if (IS_TEST === 'FALSE') {
            $this->m_ruang->delete($kode);
            $this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
        } else {
            $this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
        }

        redirect(base_url().'web/ruang', 'reload');
    }

    public function ruang_search()
    {
        $search_query = $this->input->post('search_query');

        $data['rs_ruang'] = $this->m_ruang->get_search($search_query);
        $data['page_title'] = 'Cari Ruangan';
        $data['page_name'] = 'ruang';
        $data['search_query'] = $search_query;

        $this->render_view($data);
    }

    /*************************************************************************************************/

    public function jam()
    {
        $data = [];

        $data['page_title'] = 'Modul Jam';
        $data['rs_jam'] = $this->m_jam->get();
        $data['page_name'] = 'jam';
        $this->render_view($data);
    }

    public function jam_add()
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('range_jam', 'Range Jam', 'xss_clean|required|is_unique[jam.range_jam]');

            if ($this->form_validation->run() == true) {
                $data['range_jam'] = $this->input->post('range_jam');

                if (IS_TEST === 'FALSE') {
                    $this->m_jam->insert($data);
                    $data['msg'] = 'Data Telah Berhasil Ditambahkan';
                    $data['clear_text_box'] = 'TRUE';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'jam_add';
        $data['page_title'] = 'Modul Tambah Range Jam';

        $this->render_view($data);
    }

    public function jam_edit($kode)
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('range_jam', 'Range Jam', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['range_jam'] = $this->input->post('range_jam');

                if (IS_TEST === 'FALSE') {
                    $this->m_jam->update($kode, $data);
                    $data['msg'] = 'Data telah berhasil dirubah';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'jam_edit';
        $data['page_title'] = 'Modul Edit Range Jam';
        $data['rs_jam'] = $this->m_jam->get_by_kode($kode);

        $this->render_view($data);
    }

    public function jam_delete($kode)
    {
        if (IS_TEST === 'FALSE') {
            $this->m_jam->delete($kode);
            $this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
        } else {
            $this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
        }

        redirect(base_url().'web/jam', 'reload');
    }

    public function jam_search()
    {
        $search_query = $this->input->post('search_query');

        $data['rs_jam'] = $this->m_jam->get_search($search_query);
        $data['page_title'] = 'Cari Range Jam';
        $data['page_name'] = 'jam';
        $data['search_query'] = $search_query;
        //$data['start_number'] = 0;

        $this->render_view($data);
    }

    /**************************************************************************************************/

    public function hari()
    {
        $data = [];

        $data['page_title'] = 'Modul Hari';
        $data['rs_hari'] = $this->m_hari->get();
        $data['page_name'] = 'hari';
        $this->render_view($data);
    }

    public function hari_add()
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('nama', 'Nama Hari', 'xss_clean|required|is_unique[hari.nama]');

            if ($this->form_validation->run() == true) {
                $data['nama'] = $this->input->post('nama');

                if (IS_TEST === 'FALSE') {
                    $this->m_hari->insert($data);
                    $data['msg'] = 'Data Telah Berhasil Ditambahkan';
                    $data['clear_text_box'] = 'TRUE';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'hari_add';
        $data['page_title'] = 'Modul Tambah Hari';

        $this->render_view($data);
    }

    public function hari_edit($kode)
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('nama', 'Nama Hari', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['nama'] = $this->input->post('nama');

                if (IS_TEST === 'FALSE') {
                    $this->m_hari->update($kode, $data);
                    $data['msg'] = 'Data telah berhasil dirubah';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'hari_edit';
        $data['page_title'] = 'Modul Edit Hari';
        $data['rs_hari'] = $this->m_hari->get_by_kode($kode);

        $this->render_view($data);
    }

    public function hari_delete($kode)
    {
        if (IS_TEST === 'FALSE') {
            $this->m_hari->delete($kode);
            $this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
        } else {
            $this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
        }

        redirect(base_url().'web/hari', 'reload');
    }

    public function hari_search()
    {
        $search_query = $this->input->post('search_query');

        $data['rs_hari'] = $this->m_hari->get_search($search_query);
        $data['page_title'] = 'Cari Hari';
        $data['page_name'] = 'hari';
        $data['search_query'] = $search_query;

        $this->render_view($data);
    }

    /**************************************************************************/
    public function pengampu($semester_tipe = null, $tahun_akademik = null)
    {
        $data = [];

        if (!$this->session->userdata('pengampu_semester_tipe') && !$this->session->userdata('pengampu_tahun_akademik')) {
            $this->session->set_userdata('pengampu_semester_tipe', 1);
            $this->session->set_userdata('pengampu_tahun_akademik', '2011-2012');
        }

        if ($semester_tipe == null && $tahun_akademik == null) {
            $semester_tipe = $this->session->userdata('pengampu_semester_tipe');
            $tahun_akademik = $this->session->userdata('pengampu_tahun_akademik');
        } else {
            $this->session->set_userdata('pengampu_semester_tipe', $semester_tipe);
            $this->session->set_userdata('pengampu_tahun_akademik', $tahun_akademik);

            $semester_tipe = $this->session->userdata('pengampu_semester_tipe');
            $tahun_akademik = $this->session->userdata('pengampu_tahun_akademik');
        }

        $data['page_title'] = 'Modul Pengampu';
        $url = base_url().'web/pengampu/'.$semester_tipe.'/'.$tahun_akademik.'/';
        $res = $this->m_pengampu->num_page($semester_tipe, $tahun_akademik);
        $per_page = 20;

        $config = admin_paginate($url, $res, $per_page, 5);
        $this->pagination->initialize($config);

        $this->m_pengampu->limit = $per_page;

        if ($this->uri->segment(5) == true) {
            $this->m_pengampu->offset = $this->uri->segment(5);
        } else {
            $this->m_pengampu->offset = 0;
        }

        $data['start_number'] = $this->m_pengampu->offset;
        //	"ORDER BY b.nama,a.kelas";
        $this->m_pengampu->sort = 'b.nama,a.kelas';
        $this->m_pengampu->order = 'ASC';
        $data['rs_pengampu'] = $this->m_pengampu->get($semester_tipe, $tahun_akademik);

        //$data['semester_tipe'] = $semester_tipe;
        //$data['tahun_akademik'] = $tahun_akademik;

        if ($this->input->post('ajax')) {
            $this->load->view('pengampu_ajax', $data);
        } else {
            $data['page_name'] = 'pengampu';
            $this->render_view($data);
        }
    }

    public function pengampu_add()
    {
        $data = [];
        //$data['semester_tipe'] = $semester_tipe;

        if (!empty($_POST)) {
            $this->form_validation->set_rules('semester_tipe', 'Semester', 'xss_clean|required');
            $this->form_validation->set_rules('kode_mk', 'Matakuliah', 'xss_clean|required');
            $this->form_validation->set_rules('kode_dosen', 'Dosen', 'xss_clean|required');
            $this->form_validation->set_rules('kelas', 'Kelas', 'xss_clean|required');
            $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['kode_mk'] = $this->input->post('kode_mk');
                $data['kode_dosen'] = $this->input->post('kode_dosen');

                $data['tahun_akademik'] = $this->input->post('tahun_akademik');

                if (IS_TEST === 'FALSE') {
                    $kelas = $this->input->post('kelas');
                    if (strlen($kelas) == 1) {
                        $data['kelas'] = $this->input->post('kelas');
                        $this->m_pengampu->insert($data);
                    } else {
                        $arrKelas = explode(',', $kelas);
                        foreach ($arrKelas as $kls) {
                            $data['kelas'] = $kls;
                            $this->m_pengampu->insert($data);
                        }
                    }

                    $data['msg'] = 'Data Telah Berhasil Ditambahkan';
                    $data['clear_text_box'] = 'TRUE';
                    $data['semester_tipe'] = $this->input->post('semester_tipe');
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'pengampu_add';
        $data['page_title'] = 'Modul Tambah Pengampu';
        if (isset($data['semester_tipe'])) {
            $semester_tipe = $data['semester_tipe'];
        } else {
            $semester_tipe = 1;
        }

        $data['rs_mk'] = $this->m_matakuliah->get_by_semester($semester_tipe);
        $data['rs_dosen'] = $this->m_dosen->get_all();
        $this->render_view($data);
    }

    public function pengampu_edit($kode)
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('kode_mk', 'Matakuliah', 'xss_clean|required');
            $this->form_validation->set_rules('kode_dosen', 'Dosen', 'xss_clean|required');
            $this->form_validation->set_rules('kelas', 'Kelas', 'xss_clean|required');
            $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['kode_mk'] = $this->input->post('kode_mk');
                $data['kode_dosen'] = $this->input->post('kode_dosen');
                $data['kelas'] = $this->input->post('kelas');
                $data['tahun_akademik'] = $this->input->post('tahun_akademik');

                if (IS_TEST === 'FALSE') {
                    $this->m_pengampu->update($kode, $data);
                    $data['msg'] = 'Data telah berhasil dirubah';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'pengampu_edit';
        $data['page_title'] = 'Modul Edit Pengampu';
        $data['rs_pengampu'] = $this->m_pengampu->get_by_kode($kode);

        $data['rs_mk'] = $this->m_matakuliah->get_all();
        $data['rs_dosen'] = $this->m_dosen->get_all();

        $this->render_view($data);
    }

    public function pengampu_delete($kode)
    {
        if (IS_TEST === 'FALSE') {
            $this->m_pengampu->delete($kode);
        //$this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
        } else {
            //$this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
        }

        //redirect($url,'reload');
        echo 'OK';
    }

    public function pengampu_search()
    {
        $search_query = $this->input->post('search_query');
        $semester_tipe = $this->input->post('semester_tipe');
        $tahun_akademik = $this->input->post('tahun_akademik');

        $data['rs_pengampu'] = $this->m_pengampu->get_search($search_query, $semester_tipe, $tahun_akademik);
        $data['page_title'] = 'Cari Pengampu';
        $data['page_name'] = 'pengampu';
        $data['search_query'] = $search_query;
        $data['semester_tipe'] = $semester_tipe;
        $data['tahun_akademik'] = $tahun_akademik;
        $data['start_number'] = 0;

        $this->render_view($data);
    }

    /***************************************************************************/
    public function waktu_tidak_bersedia($kode_dosen = null)
    {
        $data = [];

        if ($kode_dosen == null) {
            $kode_dosen = $this->db->query('SELECT kode FROM dosen ORDER BY nama LIMIT 1')->row()->kode;
        }

        if (array_key_exists('arr_tidak_bersedia', $_POST) && !empty($_POST['arr_tidak_bersedia'])) {
            if (IS_TEST === 'FALSE') {
                $this->db->query("DELETE FROM waktu_tidak_bersedia WHERE kode_dosen = $kode_dosen");

                foreach ($_POST['arr_tidak_bersedia'] as $tidak_bersedia) {
                    $waktu_tidak_bersedia = explode('-', $tidak_bersedia);
                    $this->db->query("INSERT INTO waktu_tidak_bersedia(kode_dosen,kode_hari,kode_jam) VALUES($waktu_tidak_bersedia[0],$waktu_tidak_bersedia[1],$waktu_tidak_bersedia[2])");
                }

                $data['msg'] = 'Data telah berhasil diupdate';
            } else {
                $data['msg'] = 'WARNING: READ ONLY !';
            }
        } elseif (!empty($_POST['hide_me']) && empty($_POST['arr_tidak_bersedia'])) {
            $this->db->query("DELETE FROM waktu_tidak_bersedia WHERE kode_dosen = $kode_dosen");
            $data['msg'] = 'Data telah berhasil diupdate';
        }

        $data['rs_dosen'] = $this->m_dosen->get_all();
        $data['rs_waktu_tidak_bersedia'] = $this->m_waktu_tidak_bersedia->get_by_dosen($kode_dosen);
        $data['rs_hari'] = $this->m_hari->get();
        $data['rs_jam'] = $this->m_jam->get();

        $data['page_title'] = 'Waktu Tidak Bersedia';
        $data['page_name'] = 'waktu_tidak_bersedia';
        $data['kode_dosen'] = $kode_dosen;
        $this->render_view($data);
    }

    //function
    public function penjadwalan()
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('semester_tipe', 'Semester', 'xss_clean|required');
            $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'xss_clean|required');
            $this->form_validation->set_rules('jumlah_populasi', 'Jumlah Populiasi', 'xss_clean|required');
            $this->form_validation->set_rules('probabilitas_crossover', 'Probabilitas CrossOver', 'xss_clean|required');
            $this->form_validation->set_rules('probabilitas_mutasi', 'Probabilitas Mutasi', 'xss_clean|required');
            $this->form_validation->set_rules('jumlah_generasi', 'Jumlah Generasi', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $jenis_semester = $this->input->post('semester_tipe');
                $tahun_akademik = $this->input->post('tahun_akademik');
                $jumlah_populasi = $this->input->post('jumlah_populasi');
                $crossOver = $this->input->post('probabilitas_crossover');
                $mutasi = $this->input->post('probabilitas_mutasi');
                $jumlah_generasi = $this->input->post('jumlah_generasi');

                $data['semester_tipe'] = $jenis_semester;
                $data['tahun_akademik'] = $tahun_akademik;
                $data['jumlah_populasi'] = $jumlah_populasi;
                $data['probabilitas_crossover'] = $crossOver;
                $data['probabilitas_mutasi'] = $mutasi;
                $data['jumlah_generasi'] = $jumlah_generasi;

                $rs_data = $this->db->query('SELECT   a.kode,'
                                    .'       b.sks,'
                                    .'       a.kode_dosen,'
                                    .'       b.jenis '
                                    .'FROM pengampu a '
                                    .'LEFT JOIN matakuliah b '
                                    .'ON a.kode_mk = b.kode '
                                    ."WHERE b.semester%2 = $jenis_semester "
                                    ."      AND a.tahun_akademik = '$tahun_akademik'");

                if ($rs_data->num_rows() == 0) {
                    $data['msg'] = 'Tidak Ada Data dengan Semester dan Tahun Akademik ini <br>Data yang tampil dibawah adalah data dari proses sebelumnya';
                } else {
                    $genetik = new genetik($jenis_semester, $tahun_akademik, $jumlah_populasi, $crossOver, $mutasi, 5, '4-5-6', 6);
                    $genetik->AmbilData();
                    $genetik->Inisialisai();

                    $found = false;

                    for ($i = 0; $i < $jumlah_generasi; $i++) {
                        $fitness = $genetik->HitungFitness();
                        $genetik->Seleksi($fitness);
                        $genetik->StartCrossOver();

                        $fitnessAfterMutation = $genetik->Mutasi();

                        for ($j = 0; $j < count($fitnessAfterMutation); $j++) {
                            if ($fitnessAfterMutation[$j] == 1) {
                                $this->db->query('TRUNCATE TABLE jadwalkuliah');
                                $jadwal_kuliah = [[]];
                                $jadwal_kuliah = $genetik->GetIndividu($j);

                                for ($k = 0; $k < count($jadwal_kuliah); $k++) {
                                    $kode_pengampu = intval($jadwal_kuliah[$k][0]);
                                    $kode_jam = intval($jadwal_kuliah[$k][1]);
                                    $kode_hari = intval($jadwal_kuliah[$k][2]);
                                    $kode_ruang = intval($jadwal_kuliah[$k][3]);
                                    $this->db->query('INSERT INTO jadwalkuliah(kode_pengampu,kode_jam,kode_hari,kode_ruang) '.
                                                     "VALUES($kode_pengampu,$kode_jam,$kode_hari,$kode_ruang)");
                                }
                                $found = true;
                            }
                            if ($found) {
                                break;
                            }
                        }
                        if ($found) {
                            break;
                        }
                    }
                    if (!$found) {
                        $data['msg'] = 'Tidak Ditemukan Solusi Optimal';
                    }
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'penjadwalan';
        $data['page_title'] = 'Penjadwalan';
        $data['rs_jadwal'] = $this->m_jadwalkuliah->get();
        $this->render_view($data);
    }

    public function excel_report()
    {
        $query = $this->m_jadwalkuliah->get();
        if (!$query) {
            return false;
        }

        // Starting the PHPExcel library
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle('export')->setDescription('none');

        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = $query->list_fields();
        $col = 0;
        foreach ($fields as $field) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }

        // Fetching the table data
        $row = 2;
        foreach ($query->result() as $data) {
            $col = 0;
            foreach ($fields as $field) {
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
