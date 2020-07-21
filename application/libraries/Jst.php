<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Jst
{
    public $CI;
    public $populasi = [];
    public $pc = null;
    public $pm = null;
    public $kelas = null;
    public $ruang = null;
    public $waktu = null;
    public $timespace = null;
    public $post = null;

    public function __construct()
    {
        $this->CI = &get_instance(); // for accessing the model of CI later
    }

    /*
    Outline of the Basic Genetic Algorithm

    [Start] Generate random population of n chromosomes (suitable solutions for the problem)
    [Fitness] Evaluate the fitness f(x) of each chromosome x in the population
    [New population] Create a new population by repeating following steps until the new population is complete
        [Selection] Select two parent chromosomes from a population according to their fitness (the better fitness, the bigger chance to be selected)
        [Crossover] With a crossover probability cross over the parents to form a new offspring (children). If no crossover was performed, offspring is an exact copy of parents.
        [Mutation] With a mutation probability mutate new offspring at each locus (position in chromosome).
        [Accepting] Place new offspring in a new population
    [Replace] Use new generated population for a further run of algorithm
    [Test] If the end condition is satisfied, stop, and return the best solution in current population
    [Loop] Go to step 2
    */

    public function initialize($kelas, $ruang, $waktu, $post)
    {
        $this->kelas = $kelas;
        $this->ruang = $ruang;
        $this->waktu = $waktu;
        $this->post = $post;

        /*menginisiasi matriks timespace yg berisi ruang, hari, dan jam*/
        foreach ($this->ruang as $key => $value) {
            foreach ($this->waktu as $a => $item) {
                $this->timespace[] = [
                    'id_ruang' => $value['ru_id'],
                    'id_waktu' => $item['waktu_id'],
                    'waktu_hari' => $item['waktu_hari'],
                    'waktu_jam_mulai' => $item['waktu_jam_mulai'],
                    'label' => $value['ru_nama'].', '.$item['waktu_hari'].' '.$item['waktu_jam_mulai'].'-'.$item['waktu_jam_selesai'],
                    'kap_ruang' => $value['ru_kapasitas'],
                ];
            }
        }
    }

    /*
    pada kelas paralel yg mata kuliah sama, waktu harus sama dan di tempat yang berbeda
    */
    public function check_timespace_paralelclass_is_sametime($id_timespace, $individu, $timespace, $value)
    {
        $sts = true;
        foreach ($individu as $i => $item) {
            if ($item['id_mkkur'] == $value['mkkur_id'] and $item['id_waktu'] == $timespace[$id_timespace]['id_waktu']) {
                $sts = false;
                break;
            }
        }

        return $sts;
    }

    public function check_timespace_class_samepacket_not_sametime($id_timespace, $individu, $timespace, $value)
    {
        $sts = true;
        foreach ($individu as $i => $item) {
            if ($item['paket_smt'] == $value['paket_smt'] and $item['id_waktu'] == $timespace[$id_timespace]['id_waktu']) {
                $sts = false;
                break;
            }
        }

        return $sts;
    }

    public function check_capacity_class_ok($id_timespace, $timespace, $value)
    {
        $sts = true;
        if ($value['jml_peserta_kls'] > $timespace[$id_timespace]['kap_ruang']) {
            $sts = false;
        }

        return $sts;
    }

    public function check_lecture_class_not_sametime($id_timespace, $individu, $timespace, $value)
    {
        $sts = true;
        foreach ($individu as $i => $item) {
            if (!empty($item['dosen'])) {
                foreach ($item['dosen'] as $j => $item_dsn) {
                    if (!empty($value['dosen'])) {
                        foreach ($value['dosen'] as $k => $item_dsn_current_class) {
                            if ($item_dsn == $item_dsn_current_class and $item['id_waktu'] == $timespace[$id_timespace]['id_waktu']) {
                                $sts = false;
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $sts;
    }

    public function check_separatesameclass_not_sameday($id_timespace, $individu, $timespace, $value)
    {
        $sts = true;
        foreach ($individu as $i => $item) {
            if ($item['id_kelas'] == $value['id'] and $item['waktu_hari'] == $timespace[$id_timespace]['waktu_hari']) {
                $sts = false;
                break;
            }
        }

        return $sts;
    }

    public function get_neighbor_sametype_semester($smt)
    {
        $arr_smt_ganjil = [1, 3, 5, 7];
        $arr_smt_genap = [2, 4, 6, 8];
        $arr_neighbor = [];

        if (in_array($smt, $arr_smt_ganjil)) {
            foreach ($arr_smt_ganjil as $key => $value) {
                if (($smt == $value)) {
                    if (isset($arr_smt_ganjil[$key - 1])) {
                        $arr_neighbor[] = $arr_smt_ganjil[$key - 1];
                    }
                    if (isset($arr_smt_ganjil[$key + 1])) {
                        $arr_neighbor[] = $arr_smt_ganjil[$key + 1];
                    }
                }
            }
        } else {
            foreach ($arr_smt_genap as $key => $value) {
                if (($smt == $value)) {
                    if (isset($arr_smt_genap[$key - 1])) {
                        $arr_neighbor[] = $arr_smt_genap[$key - 1];
                    }
                    if (isset($arr_smt_genap[$key + 1])) {
                        $arr_neighbor[] = $arr_smt_genap[$key + 1];
                    }
                }
            }
        }

        return $arr_neighbor;
    }

    public function check_neighborpacketclass_not_sametime($id_timespace, $individu, $timespace, $value)
    {
        $sts = true;
        $smt_neighbor = $this->get_neighbor_sametype_semester($value['paket_smt']);
        foreach ($individu as $i => $item) {
            if ($item['sifat_makul'] == $value['sifat_makul'] and $value['sifat_makul'] == 'W' and (in_array($item['paket_smt'], $smt_neighbor)) and $item['id_waktu'] == $timespace[$id_timespace]['id_waktu']) {
                $sts = false;
                break;
            }
        }

        return $sts;
    }

    public function check_on_hardrule($id_timespace, $individu, $timespace, $value)
    {
        if (!empty($individu)) {
            $sts = true;
            $sts = $sts && $this->check_timespace_paralelclass_is_sametime($id_timespace, $individu, $timespace, $value);
            $sts = $sts && $this->check_timespace_class_samepacket_not_sametime($id_timespace, $individu, $timespace, $value);
            $sts = $sts && $this->check_capacity_class_ok($id_timespace, $timespace, $value);
            $sts = $sts && $this->check_lecture_class_not_sametime($id_timespace, $individu, $timespace, $value);
            $sts = $sts && $this->check_separatesameclass_not_sameday($id_timespace, $individu, $timespace, $value);
            $sts = $sts && $this->check_neighborpacketclass_not_sametime($id_timespace, $individu, $timespace, $value);

            return $sts;
        } else {
            return true;
        }
    }

    /*function set_idspacetime_for_paralelclass(){

        foreach ($individu as $i => $item) {
            if ($item['id_mkkur'] == $value['mkkur_id']) {

            }
        }
    }*/

    public function get_id_timespace_for_sametime($id_waktu)
    {
        $temp = [];
        foreach ($this->timespace as $key => $value) {
            if ($value['id_waktu'] == $id_waktu) {
                $temp[] = [
                    'id_timespace' => $key,
                    'data' => $value,
                ];
            }
        }

        return $temp;
    }

    public function get_feasible_individu($arr_data)
    {
        extract($arr_data);

        $break_rule = true;

        if (!empty($individu)) {
            foreach ($individu as $i => $item) {
                if ($item['id_mkkur'] == $value['mkkur_id']) {
                    $timespace_local = $this->get_id_timespace_for_sametime($item['id_waktu']);
                }
            }
        }

        while ($break_rule) {
            $id_timespace = mt_rand(0, (count($timespace) - 1));

            $rule_ok = $this->check_on_hardrule($id_timespace, $individu, $timespace, $value);
            if ($rule_ok) {
                $break_rule = false;
            }
        }

        $waktu_jam_mulai_kls = strtotime($timespace[$id_timespace]['waktu_jam_mulai']);
        $lama_menit_kelas = $period_waktu * 50;
        $waktu_jam_selesai_kls = date('H:i:s', strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));
        $individu[] = [
            'id_kelas' => $value['id'],
            'nama_kelas' => $value['nama_kelas'],
            'id_mkkur' => $value['mkkur_id'],
            'jml_peserta_kls' => $value['jml_peserta_kls'],
            'format_jadwal' => $value['format_jadwal'],
            'paket_smt' => $value['paket_smt'],
            'sifat_makul' => $value['sifat_makul'],
            'id_timespace' => $id_timespace,
            'id_waktu' => $timespace[$id_timespace]['id_waktu'],
            'label_timespace' => $timespace[$id_timespace]['label'],
            'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
            'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
            'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
            'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls,
            'period' => $period_waktu,
            'dosen' => $value['dosen'],
        ];

        for ($t = 0; $t < $period_waktu; $t++) {
            $id_timespace = $id_timespace + $t;
            unset($timespace[$id_timespace]);
        }

        $timespace = array_values($timespace);

        $ret_data = compact('timespace', 'individu', 'period_waktu', 'value');

        return $ret_data;
    }

    /*
    Aturan umum :
    1. Kelas mata kuliah yang sama harus waktu yang sama.
    2. Kelas mata kuliah yang satu paket harus beda waktu.
    3. Kapasitas ruang >= jumlah peserta.
    4. Dosen tidak mengajar kelas pada waktu yang sama.
    5. Kelas mata kuliah sama jika dipecah tidak dalam hari yg sama.
    */
    public function create_individu()
    {
        $individu = [];
        $timespace = $this->timespace;
        foreach ($this->kelas as $key => $value) {
            // echo '<pre>'; print_r($slot); echo '</pre>';
            if (!empty($value['format_jadwal'])) {
                $period = explode('-', $value['format_jadwal']);
                foreach ($period as $i => $item) {
                    $period_waktu = $item;
                    $arr_data = compact('timespace', 'individu', 'period_waktu', 'value');
                    $ret_data = $this->get_feasible_individu($arr_data);

                    extract($ret_data);
                }
            } else {
                $period_waktu = $value['sks'];
                $arr_data = compact('timespace', 'individu', 'period_waktu', 'value');
                $ret_data = $this->get_feasible_individu($arr_data);

                extract($ret_data);
            }
        }

        return $individu;
    }

    public function generate_population()
    {
        for ($i = 0; $i < $this->post['jml_individu']; $i++) {
            $this->populasi[] = $this->create_individu();
        }
    }

    /*public function count_gen_score_for_fitness($kelas, $individu){
        echo '<pre>'; print_r($data);
        foreach ($data as $key => $value) {

        }

    }*/

    public function separate_kelas_makul_wajib_pil($individu)
    {
        foreach ($individu as $key => $value) {
            if ($value['sifat_makul'] == 'W') {
                $makul_wajib[] = $value;
            }
            if ($value['sifat_makul'] == 'P') {
                $makul_pil[] = $value;
            }
        }

        $separate_makul = [
            'makul_wajib' => $makul_wajib,
            'makul_pil' => $makul_pil,
        ];

        return $separate_makul;
    }

    public function bentrok_sametime($kls, $kls_compare)
    {
        $sts = $kls['waktu_hari'] == $kls_compare['waktu_hari'];

        $start_time = strtotime($kls['waktu_jam_mulai']);
        $end_time = strtotime($kls['waktu_jam_selesai_kls']);
        $start_compare_time = strtotime($kls_compare['waktu_jam_mulai']);
        $end_compare_time = strtotime($kls_compare['waktu_jam_selesai_kls']);

        $sts_time_between = ((($start_time >= $start_compare_time) && ($start_time <= $end_compare_time) or (($end_time >= $start_compare_time) && ($end_time <= $end_compare_time))));
        $sts = $sts && $sts_time_between;

        return $sts;
    }

    public function count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($individu)
    {
        $separate_makul = $this->separate_kelas_makul_wajib_pil($individu);

        foreach ($separate_makul['makul_pil'] as $i => $pil) {
            $smt_neighbor = $this->get_neighbor_sametype_semester($pil['paket_smt']);
            $bentrok[$i] = 0;
            $bentrok_ket[$i] = '';
            foreach ($separate_makul['makul_wajib'] as $j => $wjb) {
                if (in_array($wjb['paket_smt'], $smt_neighbor) and ($this->bentrok_sametime($pil, $wjb))) {
                    $bentrok[$i]++;
                    $bentrok_ket[$i] .= $wjb['id_kelas'].', ';
                }
            }

            $separate_makul['makul_pil'][$i]['bentrok'] = $bentrok[$i];
            $separate_makul['makul_pil'][$i]['bentrok_ket'] = $bentrok_ket[$i];
        }
        echo '<pre>';
        print_r($separate_makul);
    }

    public function count_fitness()
    {
        foreach ($this->populasi as $i => $individu) {
            $populasi[$i]['fitness_rule_1'] = $this->count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($individu);

            /*foreach ($individu as $j => $kelas) {
                $this->populasi[$i][$j]['fitness'] = $this->count_gen_score_for_fitness($kelas, $individu);
            } */

            if ($i == 0) {
                break;
            }
        }

        // echo '<pre>'; print_r($this->populasi);
    }
}

/* End of file Someclass.php */
