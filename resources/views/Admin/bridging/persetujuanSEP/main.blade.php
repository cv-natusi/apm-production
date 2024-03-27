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
	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
		padding: 2px 8px !important;
	}
	.chosen-single {
		height: 26px !important;
	}
	#pilihDokterDpjpLayan_chosen, #tujuan_kunjugan_text, #prosedur_bpjs, #assesment_bpjs {
		width: 84% !important;
	}
	.chosen-single > span, .chosen-single > div {
		margin-top: -4px !important;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="{{asset('AssetsAdmin/plugins/datatables/dataTables.bootstrap.css')}}"/>

@stop

@section('content')
	<section class="content-header">
		<h1>Persetujuan SEP</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> SEP</a></li>
			<li class="active">Persetujuan SEP</li>
		</ol>
	</section>
	<section class="content" id="main-layer">
		<input type="hidden" id="txturl" value="https://vclaim.bpjs-kesehatan.go.id/VClaim">
		<input id="txtPiloting" type="hidden" value="1">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Action</h3>
					</div>
					<div class="box-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-md-3 col-sm-3 col-xs-12 control-label">Bulan</label>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select class="form-control" id="cbbulan">
										<option @if (date('m') == '01') selected @endif value="01">Januari</option>
										<option @if (date('m') == '02') selected @endif value="02">Februari</option>
										<option @if (date('m') == '03') selected @endif value="03">Maret</option>
										<option @if (date('m') == '04') selected @endif value="04">April</option>
										<option @if (date('m') == '05') selected @endif value="05">Mei</option>
										<option @if (date('m') == '06') selected @endif value="06">Juni</option>
										<option @if (date('m') == '07') selected @endif value="07">Juli</option>
										<option @if (date('m') == '08') selected @endif value="08">Agustus</option>
										<option @if (date('m') == '09') selected @endif value="09">September</option>
										<option @if (date('m') == '10') selected @endif value="10">Oktober</option>
										<option @if (date('m') == '11') selected @endif value="11">Nopember</option>
										<option @if (date('m') == '12') selected @endif value="12">Desember</option>
									</select>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select class="form-control" id="cbtahun">
										<option value="">-- Pilih Tahun --</option>
										@for ($i = 2016; $i <= date('Y'); $i++)
											@if ($i == date('Y'))
												<option selected value="{{ $i }}">{{ $i }}</option>
											@endif
										@endfor
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3 col-sm-3 col-xs-12"></div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									<button class="btn btn-primary" id="btnCari" type="button"> <i class="fa fa-search"></i> Cari</button>
								</div>
							</div>
						</form>
					</div>

				</div>
				<div class="box box-default">
					<div class="box-header with-border">
						<form class="form-horizontal">
							<div>
								<button class="btn btn-success" id="btnAdd" type="button"><i class="fa fa-plus"></i> Tambah Pengajuan</button>
							</div>
						</form>
					</div>
					<div class="box-body">
						<div id="tblPenjaminan_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<table class="table table-bordered table-striped dataTable no-footer" width="100%" id="tblPenjaminan" >
								<thead>
									<tr role="row">
										<th>No.</th>
										<th>No.Kartu</th>
										<th>Tgl.SEP</th>
										<th>RI/RJ</th>
										<th>Persetujuan</th>
									</tr>
								</thead>
								<tbody>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- -->
		</div>
	</section>
	<div class="other-page"></div>
	<div class="clearfix"></div>
	<div class="modal-dialog"></div>
@stop
@section('script')
	<script src="{!! url('AssetsAdmin/plugins/datatables/jquery.dataTables.js') !!}"></script>
	<script src="{!! url('AssetsAdmin/plugins/datatables/dataTables.bootstrap.js') !!}"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#tblPenjaminan').DataTable();
	});

	$('#btnAdd').click(function(){
		$.post("{!! route('create_persetujuan_sep') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('#btnCari').click(function(e){
		e.preventDefault();
		var bulan = $('#cbbulan').val();
		var tahun = $('#cbtahun').val();
		var data = new FormData();
		data.append('bulan',bulan);
		data.append('tahun',tahun);

		$.ajax({
			url : "{!! route('get_list_pengajuan') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(result) {
			if (result.code == 200) {
				var tr = ``;
				var no = 0;
				$('#tblPenjaminan').DataTable().destroy();
				$('#tblPenjaminan tbody').html('');

				result.data.forEach(element => {
					tr += `<tr>`;

					no++;
					tr += `<td>`;
					tr += `${no}`;
					tr += `</td>`;

					tr += `<td>`;
					tr += '<a href="javascript:void(0)" onclick=\'approve('+JSON.stringify(element)+', event)\' class="btn btn-info btn-xs rounded-0">'+element.noKartu+'</a>';
					// tr += `<input type="hidden" id="nosep" value="${element.noSep}">`;
					// tr += `${element.noKartu}`;
					tr += `</td>`;

					tr += `<td>`;
					tr += `${element.tglSep}`;
					tr += `</td>`;

					tr += `<td>`;
					if (element.jnsPelayanan == 2) {
						tr += `Rawat Jalan`;
					}else {
						tr += `Rawat Inap`;
					}
					tr += `</td>`;

					tr += `<td>`;
					if (element.jnsPengajuan == 1) {
						tr += `Pengajuan Backdate`;
					}else {
						tr += `Pengajuan Fingerprint`;
					}
					tr += `</td>`;

					tr += `</tr>`;
				});
				// console.log(tr);
				$("#tblPenjaminan tbody").html(tr);
				$('#tblPenjaminan').DataTable();
			}else {
				var tr = ``;
				var no = 0;
				$('#tblPenjaminan').DataTable().destroy();
				$('#tblPenjaminan tbody').html('');

				// result.list.forEach(element => {
					tr += `<tr>`;
					tr += `<td></td>`;
					tr += `<td></td>`;
					tr += `<td>Data Tidak Ditemukan</td>`;
					tr += `<td></td>`;
					tr += `<td></td>`;
					tr += `</tr>`;
				// });
				// console.log(tr);
				$("#tblPenjaminan tbody").html(tr);
				$('#tblPenjaminan').DataTable({

				});
			}
		});
	});

	function approve(e, event){
		if (e.jnsPengajuan == '1') {
			swal("Peringatan!", "Persetujuan BackDate SEP hanya dilakukan oleh KC setempat. Silahkan hubungi PIC KC BPJS Kesehatan.", "warning");
		} else if (e.jnsPengajuan == '2') {
			swal({
				title: "Approve Pengajuan SEP Fingerprint?",
				// text: "",
				type: "info",
				showCancelButton: true,
				confirmButtonText: "Approve",
				cancelButtonText: "Batal"
			}, function(){
				$.post("{!! route('aproval_pengajuan_sep') !!}", e).done(function(data){
					if(data.metaData.code == '200'){
						swal("Berhasil!", data.message, "success");
					} else{
						swal(data.metaData.message, data.response, "error");
					}
				});
			});
		}
	}
	</script>
@stop
