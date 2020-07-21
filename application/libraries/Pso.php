<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
	