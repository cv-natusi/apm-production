@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
	.small-box h3{
		font-size: 72px;
		margin:none;
	}
	.small-box .inner i{
		font-size: 72px;
		text-align: center;
	}
	.bagi{
		width: 49%;
		float: left;
	}
	.garisbawah{
		border-bottom: 1px solid #333;
	}
	.box-main .form-group{
		padding-top: 5px !important;
	}
	.history > tbody > tr:hover, .history > tbody > tr:active{
		background: #eee;
	}
	.pad{
		padding: 0 5px !important;
	}
	.chosen-single {
		height: 26px !important;
	}
	.chosen-single > span, .chosen-single > div {
		margin-top: -4px !important;
	}
	</style>

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('content')
<section class="content-header">
	<h1>Rencana Kontrol / Rencana Rawat Inap</h1>
</section>
<div class="content">
	<div style="width: 100%;">
		<div class="box box-success main-layer">
			<form id="form-cari">
				<div class="box-header">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#panel_nosep" data-toggle="tab" aria-expanded="true"><i class="fa fa-file-text-o"> Nomor SEP</i></a></li>
							<li class=""><a href="#panel_list" data-toggle="tab" aria-expanded="false"><i class="fa fa-list"> List Rencana Kunjungan Kontrol / Rawat Inap</i></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="panel_nosep">
								<!-- Form -->
								<div class="row">
									<div class="col-md-2"></div>
									<div class="col-md-8">
										<div class="row form-group">
											<label for="no_sep" class="col-sm-2 control-label" style="margin-top: 5px;">Pilih</label>
											<div class="col-sm-10" style="margin-top: 5px;">
												<label>
													<input type="radio" name="rdpilih" id="rbkontrol" value="2" checked="">
													Rencana Kontrol Rawat Jalan
												</label>&nbsp;
												<label>
													<input type="radio" name="rdpilih" id="rbspri" value="1">
													Rencana Masuk Rawat Inap
												</label>&nbsp;
											</div>
										</div>
										<div class="row rencana-inap" style="display:none;">
											<div class="form-group">
												<label for="txttglrencanakontrol_0" class="col-sm-2 control-label" style="margin-top: 5px;">Tgl. Rencana Inap</label>
												<div class="col-sm-10" style="margin-top: 5px;">
													<input type="date" class="form-control datepicker" id="txttglrencanakontrol_0" placeholder="yyyy-MM-dd" value="{!! date('Y-m-d') !!}" maxlength="10">
												</div>
											</div>
										</div>
										<div class="row rencana-kontrol">
											<div class="form-group">
												<label for="no_sep" id="lblnomor" class="col-sm-2 control-label" style="margin-top: 5px;">No. SEP</label>
												<div class="col-sm-10" style="margin-top: 5px;">
													<input type="text" class="form-control" id="txtnosep_0" placeholder="Ketik Nomor">
												</div>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-sm-2" style="margin-top: 5px;"></div>
											<div class="col-sm-10" style="margin-top: 5px;">
												<button type="button" id="btnCariSep" class="btn btn-primary" name="button"><i class="fa fa-search"> Cari</i></button>
											</div>
										</div>
									</div>
								</div>
								<!-- /.form -->
							</div>

							<!-- /.tab-pane -->
							<div class="tab-pane" id="panel_list">
								<div class="row">
									<form class="form-horizontal" id="form-sep">
										<div class="row form-group">
											<div class="col-sm-2"></div>
											<label for="filter_list" class="col-sm-2 control-label">Filter</label>
											<div class="col-sm-6">
												<select class="form-control" id="cbfilterrencanakontrol">
													<option value="2">Tanggal Rencana Kontrol</option>
													<option value="1">Tanggal Entri</option>
												</select>
											</div>
											<div class="col-sm-2"></div>
										</div>
										<div class="row form-group">
											<div class="col-sm-2"></div>
											<label class="col-sm-2 control-label">Tanggal</label>
											<div class="col-sm-6">
												<div class="input-group date col-sm-12">
													<input type="date" class="form-control datepicker" id="txtTgl1" placeholder="yyyy-MM-dd" value="{!! date('Y-m-d') !!}" maxlength="10">
													<span class="input-group-addon">
														<span class="fa fa-calendar"></span>
													</span>
													<span class="input-group-addon">
														s.d
													</span>
													<input type="date" class="form-control datepicker" id="txtTgl2" placeholder="yyyy-MM-dd" value="{!! date('Y-m-d') !!}" maxlength="10">
													<span class="input-group-addon">
														<span class="fa fa-calendar"></span>
													</span>
												</div>
											</div>
											<div class="col-sm-2"></div>
										</div>
										<div class="row form-group">
											<div class="col-sm-4"></div>
											<div class="col-sm-8">
												<button class="btn btn-sm btn-success" id="btnCariTanggal" type="button"> <i class="fa fa-search"></i> Cari</button>
											</div>
										</div>
									</form>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<table id="datatbl" class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>No</th>
													<th>No Surat</th>
													<th>Kontrol/Inap</th>
													<th>Tgl Rencana Kontrol</th>
													<th>Tgl Entri</th>
													<th>No SEP Asal</th>
													<th>Poli Asal</th>
													<th>Poli Tuju</th>
													<th>Nama DPJP</th>
													<th>No Kartu</th>
													<th>Nama Lgkp</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>-</td>
													<td>-</td>
													<td>-</td>
													<td>-</td>
													<td>-</td>
													<td>Silahkan pilih tanggal terlebih dahulu</td>
													<td>-</td>
													<td>-</td>
													<td>-</td>
													<td>-</td>
													<td>-</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- /.tab-pane -->
						</div>
						<!-- /.tab-content -->
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="other-page"></div>
</div>

<div class="clearfix"></div>
<div class="modal-dialog"></div>
<div class="printSEP"></div>
<div id="updatetglpulang"></div>
@stop

@section('script')
<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
<script src="{!! url('AssetsAdmin/plugins/datatables/jquery.dataTables.js') !!}"></script>
<script src="{!! url('AssetsAdmin/plugins/datatables/dataTables.bootstrap.js') !!}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
	$('#rbkontrol').click(function(){
		$('.rencana-inap').hide();
		$("#lblnomor").text("No. SEP");
	});
	$('#rbspri').click(function(){
		$('.rencana-inap').show();
		$("#lblnomor").text("No. Kartu");
	});

	$('#btnCariTanggal').click(function(e){
		e.preventDefault();
		$('#datatbl tbody').html('<tr><td colspan="11" style="text-align:center;">Loading ...</td></tr>');
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
				// $('#datatbl').DataTable().destroy();
				$('#datatbl tbody').html('');

				result.response.list.forEach(element => {
					if (element.noSepAsalKontrol != null) {
						element.noSepAsalKontrol = element.noSepAsalKontrol;
					} else {
						element.noSepAsalKontrol = '-';
					}
					tr += `<tr>`;

					no++;
					tr += '<td>'+no+'</td>';

					tr += '<td>';
					element.nama = element.nama.replace(/'/g, '@')
					var x = JSON.stringify(element)
					// var x = element
					// console.log(x)
					// tr += '<a href="javascript:void(0)" onclick=\'edit('+x+', event)\' class="btn btn-info btn-xs rounded-0">'+element.noSuratKontrol+'</a>';
					tr += `<a href='javascript:void(0)' onclick='edit(${x}, event)' class='btn btn-info btn-xs rounded-0'>${element.noSuratKontrol}</a>`
					// tr += `<a href='javascript:void(0)' onclick="edit(${x})" class='btn btn-info btn-xs rounded-0'>${element.noSuratKontrol}</a>`
					element.nama = element.nama.replace(/@/g, "\'")
					tr += '</td>';

					tr += '<td>'+element.jnsPelayanan+'</td>';
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

				$("#datatbl tbody").html(tr);
				$('#datatbl').DataTable();
			} else {
				var tr = '';
				var no = 0;
				// $('#datatbl').DataTable().destroy();
				$('#datatbl tbody').html('');

				// result.list.forEach(element => {
					tr += `<tr>`;
					tr += `<td colspan="11" style="text-align:center;">`;
					tr += result.metaData.message;
					tr += `</td>`;
					tr += `</tr>`;
				// });

				$("#datatbl tbody").html(tr);
				// $('#datatbl').DataTable();
			}
		});
	});

	function edit(element, e){
		e.preventDefault();
		$('.main-layer').hide();
		console.log(element)
		var data = new FormData();
		data.append('element', JSON.stringify(element));
		data.append('rdpilih', element.jnsKontrol);
		data.append('txtnosep_0', element.noSuratKontrol);
		data.append('isEdit', 'edit');
		$.ajax({
			url : "{!! route('rencana-kontrol-search') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data) {
			if (data.status == 'success') {
				$('.other-page').html(data.content).fadeIn();
			}else {
				swal('Perhatian!', data.message,'warning');
				$('.main-layer').show();
			}
		});

		$('.DokterRujKontrol').show();
	}

	$('#btnCariSep').click(function(e){
		e.preventDefault();
		$('.main-layer').hide();
		var rdpilih = $('input[name=rdpilih]:checked').val();
		var txtnosep_0 = $('#txtnosep_0').val();
		var txttglrencanakontrol_0 = $('#txttglrencanakontrol_0').val();
		var data = new FormData();
		data.append('rdpilih', rdpilih);
		data.append('txtnosep_0',txtnosep_0);
		data.append('txttglrencanakontrol_0',txttglrencanakontrol_0);
		$.ajax({
			url : "{!! route('rencana-kontrol-search') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false,
		}).done(function(data) {
			if(data.status == 'success'){
				if (data.response.request.rdpilih == '1') {
					if(data.response.response.peserta.statusPeserta.keterangan != "AKTIF"){
						swal("Peringatan!", data.response.response.peserta.statusPeserta.keterangan, "warning");
					}
				}
				$('.other-page').html(data.content).fadeIn();
				$('#txttglrencanakontrol').val(txttglrencanakontrol_0);
			} else {
				swal('Whoopss!!',data.message,'warning');
				$('.main-layer').show();
			}
		});
	});
</script>
@stop
