<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Web extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['m_guru',
            'm_matapelajaran',
            'm_ruang',
            'm_jam',
            'm_hari',
            'm_pengampu',
            'm_waktu_tidak_bersedia',
            'm_jadwalpelajaran', ]);
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
    public function guru()
    {
        $data = [];

        $data['page_title'] = 'Modul Guru';
        $url = base_url().'web/guru/';
        $res = $this->m_guru->num_page();
        $per_page = 20;

        $config = admin_paginate($url, $res, $per_page, 3);
        $this->pagination->initialize($config);

        $this->m_guru->limit = $per_page;

        if ($this->uri->segment(3) == true) {
            $this->m_guru->offset = $this->uri->segment(3);
        } else {
            $this->m_guru->offset = 0;
        }

        $data['start_number'] = $this->m_guru->offset;
        $this->m_guru->sort = 'nama';
        $this->m_guru->order = 'ASC';
        $data['rs_guru'] = $this->m_guru->get();

        if ($this->input->post('ajax')) {
            $this->load->view('guru_ajax', $data);
        } else {
            $data['page_name'] = 'guru';
            $this->render_view($data);
        }
    }

    public function guru_add()
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('nip', 'NIP', 'xss_clean');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required|is_unique[guru.nama]');
            $this->form_validation->set_rules('alamat', 'Alamat', 'xss_clean');
            $this->form_validation->set_rules('telp', 'Telephon', 'xss_clean');

            if ($this->form_validation->run() == true) {
                $data['nip'] = $this->input->post('nip');
                $data['nama'] = $this->input->post('nama');
                $data['alamat'] = $this->input->post('alamat');
                $data['telp'] = $this->input->post('telp');

                if (IS_TEST === 'FALSE') {
                    $this->m_guru->insert($data);
                    $data['msg'] = 'Data Telah Berhasil Ditambahkan';
                    $data['clear_text_box'] = 'TRUE';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'guru_add';
        $data['page_title'] = 'Modul Guru Add';

        $this->render_view($data);
    }

    public function guru_edit($kode)
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('nip', 'NIP', 'xss_clean|required');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required');
            $this->form_validation->set_rules('alamat', 'Alamat', 'xss_clean');
            $this->form_validation->set_rules('telp', 'Telephon', 'xss_clean');

            if ($this->form_validation->run() == true) {
                $data['nip'] = $this->input->post('nip');
                $data['nama'] = $this->input->post('nama');
                $data['alamat'] = $this->input->post('alamat');
                $data['telp'] = $this->input->post('telp');

                if (IS_TEST === 'FALSE') {
                    $this->m_guru->update($kode, $data);
                    $data['msg'] = 'Data telah berhasil dirubah';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'guru_edit';
        $data['page_title'] = 'Modul guru Edit';
        $data['rs_guru'] = $this->m_guru->get_by_kode($kode);
        $this->render_view($data);
    }

    public function guru_delete($kode)
    {
        if (IS_TEST === 'FALSE') {
            $this->m_guru->delete($kode);
            $this->m_pengampu->delete_by_kode_guru($kode);
            $this->m_waktu_tidak_bersedia->delete_by_guru($kode);
            $this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
        } else {
            $this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
        }

        redirect(base_url().'web/guru', 'reload');
    }

    public function guru_search()
    {
        $search_query = $this->input->post('search_query');

        $data['rs_guru'] = $this->m_guru->get_search($search_query);
        $data['page_title'] = 'Cari guru';
        $data['page_name'] = 'guru';
        $data['search_query'] = $search_query;
        $data['start_number'] = 0;

        $this->render_view($data);
    }

    /*********************************************************************************************/

    public function matapelajaran()
    {
        $data = [];

        $data['page_title'] = 'Modul Mata Pelajaran';
        $url = base_url().'web/matapelajaran/';
        $res = $this->m_matapelajaran->num_page();
        $per_page = 20;

        $config = admin_paginate($url, $res, $per_page, 3);
        $this->pagination->initialize($config);

        $this->m_matapelajaran->limit = $per_page;

        if ($this->uri->segment(3) == true) {
            $this->m_matapelajaran->offset = $this->uri->segment(3);
        } else {
            $this->m_matapelajaran->offset = 0;
        }

        $data['start_number'] = $this->m_matapelajaran->offset;
        $this->m_matapelajaran->sort = 'jenis,nama';
        $this->m_matapelajaran->order = 'ASC';
        $data['rs_mp'] = $this->m_matapelajaran->get();

        if ($this->input->post('ajax')) {
            $this->load->view('matapelajaran_ajax', $data);
        } else {
            $data['page_name'] = 'matapelajaran';
            $this->render_view($data);
        }
    }

    public function matapelajaran_add()
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('kode_mp', 'Kode Mata Pelajaran', 'xss_clean');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required|is_unique[matapelajaran.nama]');
            $this->form_validation->set_rules('beban', 'Beban', 'xss_clean|required|integer');
            $this->form_validation->set_rules('semester', 'Semester', 'xss_clean|required|integer');
            $this->form_validation->set_rules('jenis', 'Jenis', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['kode_mp'] = $this->input->post('kode_mp');
                $data['nama'] = $this->input->post('nama');
                $data['beban'] = $this->input->post('beban');
                $data['semester'] = $this->input->post('semester');
                $data['jenis'] = $this->input->post('jenis');

                if (IS_TEST === 'FALSE') {
                    $this->m_matapelajaran->insert($data);
                    $data['msg'] = 'Data Telah Berhasil Ditambahkan';
                    $data['clear_text_box'] = 'TRUE';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'matapelajaran_add';
        $data['page_title'] = 'Modul Tambah Mata Pelajaran';

        $this->render_view($data);
    }

    public function matapelajaran_edit($kode)
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('kode_mp', 'Kode Mata Pelajaran', 'xss_clean');
            $this->form_validation->set_rules('nama', 'Nama', 'xss_clean|required');
            $this->form_validation->set_rules('beban', 'Beban', 'xss_clean|required|integer');
            $this->form_validation->set_rules('semester', 'Semester', 'xss_clean|required|integer');
            $this->form_validation->set_rules('jenis', 'Jenis', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['kode_mp'] = $this->input->post('kode_mp');
                $data['nama'] = $this->input->post('nama');
                $data['beban'] = $this->input->post('beban');
                $data['semester'] = $this->input->post('semester');
                $data['jenis'] = $this->input->post('jenis');

                if (IS_TEST === 'FALSE') {
                    $this->m_matapelajaran->update($kode, $data);
                    $data['msg'] = 'Data telah berhasil dirubah';
                } else {
                    $data['msg'] = 'WARNING: READ ONLY !';
                }
            } else {
                $data['msg'] = validation_errors();
            }
        }

        $data['page_name'] = 'matapelajaran_edit';
        $data['page_title'] = 'Modul Mata Pelajaran Edit';
        $data['rs_mp'] = $this->m_matapelajaran->get_by_kode($kode);
        $this->render_view($data);
    }

    public function matapelajaran_delete($kode)
    {
        if (IS_TEST === 'FALSE') {
            $this->m_matapelajaran->delete($kode);
            $this->m_pengampu->delete_by_mp($kode);
            $this->session->set_flashdata('msg', 'Data telah berhasil dihapus');
        } else {
            $this->session->set_flashdata('msg', 'WARNING: READ ONLY !');
        }

        redirect(base_url().'web/matapelajaran', 'reload');
    }

    public function matapelajaran_search()
    {
        $search_query = $this->input->post('search_query');

        $data['rs_mp'] = $this->m_matapelajaran->get_search($search_query);
        $data['page_title'] = 'Cari Mata Pelajaran';
        $data['page_name'] = 'matapelajaran';
        $data['search_query'] = $search_query;
        $data['start_number'] = 0;

        $this->render_view($data);
    }

    public function option_matapelajaran_ajax($matapelajaran_tipe)
    {
        $data['rs_mp'] = $this->m_matapelajaran->get_by_semester($matapelajaran_tipe);
        $this->load->view('option_matapelajaran_ajax', $data);
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
            //$this->form_validation->set_rules('kode','Kode mp','xss_clean');
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
            //$this->form_validation->set_rules('kode','Kode mp','xss_clean');
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
            $this->form_validation->set_rules('kode_mp', 'Mata Pelajaran', 'xss_clean|required');
            $this->form_validation->set_rules('kode_guru', 'Guru', 'xss_clean|required');
            $this->form_validation->set_rules('kelas', 'Kelas', 'xss_clean|required');
            $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['kode_mp'] = $this->input->post('kode_mp');
                $data['kode_guru'] = $this->input->post('kode_guru');

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

        $data['rs_mp'] = $this->m_matapelajaran->get_by_semester($semester_tipe);
        $data['rs_guru'] = $this->m_guru->get_all();
        $this->render_view($data);
    }

    public function pengampu_edit($kode)
    {
        $data = [];

        if (!empty($_POST)) {
            $this->form_validation->set_rules('kode_mp', 'Mata Pelajaran', 'xss_clean|required');
            $this->form_validation->set_rules('kode_guru', 'Guru', 'xss_clean|required');
            $this->form_validation->set_rules('kelas', 'Kelas', 'xss_clean|required');
            $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'xss_clean|required');

            if ($this->form_validation->run() == true) {
                $data['kode_mp'] = $this->input->post('kode_mp');
                $data['kode_guru'] = $this->input->post('kode_guru');
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

        $data['rs_mp'] = $this->m_matapelajaran->get_all();
        $data['rs_guru'] = $this->m_guru->get_all();

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
    public function waktu_tidak_bersedia($kode_guru = null)
    {
        $data = [];

        if ($kode_guru == null) {
            $kode_guru = $this->db->query('SELECT kode FROM guru ORDER BY nama LIMIT 1')->row()->kode;
        }

        if (array_key_exists('arr_tidak_bersedia', $_POST) && !empty($_POST['arr_tidak_bersedia'])) {
            if (IS_TEST === 'FALSE') {
                $this->db->query("DELETE FROM waktu_tidak_bersedia WHERE kode_guru = $kode_guru");

                foreach ($_POST['arr_tidak_bersedia'] as $tidak_bersedia) {
                    $waktu_tidak_bersedia = explode('-', $tidak_bersedia);
                    $this->db->query("INSERT INTO waktu_tidak_bersedia(kode_guru,kode_hari,kode_jam) VALUES($waktu_tidak_bersedia[0],$waktu_tidak_bersedia[1],$waktu_tidak_bersedia[2])");
                }

                $data['msg'] = 'Data telah berhasil diupdate';
            } else {
                $data['msg'] = 'WARNING: READ ONLY !';
            }
        } elseif (!empty($_POST['hide_me']) && empty($_POST['arr_tidak_bersedia'])) {
            $this->db->query("DELETE FROM waktu_tidak_bersedia WHERE kode_guru = $kode_guru");
            $data['msg'] = 'Data telah berhasil diupdate';
        }

        $data['rs_guru'] = $this->m_guru->get_all();
        $data['rs_waktu_tidak_bersedia'] = $this->m_waktu_tidak_bersedia->get_by_guru($kode_guru);
        $data['rs_hari'] = $this->m_hari->get();
        $data['rs_jam'] = $this->m_jam->get();

        $data['page_title'] = 'Waktu Tidak Bersedia';
        $data['page_name'] = 'waktu_tidak_bersedia';
        $data['kode_guru'] = $kode_guru;
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
                                    .'       b.beban,'
                                    .'       a.kode_guru,'
                                    .'       b.jenis '
                                    .'FROM pengampu a '
                                    .'LEFT JOIN matapelajaran b '
                                    .'ON a.kode_mp = b.kode '
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
                                $this->db->query('TRUNCATE TABLE jadwalpelajaran');
                                $jadwal_kuliah = [[]];
                                $jadwal_kuliah = $genetik->GetIndividu($j);

                                for ($k = 0; $k < count($jadwal_kuliah); $k++) {
                                    $kode_pengampu = intval($jadwal_kuliah[$k][0]);
                                    $kode_jam = intval($jadwal_kuliah[$k][1]);
                                    $kode_hari = intval($jadwal_kuliah[$k][2]);
                                    $kode_ruang = intval($jadwal_kuliah[$k][3]);
                                    $this->db->query('INSERT INTO jadwalpelajaran(kode_pengampu,kode_jam,kode_hari,kode_ruang) '.
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
        $data['rs_jadwal'] = $this->m_jadwalpelajaran->get();
        $this->render_view($data);
    }

    public function excel_report()
    {
        $query = $this->m_jadwalpelajaran->get();
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
