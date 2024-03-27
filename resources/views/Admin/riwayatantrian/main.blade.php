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
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			RIWAYAT ANTRIAN
		</h1>
	</section>

	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		<div class="box box-primary main-layer">
			<div class="panel panel-default">
				<div class="row" style="margin-top: 1rem; margin-left: 1rem;">
					<div class="col-md-2">
						<label for="namaCounter">Nama Counter</label>
						<select id="namaCounter" class="form-control" @if(Auth::user()->level != 1) readonly disabled @endif >
							<option value="">Pilih Counter</option>
							@foreach ($dataDropdownCounter as $item)
								<option value="{{$item->nama_konterpoli}}" @if(Auth::user()->name_user == $item->nama_konterpoli) selected @endif >{{$item->nama_konterpoli}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-10">
						<div class="row">
							<div class="pull-right">
								<div class="col-md-5">
									<label for="min">Tanggal Awal</label>
									<input type="date" id="min" class="form-control">
								</div>
								<div class="col-md-5">
									<label for="max">Tanggal Akhir</label>
									<input type="date" id="max" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<table id="dataTable" class="table table-striped dataTable" style="width: 100%;">
						<thead class="text-center">
							<tr>
								<th>No</th>
								<th>No Antrian</th>
								<th>Kd Booking</th>
                                <th>No RM</th>
                                <th class="text-center">Nama Pasien</th>
                                <th>Tanggal Daftar</th>
                                <!-- <th>Ambil Antrian</th>
                                <th>Tunggu Loket</th>
                                <th>Selesai Dilayani</th> -->
                                <th>Konter Poli</th>
                                <th>Metode Pendaftaran</th>
                                <th>Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="other-page"></div>
	</div>
	<div class='clearfix'></div>
@stop
@section('script')
<script type="text/javascript">
	//indikator playsound
	let ind = 0;
	//filter date
	var minDate, maxDate;

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

		//initial run
		loadTable($("#namaCounter").val(), today , today);
		filterTableByCounter();
	});

	function loadTable(namaCounter = null,tglAwal = null,tglAkhir = null){
		var loading = '<div class="loader" id="loader-4"><span></span><span></span><span></span></div>'
		var url = "{{route('riwayat-antrian-list')}}";
		var x = $('#dataTable').DataTable({
			scrollX: true,
			bPaginate: true,
			bFilter: true,
			processing: true,
			serverSide: true,
			language: {
				processing: loading,
			},
			search: {
				caseInsensitive: true
			},
			columnDefs: [{
				orderable: false,
				targets: -1
			}],
			ajax: {
				url:url,
				type: 'post',
				data: {
					namaCounter : namaCounter,
					tglAwal : tglAwal,
					tglAkhir : tglAkhir
				}
			},
			columns: [
				{data: 'DT_Row_Index', name: 'DT_Row_Index'},
				{data: 'noAntrian', name: 'noAntrian'},
				{data: 'kode_booking', name: 'kode_booking'},
				{
					data: 'RM', 
					name: 'no_rm',
				},
				{
					data: 'NamaCust', 
					name: 'tm_customer.NamaCust',
				},
				{
					data: 'tgl_periksa', 
					name: 'tgl_periksa',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + ((data=='' || data==null) ? '-' : data) + '</p>';
					}
				},
				// {
				// 	data: 'antrian_tracer.time', 
				// 	name: 'Ambil Antrian',
				// 	render: function(data, type, row) {
				// 		return '<p style="color:black" class="text-center">' + ((data == undefined) ? "-" : data) + '</p>';
				// 	}
				// },
				// {
				// 	data: 'antrian_tracer.time', 
				// 	name: 'Tunggu Loket',
				// 	render: function(data, type, row) {
				// 		return '<p style="color:black" class="text-center">' + ((data == undefined) ? "-" : data) + '</p>';
				// 	}
				// },
				// {
				// 	data: 'selesai_dilayani', 
				// 	name: 'Selesai Dilayani',
				// 	render: function(data, type, row) {
				// 		return '<p style="color:black" class="text-center">' + data + '</p>';
				// 	}
				// },
				{
					data: 'counter_poli', 
					name: 'Konter Poli',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + data + '</p>';
					}
				},
				{
					data: 'metode_ambil', 
					name: 'metode_ambil',
					render: function(data, type, row) {
						return '<p style="color:black" class="text-center">' + data + '</p>';
					}
				},
				{data: 'action', name: 'action'},
			],
		})
	}

	function filterTableByCounter() {
		$("#namaCounter").change(function (e) { 
			e.preventDefault();
			$('#dataTable').DataTable().destroy()
			loadTable( $(this).val(), $("#min").val(), $("#max").val() );
		});

		$("#min").change(function (e) { 
			e.preventDefault();
			$('#dataTable').DataTable().destroy()
			loadTable( $("#namaCounter").val() , $(this).val() , $("#max").val() );
		});

		$("#max").change(function (e) { 
			e.preventDefault();
			$('#dataTable').DataTable().destroy()
			loadTable( $("#namaCounter").val(), $("#min").val() , $(this).val() );
		});
	}

	//function sound panggil antrian
	function nomorAntrian(noAntrian,time = 500){
		setTimeout(() => {
			//looping nomor antrian
			if(ind < noAntrian.length){
				let path = "{!! asset('aset/sound/Pemanggilan/Antrian/"+noAntrian[ind]+".mp3') !!}";
				new Audio(path).play();
				nomorAntrian(noAntrian,1250);
			}
			//panggil tujuan
			if( ind == noAntrian.length){
				let path = "{!! asset('aset/sound/Pemanggilan/menujukeloket.mp3') !!}";
				new Audio(path).play();
				ind = 0;
				$(".btnPanggil").attr('disabled',false);
				return;
			}
			ind++;
		}, time);
	}

	function panggil(noAntrian) {
		$(".btnPanggil").attr('disabled',true);
		let no_antrian = noAntrian;
		//penggil sound pemanggilan
		let path = "{!! asset('aset/sound/Pemanggilan/nomorantrian.mp3') !!}";
		new Audio(path).play();
		//panggil nomor dan tujuan
		setTimeout(() => {
			nomorAntrian(no_antrian,1000);
		}, 500);
	}

	function detail(id) {
		console.log(id)
		$('.main-layer').hide();
		var url = "{{route('kerjakanAntrian')}}";
		$.post(url,{id:id, view:1}).done(function(data){
			if(data.status == 'success'){
				$('.preloader').hide();
				$('.other-page').html(data.content).fadeIn();
			}else{
				$('.main-layer').show();
			}
		})
	}

</script>
@stop