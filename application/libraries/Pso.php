<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('max_execution_time', 0);

class Algoritma_pso {
    var $CI;
    var $populasi = array();
    var $MAX_VALUE = null;
    var $MIN_VALUE = null;
    var $kelas = null;
    var $ruang = null;
    var $waktu = null;
    var $timespace = null;
		var $post = null;
		var $prodi = null;
		var $min_prosen_capacity = null;
		var $populasi_breeding = array();
    var $populasi_breeding_selected = array();
    var $total_fitness = 0;
    var $individu_breed = array();
    var $individu_update_calon = array();
    var $populasi_baru = array();
    var $kromosom = array();
    var $err_msg = '';
    var $status_rule = '';
    var $log_proses = array();

		//between 0.9 and 1.2
		const INERTIAL_WEIGHT = 0.9;
		//acceleration constant
		const C1 = 2;
		//acceleration constant
		const C2 = 2;

		//swarm
		public $swarm = array();
		//Global Best
		public $gBest = array();
		//Best Fitness over all particles
		public $bestFitness = 999999999999;

    public function __construct()
    {
        $this->CI = &get_instance(); // for accessing the model of CI later
				$this->CI->load->library('bantu');
				$this->CI->load->library('aturan_jadwal');
				$this->CI->load->library('session');


			//initialization
			for ($i = 1; $i <= Problem::PARTICLES_NUMBER; $i++) {
				for ($j = 1; $j <= Problem::DIMENSIONS; $j++) {

						$randomPosition = $this->generateRandomPosition();
						$randomVelocity = $this->generateRandomVelocity();

						//velocity
						$this->swarm[$i]["VELOCITY"][$j] = $randomVelocity;
						//position
						$this->swarm[$i]["POSITION"][$j] = $randomPosition;
				}

				//current particles
				$particle = $this->swarm[$i];
				//fitness calculation of currenr particle
				$fitness = $this->fitnessFunction($particle["POSITION"][1], $particle["POSITION"][2]);
				//if fitness of current particle was beter than global best fitness
				if ($fitness < $this->bestFitness) {
						//replacing best fitness and global best
						$this->bestFitness = $fitness;
						$this->gBest = $particle;
				}
				$this->swarm[$i]["P_BEST"] = $this->swarm[$i];
			}
		}

		protected function fitnessFunction($x, $y) {
			return pow(($x + (2 * $y) - 7), 2) + pow(((2 * $x) + $y - 5), 2);
		}
		 /**
     * to get answer
     * actually, f(1,3)=0
     * @return array [1]=x | [2]=y
     */
    protected function getAnswer() {
			return array(
					1 => 1,
					2 => 3
			);
		}

	/*
    Outline of the Basic Genetic Algorithm
		[Start] Generate random population of n chromosomes (suitable solutions for the problem)
    [Fitness] Evaluate the fitness f(x) of each chromosome x in the population
    [New population] Create a new population by repeating following steps until the new population is complete
    [Accepting] Place new offspring in a new population
    [Replace] Use new generated population for a further run of algorithm
    [Test] If the end condition is satisfied, stop, and return the best solution in current population
    [Loop] Go to step 2
    */

	// Inisialisasi partikel
	public function initialize($kelas, $ruang, $waktu, $post, $prodi, $min_prosen_capacity){
      $this->kelas = $kelas;
      $this->ruang = $ruang;
      $this->waktu = $waktu;
      $this->post = $post;
      $this->MAX_VALUE = $post['maxv'];
      $this->MIN_VALUE = $post['minv'];
      $this->prodi = $prodi;
			$this->min_prosen_capacity = $min_prosen_capacity;


      /*menginisiasi matriks timespace yg berisi ruang, hari, dan jam*/
      $i = 0;
      foreach ($this->ruang as $key => $value) {
          foreach ($this->waktu as $a => $item) {
              $this->timespace[] = array(
                  'id_timespace' => $i++,
                  'id_ruang' => $value['ru_id'],
                  'id_waktu' => $item['waktu_id'],
                  'waktu_hari' => $item['waktu_hari'],
                  'waktu_jam_mulai' => $item['waktu_jam_mulai'],
                  'label' => $value['ru_nama'].', '.$item['waktu_hari'].' '.$item['waktu_jam_mulai'].'-',
                  'kap_ruang' => $value['ru_kapasitas'],
                  'status' => '',
                  'status_nested' => ''
              );
          }
      }
	}

	public function generate_population(){
			$this->kromosom = $this->create_information_class(); // buat individu
			/* bangkitkan populasi yang terdiri dari sejumlah individu */
			$this->classinfo = array();
			$this->populasi = array(); // empty population each generation
			for ($i=0; $i < $this->post['jml_individu']; $i++) {
					$individu = $this->create_individu(); // buat individu
					// $individu = $this->repair_kelas_on_hardrule($individu);
					$this->populasi[] = $individu;
					// break;
			}
	}

	public function create_information_class(){
			$class = array();
			$id_individu = 0;
			foreach ($this->kelas as $key => $value) {
					if (!empty($value['format_jadwal']) ) {
							$period = explode('-', $value['format_jadwal']);
							foreach ($period as $i => $item) {
									$period_waktu = $item;
									$arr_data = compact('class','period_waktu','value', 'id_individu');
									$ret_data = $this->make_class($arr_data);
									extract($ret_data);
							}
					}else{
							$period_waktu = $value['sks'];
							$arr_data = compact('class','period_waktu','value', 'id_individu');
							$ret_data = $this->make_class($arr_data);
							extract($ret_data);
					}
			}
			return $class;
	}

	/*
	Aturan umum :
	1. Kelas mata kuliah yang sama harus waktu yang sama.
	2. Kelas mata kuliah yang satu paket harus beda waktu.
	3. Kapasitas ruang >= jumlah peserta.
	4. Dosen tidak mengajar kelas pada waktu yang sama.
	5. kelas makul sama yg dipecah sks nya diadakan pada hari yang berbeda.
	6. Kelas makul paket wajib berdekatan jenis smt harus beda waktu.
	*/
	public function create_individu(){
			$individu = array(); // untuk menampung sejumlah individu yang mewakili jadwal
			$makul_grup = array(); // untuk mengelompokan kelas berdasar matakuliahnya.
			$waktudistinct_grup = array(); // untuk menampung waktu_id hasil pengelompokan kelas berdasar makulnya.
			$timespace = $this->timespace; // matriks data ruang, hari, dan waktu
			$grup_kemipaan = array();
			$waktudistinct_grup_kemipaan = array();
			$status_reset_individu = false;

			foreach ($this->kromosom as $key => $value) {
					$arr_data = compact('timespace','individu','value', 'makul_grup', 'waktudistinct_grup', 'grup_kemipaan','waktudistinct_grup_kemipaan', 'status_reset_individu');

					$ret_data = $this->get_feasible_individu($arr_data);
					if ($status_reset_individu) {
							unset($individu);
							unset($grup_kemipaan);
							unset($waktudistinct_grup_kemipaan);
							unset($makul_grup);
							unset($waktudistinct_grup);
							unset($timespace);

						return $this->create_individu();
					}
					extract($ret_data);
			}
			return $individu;
	}

	public function get_feasible_individu($arr_data){
			extract($arr_data);

			/*
			menentukan jadwal ruang & waktu untuk kelas diwakili oleh id_timespace
			cek apakah kelas makul yang sama sudah ada sebelumnya di kelas terjadwal
			kelas makul yang sama adalah kelas paralel yang makulnya sama
			jika tidak maka id_timespace bisa diambil dari matriks ruang & waktu
			jika iya maka id_timespace diambil dari matriks ruang & waktu yg lebih spesifik,
			yakni yg waktunya sama dgn kls terjdwal sebelumnya yg makul sama. Karna ada aturan
			kelas paralel diadakan dalam waktu yg sama.
			*/

			$period_waktu = $value['period'];
			$individu_classprodi = $this->break_individu_prodi($individu); // uni atau bukan

			$kode_kemipaan = $value['id_mkkur'].'-'.$period_waktu.'-'.$value['kls_jadwal_merata'].'-'.$value['kls_id_grup_jadwal'];
			$status_kemipaan = !in_array($kode_kemipaan, $grup_kemipaan) && ($value['kls_jadwal_merata'] == '1');

			if ($value['kls_jadwal_merata'] == '1') {
					if ( $status_kemipaan) {
							$id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, 0);

							$waktudistinct_grup_kemipaan[] = $timespace[$id_timespace]['id_waktu'];
							$grup_kemipaan[] = $kode_kemipaan;
					}else{
							$id_timespace = $this->get_random_local($individu_classprodi, $individu, $value, $timespace, $grup_kemipaan, $waktudistinct_grup_kemipaan, $period_waktu);

					}

			}else{

					if ( !in_array($value['id_mkkur'].'-'.$period_waktu, $makul_grup) ) {
							$id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, 0);
							$waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
							$makul_grup[] = $value['id_mkkur'].'-'.$period_waktu;
					}else{
							$id_timespace = $this->get_random_local($individu_classprodi, $individu, $value, $timespace, $makul_grup, $waktudistinct_grup, $period_waktu);
					}

			}
			if ($id_timespace == 'nochance') {
				$status_reset_individu = true;
			}else{
				/* menyimpan hasil ruang & waktu untuk kelas, beserta periodenya */
				$waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $period_waktu);
				$individu[] = array(
						'id_kromosom' => $value['id_individu'],
						'nama_kelas' => $value['nama_kelas'],
						'id_timespace' => $timespace[$id_timespace]['id_timespace'],
						'id_waktu' => $timespace[$id_timespace]['id_waktu'],
						'period' => $value['period'],
						'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
						'id_ruang' => $timespace[$id_timespace]['id_ruang'],
						'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
						'label_timespace' => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
						'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
						'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls
				);
				// menghapus index beserta nilainya untuk data ruang & waktu yg dipakai kelas untuk jadwal
				for ($t=0; $t < $period_waktu; $t++) {
						$id_timespace += $t;
						$timespace[$id_timespace]['status'] = 1;
				}
			}
			$ret_data = compact('timespace','individu','value', 'makul_grup', 'waktudistinct_grup', 'grup_kemipaan', 'waktudistinct_grup_kemipaan', 'status_reset_individu');
			return $ret_data;

	}

	/*
	fungsi ini digunakan untuk mencari id_timespace yg nanti dipakai kelas paralel matakuliah yg sama
	*/
	function get_random_local($individu_classprodi, $individu, $kelas, $timespace, $makul_grup, $waktudistinct_grup, $period_waktu, $id_ts=null){

			/*
			membuat matriks ruang & waktu timespace_grup_waktu khusus untuk makul yang sama,
			matriks ini waktunya sama, hanya ruang yang beda
			*/
			$period_waktu = $kelas['period'];
			if (!empty($makul_grup)) {
					if ($kelas['kls_jadwal_merata']==1) {
							$kode_kemipaan = $kelas['id_mkkur'].'-'.$period_waktu.'-'.$kelas['kls_jadwal_merata'].'-'.$kelas['kls_id_grup_jadwal'];
							foreach ($makul_grup as $a => $mk) {
									if ($mk == $kode_kemipaan) {
											$timespace_grup_waktu = $this->get_id_timespace_for_sametime($timespace, $waktudistinct_grup[$a]);
									}
							}
					}else{
							foreach ($makul_grup as $a => $mk) {
									if ($mk == $kelas['id_mkkur'].'-'.$period_waktu) {
											$timespace_grup_waktu = $this->get_id_timespace_for_sametime($timespace, $waktudistinct_grup[$a]);
									}
							}
					}

			}

			if ($id_ts != null) {
					$id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $kelas, $timespace, $period_waktu, 0, $timespace_grup_waktu, $id_ts);
			}else{
					$id_timespace = $this->getRandomTimespace($individu_classprodi, $individu, $kelas, $timespace, $period_waktu, 0, $timespace_grup_waktu);

			}

			return $id_timespace;
	}

	function get_id_timespace_for_sametime($timespace, $id_waktu){
			$temp = array();
			foreach ($timespace as $key => $value) {
					if ((isset($value['id_waktu']) and ($value['id_waktu'] == $id_waktu)) and $value['status'] != 1) {
							$temp[] = array(
									'id_timespace' => $key,
									'data' => $value
							);
					}
			}
			return $temp;
	}

	function cekPeluang($timespace, $timespace_grup_waktu=null){
		$jml=0;
			if ($timespace_grup_waktu != null) {
				foreach ($timespace_grup_waktu as $i => $item) {
					if ($item['data']['status_nested']==1) {
						$jml = $jml +1;
					}
				}
				if ($jml == count($timespace_grup_waktu)) {
					echo "peluang 0%";
					echo '<pre>'; print_r($value); echo '</pre>';
					exit();
				}
			}else{
				foreach ($timespace as $i => $item) {
					if ($item['status_nested']==1) {
						$jml = $jml +1;
					}
				}
				if ($jml == count($timespace)) {
					echo "peluang 0%";
					echo '<pre>'; print_r($value); echo '</pre>';
					exit();
				}
			}
	}

	function getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration, $timespace_grup_waktu=null, $id_ts=null ){
			$iteration++;
			if ($iteration == 10000) {
				return 'nochance';
			}else{
			}

			if ($id_ts==null) {
					if ($timespace_grup_waktu != null) { // timespace lokal
							$id_timespace_grup_waktu = mt_rand(0,(count($timespace_grup_waktu)-1));
							$id_timespace = $timespace_grup_waktu[$id_timespace_grup_waktu]['id_timespace'];
					}else{ // timespace global
							$id_timespace = mt_rand(0,(count($timespace)-1));
					}
			}else{
					$id_timespace = $id_ts;
			}



			$sts = true;
			for ($t=0; $t < $period_waktu; $t++) {
					$ts_ok = true;
					$id_ts = $id_timespace + $t;
					// if (!isset($timespace[$id_ts]) OR $timespace[$id_ts]['status'] == 1 OR $timespace[$id_ts]['status_nested']==1) {
					if (!isset($timespace[$id_ts]) OR $timespace[$id_ts]['status'] == 1 ) {
							$ts_ok = false;
					}
					$sts = $sts && $ts_ok;
			}
			/*kondisi apakah cari timespace lokal atau global*/
			if ($timespace_grup_waktu != null) {
					if (!$sts) {
							return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration, $timespace_grup_waktu);
					}else{

							$rule_ok = $this->check_on_hardrule_for_paralelclass($individu_classprodi, $individu, $value, $id_timespace, $timespace, $iteration);

							if ($rule_ok) {
									return $id_timespace;
							}else{
									return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration, $timespace_grup_waktu);
							}

					}
			}else{

					if (!$sts) {
							return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration);
					}else{
							$rule_ok = $this->check_on_hardrule($individu_classprodi, $individu, $value, $id_timespace, $timespace, $period_waktu, $iteration);
							if ($rule_ok) {
									return $id_timespace;
							}else{
									return $this->getRandomTimespace($individu_classprodi, $individu, $value, $timespace, $period_waktu, $iteration);
							}
					}
			}

	}


	// =====================================================================================================================
	/*
	pada kelas paralel yg mata kuliah sama, waktu harus sama dan di tempat yang berbeda
	*/

	public function check_on_hardrule($individu_classprodi, $individu, $value, $id_timespace, $timespace, $period_waktu, $iteration=null){
			// if (!empty($individu)) {
			$sts = true;
				// $sts = $sts && $this->check_timespace_paralelclass_is_sametime($id_timespace, $individu, $timespace, $value);
			$stsrule_1 = $this->CI->aturan_jadwal->check_time_notover_limit($id_timespace, $timespace, $period_waktu);
			$stsrule_2 = $this->CI->aturan_jadwal->check_timespace_class_samepacket_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $value, $id_timespace, $timespace, $this->prodi);
			$stsrule_3 = $this->CI->aturan_jadwal->check_capacity_class_ok($id_timespace, $timespace, $value);
			$stsrule_4 = $this->CI->aturan_jadwal->check_lecture_class_not_sametime($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
			$stsrule_5 = $this->CI->aturan_jadwal->check_separatesameclass_not_sameday($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
			$stsrule_6 = $this->CI->aturan_jadwal->check_neighborpacketclass_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $value, $id_timespace, $timespace, $this->prodi);

			$sts = $stsrule_1 && $stsrule_2 && $stsrule_3 && $stsrule_4 && $stsrule_5 && $stsrule_6 ;
			return $sts;
	}

	function cek_kebenaran($param){
			foreach ($param as $key => $value) {
					if (!$value) {
							echo 'rule_idx_'.$key;
					}
			}

	}

	public function check_on_hardrule_for_paralelclass($individu_classprodi, $individu, $value, $id_timespace, $timespace, $iteration=null){
			if (!empty($individu)) {
					$sts = true;
					$stsrule_1 = $this->CI->aturan_jadwal->check_timespace_paralelclass_is_sametime($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
					$stsrule_2 = $this->CI->aturan_jadwal->check_capacity_class_ok($id_timespace, $timespace, $value);
					$stsrule_3 = $this->CI->aturan_jadwal->check_lecture_class_not_sametime($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
					$stsrule_4 = $this->CI->aturan_jadwal->check_separatesameclass_not_sameday($this->kromosom, $this->timespace, $individu, $value, $id_timespace, $timespace);
					$stsrule_5 = $this->CI->aturan_jadwal->check_neighborpacketclass_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $value, $id_timespace, $timespace, $this->prodi);
					$sts = $stsrule_1 && $stsrule_2 && $stsrule_3 && $stsrule_4 && $stsrule_5 ;
					return $sts;
			}else{
					return true;
			}

	}

	//===================================================================================================================


	public function get_jam_selesai_kelas($waktu_jam_mulai, $period_waktu){
		$waktu_jam_mulai_kls = strtotime($waktu_jam_mulai);
			$lama_menit_kelas = $period_waktu * 50;
			$waktu_jam_selesai_kls = date('H:i:s', strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));

			return $waktu_jam_selesai_kls;
	}

	function break_individu_prodi($individu){
			$u = null;
			$p = null;
			foreach ($individu as $t => $ind) {
					if ($this->kromosom[$ind['id_kromosom']]['is_universal'] == '1') {
							$u[] = $ind;
					}else{
							foreach ($this->prodi as $t => $pr) {
									$kelas_prodi = explode('|', $this->kromosom[$ind['id_kromosom']]['kelas_prodi']);
									if (!empty($kelas_prodi) AND in_array($pr['prodi_id'],$kelas_prodi)) {
											$p[$t][] = $ind;
									}
							}

					}
			}

			$return = array(
					"uni" => $u,
					"pro" => $p,
			);

			return $return;
	}

	public function make_class($arr_data){
			extract($arr_data);
			/*
			menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
			*/
			$class[] = array(
					'id_individu' => $id_individu,
					'id_kelas' => $value['id'],
					'id_mkkur' => $value['mkkur_id'],
					'period' => $period_waktu,
					'nama_kelas' => $value['nama_kelas'],
					'jml_peserta_kls' => $value['jml_peserta_kls'],
					'format_jadwal' => $value['format_jadwal'],
					'paket_smt' => $value['paket_smt'],
					'sifat_makul' => $value['sifat_makul'],
					'dosen' => $value['dosen'],
					'ruang_blok_prodi' => $value['ruang_blok_prodi'],
					'kelas_prodi' => $value['kelas_prodi'],
					'alternatif_waktu_ajar' => $value['alternatif_waktu_ajar'],
					'is_universal' => $value['is_universal'],
					'kls_jadwal_merata' => $value['kls_jadwal_merata'],
					'kls_id_grup_jadwal' => $value['kls_id_grup_jadwal']
			);

			$id_individu++;

			$ret_data = compact('class','period_waktu','value', 'id_individu');
			return $ret_data;
	}

	function cek_langgar_jam($individu){
		$jam_akhir_kampus = '17:20:00';
			$hari_waktu_jumatan = 'jumat';
			$jam_waktu_jumatan = '11:20:00';
		foreach ($individu as $key => $value) {
			$hari_kls = ($this->timespace[$value['id_kromosom']]['waktu_hari']);
				$waktu_jam_mulai_kls = strtotime($this->timespace[$value['id_kromosom']]['waktu_jam_mulai']);
				$lama_menit_kelas = $this->kromosom[$value['id_kromosom']]['period'] * 50;
				$waktu_jam_selesai_kls = date('H:i:s', strtotime('+'.$lama_menit_kelas.' minutes', $waktu_jam_mulai_kls));
			echo 'Kelas : '.$this->kromosom[$value['id_kromosom']]['id_kelas'].' => '.$this->timespace[$value['id_kromosom']]['id_ruang'].' '.$this->timespace[$value['id_kromosom']]['waktu_hari'].', '.$this->timespace[$value['id_kromosom']]['waktu_jam_mulai'].'-'.$waktu_jam_selesai_kls.' ';
			if (
						strtotime($waktu_jam_selesai_kls) > strtotime($jam_akhir_kampus)
						OR ($hari_kls == $hari_waktu_jumatan
								AND ($waktu_jam_mulai_kls) < strtotime($jam_waktu_jumatan)
								AND strtotime($waktu_jam_selesai_kls) > strtotime($jam_waktu_jumatan)
						)
				) {
						echo '[bukti!!!!]';
				}
				echo '<br>';
		}
	}

	function bentrok_sametime($kls, $kls_compare) {

			$sts = $this->timespace[$kls['id_timespace']]['waktu_hari'] == $this->timespace[$kls_compare['id_timespace']]['waktu_hari'];

			$start_time = strtotime($this->timespace[$kls['id_timespace']]['waktu_jam_mulai']);
			$waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($this->timespace[$kls['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$kls['id_kromosom']]['period']);
			$end_time = strtotime($waktu_jam_selesai_kls);

			$start_compare_time = strtotime($this->timespace[$kls_compare['id_timespace']]['waktu_jam_mulai']);
			$waktu_jam_selesai_kls_compare = $this->get_jam_selesai_kelas($this->timespace[$kls_compare['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$kls_compare['id_kromosom']]['period']);
			$end_compare_time = strtotime($waktu_jam_selesai_kls_compare);

			$sts_time_between = ( ( ($start_time >= $start_compare_time) && ($start_time <= $end_compare_time) OR ( ($end_time >= $start_compare_time) && ($end_time <= $end_compare_time) ) ) );
			$sts = $sts && $sts_time_between;

			return $sts;
	}

	public function separate_kelas_makul_wajib_pil($individu){
			foreach ($individu as $key => $value) {
					if (strtolower($this->kromosom[$value['id_kromosom']]['sifat_makul']) == 'w') {
							$makul_wajib[] = $value;
					}
					if (strtolower($this->kromosom[$value['id_kromosom']]['sifat_makul']) == 'p') {
							$makul_pil[] = $value;
					}
			}

			$separate_makul = array(
					'makul_wajib' => $makul_wajib,
					'makul_pil' => $makul_pil
			);

			return $separate_makul;
	}

	public function count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($individu){
		$ind_classprodi = $this->break_individu_prodi($individu);

		foreach ($ind_classprodi['pro'] as $i => $arr_kromosom) {
		foreach ($arr_kromosom as $k => $kr_pr) {
			$data_perprod[$i][] = $kr_pr;
		}
		if (!empty($ind_classprodi['uni'])) {
			foreach ($ind_classprodi['uni'] as $j => $kr_un) {
				$data_perprod[$i][] = $kr_un;

			}
		}

	}

	$jumlah_perprod = 0;
	$jumlah_makulpil = 0;
	foreach ($data_perprod as $key => $indi_pr) {
		$separate_makul = $this->separate_kelas_makul_wajib_pil($indi_pr);
		$jumlah = 0;
				foreach ($separate_makul['makul_pil'] as $i => $pil) {
						$smt_neighbor = $this->CI->aturan_jadwal->get_neighbor_sametype_semester($this->kromosom[$pil['id_kromosom']]['paket_smt']);

						$bentrok[$i] = 0;
						$bentrok_ket[$i] = '';

						$sts_bentrok = false;
						foreach ($separate_makul['makul_wajib'] as $j => $wjb) {
								if (in_array($this->kromosom[$wjb['id_kromosom']]['paket_smt'],$smt_neighbor) AND ($this->bentrok_sametime($pil,$wjb)) ) {
										$bentrok[$i]++;
										$bentrok_ket[$i] .= $this->kromosom[$wjb['id_kromosom']]['id_kelas'].', ';
										$sts_bentrok = true;
								}
						}

						$separate_makul['makul_pil'][$i]['bentrok'] = $bentrok[$i];
						$separate_makul['makul_pil'][$i]['bentrok_ket'] = $bentrok_ket[$i];

						if ($sts_bentrok) {
								$jumlah++;
						}
				}
				$jumlah_makulpil = $jumlah_makulpil + count($separate_makul['makul_pil']);
				$jumlah_perprod = $jumlah_perprod + $jumlah;
	}

	$fitness = $jumlah_perprod / $jumlah_makulpil;
			return $fitness;
	}

	public function count_fitness_based_rule_kelasmakul_on_ruangblokprodi($individu){
			$score = 0;
			$i = 0;
			foreach ($individu as $key => $value) {
					$arr_ruangblokprodi[$key] = explode('|', $this->kromosom[$value['id_kromosom']]['ruang_blok_prodi']);
					if (!empty($arr_ruangblokprodi[$key]) AND !in_array($this->timespace[$value['id_timespace']]['id_ruang'], $arr_ruangblokprodi[$key])) {
							$score++;
					}
					if ( !empty($arr_ruangblokprodi[$key]) ) {
							$i++;
					}

			}

			$fitness = $score / $i;

			return $fitness;
	}

	public function count_fitness_based_rule_kelasmakulsepaket_max_8_sks_sehari($individu){
			$arr_hari = array('senin', 'selasa', 'rabu', 'kamis', 'jumat');

			$total_langgar = 0;
			foreach ($arr_hari as $i => $hari) {
					$arr_grup_hari[$i] = array(
							'hari' => $hari,
							'data_prodi' => array()
					);
					$jml_prodi_langgar = 0;
					foreach ($this->prodi as $j => $prodi) {
							$arr_grup_hari[$i]['data_prodi'][$j] = array(
									'prodi_id' => $prodi['prodi_id'],
									'data_semester' => array()
							);
							$jml_smt_langgar = 0;
							for ($l=1; $l <= 8; $l++) {
									$arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l] = array(
											'paket_semester' => $l,
											'data_kelas' => array()
									);
									$total_sks = 0;
									foreach ($individu as $k => $kelas_terjadwal) {
											$arr_kelas_prodi = explode('|', $this->kromosom[$kelas_terjadwal['id_kromosom']]['kelas_prodi']);
											if ($hari == $this->timespace[$kelas_terjadwal['id_timespace']]['waktu_hari'] AND in_array($prodi['prodi_id'], $arr_kelas_prodi) AND $this->kromosom[$kelas_terjadwal['id_kromosom']]['paket_smt'] == $l) {
													$total_sks += $this->kromosom[$kelas_terjadwal['id_kromosom']]['period'];
													$arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l]['data_kelas'][] = $kelas_terjadwal;
											}
									}
									$arr_grup_hari[$i]['data_prodi'][$j]['data_semester'][$l]['total_sks'] = $total_sks;
									if ($total_sks > 8) {
											$jml_smt_langgar++;
									}

							}
							$arr_grup_hari[$i]['data_prodi'][$j]['jml_smt_langgar'] = $jml_smt_langgar;
							if ($jml_smt_langgar > 0) {
									$jml_prodi_langgar++;
							}
					}
					$arr_grup_hari[$i]['jml_prodi_langgar'] = $jml_prodi_langgar;
					$total_langgar += $jml_prodi_langgar;
			}

			$jml_semesta_himp = count($arr_hari) * count($this->prodi);
			$fitness = $total_langgar / $jml_semesta_himp;

			return $fitness;

	}

	public function count_fitness_based_rule_kelas_filled_min_prosen_capacity($individu){
			$melanggar = 0;
			foreach ($individu as $key => $value) {
					$harapan_jml = ($this->min_prosen_capacity / 100) * $this->timespace[$value['id_timespace']]['kap_ruang'];
					$individu[$key]['harapan_jml'] = ceil($harapan_jml);
					$individu[$key]['melanggar'] = 0;
					if ($this->kromosom[$value['id_kromosom']]['jml_peserta_kls'] < ceil($harapan_jml)) {
							$individu[$key]['melanggar'] = 1;
							$melanggar++;

					}
			}

			$fitness = $melanggar / count($individu);
			return $fitness;

	}

	public function count_fitness_based_rule_kelas_dosen_choose_their_time($individu){
			$jml_langgar = 0;
			foreach ($individu as $key => $value) {
					$arr_alternatif_waktu_ajar = explode('|', $this->kromosom[$value['id_kromosom']]['alternatif_waktu_ajar']);
					if (!in_array($this->timespace[$value['id_timespace']]['id_waktu'], $arr_alternatif_waktu_ajar)) {
							$jml_langgar++;
					}
			}

			$fitness = $jml_langgar / count($individu);

			return $fitness;
	}

	public function transform_populasi(){
			foreach ($this->populasi as $key => $individu) {
					$this->populasi_breeding[$key]['fitness'] = 0;
					foreach ($individu as $i => $gen) {
							$this->populasi_breeding[$key]['arr_gen'][$i] = $gen;
					}
			}
	}

	public function count_fitness(){

			$this->transform_populasi();
			$this->total_fitness = 0;
			foreach ($this->populasi as $i => $individu) {
					$populasi[$i]['fitness_rule_1'] = $this->count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($individu);
					$populasi[$i]['fitness_rule_2'] = $this->count_fitness_based_rule_kelasmakul_on_ruangblokprodi($individu);
					$populasi[$i]['fitness_rule_3'] = $this->count_fitness_based_rule_kelasmakulsepaket_max_8_sks_sehari($individu);
					$populasi[$i]['fitness_rule_4'] = $this->count_fitness_based_rule_kelas_filled_min_prosen_capacity($individu);
					$populasi[$i]['fitness_rule_5'] = $this->count_fitness_based_rule_kelas_dosen_choose_their_time($individu);

					$populasi[$i]['fitness'] = 1-(($populasi[$i]['fitness_rule_1'] + $populasi[$i]['fitness_rule_2'] + $populasi[$i]['fitness_rule_3'] + $populasi[$i]['fitness_rule_4'] + $populasi[$i]['fitness_rule_5']) / 5);
					$this->populasi_breeding[$i]['fitness'] = $populasi[$i]['fitness'];
					$this->total_fitness += $populasi[$i]['fitness'];
			}

			unset($populasi);
	}

	public function roulette_wheel_selection(){
			$populasi_breeding = $this->populasi_breeding;
			foreach ($populasi_breeding as $key => $value) {
					$prob = $value['fitness'] / $this->total_fitness;
					$populasi_breeding[$key]['idx'] = $key;
					$populasi_breeding[$key]['prob'] = round($prob,5);

					if ($key == 0) {
							$rentangan[$key]['awal'] = 0;
					}else{
							$rentangan[$key]['awal'] = $rentangan[($key-1)]['akhir'] + 0.00001;
					}

					$rentangan[$key]['akhir'] = $rentangan[$key]['awal'] + $populasi_breeding[$key]['prob'];
					$random_number[$key] = mt_rand(0,100000)/100000;

			}

			$pick_individu = array();
			foreach ($random_number as $i => $val) {
					foreach ($rentangan as $j => $vale) {
							// if ($val >= $vale['awal'] and $val <= $vale['akhir'] and !in_array($j, $pick_individu)) {
							if ($val >= $vale['awal'] and $val <= $vale['akhir'] ) {
									$pick_individu[] = $j;
							}
					}
			}

			// foreach ($pick_individu as $key => $value) {
			for ($i=0; $i < $this->post['jml_individu']; $i++) {
					$populasi_breeding_selected[] = $populasi_breeding[$pick_individu[$i]];
			}

			foreach ($populasi_breeding_selected as $key => $value) {
					$populasi_breeding_selected[$key]['val_random'] = mt_rand(0,100000)/100000; // for selecting on crossover
			}

			$this->total_fitness = 0; // set total fitness 0 karna sudah digunakan
			$this->populasi_breeding_selected = $populasi_breeding_selected;

			// unset($populasi_breeding_selected);
			// unset($pick_individu);
			// unset($populasi_breeding);
			// unset($random_number);
			// unset($rentangan);
	}

	public function repair_duplikasi_kelas_timespace($individu){
			$arr_kelas = array();
			$arr_id_timespace = array();
			$kelas_duplikasi = array();
			$timespace = $this->timespace;

			foreach ($individu as $key => $value) {
					if (!in_array($value['id_timespace'], $arr_id_timespace)) {
							$arr_id_timespace[] = $value['id_timespace'];
							$arr_kelas[] = $value;
					}else{
							$kelas_duplikasi[] = array(
									'idx' => $key,
									'data' => $value
							);
					}
			}

			$makul_grup = array();
			$waktudistinct_grup = array();
			foreach ($arr_kelas as $key => $value) {
					foreach ($timespace as $i => $item) {
							if ( $value['id_timespace'] == $i) {
									for ($j=0; $j < $this->kromosom[$value['id_kromosom']]['period']; $j++) {
											unset($timespace[$j+$i]);
									}

							}
					}
					// membuat grup kelas per matakuliah secara dinamis
					if (!in_array($this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'], $makul_grup)) {
							$makul_grup[] = $this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'];
							$waktudistinct_grup[] = $this->timespace[$value['id_kromosom']]['id_waktu'];
					}
			}
			$timespace = array_values($timespace);

		if (!empty($kelas_duplikasi)) {
			foreach ($kelas_duplikasi as $key => $value) {
						$id_timespace = mt_rand(0,(count($timespace)-1));

						$individu[$value['idx']]['id_timespace'] = $id_timespace;

				}
		}
			return $individu;
	}

	public function repair_kelas_on_hardrule($individu){
			$individu_temp = array(); // untuk menampung sejumlah individu yang mewakili jadwal
			$makul_grup = array(); // untuk mengelompokan kelas berdasar matakuliahnya.
			$waktudistinct_grup = array(); // untuk menampung waktu_id hasil pengelompokan kelas berdasar makulnya.
			$timespace = $this->timespace; // matriks data ruang, hari, dan waktu
			$grup_kemipaan = array();
			$waktudistinct_grup_kemipaan = array();
			$status_reset_individu = false;


			foreach ($this->kromosom as $key => $value) {
					$individu_classprodi = $this->break_individu_prodi($individu_temp);

					$id_mkkur = $value['id_mkkur'];
					$period_waktu = $value['period'];
					$kls_jadwal_merata = $value['kls_jadwal_merata'];
					$kls_id_grup_jadwal = $value['kls_id_grup_jadwal'];

					$kode_kemipaan = $id_mkkur.'-'.$period_waktu.'-'.$kls_jadwal_merata.'-'.$kls_id_grup_jadwal;
					$status_kemipaan = !in_array($kode_kemipaan, $grup_kemipaan) && ($kls_jadwal_merata == '1');

					if ($kls_jadwal_merata == '1') {
							if ( $status_kemipaan) {
									$id_timespace = $this->getRandomTimespace($individu_classprodi, $individu_temp, $value, $timespace, $period_waktu, 0, null, $individu[$value['id_individu']]['id_timespace']);

									$waktudistinct_grup_kemipaan[] = $timespace[$id_timespace]['id_waktu'];
									$grup_kemipaan[] = $kode_kemipaan;
							}else{
									$id_timespace = $this->get_random_local($individu_classprodi, $individu_temp, $value, $timespace, $grup_kemipaan, $waktudistinct_grup_kemipaan, $period_waktu, $individu[$value['id_individu']]['id_timespace']);
							}

					}else{
							if ( !in_array($value['id_mkkur'].'-'.$period_waktu, $makul_grup) ) {
									$id_timespace = $this->getRandomTimespace($individu_classprodi, $individu_temp, $value, $timespace, $period_waktu, 0, null, $individu[$value['id_individu']]['id_timespace']);

									$waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
									$makul_grup[] = $value['id_mkkur'].'-'.$period_waktu;
							}else{
									$id_timespace = $this->get_random_local($individu_classprodi, $individu_temp, $value, $timespace, $makul_grup, $waktudistinct_grup, $period_waktu, $individu[$value['id_individu']]['id_timespace']);
							}
					}

					/*
					menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
					*/
					if ($id_timespace == 'nochance') {
							unset($individu_temp);
							unset($grup_kemipaan);
							unset($waktudistinct_grup_kemipaan);
							unset($makul_grup);
							unset($waktudistinct_grup);
							unset($timespace);

							$individu_temp = $this->create_individu();
							break;
					}else{
							/*
							menyimpan hasil ruang & waktu untuk kelas, beserta periodenya
							*/
							$waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $period_waktu);
							$individu_temp[] = array(
									'id_kromosom' => $value['id_individu'],
									'nama_kelas' => $value['nama_kelas'],
									'id_timespace' => $timespace[$id_timespace]['id_timespace'],
									'id_waktu' => $timespace[$id_timespace]['id_waktu'],
									'period' => $value['period'],
									'waktu_hari' => $timespace[$id_timespace]['waktu_hari'],
									'id_ruang' => $timespace[$id_timespace]['id_ruang'],
									'waktu_jam_mulai' => $timespace[$id_timespace]['waktu_jam_mulai'],
									'label_timespace' => $timespace[$id_timespace]['label'].$waktu_jam_selesai_kls,
									'kap_ruang' => $timespace[$id_timespace]['kap_ruang'],
									'waktu_jam_selesai_kls' => $waktu_jam_selesai_kls
							);

							for ($t=0; $t < $period_waktu; $t++) {
									$id_timespace += $t;
									$timespace[$id_timespace]['status'] = 1;
							}
					}

			}

			return $individu_temp;
	}

	public function repairing_individu($individu){

			$individu = $this->repair_kelas_on_hardrule($individu);
			return $individu;
	}

	public function build_offspring_population_crossover_twopoint($parent_1, $parent_2, $point_random){

			$jumlah_gen = count($parent_1['arr_gen']);
			$i = 0;

			$arr_gen_1 = $parent_1['arr_gen'];
			$arr_gen_2 = $parent_2['arr_gen'];
			while ($jumlah_gen>0) {
					if (!in_array($i, $point_random) ) {
							$off_1[] = $arr_gen_1[$i];
							$off_2[] = $arr_gen_2[$i];

							$i++;
							$jumlah_gen--;
					}else{
							$point_random = array_diff($point_random, array($i));
							$point_random = array_values($point_random);

							$temp_1 = $arr_gen_1;
							$temp_2 = $arr_gen_2;
							$arr_gen_1 = $temp_2;
							$arr_gen_2 = $temp_1;
					}
			}

			$offspring[] = array(
					'parent' => $parent_1,
					'offspring' => $off_1
			);

			$offspring[] = array(
					'parent' => $parent_2,
					'offspring' => $off_2
			);

			foreach ($offspring as $key => $value) {
					$offspring[$key]['fitness_rule_1'] = $this->count_fitness_based_rule_kelasmakul_pilihan_wajib_not_sametime($value['offspring']);
					$offspring[$key]['fitness_rule_2'] = $this->count_fitness_based_rule_kelasmakul_on_ruangblokprodi($value['offspring']);
					$offspring[$key]['fitness_rule_3'] = $this->count_fitness_based_rule_kelasmakulsepaket_max_8_sks_sehari($value['offspring']);
					$offspring[$key]['fitness_rule_4'] = $this->count_fitness_based_rule_kelas_filled_min_prosen_capacity($value['offspring']);
					$offspring[$key]['fitness_rule_5'] = $this->count_fitness_based_rule_kelas_dosen_choose_their_time($value['offspring']);

					$offspring[$key]['fitness'] = 1-(($offspring[$key]['fitness_rule_1'] + $offspring[$key]['fitness_rule_2'] + $offspring[$key]['fitness_rule_3'] + $offspring[$key]['fitness_rule_4'] + $offspring[$key]['fitness_rule_5'] ) / 5);
					$offspring[$key]['randvalmut'] = mt_rand(0,1);
			}

			$this->individu_breed[] = $offspring;
	}

	public function crossover(){
			$populasi_breeding_crossover_selected = array();
			foreach ($this->populasi_breeding_selected as $key => $value) {
					if ($value['val_random'] <= $this->pc) {
							$populasi_breeding_crossover_selected[] = $value;
					}
			}

			if (empty($populasi_breeding_crossover_selected)) {
				echo "tidak ada nilai lebih kecil dari Pc"; exit();
			}

			$n_gen = count($populasi_breeding_crossover_selected[0]['arr_gen']);
			$n_ind = count($populasi_breeding_crossover_selected);

			$point_random = array(mt_rand(2,$n_gen-1), mt_rand(2,$n_gen-1) );
			for ($i=0; $i < $n_ind-1 ; $i++) {
					$this->build_offspring_population_crossover_twopoint($populasi_breeding_crossover_selected[$i], $populasi_breeding_crossover_selected[$i+1], $point_random);

			}
			$this->build_offspring_population_crossover_twopoint($populasi_breeding_crossover_selected[($n_ind-1)], $populasi_breeding_crossover_selected[0], $point_random);

	}

	public function mutasi_kromosom($individu){
			$timespace = $this->timespace;

			$pos_mutasi = mt_rand(0,count($individu)-1);
			$id_timespace = mt_rand(0,(count($timespace)-1));

			$gen = $individu[$pos_mutasi];


			$waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($timespace[$id_timespace]['waktu_jam_mulai'], $this->kromosom[$gen['id_kromosom']]['period']);

		$individu[$pos_mutasi] = array(
					'id_kromosom' => $gen['id_kromosom'],
					'id_timespace' => $timespace[$id_timespace]['id_timespace']
			);

			$individu = $this->repairing_individu($individu);
			return $individu;
	}

	public function cek_kelas_on_hardrule($individu){
			$individu_temp = array();
			$timespace = $this->timespace;
			$makul_grup = array();
			$waktudistinct_grup = array();
			$individu_classprodi = $this->break_individu_prodi($individu);
			foreach ($individu as $key => $value) {
					$id_timespace = $value['id_timespace'];

					if ( !in_array($this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'],$makul_grup) ) {
							$break_rule = true;

							while ($break_rule) {
									$rule_ok = $this->check_on_hardrule($individu_classprodi, $individu_temp, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace, $this->kromosom[$value['id_kromosom']]['period']);
									if ($rule_ok) {
											$break_rule = false;
									}else{
											$sts = true;

											$stsrule_1 = $this->CI->aturan_jadwal->check_time_notover_limit($id_timespace, $timespace, $this->kromosom[$value['id_kromosom']]['period']);
											$stsrule_2 = $this->CI->aturan_jadwal->check_timespace_class_samepacket_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace, $this->prodi);
											$stsrule_3 = $this->CI->aturan_jadwal->check_capacity_class_ok($id_timespace, $timespace, $this->kromosom[$value['id_kromosom']]);
											$stsrule_4 = $this->CI->aturan_jadwal->check_lecture_class_not_sametime($this->kromosom, $this->timespace, $individu_temp, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace);
											$stsrule_5 = $this->CI->aturan_jadwal->check_separatesameclass_not_sameday($this->kromosom, $this->timespace, $individu_temp, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace);
											$stsrule_6 = $this->CI->aturan_jadwal->check_neighborpacketclass_not_sametime($this->kromosom, $this->timespace, $individu_classprodi, $this->kromosom[$value['id_kromosom']], $id_timespace, $timespace, $this->prodi);

											$sts = $stsrule_1 && $stsrule_2 && $stsrule_3 && $stsrule_4 && $stsrule_5 && $stsrule_6 ;
									}
							}

					}else{
							// break;
						echo 'par<pre>'; print_r($individu_temp); echo "</pre>";
						echo '<pre>'; print_r($value); echo "</pre>";

							exit();
							$id_timespace = $this->get_random_local($individu_classprodi, $individu_temp, $this->kromosom[$value['id_kromosom']], $timespace, $makul_grup, $waktudistinct_grup, $this->kromosom[$value['id_kromosom']]['period']);


					}

					$waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($this->timespace[$value['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$value['id_kromosom']]['period']);
					$individu_temp[] = array(
							'id_kromosom' => $value['id_kromosom'],
							// 'nama_kelas' => $value['nama_kelas'],
							'id_timespace' => $timespace[$id_timespace]['id_timespace'],
							// 'period' => $value['period'],
							'id_waktu' => $timespace[$id_timespace]['id_waktu'],
							'id_ruang' => $timespace[$id_timespace]['id_ruang'],
					);

					// membuat grup kelas per matakuliah secara dinamis
					if (!in_array($this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'], $makul_grup)) {
							$makul_grup[] = $this->kromosom[$value['id_kromosom']]['id_mkkur'].'-'.$this->kromosom[$value['id_kromosom']]['period'];
							$waktudistinct_grup[] = $timespace[$id_timespace]['id_waktu'];
					}

					// menghapus index beserta nilainya untuk data ruang & waktu yg dipakai kelas untuk jadwal
					for ($t=0; $t < $this->kromosom[$value['id_kromosom']]['period']; $t++) {
							$id_timespace += $t;
							$timespace[$id_timespace]['status'] = 1;
							// unset($timespace[$id_timespace]);
					}

			}
	}

	public function mutation(){
			foreach ($this->individu_breed as $key => $value) {
					foreach ($value as $i => $item) {
							// echo '<pre>'; print_r($item['randvalmut']);
							if ($item['randvalmut'] < $this->pm) {
									$this->individu_breed[$key][$i]['offspring'] = $this->mutasi_kromosom($item['offspring']);
							}

							$this->individu_update_calon[] = $this->individu_breed[$key][$i];
					}
			}
			$this->individu_breed = null;
	}

	public function count_total_fitness_populasi_breeding(){
			$total = 0;
			foreach ($this->populasi_breeding as $key => $value) {
					$total += $value['fitness'];
			}

			return $total;

	}

	public function update_selection(){
			$populasi_breeding = $this->populasi_breeding;
			$this->populasi_breeding = array();
			foreach ($populasi_breeding as $key => $value) {
					$this->populasi_breeding[] = array(
							'fitness' => $value['fitness'],
							'arr_gen' => $value['arr_gen']
					);
			}

			foreach ($this->individu_update_calon as $key => $value) {
					$this->populasi_breeding[] = array(
							'fitness' => $value['fitness'],
							'arr_gen' => $value['offspring']
					);
			}

			$this->total_fitness = $this->count_total_fitness_populasi_breeding();

			$this->roulette_wheel_selection();

			foreach ($this->populasi_breeding_selected as $key => $value) {
					$this->populasi_baru[] = $value['arr_gen'];
			}

			$this->individu_update_calon = null;
	}

	public function update_population(){
			$this->populasi = array();
			foreach ($this->populasi_baru as $key => $value) {
					// $this->populasi[] = $this->repairing_individu($value);
					$this->populasi[] = $value;
			}
			$this->populasi_baru = null;

	}

	public function get_solution(){
			$max_fitness = 0;
			$idx = null;
			foreach ($this->populasi_breeding_selected as $key => $value) {
					if ($value['fitness'] > $max_fitness) {
							$max_fitness = $value['fitness'];
							$idx = $key;
							$this->log_proses['max_fitness'] = $max_fitness;
					}
			}

			foreach ($this->populasi_breeding_selected[$idx]['arr_gen'] as $key => $value) {
				$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_kelas'] = $this->kromosom[$value['id_kromosom']]['id_kelas'];
				$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_waktu'] = $this->timespace[$value['id_timespace']]['id_waktu'];
				$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['id_ruang'] = $this->timespace[$value['id_timespace']]['id_ruang'];
				$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['period'] = $this->kromosom[$value['id_kromosom']]['period'];

				$waktu_jam_selesai_kls = $this->get_jam_selesai_kelas($this->timespace[$value['id_timespace']]['waktu_jam_mulai'], $this->kromosom[$value['id_kromosom']]['period'] );
				$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['jam_selesai'] = $waktu_jam_selesai_kls;
				$this->populasi_breeding_selected[$idx]['arr_gen'][$key]['label_timespace'] = $this->timespace[$value['id_timespace']]['label'].$waktu_jam_selesai_kls;
			}

			// exit();
			$sts = $this->set_log_proses();

			return $this->populasi_breeding_selected[$idx];
	}

	function set_log_proses(){
			$data = base64_encode(serialize($this->log_proses));
			$sts = $this->CI->bantu->simpan_log_proses('algen_penjadwalan', $data);
			return $sts;
	}

		/**
     * to calculate volacity
     * @param integer $index particle index
     * @param integerr $dimention particle dimension
     * @return mixed result of calculation
     */
    private function calculateVelocity($index, $dimention) {
				//The euquation as shown in ORIGINAL paper
				return self::INERTIAL_WEIGHT * $this->swarm[$index]["VELOCITY"][$dimention] +
								(self::C1 * rand(0, 1) * ($this->swarm[$index]["P_BEST"]["POSITION"][$dimention] - $this->swarm[$index]["POSITION"][$dimention])) +
								(self::C2 * rand(0, 1) * ($this->gBest["POSITION"][$dimention] - $this->swarm[$index]["POSITION"][$dimention]));
		}

		/**
     * to calculate position
     * @param integer $index particle index
     * @param integerr $dimention particle dimension
     * @return mixed result of calculation
     */
    private function calculatePbest($index, $dimention) {
				//The euquation as shown in ORIGINAL paper
				return $this->swarm[$index]["POSITION"][$dimention] + $this->swarm[$index]["VELOCITY"][$dimention];
		}

		/**
		 * to produce a random value between problem's MAX and MIN value
		 * @return integer a value between problem's MAX and MIN value
		 */
		private function generateRandomPosition() {
				return rand(Problem::MIN_VALUE, Problem::MAX_VALUE);
		}

		// To produce a random value for velocity
    private function generateRandomVelocity() {
			return rand(-(Problem::MAX_VALUE / 3), (Problem::MAX_VALUE / 3));
		}


	// Evaluasi Fitnes berdasarkan posisi
	//
	//
	// Update nilai partikel dan kecepatan (velocity)
	//
	//
	// Update posisi
	//
	//
	// Evaluasi Fitnes setiap partikel

	/**
     * ALGORITHM MAIN METHOD
     * ALGORITHM EXECUTION
     */
    public function run() {
			$generation = 0;
			print "STARTED \n";
			while ($generation != Problem::MAX_ITERATION) {
					print "GENERATION[$generation]\t-----> BEST FITNESS = #{$this->bestFitness}# \t -- X={$this->gBest["POSITION"][1]}\tY={$this->gBest["POSITION"][2]} \n";
					//to make output more beautiful -- some sleep :)
					usleep(5000);
					// main loop through particles
					for ($i = 1; $i <= count($this->swarm); $i++) {

									//position calculation
									$this->swarm[$i]["POSITION"][$j] = $this->calculatePbest($i, $j) > Problem::MAX_VALUE ? Problem::MAX_VALUE : ($this->calculatePbest($i, $j) < Problem::MIN_VALUE ? Problem::MIN_VALUE : ($this->calculatePbest($i, $j)));
									//velocity calculation
									$this->swarm[$i]["VELOCITY"][$j] = $this->calculateVelocity($i, $j) > Problem::MAX_VALUE ? Problem::MAX_VALUE : ($this->calculateVelocity($i, $j) < Problem::MIN_VALUE ? Problem::MIN_VALUE : $this->calculateVelocity($i, $j));
							}

							//calculating fitness of current particle
							$fitness = $this->fitnessFunction($this->swarm[$i]["POSITION"][1], $this->swarm[$i]["POSITION"][2]);
							//if better fitness ...
							if ($fitness < $this->bestFitness) {
									$this->bestFitness = $fitness;
									$this->gBest = $this->swarm[$i];
							}
					}
					$generation++;
					//if found the best, exit takes place
					if ($this->gBest["POSITION"] == $this->getAnswer()) {
							print "WOW #GENERATION -{$generation}-#, FOUND THE BEST MINIMUM | X=[{$this->gBest["POSITION"][1]}] - Y=[{$this->gBest["POSITION"][2]}]";
							exit();
					}
		}

}

class PSO extends CI_Model {
	// inisialisasi partikel setiap variabel   //
	function inisialisasi_partikel()
	{

			$sqls = "SELECT id_slot, id_ruang, id_guru_mengajar FROM slot_jadwal where id_guru_mengajar is NULL order by id_slot asc";
			$t=mysql_query($sqls); while($s=mysql_fetch_row($t)){
			$id=$s['0'];
			$ir=$s['1'];
			$igp=$s['2']; echo "$id <br>";
			$sqb = "SELECT a.id_slot, b.id_plot, b.id_mapel, b.id_guru_mengajar, a.id_jam, a.id_hari, b.id_kelas, a.id_ruang from slot_jadwal aleft join plot_mapel b on a.id_ruang=b.id_ruang
			where a.id_slot='$id' and b.jumlah='0' order by rand()";
			$b=mysql_query($sqb);
			$bc=mysql_fetch_row($b);
			$slot=$bc['0'];
			$plot=$bc['1'];
			$mapel=$bc['2'];
			$guru=$bc['3'];
			$hari=$bc['5'];
			$kelas=$bc['6'];
			$ruang=$bc['7'];
			//cek apakah kelas tersebut lebih dr 4 jam/hari
			$ceksql="SELECT a.id_slot, b.id_plot, b.id_mapel, b.id_guru, a.id_jam, b.id_kelas, a.id_hari from slot_jadwal aleft join plot_mapel b on a.id_guru_mengajar=b.id_plot where a.id_hari='$hari' and b.id_kelas='$kelas' and b.jumlah_plot=1";
			$cekjumlah=mysql_num_rows(mysql_query($ceksql));
			$ceksqlr="SELECT a.id_slot, b.id_plot, b.id_mapel, b.id_guru, a.id_jam, b.id_kelas, a.id_hari from slot_jadwal aleft join plot_mapel b on a.id_guru_mengajar=b.id_plot where a.id_hari='$hari' and a.id_ruang='$ruang' and b.jumlah_plot=1";
			$cekjumlahr=mysql_num_rows(mysql_query($ceksqlr));
			if ($cekjumlah>=4){
			echo "<b>kelas lebih dari $cekjumlah </b>";
			}
			else if($cekjumlahr >=4){
			echo "<b> ruang lebih dari $cekjumlahr </b>";
			} else {
			$sqlus = "UPDATE slot_jadwal SET id_mapel='$mapel', id_guru='$guru', id_guru_mengajar='$plot' where id_slot='$slot'";
			$hasil = mysql_query($sqlus); echo "$sqlus <br>";
			$sqlj = "UPDATE tbl_plot_matakuliah SET jumlah=jumlah+1 where id='$plot'";
			$j=mysql_query($sqlj);

			return $j;
			}



			// inisialisasi kecepatan   //
			$query="UPDATE tbl_fitnes set velocity=id_slot";
			$fit = mysql_query($query);

			// Evaluasi fitness semua partikel   //

			$sqlss = "SELECT id_slot, id_hari, id_jam, id_guru_mengajar, id_guru, id_mapel, id_ruang FROM slot_jadwal order by id_slot desc";
			$ts=mysql_query($sqlss);
			while($d = mysql_fetch_array($ts)){
			$cek_slot	= $d['id_slot'];
			$cek_guru	= $d['id_guru'];
			$cek_hari	= $d['id_hari'];
			$cek_jam	= $d['id_jam'];
			$cek_mapel	= $d['id_mapel'];
			$cek_ruang	= $d['id_ruang'];
			$cek_plot	= $d['id_guru_mengajar'];
			$sqlcek="SELECT a.id_jadwal, a.id_hari, a.id_jam, a.id_plot, a.id_slot, c.id_kelas FROM jadwal_tb a LEFT JOIN slot_jadwal b ON a.id_slot = b.id_slot left join plot_mapel c on b.id_guru_mengajar=c.id_plot where a.id_slot='$cek_slot'";
			$cekjadwal=mysql_query($sqlcek);
			$cekdos='';
			$cekruang=''; while($cekjdwl=mysql_fetch_array($cekjadwal)){
			$cek_jam=$cekjadwal['id_jam'];
			$cek_hari=$cekjadwal['id_hari'];
			$cek_kelas=$cekjadwal['id_kelas'];
			//cek dosen
			$sqlgurux="SELECT a.id_jadwal,a.id_slot, a.id_hari, a.id_jam, b.id_ruang, id_guru, a.id_plot FROM jadwal_tb a LEFT JOIN slot_jadwal b ON a.id_slot = b.id_slot left join plot_mapel c on a.id_plot=c.id_plot where a.id_jam='$cek_jam' and a.id_hari='$cek_hari' and b.id_guru='$cek_guru ";
			$Testguru=mysql_num_rows(mysql_query($sqlgurux)); if($Testguru >= 2){
			$cekguru=$cekdos+$Testdos;
			}
			//cek bentrok ruang
			$sqlruang="SELECT a.id_jadwal, a.id_slot, a.id_hari, a.id_jam, b.id_ruang, id_guru, a.id_plot FROM jadwal_tb a LEFT JOIN slot_jadwal b ON a.id_slot = b.id_slot left join plot_mapel c on a.id_plot=c.id_plot where a.id_jam='$cek_jam' and a.id_hari='$cek_hari' and b.id_ruang='$cek_ruang' ";
			$Testruang=mysql_num_rows(mysql_query($sqlruang)); if($Testruang >= 2){

			$cekruang=$cekruang+$Testruang;
			}
			$hard=$cekguru+$cekruang;
			$sql = "UPDATE tbl_fitnes SET id_plot='$cek_plot', fitnes='$hard', fit_kls='' where id_slot='$cek_slot'";
			$y=mysql_query($sql);

			return $y;



			//update nilai terbaik partikel   //

			$sqll = "SELECT min(fitnes) FROM `tbl_fitnes`WHERE fitnes !=0 ";
			$l=mysql_query($sqll);
			$lb = mysql_fetch_array($l);
			$lbest=$lb['0']; echo "iki".$sqll;

			if ($lbest<=$gbest){
			$gbest=$lbest;
			}
			else if($gbest== null){
			$gbest=$lbest;
			}
			}

			// update velocity   //

			$rnd=$n[rand(0,99)];
			//cek velocity
			$sqllv = "SELECT velocity FROM tbl_fitnes WHERE id_slot='$cek_slot' ";
			$lv=mysql_query($sqllv);
			$lbv = mysql_fetch_array($lv);
			$vi=$lbv['0'];
			echo "velocity sebelume $vi - ".$sqllv;
			//persamaan 2
			$v= $w*$vi+$ci*$rnd*($lbest-$cek_slot )+$cii*$rnd*($gbest-
			$cek_slot);


			// update posisi     //

			$x=$cek_slot+$v;
			$xi=round($x, 0);
			$sqlv = "UPDATE slot_jadwal SET id_mapel =$kmap, id_guru =$kguru, id_guru_mengajar=$kplot where id_slot=$xi";
			$v = mysql_query($sqlv);

			return $v;


			// cek kriteria      //

			$sqlk = "SELECT min(fitnes) FROM tbl_fitnes a left join slot_jadwal b on a.id_slot=b.id_slot
			WHERE b.id_mapel is not null and fitnes !=0";

			$f=mysql_query($sqlk);
			$dat = mysql_fetch_array($f);
			$fet=$dat['0'];
			if($fet==null){
				break;
			}
		}
}
