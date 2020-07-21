<div id="content_modal">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
	<h3>Data Program Studi</h3>
</div>

<form id="myform" class="myform" method="post" name="myform" action="<?=$url_submit?>" >
<div class="modal-body">

<div class="row-fluid">


<table class="table table-bordered table-striped table-condensed table-nonfluid" style="overflow:;width:200%;">
	<thead>
		<tr>
			<th class="">No</th>
			<th class="">Kode</th>
			<th>Nama</th>
			<th class="">Aksi</th>
		</tr>
	</thead>   
	<tbody>
		<?php 
		$no = $filter['start']+1; 
		if (!empty($data)) {
			foreach ($data as $key => $value) { 
				?>
				<tr>
					<td><?=$no++?></td>
					<td class="center"><?=$value['prodi_kode']?></td>
					<td class="center"><?=$value['prodi_nama']?></td>
					<td class="center">
						<input type="checkbox" name="prodi[]" value="<?=$value['prodi_id']?>" id="" style="" <?=$value['checked']?> >

						<!-- <a href="#" class="btn btn-small btn-inverse span1">
							<span class="icon icon-white icon-check" title=".icon  .icon-white  .icon-check "></span>
							Pilih
						</a> -->
					</td>                                       
				</tr>
				<?php 
			}
		}else{
			$filter['start'] = $filter['start'] - 1;
			?>
			<tr>
				<td colspan="7" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>                                     
			</tr>
			<?php
		} 
		?>

	</tbody>
</table>

</div>

</div>
<div class="modal-footer">
	<input type="hidden" name="id" id="id" value="<?=$id?>" />
	<button type="button" data-dismiss="modal" class="btn">Close</button>
	<input type="submit" id="submit" class="btn btn-primary" value="Simpan" ></button>
</div>
</form>


<div class="row-fluid">
	<div class="" style="margin-left:10px;width:200px;float:left;">
		<div class="dataTables_info" id="DataTables_Table_0_info">
			Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=($jumlah_data)?> entries
		</div>
	</div>

	<div class="" style="float:right;margin-right:10px;">
		<div class="dataTables_paginate paging_bootstrap pagination" style="margin:0px;">
			<ul>
				<?=$paging_data?>
			</ul>
		</div>
	</div>
</div>

</div>

<script type="text/javascript">
$(function(){
	var form = document.myform;
	var dataString = $(form).serialize();

	$(".pagin" ).on( "click", function() {
		
		urle = $(this).find("a").attr('href');
		id = $("#id").val();
		// alert(urle);
		$.ajax({
			type: "POST",
			// url: "http://localhost/gluse/index.php/penjadwalan/get_dosen",
			url: urle,
			data: { <?=$filter['from']?>: id }
		}).done(function( msg ) {
			$('#content_modal').html(msg);
			$modal.modal('show');
		});

		return false;
	});
});
</script>