<div class="row" id="divrowPraktek" style="">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-calendar"></i>
				<h3 class="box-title">Jadwal Praktek Rumah Sakit</h3>
			</div>
			<div class="box-body">
				<div id="divSpesialis">
					<div class="alert alert-info alert-dismissible">
						<p>
							1. Untuk Melihat Jadwal Praktek Dokter klik Nama Spesialis/SubSpesialis<br>
							2. Jumlah Rencana Kontrol Merupakan Penjumlahan dari Rencana Kontrol Spesialis/Subspesialis Per Tanggal<br>
							3. Kapasitas Merupakan Jumlah Maksimal Layanan yang Dapat dilayani Oleh Spesialis/Subspesialis<br>
						</p>
					</div>
					<div id="tblSpesialis_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<div class="row">
							<div class="col-sm-6"></div>
							<div class="col-sm-6">
								<div id="tblSpesialis_filter" class="dataTables_filter">
									<label>
										Search:
										<input type="search" class="form-control input-sm" placeholder="" aria-controls="tblSpesialis">
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table id="datatblPoli" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Spesialis/Sub</th>
											<th>Kapasitas</th>
											<th>Jml.Rencana Kontrol & Rujukan</th>
											<th>Prosentase</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											{{-- <td colspan="11" style="text-align:center;">Loading...</td> --}}
											<td>-</td>
											<td>-</td>
											<td>Loading...</td>
											<td>-</td>
											<td>-</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button class="btn btn-danger" id="btnBatalJadwalRujKontrol" type="button">
			<i class="fa fa-undo"></i> Batal
		</button>
	</div>
</div>
<div id="cariDokterRujKontrol"></div>

<script type="text/javascript">
	$(document).ready(function(){
		loadData();
	});

	$('#rbkontrol').click(function(){
		$('.rencana-inap').hide();
		$("#lblnomor").text("No. SEP");
	});

	$('#btnBatalJadwalRujKontrol').click(function(e){
		e.preventDefault();
		$('#divrowPraktek').fadeOut();
		$('.JadwalRujKontrol').fadeOut(function(){
			$('.JadwalRujKontrol').empty();
			$('.panel-page').fadeIn();
		});
	});


	function loadData(){
		e.preventDefault();
		$('#datatblPoli tbody').html('<tr><td colspan="11" style="text-align:center;">Loading ...</td></tr>');
		var txtTgl1 = $('#txtTgl1').val();
		var txtTgl2 = $('#txtTgl2').val();
		var cbfilterrencanakontrol = $('#cbfilterrencanakontrol').val();
		var data = new FormData();
		data.append('tgl1',txtTgl1);
		data.append('tgl2',txtTgl2);
		data.append('cbfilterrencanakontrol',cbfilterrencanakontrol);

		$.ajax({
			url : "{!! route('list-rencana-kontrol-search') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(result) {
			if (result.response != null) {
				var tr = '';
				var no = 0;
				$('#datatblPoli').DataTable().destroy();
				$('#datatblPoli tbody').html('');

				result.response.forEach(element => {
					tr += `<tr>`;

					no++;
					tr += '<td>'+no+'</td>';

					tr += `<td>`;
					tr += `<a href="javascript:void(0)" onclick="rujukan_bpjs('${element.noSuratKontrol}')" class="btn btn-info btn-xs rounded-0">${element.kodeSpesialis}</a>`;
					tr += `</td>`;

					tr += '<td>'+element.jnsPelayanan+'</td>';
					tr += '<td>'+element.noSepAsalKontrol+'</td>';
					tr += '<td>'+element.tglRencanaKontrol+'</td>';
					tr += '<td>'+element.tglTerbitKontrol+'</td>';
					tr += '<td>'+element.noSepAsalKontrol+'</td>';
					tr += '<td>'+element.namaPoliAsal+'</td>';
					tr += '<td>'+element.namaPoliTujuan+'</td>';
					tr += '<td>'+element.namaDokter+'</td>';
					tr += '<td>'+element.noKartu+'</td>';
					tr += '<td>'+element.nama+'</td>';

					tr += `</tr>`;
				});
				$("#datatblPoli tbody").html(tr);
				$('#datatblPoli').DataTable();
			}else {
				var tr = '';
				var no = 0;
				$('#datatblPoli').DataTable().destroy();
				$('#datatblPoli tbody').html('');

				tr += `<tr>`;
				tr += `<td colspan="11" style="text-align:center;">`;
				tr += result.metaData.message;
				tr += `</td>`;
				tr += `</tr>`;
				$("#datatblPoli tbody").html(tr);
				$('#datatblPoli').DataTable();
			}
		});
	}

	// $(document).ready(function(){
	// 	$('#divrowPraktek').hide();
	// 	var rdpilih = $('input[name=rdpilih]:checked').val();
	// 	var txtnosep_0 = $('#txtnosep_0').val();
	// 	var txttglrencanakontrol_0 = $('#txttglrencanakontrol_0').val();
	// 	var data = new FormData();
	// 	data.append('rdpilih', rdpilih);
	// 	data.append('txtnosep_0',txtnosep_0);
	// 	data.append('txttglrencanakontrol_0',txttglrencanakontrol_0);
	// 	$.ajax({
	// 		url : "{!! route('cariDokterRujKontrol') !!}",
	// 		type: 'POST',
	// 		dataType: 'json',
	// 		data: data,
	// 		async: true,
	// 		cache: false,
	// 		contentType: false,
	// 		processData: false
	// 	}).done(function(result) {
	// 		if(data.status == 'success'){
	// 			$('#cariDokterRujKontrol').html(data.content).fadeIn();
	// 		} else {
	// 			swal('Whoopss!!',data.message,'warning');
	// 			$('#divrowPraktek').show();
	// 		}
	// 	});
	// });
</script>
