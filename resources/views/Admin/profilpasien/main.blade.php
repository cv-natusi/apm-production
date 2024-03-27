@extends('Admin.master.layout')

@section('extended_css')
<style type="text/css">
</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			DATA PROFIL PASIEN
		</h1>
	</section>
	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		<div class="box box-primary main-layer">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="col">
						<div class="col-md-2">
							<button class="btn btn-success" id="tambahData">Tambah Data Pasien</button>
						</div>
						<div class="pull-right">
							<div class="col-md-12">
								<div class="col-md-10">
									<input type="text" id="filterNama" class="form-control" value="" placeholder="Cari Nama Pasien">
								</div>
								<div class="col-md-2">
									<button class="btn btn-danger" id="btnFilter">Cari</button>
								</div>
							</div>
						</div>
					</div>
					<table id="dataTable" class="table table-striped dataTable display nowrap" style="width: 100%;">
						<thead class="text-center">
							<tr>
								<th>No</th>
								<th>No. RM</th>
								<th>Nama Pasien</th>
								<th>P/L</th>
                                <th>NIK</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Umur</th>
                                <th>Gol Darah</th>
                                <th>Kewarganegaraan</th>
                                <th>Alamat</th>
                                <th>Kelurahan</th>
                                <th>Kecamatan</th>
                                <th>Kab/Kota</th>
                                <th>Provinsi</th>
                                <th>Telepon</th>
                                <th>Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="other-page">
			@include('Admin.profilpasien.form')
		</div>
	</div>
	<div class='clearfix'></div>
@stop
@section('script')
<script type="text/javascript">
	loadTable();
	filterTableByCounter();
	$(".other-page").hide();

	$(document).ready(function () {
		var canceled = false;

		$("#tambahData").click(function (e) { 
			e.preventDefault();
			$(".main-layer").hide();
			$(".other-page").show();
		});
		
		$(".btn-cancel").click(function (e) { 
			e.preventDefault();
			$(".other-page").hide();
			$(".main-layer").show();
		});

		$('#provinsi').change(function(){
			var id = $('#provinsi').val();
			$.post("{{route('get_kabupaten')}}",{id:id},function(data){
				var kabupaten = '<option value="">..:: Pilih Kabupaten ::..</option>';
				if(data.status=='success'){
					if(data.data.length>0){
						$.each(data.data,function(v,k){
							kabupaten+='<option value="'+k.id+'">'+k.name+'</option>';
						});
					}
				}
				$('#kabupaten').html(kabupaten);
			});
		});

		$('#kabupaten').change(function(){
			var id = $('#kabupaten').val();
			$.post("{{route('get_kecamatan')}}",{id:id},function(data){
				var kecamatan = '<option value="">..:: Pilih Kecamatan ::..</option>';
				if(data.status=='success'){
					if(data.data.length>0){
						$.each(data.data,function(v,k){
							kecamatan+='<option value="'+k.id+'">'+k.name+'</option>';
						});
					}
				}
				$('#kecamatan').html(kecamatan);
			});
		});

		$('#kecamatan').change(function(){
			var id = $('#kecamatan').val();
			$.post("{{route('get_desa')}}",{id:id},function(data){
				var desa = '<option value="">..:: Pilih Desa ::..</option>';
				if(data.status=='success'){
					if(data.data.length>0){
						$.each(data.data,function(v,k){
							desa+='<option value="'+k.id+'">'+k.name+'</option>';
						});
					}
				}
				$('#desa').html(desa);
			});
		});

		$(".btn-store").click(function (e) { 
			e.preventDefault();
			//validasi
			let valid = validasiForm();
			//create data
			if(!valid){
				let data = new FormData();
				//Add Foto To FormData
				var fotoPasien = $('input[name=foto_pasien]')[0].files;
				for (var i = 0; i < fotoPasien.length; i++) {
					data.append("foto_pasien", fotoPasien[i]);
				}
				//Add DataSerialize to FormData
				let dataForm = $(".formAdd").serializeArray();
				$.each(dataForm, function( key, value ) {
					data.append(value.name, value.value);
				});

				$.ajax({
					type: "POST",
					url: "{{ route('simpanProfilPasien') }}",
					data: data,
					processData: false,
					contentType: false,
					dataType: "json",
					success: function(data, textStatus, jqXHR) {
						if(data.status == "success"){
							swal('Berhasil',data.message,data.status);
							// $(".main-layer").show();
							// $(".add-form").hide();
							window.location.reload();
						}else{
							swal('Whooops',data.message,data.status);
						}
					},
					error: function(data, textStatus, jqXHR) {
						console.log("fail");
						swal('Whooops',data.message,data.status);
					},
				});
			}
		});
	});

	function loadTable(filterNama = null){
		var url = "{{ route('getProfilPasien') }}";
		var x = $('#dataTable').dataTable({
			scrollX: true,
			bPaginate: true,
			paging: true,
			bFilter: true,
			bDestroy: true,
			processing: false,
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
					filterNama : filterNama
				}
				// method: 'post',
			},
			columns: [
				{data: 'DT_Row_Index', name: 'DT_Row_Index'},
				// {
				// 	data: 'NamaCust', 
				// 	name: 'Nama Pasien',
				// 	render: function(data, type, row) {
				// 		return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
				// 	}
				// },
				{
					data: 'KodeCust', 
					name: 'No. RM',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'profil', 
					name: 'Nama Pasien',
					// render: function(data, type, row) {
					// 	return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					// }
				},
				{
					data: 'JenisKel', 
					name: 'L/P',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'NoKtp', 
					name: 'Nik',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'Tempat', 
					name: 'Tempat Lahir',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'TglLahir', 
					name: 'Tanggal Lahir',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data.substring(0,10) ) + '</p>';
					}
				},
				{
					data: 'umur', 
					name: 'Umur',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'goldarah', 
					name: 'Gol Darah',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'warganegara', 
					name: 'Kewarganegaraan',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'Alamat', 
					name: 'Alamat',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'namaKel', 
					name: 'Kelurahan',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'namaKec', 
					name: 'Kecamatan',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'namaKab', 
					name: 'Kab/Kota',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'namaProv', 
					name: 'Provinsi',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'Telp', 
					name: 'Telepon',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
				{
					data: 'action', 
					name: 'action',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data == null || data == undefined) ? "-" : data) + '</p>';
					}
				},
			],
		})
	}

	function filterTableByCounter() {
		$("#dataTable_filter").addClass("hidden");

		$("#btnFilter").click(function (e) {
			e.preventDefault();
			loadTable( $("#filterNama").val());
			$("#dataTable_filter").addClass("hidden");
		});
	}

	function detail(id) {
		console.log(id)
		$('.main-layer').hide();
		var url = "{{route('viewProfilPasien')}}";
		$.post(url,{cust_id:id, view:1}).done(function(data){
			if(data.status == 'success'){
				// $('.main-layer').hide();
				$('.other-page').html(data.content).fadeIn();
			}else{
				$('.main-layer').show();
			}
		})
	}

	function edit(id) {
		console.log(id)
		$('.main-layer').hide();
		var url = "{{route('viewProfilPasien')}}";
		$.post(url,{cust_id:id, view:0}).done(function(data){
			if(data.status == 'success'){
				// $('.main-layer').hide();
				$('.other-page').html(data.content).fadeIn();
			}else{
				$('.main-layer').show();
			}
		})
	}

	$(".btn-cancel").click(function (e) { 
		e.preventDefault();
		$(".main-layer").show();
		$(".other-page").hide();
	});

	function cetakrm() {
		swal({
			title: 'KONFIRMASI !',
			type: 'info',
			text: 'Anda Ingin Cetak No RM?',
			confirmButtonClass: "btn-primary",
			confirmButtonText: "Cetak",
			cancelButtonText: "Tidak",
			showCancelButton: true,
		}, function (isConfirm) {
			if(isConfirm){
				var url = "{{route('generateNoRm')}}";
				$.post(url,{}).done(function(data){
					if(data.status == 'success'){
						swal({
							title: 'Berhasil',
							type: data.status,
							text: data.message,
							showConfirmButton: true
						})
						$('#no_rm').val(data.data);
						$('.btn-cetak-rm').hide()
					}else{
						swal({
							title: 'Whoops',
							type: data.status,
							text: data.message,
						})
					}
				})
			}
		})
	}
</script>
@stop