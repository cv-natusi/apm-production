@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			DAFTAR ANTRIAN <?php
			if(Auth::User()->level != 1){
				$a = strtoupper(Auth::User()->name_user);
			}else{
				$a = "KONTER POLI";
			}
			echo $a;
		?>
		</h1>
	</section>

	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		{{-- <div class="box box-default main-layer">
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
		</div> --}}
		<div class="box box-primary main-layer">
			<div class="panel panel-default">
				<div class="panel-body">
					<table id="dataTable" class="table table-striped dataTable display nowrap" style="width: 100%;">
						<thead class="text-center">
							<tr>
								<th>No</th>
								<th>Antrian</th>
								<th>Kode Booking</th>
								<th>Nama Pasien</th>
								<th>No RM</th>
								<th>NIK</th>
								<th>Tgl. Lahir</th>
								<th>Jenis Pasien</th>
								<th>Poli Tujuan</th>
								{{-- <th>Status</th> --}}
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>

		<div class="other-page"></div>
	</div>
	
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
		})

		// Action Batal
		function batal(kode) {
			swal({
				title: "Konfirmasi Batal!",
				text: "KODE BOOKING :" + kode,
				type: "input",
				showCancelButton: true,
				closeOnConfirm: false,
				inputPlaceholder: "Alasan Batal..."
			}, function (inputValue) {
				if (inputValue === false) return false;
				if (inputValue === "") {
					swal.showInputError("Inputan Tidak Boleh Kosong!");
					return false
				} else {
					var input = inputValue;

					$.ajax({
						type: "post",
						url: "{{route('batalAntrian')}}",
						data: {
							keterangan:input,
							kodebooking:kode
						},
						success: function (response) {
							if(response.metaData.code == 200){
								swal("Berhasil!", "Antrian Berhasil Dibatalkan", "success");
								location.reload();
							}else{
								swal("Warning!", response.metaData.message, "error");

							}
						}
					});
				}
			});
		}

		var datagrid = $("#datagrid").datagrid({
			url                 : "{!! route('dataGridLoket') !!}",
			primaryField        : 'id', 
			rowNumber           : true, 
			rowCheck            : false, 
			searchInputElement  : '#search',
			searchFieldElement  : '#search-option', 
			pagingElement       : '#paging', 
			optionPagingElement : '#option', 
			pageInfoElement     : '#info',
			columns             : [
				{field: 'no_antrian', title: 'Nomor Antrian', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'namaCust', title: 'Nama Pasien', editable: false, sortable: true, width: 200, align: 'left', search: true,
					rowStyler: function(rowData,rowIndex){
						return namaPasien(rowData,rowIndex)
					}
				},
				{field: 'kode_booking', title: 'No RM', editable: false, sortable: true, width: 150, align: 'center', search: true},
				{field: 'kode_booking', title: 'Kode Booking', editable: false, sortable: true, width: 150, align: 'center', search: true},
				// {field: 'metode_ambil', title: 'Antrian Via', editable: false, sortable: true, width: 200, align: 'left', search: false},
				{field: 'NamaPoli', title: 'Nama Poli', editable: false, sortable: true, width: 200, align: 'left', search: false},
				{field: 'jenis_pasien', title: 'Jenis Pasien', editable: false, sortable: true, width: 200, align: 'left', search: false},
				{field: 'statusAntrian', title: 'Status', editable: false, sortable: false, width: 200, align: 'center', search: false,
					rowStyler: function(rowData,rowIndex){
						return statusAntrian(rowData,rowIndex)
					}
				},
				{field: 'Action', title: 'Action', editable: false, sortable: false, width: 300, align: 'left', search: false,
					rowStyler: function(rowData, rowIndex) {
						return action(rowData, rowIndex)
					}
				},
			]
		})

		function loadTable(){
			var x = $('#dataTable').dataTable({
				scrollX: true,
				processing: true,
				serverSide: true,
				columnDefs: [{
					orderable: false,
					targets: -1
				}],
				ajax: {
					url: "{{route('listKonter')}}",
					type: 'get',
					// method: 'post',
				},
				columns: [
					{data: 'DT_Row_Index', name: 'DT_Row_Index'},
					{data: 'no_antrian', name: 'no_antrian'},
					// {data: 'kodeBooking', name: 'kodeBooking'},
					{data: 'kode_booking', name: 'kode_booking'},
					// {data: 'namaCust', name: 'namaCust'},
					{data: 'tm_customer.NamaCust', name: 'NamaCust'},
					// {data: 'noRM', name: 'noRM'},
					{data: 'no_rm', name: 'no_rm'},
					{data: 'nik', name: 'nik'},
					{data: 'tglLahir', name: 'tglLahir'},
					// {data: 'tm_customer.TglLahir', name: 'TglLahir'},
					{data: 'jenis_pasien', name: 'jenis_pasien'},
					// {data: 'poli', name: 'poli'},
					{data: 'mapping_poli_bridging.tm_poli.NamaPoli', name: 'NamaPoli'},
					// {data: 'status', name: 'status'},
					{data: 'action', name: 'status'},
				],
				// rowsGroup: [
				// 	'batch:name',
				// 	'namaObat:name',
				// 	'jumlah_masuk:name',
				// 	'stok_barang.jumlah:name',
				// 	'cetak:name',
				// ]
			})
		}

		$(document).ready(()=>{
			loadTable()
			// datagrid.run()
		})

		function namaPasien(rowData, rowIndex){
			var namaCust = datagrid.getRowData(rowIndex).NamaCust;
			if(!namaCust){
				var html = "<span>-</span>"
			}else{
				var html = "<span>"+namaCust+"</span>"
			}
			return html
		}

		function statusAntrian(rowData, rowIndex) {
			var status = datagrid.getRowData(rowIndex).status;
			if(status == 'belum'){
				var html = '<span style="background:#e6943ce0; color:white; border-radius:8px; padding:5px;">Dalam Antrian</span>'
			}else if(status == 'panggil'){
				var html = '<span style="background:#309684; color:white; border-radius:8px; padding:5px;">Dilayani</span>'
			}else{
				var html = '<span>-</span>'
			}
			return html;
		}

		function action(rowData, rowIndex) {
			var status = datagrid.getRowData(rowIndex).status;
			var booking = rowData.kode_booking
			var url = '{{route("formListAntrian",":id")}}'
			url = url.replace(':id',booking)
			var html = ''
			if(status == 'belum' ){
				html = '<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="batal(`'+rowData.kode_booking+'`)">Batal Antrian</a>&nbsp;&nbsp;'
				html += '<a href="javascript:void(0)" class="btn btn-xs btn-success m-0" onclick="panggil(`'+rowData.kode_booking+'`)">Panggil</a>'
			}else if(status == 'panggil'){
				html = '<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="batal(`'+rowData.kode_booking+'`)">Batal Antrian</a>&nbsp;&nbsp;'
				html += '<a href="'+url+'" class="btn btn-xs btn-warning m-0" >Kelola Pasien</a>'
				// html += '<a href="javascript:void(0)" onclick="pasienBaru(`'+booking+'`)" class="btn btn-xs btn-warning m-0" >Kelola Pasien</a>'
				// html += '<a href="javascript:void(0)" class="btn btn-xs btn-primary m-0" onclick="arahkan(`'+rowData.kode_booking+'`)">Arahkan ke Poli</a>'
			}else{
				var html = '<span>-</span>'
			}
			return html;
		}

		function panggil(id) {
			swal({
				title: 'KONFIRMASI !',
				type: 'info',
				text: 'Apakah Antrian Ingin Dipanggil?',
				confirmButtonClass: "btn-primary",
				confirmButtonText: "Panggil",
				cancelButtonText: "Tidak",
				showCancelButton: true,
			}, function (isConfirm) {
				if(isConfirm){
					var url = "{{route('panggilAntrian')}}";
					$.post(url,{id:id}).done(function(data){
						if(data.status == 'success'){
							swal({
								title: 'Berhasil',
								type: data.status,
								text: data.message,
								showConfirmButton: true,
								// timer: 1500
							})
							datagrid.reload();
						}else{
							swal({
								title: 'Whoops',
								type: data.status,
								text: data.message,
								// showConfirmButton: false,
								// timer: 1300
							})
							datagrid.reload();
						}
					})
				}
			})
		}

		function arahkan(param){
			swal({
				title: 'KONFIRMASI !',
				type: 'info',
				text: 'Yakin ingin mengarahkan ke Poli?',
				confirmButtonClass: "btn-primary",
				confirmButtonText: "Arahkan ke poli",
				cancelButtonText: "Batal",
				showCancelButton: true,
			},(isConfirm)=>{
				if(isConfirm){
					$.post('{{route("counterToPoli")}}',{kode:param}).done((res)=>{
						if(res.status == 'success'){
							swal({
								title: 'Berhasil',
								type: res.status,
								text: res.message,
								showConfirmButton: true,
								// timer: 1500
							})
							datagrid.reload();
						}else{
							swal({
								title: 'Whoops',
								type: res.status,
								text: res.message,
							})
							datagrid.reload();
						}
					})
				}
			})
		}

		function pasienBaru(param){
			$.ajax({
				url: '{{route("formListAntrian")}}',
				type: 'get',
				data: {id:param},
				success: function (data) {
					console.log(data)
				}
			});
		}

		function detail(id) {
			// console.log(id)
			$.post("{!! route('detailListCounter') !!}", {id:id}).done(function(data){
				// console.log(data)
				if(data.status == 'success'){
					$('.main-layer').hide();
					$('.other-page').html(data.content).fadeIn();
				} else {
					$('.main-layer').show();
				}
			});
		}

		function generate(id) {
			// console.log(id)
			$.post("{!! route('generateAntrianCounter') !!}", {id:id}).done(function(data){
				console.log(data)
				if(data.status == 'success'){
					var id = data.data.nomor_antrian_poli;
					// swal("Success!", 'Antrian Berhasil Generate No. Antrian', "success");
					// $('.other-page').fadeOut(function() {
					// 	$('.other-page').empty();
					// 	$('.main-layer').fadeIn();
					// 	$('#dataTable').DataTable().ajax.reload();
					// });
					swal({
						title: 'Berhasil !',
						type: 'success',
						html: true,
						text: 'Nomor Antrian Pasien berhasil di generate<br><b style="font-size: 20pt;">'+id+'<b>',
						confirmButtonClass: "btn-primary",
						confirmButtonText: "Cetak",
						// cancelButtonText: "Tidak",
						// showCancelButton: true,
					}, function (isConfirm) {
						if(isConfirm){
							var url = "{{route('cetakAntrianPoli')}}";
							$.post(url,{id:id}).done(function(data){
								if(data.status == 'success'){
									swal({
										title: 'Berhasil',
										type: data.status,
										text: data.message,
										showConfirmButton: true,
										// timer: 1500
									})
									// datagrid.reload();
									$('#dataTable').DataTable().ajax.reload();
								}else{
									swal({
										title: 'Whoops',
										type: data.status,
										text: data.message,
										// showConfirmButton: false,
										// timer: 1300
									})
									// datagrid.reload();
									$('#dataTable').DataTable().ajax.reload();
								}
							})
						}
					})

				} else {
					swal('Whoops!', 'Antrian Gagal Generate No. Antrian', 'warning');
				}
			});
		}

		// $('.btn-search').click(function(e){
		// 	e.preventDefault()
		// 	var date = $('.datepicker').val()
		// 	if(date){
		// 		console.log(date)
		// 	}else{
		// 		console.log('kosong')
		// 	}
		// })
	</script>
@stop