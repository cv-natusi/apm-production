@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			DAFTAR ANTRIAN
		</h1>
	</section>

	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		<!-- <div class="box box-default main-layer">
			<div class="row">
				<div class="col-md-4" style="padding: 15px; margin-left: 10px;">
					<div class="input-group date">
						<input type="text" class="form-control datepicker" placeholder="Pilih Tanggal..">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
					</div>
				</div>
				<div class="col-md-1 btn-search" style="padding: 18px; margin-left: -20px;">
					<div class="input-group-addon">
						<i class="fa fa-search" aria-hidden="true"></i>
					</div>
				</div>
				<div class='clearfix' style="margin-bottom: 5px"></div>
			</div>
		</div> -->
		<div class="box box-primary main-layer">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="col-md-4 col-sm-4 col-xs-12 form-inline main-layer" style='padding:5px'>
					</div>
					<div class='clearfix'></div>
					<div class="col-md-12" style='padding:0px'>
						<!-- <div class="col-md-3">
							<div class="form-group">
								<select class="input-sm form-control input-s-sm inline v-middle option-search" id="search_data">
									<option value="nama_pasien">Nama Pasien</option>
									<option value="no_rm">No RM</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<input type="text" class="input-sm form-control" placeholder="Search" id="search_data_input">
							</div>
						</div> -->
						<!-- Datagrid -->
						<div class="table-responsive">
							<table class="table table-striped b-t b-light" id="dataTable" style="width:100%">
								<thead>
									<tr>
										<th>No</th>
										<th>Nomor RM</th>
										<th>Nama Pasien</th>
										<th>Pendaftaran Pasien</th>
										<th>Jenis Kelamin</th>
										<th>Tgl Lahir</th>
										<th>Alamat</th>
										<th>Waktu Masuk</th>
										<th>Poli Tujuan</th>
										<th>Status Filling</th>
										<th>Tgl Filling</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						{{-- <div class="col-md-2">
							<h6>Berkas Sudah Keluar (K)</h6>
							<input type="color" readonly disabled value="#FF0000" style="width:100%">
						</div> --}}
						<div class="col-md-2">
							<h6>Berkas Sedang Dicari (C)</h6>
							<input type="color" readonly disabled value="#FFC500" style="width:100%">
						</div>
						<div class="col-md-2">
							<h6>Berkas Ditemukan (R)</h6>
							<input type="color" readonly disabled value="#42FF00" style="width:100%">
						</div>
						<div class="col-md-2">
							<h6>Berkas Tidak Ditemukan (T)</h6>
							<input type="color" readonly disabled value="#9F9F9F" style="width:100%">
						</div>
					</div>
					<div class='clearfix'></div>
				</div>
			</div>
		</div>
	</div>
	<div class='clearfix'></div>
	
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@stop
@section('script')
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.2/js/bootstrap.min.js" integrity="sha512-5BqtYqlWfJemW5+v+TZUs22uigI8tXeVah5S/1Z6qBLVO7gakAOtkOzUtgq6dsIo5c0NJdmGPs0H9I+2OHUHVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript">
		$(function(){
			$(".datepicker").datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true,
				todayHighlight: true,
			});

			$("#search_data_input").keyup(function (e) { 
				let filternya = $("#search_data").val();
				loadTable(filternya, this.value);
			});
		});

		loadTable();
		function loadTable(type = "nama_pasien",filterNama = null){
			var url = "{{ route('dataGridFilling') }}";
			var x = $('#dataTable').dataTable({
				scrollX: true,
				bPaginate: true,
				paging: true,
				// bFilter: true,
				// sDom: 'lrtip',
				bDestroy: true,
				processing: true,
				serverSide: true,
				ordering: false,
				columnDefs: [{
					orderable: false,
					targets: -1
				}],
				ajax: {
					url:url,
					type: 'post',
					data: {
						type : type,
						filterNama : filterNama
					}
				},
				columnDefs: [
					{sortable: false, 'targets': [0,1,2]},
					{searchable: false, 'targets': [0,4,5,7,10]},
				],
				columns: [
					{data: 'DT_Row_Index', name: 'DT_Row_Index'},
					{
						data: 'no_rm', 
						name: 'no_rm',
					},
					{
						data: 'customer.NamaCust', 
						name: 'Nama Pasien',
						render: function(data, type, row) {
							return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
						}
					},
					{
						data: 'dari',
						name: 'dari'
					},
					{
						data: 'customer.JenisKel', 
						name: 'Jenis Kelamin',
						render: function(data, type, row) {
							return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
						}
					},
					{
						data: 'customer.TglLahir', 
						name: 'Tgl Lahir',
						render: function(data, type, row) {
							return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
						}
					},
					{
						data: 'customer.Alamat', 
						name: 'Alamat',
						render: function(data, type, row) {
							return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
						}
					},
					{
						data: 'tgl_periksa', 
						name: 'Waktu Masuk',
						render: function(data, type, row) {
							return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
						}
					},
					{data: 'namaPoli', name: 'namaPoli'},
					{
						data: 'statusnya', 
						name: 'Status Filling',
						render: function(data, type, row) {
							return data;
						}
					},
					{
					data: 'tgl_filling', 
					name: 'Tgl Filling',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}},
				],
			})
		}

		function changeStatFilling(idFilling){
			let select = $("#"+idFilling);
			let val = select.val();
			let valPrev = select.attr('data-init');
			// styling(idFilling,val);
			// swal({
			// 	title: 'KONFIRMASI !',
			// 	type: 'info',
			// 	text: 'Anda Ingin Mengubah Status Data Ini?',
			// 	confirmButtonClass: "btn-primary",
			// 	confirmButtonText: "Ya, Benar",
			// 	cancelButtonText: "Tidak",
			// 	showCancelButton: true,
			// }, function (isConfirm) {
			// 	if(isConfirm){
					$.post("{{route('changeStatFilling')}}",{idFilling:idFilling,status:val})
					.done(function(data){
						swal({
							title:data.status,
							type: data.status,
							text: data.message,
							showConfirmButton: false,
							timer: 1200,
						});
						loadTable();
					}).fail(function(data){
						swal({
							title:data.status,
							type: data.status,
							text: data.message
						});
						loadTable();
					})
			// 	}else{
			// 		styling(idFilling,val,valPrev);
			// 	}
			// })
		};

		function styling(idFilling,value,valPrev = null) {
			if(valPrev != null){
				if(valPrev == "belum"){
					console.log("u here")
					console.log("now"+value);
					console.log("old"+valPrev);
					$("#"+idFilling).css('background-color', 'black');
					$("#"+idFilling).css('color', 'white');
					$("#"+idFilling).val(valPrev).change();
				}else if( valPrev == "dicari" ){
					$("#"+idFilling).css('background-color', 'orange');
					$("#"+idFilling).css('color', 'white');
					$("#"+idFilling).val(valPrev).change();
				}else if( valPrev == "kosong" ){
					$("#"+idFilling).css('background-color', 'grey');
					$("#"+idFilling).css('color', 'white');
					$("#"+idFilling).val(valPrev).change();
				}else if( valPrev == "keluar" ){
					$("#"+idFilling).css('background-color', 'red');
					$("#"+idFilling).css('color', 'white');
					$("#"+idFilling).val(valPrev).change();
				}else if( valPrev == "ada" ){
					$("#"+idFilling).css('background-color', 'green');
					$("#"+idFilling).css('color', 'white');
					$("#"+idFilling).val(valPrev).change();
				}
			}else{
				if(value == "belum"){
					$("#"+idFilling).css('background-color', 'black');
					$("#"+idFilling).css('color', 'white');
				}else if( value == "dicari" ){
					$("#"+idFilling).css('background-color', 'orange');
					$("#"+idFilling).css('color', 'white');
				}else if( value == "kosong" ){
					$("#"+idFilling).css('background-color', 'grey');
					$("#"+idFilling).css('color', 'white');
				}else if( value == "keluar" ){
					$("#"+idFilling).css('background-color', 'red');
					$("#"+idFilling).css('color', 'white');
				}else if( value == "ada" ){
					$("#"+idFilling).css('background-color', 'green');
					$("#"+idFilling).css('color', 'white');
				}
			}
		}

	</script>
@stop