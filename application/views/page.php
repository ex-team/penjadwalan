<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title>Penjadwalan Mata Pelajaran - <?php echo $page_title;?></title>
      <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="description" content="" />
      <meta name="author" content="" />
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/lib/bootstrap/css/bootstrap.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/theme.css" />
	  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/badger.min.css" />
	  
      <link rel="stylesheet" href="<?php echo base_url();?>assets/lib/font-awesome/css/font-awesome.css" />
	  <link rel="stylesheet" href="<?php echo base_url();?>assets/lib/datepicker/css/datepicker.css" />
	  
      <script src="<?php echo base_url()?>assets/lib/jquery-latest.min.js" type="text/javascript"></script>	  
      
      <!-- Demo page code -->
      <style type="text/css">
		 body .frmModalMsg {
			/* new custom width */
			width: 740px;
			/* must be half of the width, minus scrollbar on the left (30px) */
			margin-left: -280px;
		 }
   
         #line-chart {
			height:300px;
			width:800px;
			margin: 0px auto;
			margin-top: 1em;
         }
         .brand { font-family: georgia, serif; }
         .brand .first {
         color: #ccc;
			font-style: italic;
         }
         .brand .second {
			color: #fff;
			font-weight: bold;
         }
		 
		 #loading-div-background{
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			background: #fff;
			width: 100%;
			height: 100%;
		}
		
		#loading-div{
			width: 300px;
			height: 150px;
			background-color: #fff;
			border: 5px solid #1468b3;
			text-align: center;
			color: #202020;
			position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -150px;
			margin-top: -100px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
		}
      </style>

      <script type="text/javascript">
	     
	     
	     
		
		$(document).ready(function (){
			$("#loading-div-background").css({ opacity: 0.5 });
		 <?php if(isset($clear_text_box)) { ?>    
			$('input[type=text]').each(function() {
			    $(this).val('');
			});
		 <?php } ?>
		});
	
		function ShowProgressAnimation(){
			$("#loading-div-background").show();
		}
		 
		 function change_get(){		
		 	var semester_tipe = document.getElementById('semester_tipe');
			var tahun_akademik = document.getElementById('tahun_akademik');
		 	window.location.href = "<?php echo base_url().'web/pengampu/' ?>" + semester_tipe.options[semester_tipe.selectedIndex].value  + "/"	  + tahun_akademik.options[tahun_akademik.selectedIndex].value;		
		 }
		 
		 function change_guru_tidak_bersedia() {
			
			var kode_guru = document.getElementById('kode_guru');			
			window.location.href = "<?php echo base_url().'web/waktu_tidak_bersedia/' ?>" + kode_guru.options[kode_guru.selectedIndex].value;		
		 }
		 
		function get_matapelajaran() {		   
			var semester_tipe = document.getElementById('semester_tipe');
			//
			$.ajax({
			   type:"POST",
			   async   : false,
			   cache   : false,
			   url: "<?php echo base_url()?>web/option_matapelajaran_ajax/" + semester_tipe.options[semester_tipe.selectedIndex].value,
			   success: function(msg){
				  //alert(msg);
				  $('#option_matapelajaran').html(msg);
			   }
			});
			return false;		 
		}
		
		/*
	  $('#myTable tr').click({
		 $(this).remove();
		   return false;
	   };
		
		*/
		function delete_row(link,kode) {
		 	var answer =  confirm('Anda yakin ingin menghapus data ini?');
			if(answer){
			   $.ajax({
				  type:"POST",
				  async   : false,
				  cache   : false,
				  url: "<?php echo base_url()?>" + link + kode,
				  success: function(msg){
					 //alert(msg);
					 //$('#option_matapelajaran').html(msg);
					 var tr  = $('#row_' + kode);
					 tr.css("background-color","#FF3700");
					 tr.fadeOut(400, function(){
					   tr.remove();
					 });				  
				  }
			   });
			   
			}
			return false;
		}
		
        $(function() {
                applyPagination();
             
			    function applyPagination() {
                 $("#ajax_paging a").click(function() {             
             
                   var url = $(this).attr("href");
                   $.ajax({
                     type: "POST",
                     data: "ajax=1",
                     url: url, 
                     success: function(msg) {
                       $('#content_ajax').fadeOut(0,function(){
                           $('#content_ajax').html(msg);
						   $("#content_ajax").removeAttr("style");
                           applyPagination();                 
                       }).fadeIn(0);                       
                     }
                   });              
                   return false;
                 });
               }
         
         
             });
          
      </script>
	  

      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

      
   </head>
  

               <body class="">
                  <!--<![endif]-->
                  <div class="navbar">
                     <div class="navbar-inner">
                        <a class="brand" href="#"><span class="first">Penjadwalan</span> <span class="second">Mata Pelajaran</span></a>
                     </div>
                  </div>
                  <div class="sidebar-nav" style="padding-top: 5px">
                     <!--<form class="search form-inline" >
                        <input type="text" placeholder="Search..." />
                     </form>-->
					 
                     <a href="<?php echo base_url();?>" class="nav-header"><i class="icon-th-list"></i>Beranda</a>
                     <a class="nav-header" ><i class="icon-book"></i>Data </a>
                     <ul id="content-menu" class="nav nav-list collapse in">                        
						<li><a href="<?php echo base_url()?>web/guru"><i class="icon-user"></i>Guru Pelajaran</a></li>   
						<li><a href="<?php echo base_url()?>web/matapelajaran"><i class="icon-book"></i>Mata Pelajaran</a></li>
						<li><a href="<?php echo base_url();?>web/pengampu"><i class="icon-th-list"></i>Pengampu</a></li>
						<li><a href="<?php echo base_url()?>web/ruang"><i class="icon-home"></i>Ruang Kelas</a></li>
						<li><a href="<?php echo base_url()?>web/jam"><i class="icon-time"></i>Jam Pelajaran</a></li>
						<li><a href="<?php echo base_url()?>web/hari"><i class="icon-calendar"></i>Hari Sekolah</a></li>
						<li><a href="<?php echo base_url()?>web/waktu_tidak_bersedia"><i class="icon-ban-circle"></i>Waktu Tidak Bersedia</a></li>
                     </ul>

					 <a class="nav-header" ><i class="icon-book"></i>Proses </a>
                     <ul id="content-menu" class="nav nav-list collapse in">                        
						<li><a href="<?php echo base_url();?>web/penjadwalan"><i class="icon-th-list"></i>Penjadwalan</a></li>
					 </ul>	
                  </div>
                  

                 <?php 
                    $page_name .= ".php";
                    include $page_name;
                  ?>

      
				  <script type="text/javascript" src="<?php echo base_url();?>assets/lib/jquery.slugit.js"></script>  
				  <script type="text/javascript" src="<?php echo base_url();?>assets/lib/datepicker/js/bootstrap-datepicker.js"></script>
				  <script type="text/javascript" src="<?php echo base_url();?>assets/lib/bootstrap/js/bootstrap.js"></script>
				  <script type="text/javascript" src="<?php echo base_url();?>assets/lib/bootstrap/js/bootstrap-filestyle.min.js"> </script>
	  
                  <script type="text/javascript">
                     $("[rel=tooltip]").tooltip();
                     $(function() {
                         $('.demo-cancel-click').click(function(){return false;});
                     });
                  </script>
   </body>
</html>