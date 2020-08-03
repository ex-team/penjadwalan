<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pso extends CI_Controller
{
     
    private $PRAKTIKUM = 'PRAKTIKUM';
    private $TEORI = 'TEORI';
    private $LABORATORIUM = 'LABORATORIUM';
    
    private $jenis_semester;
    private $tahun_akademik;
    private $populasi;
    private $c1;
    private $c2;
    private $w;
    
    private $pengampu = array();
    private $individu = array(array(array()));
    private $sks = array();
    private $dosen = array();
    
    private $jam = array();
    private $hari = array();
    private $idosen = array();
    
    //waktu keinginan dosen
    private $waktu_dosen = array(array());
    private $jenis_mk = array(); //reguler or praktikum
    
    private $ruangLaboratorium = array();
    private $ruangReguler = array();
    private $logAmbilData;
    private $logInisialisasi;
    
    private $log;
    private $induk = array();
    
    //jumat
    private $kode_jumat;
    private $range_jumat = array();
    private $kode_dhuhur;
    private $is_waktu_dosen_tidak_bersedia_empty;
    
    
    
    function __construct($jenis_semester, $tahun_akademik, $populasi, $c1, $c2, $w, $kode_jumat, $range_jumat, $kode_dhuhur)
    {        
        parent::__construct();        
        $this->jenis_semester = $jenis_semester;
        $this->tahun_akademik = $tahun_akademik;
        $this->populasi       = intval($populasi);
        $this->c1             = $c1;
        $this->c2             = $c2;
        $this->w              = $w;
        $this->kode_jumat     = intval($kode_jumat);
        $this->range_jumat    = explode('-',$range_jumat);
        $this->kode_dhuhur    = intval($kode_dhuhur);
    }
    
    public function AmbilData()
    {
        $rs_data = $this->db->query("SELECT   a.kode,"
                                    . "b.sks,"
                                    . "a.kode_dosen,"
                                    . "b.jenis "
                                    . "FROM pengampu a "
                                    . "LEFT JOIN matakuliah b "
                                    . "ON a.kode_mk = b.kode "
                                    . "WHERE b.semester%2 = $this->jenis_semester "
                                    . "      AND a.tahun_akademik = '$this->tahun_akademik'");
        
        $i = 0;
        foreach ($rs_data->result() as $data) {
            $this->pengampu[$i] = intval($data->kode);
            $this->sks[$i]         = intval($data->sks);
            $this->dosen[$i]       = intval($data->kode_dosen);
            $this->jenis_mk[$i]    = $data->jenis;
            $i++;
        }
        
        //var_dump($this->jenis_mk);
        //exit();
        
        //Fill Array of Jam Variables
        $rs_jam = $this->db->query("SELECT kode FROM jam");
        $i      = 0;
        foreach ($rs_jam->result() as $data) {
            $this->jam[$i] = intval($data->kode);
            $i++;
        }
        
        //Fill Array of Hari Variables
        $rs_hari = $this->db->query("SELECT kode FROM hari");
        $i       = 0;
        foreach ($rs_hari->result() as $data) {
            $this->hari[$i] = intval($data->kode);
            $i++;
        }
        
        
        $rs_RuangReguler = $this->db->query("SELECT kode "
                                            ."FROM ruang "
                                            ."WHERE jenis = '$this->TEORI'");
        $i               = 0;
        foreach ($rs_RuangReguler->result() as $data) {
            $this->ruangReguler[$i] = intval($data->kode);
            $i++;
        }
        
        
        $rs_Ruanglaboratorium = $this->db->query("SELECT kode "
                                                 ."FROM ruang "
                                                 ."WHERE jenis = '$this->LABORATORIUM'");
        $i                    = 0;
        foreach ($rs_Ruanglaboratorium->result() as $data) {
            $this->ruangLaboratorium[$i] = intval($data->kode);
            $i++;
        }
        
        $rs_WaktuDosen = $this->db->query("SELECT kode_dosen,".
                                          "CONCAT_WS(':',kode_hari,kode_jam) as kode_hari_jam ".
                                          "FROM waktu_tidak_bersedia");        
        $i             = 0;
        foreach ($rs_WaktuDosen->result() as $data) {
            $this->idosen[$i]         = intval($data->kode_dosen);
            $this->waktu_dosen[$i][0] = intval($data->kode_dosen);
            $this->waktu_dosen[$i][1] = $data->kode_hari_jam;
            $i++;
        }  
     
        
    }
    
    
    public function Inisialisai()
    {
        
        $jumlah_pengampu = count($this->pengampu);        
        $jumlah_jam = count($this->jam);
        $jumlah_hari = count($this->hari);
        $jumlah_ruang_reguler = count($this->ruangReguler);
        $jumlah_ruang_lab = count($this->ruangLaboratorium);
        
        for ($i = 0; $i < $this->populasi; $i++) {
            for ($j = 0; $j < $jumlah_pengampu; $j++) {
                $sks = $this->sks[$j];
                $this->individu[$i][$j][0] = $j;

                // Penentuan jam secara acak ketika 1 sks 
                if ($sks == 1) {
                    $this->individu[$i][$j][1] = mt_rand(0,  $jumlah_jam - 1);
                }
                
                // Penentuan jam secara acak ketika 2 sks 
                if ($sks == 2) {
                    $this->individu[$i][$j][1] = mt_rand(0, ($jumlah_jam - 1) - 1);
                }
                
                // Penentuan jam secara acak ketika 3 sks
                if ($sks == 3) {
                    $this->individu[$i][$j][1] = mt_rand(0, ($jumlah_jam - 1) - 2);
                }
                
                // Penentuan jam secara acak ketika 4 sks
                if ($sks == 4) {
                    $this->individu[$i][$j][1] = mt_rand(0, ($jumlah_jam - 1) - 3);
                }

                // Penentuan hari secara acak 
                $this->individu[$i][$j][2] = mt_rand(0, $jumlah_hari - 1);
                
                if ($this->jenis_mk[$j] === $this->TEORI) {
                    $this->individu[$i][$j][3] = intval($this->ruangReguler[mt_rand(0, $jumlah_ruang_reguler - 1)]);
                } else {
                    $this->individu[$i][$j][3] = intval($this->ruangLaboratorium[mt_rand(0, $jumlah_ruang_lab - 1)]);                    
                }
            }
        }
    }
    
    private function CekFitness($indv)
    {
        $penalty = 0;
        $hari_jumat = intval($this->kode_jumat);
        $jumat_0 = intval($this->range_jumat[0]);
        $jumat_1 = intval($this->range_jumat[1]);
        $jumat_2 = intval($this->range_jumat[2]);
        $jumlah_pengampu = count($this->pengampu);
        
        for ($i = 0; $i < $jumlah_pengampu; $i++)
        {
          $sks = intval($this->sks[$i]);
          $jam_a = intval($this->individu[$indv][$i][1]);
          $hari_a = intval($this->individu[$indv][$i][2]);
          $ruang_a = intval($this->individu[$indv][$i][3]);
          $dosen_a = intval($this->dosen[$i]);        

            for ($j = 0; $j < $jumlah_pengampu; $j++) {                 
                $jam_b = intval($this->individu[$indv][$j][1]);
                $hari_b = intval($this->individu[$indv][$j][2]);
                $ruang_b = intval($this->individu[$indv][$j][3]);
                $dosen_b = intval($this->dosen[$j]);
                //1.bentrok ruang dan waktu dan 3.bentrok dosen
                //ketika pemasaran matakuliah sama, maka langsung ke perulangan berikutnya
                if ($i == $j)
                    continue;
                //#region Bentrok Ruang dan Waktu
                //Ketika jam,hari dan ruangnya sama, maka penalty + satu
                if ($jam_a == $jam_b &&
                    $hari_a == $hari_b &&
                    $ruang_a == $ruang_b)
                {
                    $penalty += 1;
                }
                //Ketika sks  = 2, hari dan ruang sama, dan 
                //jam kedua sama dengan jam pertama matakuliah yang lain, maka penalty + 1
                if ($sks >= 2)
                {
                    if ($jam_a + 1 == $jam_b &&
                        $hari_a == $hari_b &&
                        $ruang_a == $ruang_b)
                    {
                        $penalty += 1;
                    }
                }
                
                //Ketika sks  = 3, hari dan ruang sama dan 
                //jam ketiga sama dengan jam pertama matakuliah yang lain, maka penalty + 1
                if ($sks >= 3) {
                    if ($jam_a + 2 == $jam_b &&
                        $hari_a == $hari_b &&
                        $ruang_a == $ruang_b)
                    {
                        $penalty += 1;
                    }
                }
                
                //Ketika sks  = 4, hari dan ruang sama dan 
                //jam ketiga sama dengan jam pertama matakuliah yang lain, maka penalty + 1
                if ($sks >= 4) {
                    if ($jam_a + 3 == $jam_b &&
                        $hari_a == $hari_b &&
                        $ruang_a == $ruang_b)
                    {
                        $penalty += 1;
                    }
                }
                
                //BENTROK DOSEN
                if (
                //ketika jam sama
                    $jam_a == $jam_b && 
                //dan hari sama
                    $hari_a == $hari_b && 
                //dan dosennya sama
                    $dosen_a == $dosen_b)
                {
                  //maka...
                  $penalty += 1;
                }
                
                
                
                if ($sks >= 2) {
                    if (
                    //ketika jam sama
                      ($jam_a + 1) == $jam_b && 
                    //dan hari sama
                      $hari_a == $hari_b && 
                    //dan dosennya sama
                      $dosen_a == $dosen_b)
                    {
                      //maka...
                      $penalty += 1;
                    }
                }
                
                if ($sks >= 3) {
                    if (
                    //ketika jam sama
                      ($jam_a + 2) == $jam_b && 
                    //dan hari sama
                      $hari_a == $hari_b && 
                    //dan dosennya sama
                      $dosen_a == $dosen_b)
                    {
                      //maka...
                      $penalty += 1;
                    }
                }
                
                if ($sks >= 4) {
                    if (
                    //ketika jam sama
                      ($jam_a + 3) == $jam_b && 
                    //dan hari sama
                      $hari_a == $hari_b && 
                    //dan dosennya sama
                      $dosen_a == $dosen_b)
                    {
                      //maka...
                      $penalty += 1;
                    }
                }                
            }
            
            //
            // #region Bentrok sholat Jumat
            if (($hari_a  + 1) == $hari_jumat) //2.bentrok sholat jumat
            {
                if ($sks == 1)
                {
                   if (  
                        ($jam_a == ($jumat_0 - 1)) ||
                        ($jam_a == ($jumat_1 - 1)) ||
                        ($jam_a == ($jumat_2 - 1))
                       
                       )
                   {
                       $penalty += 1;
                   }
                }
                
                
                if ($sks == 2)
                {
                    if (
                          ($jam_a == ($jumat_0 - 2)) ||
                          ($jam_a == ($jumat_0 - 1)) ||
                          ($jam_a == ($jumat_1 - 1)) ||
                          ($jam_a == ($jumat_2 - 1))
                        )
                    {
                        /*
                        echo '$sks = ' . $sks. '<br>';
                        echo '$jam_a = ' . $jam_a. '<br>';
                        echo '($jumat_0 - 2) = ' . ($jumat_0 - 2) . '<br>';
                        echo '($jumat_0 - 1) = ' . ($jumat_0 - 1). '<br>';
                        echo '($jumat_1 - 1) = ' . ($jumat_1 - 1). '<br>';
                        echo '($jumat_2 - 1) = ' . ($jumat_2- 1). '<br>';
                        exit();
                        */
                        $penalty += 1;                        
                    }
                }
                
                if ($sks == 3)
                {
                    if (
                          ($jam_a == ($jumat_0 - 3)) ||
                          ($jam_a == ($jumat_0 - 2)) ||
                          ($jam_a == ($jumat_0 - 1)) ||
                          ($jam_a == ($jumat_1 - 1)) ||
                          ($jam_a == ($jumat_2 - 1))
                        )
                    {                        
                        $penalty += 1;                        
                    }
                }
                
                if ($sks == 4)
                {
                    if (
                          ($jam_a == ($jumat_0 - 4)) ||
                          ($jam_a == ($jumat_0 - 3)) ||
                          ($jam_a == ($jumat_0 - 2)) ||
                          ($jam_a == ($jumat_0 - 1)) ||
                          ($jam_a == ($jumat_1 - 1)) ||
                          ($jam_a == ($jumat_2 - 1))
                        )
                    {                        
                        $penalty += 1;                        
                    }
                }
            }
            //#endregion
            //#region Bentrok dengan Waktu Keinginan Dosen
            //Boolean penaltyForKeinginanDosen = false;
            
            $jumlah_waktu_tidak_bersedia = count($this->idosen);
            
            for ($j = 0; $j < $jumlah_waktu_tidak_bersedia; $j++)
            {
                if ($dosen_a == $this->idosen[$j])
                {
                    $hari_jam = explode(':', $this->waktu_dosen[$j][1]);
                    
                    if ($this->jam[$jam_a] == $hari_jam[1] &&
                        $this->hari[$hari_a] == $hari_jam[0])
                    {                    
                        $penalty += 1;                        
                    }
                }                            
            }
                       
            //#endregion
            //#region Bentrok waktu dhuhur
            /*
            if ($jam_a == ($this->kode_dhuhur - 1))
            {                
                $penalty += 1;
            }
            */
            
        }      
        
        $fitness = floatval(1 / (1 + $penalty));  
        
        return $fitness;        
    }
    
    public function HitungFitness()
    {
        //hard constraint
        //1.bentrok waktu
        //2.bentrok sholat jumat
        //3.bentrok guru
        //4.bentrok keinginan waktu dosen 
        //5.bentrok waktu dhuhur 
        //=>6.praktikum harus pada ruang lab {telah ditetapkan dari awal perandoman
        //    bahwa jika praktikum harus ada pada LAB dan mata kuliah reguler harus 
        //    pada kelas reguler
        
        //soft constraint //TODO
        //$fitness = array();
        
        for ($indv = 0; $indv < $this->populasi; $indv++)
        {            
            $fitness[$indv] = $this->CekFitness($indv);            
        }
        
        return $fitness;
    }
    
    #endregion
    
    #region Seleksi
    public function Seleksi($fitness)
    {
        $jumlah_pengampu = count($this->pengampu);
        $jumlah_hari = count($this->hari);
        $jumlah_jam = count($this->jam);
        $xa = array();
        $xb = array();

        for ($i=0; $i < $this->populasi ; $i++) { 
            //mencari posisi untuk pengampu pada hari dan jam
            for ($j=0; $j < $this->$jumlah_pengampu ; $j++) { 
                // posisi dllm hari
                $xa = min($jumlah_hari) + rand(0.1)*max($jumlah_hari)- min($jumlah_hari);
                // posisi dlm jam
                $xb =  min($jumlah_jam) + rand(0.1)*max($jumlah_jam)- min($jumlah_jam);

                    $x[$j]= ($xa.$xb);
            }
        }
            //menentukan pbest dan gbest
            //untuk pbest pertama akan disamakan dengan nilai posisi awal yg udah dicari secara random ($x)
            $pbest = array();
            for ($i=0; $i < $this->populasi ; $i++) { 
                $pbest[$i] = $x[$j];
                for ($j=0; $j < $this-> $jumlah_pengampu ; $j++) { 
                    // jika posisi awal lebih kecil drpd pbest maka pbest update dengan poisi awal.
                    if ($x[$j] <= $pbest[$i]) {
                        $pbest[$i] =  $x[$j];
                    }
                }
            }

            //untuk gbest pertama akan disamakan dengan nilai fitness terbaik yg udah dicari sebelumnya

            $gbest = array();
            $fitness = array();
            for ($i=0; $i < $this->populasi ; $i++) { 
                $gbest[$i] = $fitness[$i];
                for ($j=0; $j < $this-> $jumlah_pengampu ; $j++) { 
                    // jika posisi awal lebih kecil drpd gbest maka gbest update dengan poisi awal.
                    if ($x[$j] <= $gbest[$i]) {
                        $gbest[$i] =  $x[$j];
                    }
                }
            }
        }
                

   
   Public function StartCrossOver()
    {
            $w = 0.5;
            $c1 = 2;
            $c2 = 2;  
            $xa = array();
            $xb = array();
            $jumlah_pengampu = count($this->pengampu);
            $gbest = array();
            $pbest = array();
        for ($i = 0; $i < $this->populasi; $i += 2) 
        {
            // update velocity setelah menentukan pbest  dan gbest 
            for ($j=0; $j < $this-> $jumlah_pengampu ; $j++) {
                //mencari velocity pada hari
               $va= $w*$xa+$c1*rand($pbest-$xa)+$c2*rand($gbest-$xa);
               //mencari velocity pada hari
               $vb= $w*$xb+$c1*rand($pbest-$xb)+$c2*rand($gbest-$xb);
            
                
          }
        }
    }

    public function Mutasi()
    {
        $va = array();
        $vb = array();
        $xa = array();
        $xb = array();
        for ($i = 0; $i < $this->populasi; $i++){
            //mecari posisi dlm hari
        $x1[$i]=$xa+$va;
            // mencari posisi dlm jam
        $x2[$i]=$xa+$va;
            //hasil pencarian terakhir  
            $x[$i] = ($x1[$i].$x2[$i]);
            $x = count($x[$i]);

            return $x;
            }
            $fitness[$i] = $this->CekFitness($i);
        
        return $fitness;
    }
    
    
    public function GetIndividu($indv)
    {
        //return individu;
        
        //int[,] individu_solusi = new int[mata_kuliah.Length, 4];
        $individu_solusi = array(array());
        
        for ($j = 0; $j < count($this->pengampu); $j++)
        {
            $individu_solusi[$j][0] = intval($this->pengampu[$this->individu[$indv][$j][0]]);
            $individu_solusi[$j][1] = intval($this->jam[$this->individu[$indv][$j][1]]);
            $individu_solusi[$j][2] = intval($this->hari[$this->individu[$indv][$j][2]]);                        
            $individu_solusi[$j][3] = intval($this->individu[$indv][$j][3]);            
        }
        
        return $individu_solusi;
    }
    
    
}