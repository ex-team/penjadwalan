<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sc extends CI_Controller
{
    public $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sc_model');
    }

    public function index()
    {
    }

    public function input_dosenkelas()
    {
        $dosen = $this->sc_model->get_all_dosen();
        $kelas = $this->sc_model->get_all_kelas();

        foreach ($kelas as $key => $value) {
            $dsn_id = mt_rand(0, (count($dosen) - 1));
            $set[$key] = [
                'kls_id' => $value['kls_id'],
                'dsn_id' => $dosen[$dsn_id]['dsn_id'],
            ];
            // echo '<pre>'; print_r($set[$key]); echo '</pre>';
            unset($dosen[$dsn_id]);
            $dosen = array_values($dosen);
        }
        // echo '<pre>'; print_r($set);

        $sts = true;
        $this->db->trans_start();
        $sts = $sts && $this->sc_model->del_dsnkelas();
        if (!empty($set)) {
            foreach ($set as $a => $item) {
                $sts = $sts && $this->sc_model->ins_dosenkelas($item);
            }
            $this->db->trans_complete();
        }

        if ($this->db->trans_status() === true) {
            echo 'sukses';
        } else {
            echo 'gagal';
        }

        exit();
    }

    public function getRandomDosenUnik($value, $jumlah_dosen)
    {
        $id = mt_rand(1, ($jumlah_dosen));
        // $status = $this->sc_model->cek_id_dosen_unik($id);
        $arr_dosen = $this->sc_model->getDosenIdByMkkurIdSame($value['kls_mkkur_id']);

        $status = true;
        if (in_array($id, $arr_dosen)) {
            return $this->getRandomDosenUnik($value, $jumlah_dosen);
        } else {
            return $id;
        }
    }

    public function input_dosenkelas_sisa()
    {
        $dosen = $this->sc_model->get_all_dosen();
        $kelas = $this->sc_model->get_all_kelas();

        $jumlah_dosen = count($dosen);
        foreach ($kelas as $key => $value) {
            if ($value['dosen_id'] == null) {
                $dsn_id = $this->getRandomDosenUnik($value, $jumlah_dosen);
                $set[] = [
                    'kls_id' => $value['kls_id'],
                    'dsn_id' => $dsn_id,
                ];
            }
        }

        $sts = true;
        $this->db->trans_start();
        if (!empty($set)) {
            foreach ($set as $a => $item) {
                $sts = $sts && $this->sc_model->ins_dosenkelas($item);
            }
            $this->db->trans_complete();
        }

        if ($this->db->trans_status() === true) {
            echo 'sukses';
        } else {
            echo 'gagal';
        }

        exit();
    }

    public function input_mkkur_prodi()
    {
        $mk = $this->sc_model->get_all_mk();
        $prodi = $this->sc_model->get_all_prodi();

        $sts = true;
        $this->db->trans_start();
        foreach ($mk as $key => $value) {
            $mk_prodi_kode = substr($value['mkkur_kode'], 0, 3);
            $mk[$key]['prodi'] = '';
            if ($mk_prodi_kode == 'UNU') {
                $mk[$key]['prodi'] = 'Universal';
            } else {
                foreach ($prodi as $i => $item) {
                    if ($mk_prodi_kode == $item['prodi_prefix_mk']) {
                        $mk[$key]['prodi'] = $item['prodi_nama'];
                        $param = [
                            $value['mkkur_id'],
                            $item['prodi_id'],
                        ];
                        $sts = $sts && $this->sc_model->ins_mkkur_prodi($param);
                    }
                }
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === true) {
            echo 'sukses';
        } else {
            echo 'gagal';
        }

        // echo '<pre>'; print_r($mk);
        // echo '<pre>'; print_r($prodi);
        exit();
    }

    public function test()
    {
        $kelas = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
        $slot = [
            'r1-1', 'r1-2', 'r1-3', 'r1-4', 'r1-5', 'r1-6',
            'r2-1', 'r2-2', 'r2-3', 'r2-4', 'r2-5', 'r2-6',
            'r3-1', 'r3-2', 'r3-3', 'r3-4', 'r3-5', 'r3-6',
            'r4-1', 'r4-2', 'r4-3', 'r4-4', 'r4-5', 'r4-6',
            'r5-1', 'r5-2', 'r5-3', 'r5-4', 'r5-5', 'r5-6',
            'r6-1', 'r6-2', 'r6-3', 'r6-4', 'r6-5', 'r6-6',
        ];

        // echo '<pre>'; print_r($kelas); echo '</pre>';
        // echo '<pre>'; print_r($slot); echo '</pre>';

        foreach ($kelas as $key => $value) {
            // echo '<pre>'; print_r($slot); echo '</pre>';
            $id_slot = mt_rand(0, (count($slot) - 1));
            $name_slot = $slot[$id_slot];
            $set[$key] = [
                'kelas' => $value,
                'id_slot' => $id_slot,
                'name_slot' => $name_slot,
            ];
            // echo '<pre>'; print_r($set[$key]); echo '</pre>';
            unset($slot[$id_slot]);
            $slot = array_values($slot);
        }

        echo '<pre>';
        print_r($set);
        echo '</pre>';
        echo '<pre>';
        print_r($slot);
        echo '</pre>';
    }

    public function set_null_prediksi()
    {
        $sts = true;
        $this->db->trans_start();
        $sts = $sts && $this->sc_model->set_null_prediksi();
        $this->db->trans_complete();

        if ($this->db->trans_status() === true) {
            $notif = [
                'style' => 'success',
                'msg' => '<strong>Congratulation</strong> Konfigurasi berhasil disimpan.',
            ];
        } else {
            $notif = [
                'style' => 'error',
                'msg' => '<strong>Warning</strong> Konfigurasi gagal disimpan.',
            ];
        }

        echo $notif['msg'];
    }
}
