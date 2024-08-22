@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
		/* ALL LOADERS */
		.loader{
			width: 100px;
			height: 100px;
			border-radius: 100%;
			position: relative;
			margin: 0 auto;
		}

		/* LOADER 4 */
		#loader-4 span{
			display: inline-block;
			width: 20px;
			height: 20px;
			border-radius: 100%;
			background-color: #3498db;
			margin: 35px 5px;
			opacity: 0;
		}
		#loader-4 span:nth-child(1){
			animation: opacitychange 1s ease-in-out infinite;
		}
		#loader-4 span:nth-child(2){
			animation: opacitychange 1s ease-in-out 0.33s infinite;
		}
		#loader-4 span:nth-child(3){
			animation: opacitychange 1s ease-in-out 0.66s infinite;
		}
		@keyframes opacitychange{
			0%, 100%{
				opacity: 0;
			}

			60%{
				opacity: 1;
			}
		}
		#outer-action{
			width:100%;
			text-align: center;
		}
		.inner-action{
			display: inline-block;
		}

		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: unset !important;
		}
		.select2-container .select2-selection--single .select2-selection__rendered {
			text-align: center;
		}
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			DAFTAR ANTRIAN LOKET / ADMISI
		</h1>
	</section>

	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		<div class="box box-primary main-layer">
			<div class="panel panel-default">
				<div class="col" style="margin-top: 1rem;">
					<div class="twodate">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<label for="min">Tanggal Awal</label>
							<input type="date" id="min" class="form-control">
						</div>
						<div class="col-md-4">
							<label for="max">Tanggal Akhir</label>
							<input type="date" id="max" class="form-control">
						</div>
					</div>
				</div>
				<div class="clearfix" style="margin-bottom: 30px;"></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							{{-- <table id="dataTable" class="table table-striped dataTable" cellspacing="0" style="width: 100%;"> --}}
							<table id="dataTable" class="table table-striped" style="
								width: 100%;
								overflow-x: auto;
								white-space: nowrap;
							">
								<thead>
									<tr>
										<th>No</th>
										<th>No Antrian</th>
										<th>Kode Booking</th>
										<th class="text-center">No RM</th>
										<th>Poli Tujuan</th>
										<th>Nama Pasien</th>
										<th>Alamat</th>
										<th>Kategori Pasien</th>
										<th>Jenis</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="other-page"></div>
	</div>

	{{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
@stop
@section('script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			var date = new Date();
			var day = date.getDate();
			var month = date.getMonth() + 1;
			var year = date.getFullYear();

			if (month < 10) month = "0" + month;
			if (day < 10) day = "0" + day;

			var today = year + "-" + month + "-" + day ;      
			$("#min").attr("value", today);
			$("#max").attr("value", today);

			loadTable(today , today);
			filterByDate();
		})

		//filter date
		var minDate, maxDate;

		function loadTable(tglAwal = null,tglAkhir = null){
			var loading = '<div class="loader" id="loader-4"><span></span><span></span><span></span></div>'
			var url = "{{route('loket.listAntrian')}}"
			var x = $('#dataTable').dataTable({
				scrollX: true,
				bPaginate: true,
				bFilter: true,
				bDestroy: true,
				processing: true,
				serverSide: true,
				language: {
					processing: loading,
				},
				search: {
					caseInsensitive: true
				},
				ajax: {
					url:url,
					type: 'GET',
					data: {
						tglAwal : tglAwal,
						tglAkhir : tglAkhir
					}
				},
				columns: [
					{data: 'DT_Row_Index', name: 'DT_Row_Index', orderable:false, searchable:false},
					{
						data: 'no_antrian_pbaru', 
						name: 'no_antrian_pbaru',
						render: function(data, type, row) {
							return '<p style="color:black" class="text-center">' + ((data=='' || data ==null) ? '-' : data) + '</p>';
						}
					},
					{data: 'kode_booking', name: 'kode_booking'},
					{
						data: 'no_rm', 
						name: 'no_rm',
						render: function(data, type, row) {
							return '<p style="color:black" class="text-center">' + ((data=='00000000000' || data=='' || data ==null) ? '-' : data) + '</p>';
						}
					},
					{data: 'mapping_poli_bridging.tm_poli.NamaPoli', name: 'NamaPoli'},
					{
						// data: 'namaCust',
						// name: 'tm_customer.NamaCust',
						data: 'namaPasien',
						name: 'namaPasien',
						orderable:false,
						searchable:true,
					},
					{
						data: 'tm_customer.Alamat', 
						name: 'Alamat',
						render: function(data, type, row) {
							return '<p style="color:black">' + ((data=='' || data==null) ? '-' : data) + '</p>';
						}
					},
					{
						data: 'is_geriatri', 
						name: 'is_geriatri',
						render: function(data, type, row) {
							return '<p style="color:black">' + ((data == 'N') ? 'Normal' : 'Geriatri') + '</p>';
						}
					},
					{data: 'jenis_pasien', name: 'jenis_pasien'},
					{data: 'action', name: 'action'},
				],
			})
		}

		function filterByDate() {
			$("#min").change(function (e) { 
				e.preventDefault();
				loadTable( $(this).val() , $("#max").val() );
			});

			$("#max").change(function (e) { 
				e.preventDefault();
				loadTable( $("#min").val() , $(this).val() );
			});
		}

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
			}else{
				var html = '<span>-</span>'
			}
			return html;
		}

		function panggil(id) {
			var url = "{{route('panggilAntrian')}}";
			$.post(url,{id:id}).done(function(data){
				if(data.status == 'success'){
					//insert data ke table pemanggilan
					let url = '{{route("insertDataPemanggilan")}}';
					$.post(url, {kode_booking : id}).done(function(){
					}).fail(function(){});

					swal({
						title: 'Berhasil',
						type: data.status,
						text: data.message,
						showConfirmButton: false,
						timer: 800
					})
					$('#dataTable').DataTable().ajax.reload();
				}else{
					swal({
						title: 'Whoops',
						type: data.status,
						text: data.message,
					})
					$('#dataTable').DataTable().ajax.reload();
				}
			})
		}

		// Action Batal
		function batalkan(kode) {
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

		function cetakrm(id) {
			swal({
				title: 'KONFIRMASI !',
				type: 'info',
				text: 'Apakah Antrian Ingin Cetak RM?',
				confirmButtonClass: "btn-primary",
				confirmButtonText: "Cetak",
				cancelButtonText: "Tidak",
				showCancelButton: true,
			}, function (isConfirm) {
				if(isConfirm){
					var url = "{{route('cetakRMAntrian')}}";
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
		}

		function cetaksep() {
			console.log('Belum Ada Aksi')
		}

		function kerjakan(id) {
			$('.main-layer').hide();
			var url = "{{route('loket.kerjakanAntrian')}}";
			$.post(url,{id:id}).done(function(data){
				if(data.status == 'success'){
					$('.preloader').hide();
					$('.other-page').html(data.content).fadeIn();
				}else{
					$('.main-layer').show();
				}
			})
		}

		function arahkan(param){
			swal({
				title: 'KONFIRMASI !',
				type: 'info',
				text: 'Yakin ingin mengarahkan ke Konter Poli?',
				confirmButtonClass: "btn-primary",
				confirmButtonText: "Arahkan ke Konter poli",
				cancelButtonText: "Batal",
				showCancelButton: true,
			},(isConfirm)=>{
				if(isConfirm){
					$.post('{{route("loketToCounter")}}',{kode:param}).done((res)=>{
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
	</script>
@stop