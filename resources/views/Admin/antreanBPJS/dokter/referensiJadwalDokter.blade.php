@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
		.content-header > h1 {
			text-align: center !important;
		}
		.small-box {
			height: 150px;
		}
		.small-box>.small-box-footer {
			position: absolute !important;
			width: 100%;
			bottom: 0px;
		}
		.icon {
			top: 10px !important;
		}
		.bg-purple {
			background: #932ab6 !important;
		}
		.bg-maroon {
			background: #85144b !important;
		}
		.bg-red {
			background: #f56954 !important;
		}
	</style>
@stop

@section('content')
	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		<div  class="box box-default main-layer">
			{{-- <h4 class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='margin-top: 5px;'>Pilih Tanggal</h4> --}}
			<div style="padding: 15px;">
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5">
					<h4>Pilih Tanggal</h4>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5">
					<input type="date" name="start" class="form-control" value="{!! date('Y-m-d') !!}" id="start">
				</div>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 ">
					{{-- {{dd($poli)}} --}}
					{{-- @foreach($poli as $key => $val)
					{{$val->nmpoli}}
					@endforeach --}}
					<select name="poli" class="form-control" id="jenis">
						<option value="all">--Pilih Poli--</option>
						@foreach($poli as $key => $val)
							<option value="{{$val->kdpoli}},{{$val->kdsubspesialis}}">({{$val->nmpoli}}) || {{$val->nmsubspesialis}}</option>
						@endforeach
					</select>
				</div>
				<input type="hidden" name="kategori" class="kategori" value="rj"> &nbsp;
				<a href="javascript:void(0)" class="btn-result btn btn-primary">check</a>
			</div>
			<div class='clearfix' style="margin-bottom: 5px"></div>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div  class="box box-default main-layer">
			<center>
				<h4 class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='margin-top: 0px;'>Referensi Jadwal Dokter</h4>
				<span id="tgltabel">dd/MM/YYYY</span>
			</center>
			<div style="padding: 15px; min-height: 450px;" class="paneltabel">
				<table class="table">
					<thead>
						<th>No.</th>
						<th>Nama Dokter</th>
						<th>Hari</th>
						<th>Jadwal</th>
						<th>Poli</th>
						<th>Kapasitas Pasien</th>
						{{-- <th>Action</th> --}}
					</thead>
					<tbody id="resulttabel">
					</tbody>
				</table>
				<div id="loadingjuga" style="display: none;">
	        		<center style="margin-top: 100px;"><img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}"></center>
	        	</div>
			</div>
			<div class='clearfix'></div><br>
		</div>
	</div>
	<div class='clearfix'></div>

	{{-- <div class="modal fade" id="dokterModal">
		<div class="modal-dialog" style="width:90%;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" data-dismiss="modal"><span>&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<table width="100%" border="solid 1px">
								<thead class="text-center">
									<tr>
										<th rowspan="2" style="text-align:center;">Hari</th>
										<th rowspan="2" style="text-align:center;">Jadwal</th>
										<th rowspan="2" style="text-align:center; width: 10%;">Kapasitas Pasien</th>
										<th colspan="2" style="text-align:center;">Edit Jadwal</th>
									</tr>
									<tr>
										<th style="text-align:center;">Buka</th>
										<th style="text-align:center;">Tutup</th>
									</tr>
								</thead>
								<tbody id="dataDokter">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-danger" type="button" data-dismiss="modal">Tutup</button>
					<button class="btn btn-success btn-simpan">Simpan</button>
				</div>
			</div>
		</div>
	</div> --}}

@stop
@section('script')
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){
			// $('#dokterModal').modal({backdrop: 'static', keyboard: false})
		});

		$('.btn-result').click(function(){
			$('#container').hide()
			$('#resulttabel').empty()
			$('#loading').show()
			$('#loadingjuga').show()
			// $('#container').hide()
			var date = $('#start').val()
			var poli = $('#jenis').val()
			poli = poli.split(",")
			if(!date){
				$('#container').show()
				$('#resulttabel').show()
				$('#loading').hide()
				$('#loadingjuga').hide()
				swal('Perhatian','Tanggal tidak boleh kosong.','warning')
			}else if(poli=='all'){
				$('#container').show()
				$('#resulttabel').show()
				$('#loading').hide()
				$('#loadingjuga').hide()
				swal('Perhatian','Poli tidak boleh kosong.','warning')
			}else{
				$.ajax({
					url: "{{route('webRefJadDok')}}",
					type: "GET",
					data:{
						tanggal: date,
						kodePoli: poli[0]
					},
					success:function(res){
						var tab = '';
						if(res.metaData.code==200){
							if('response' in res){
									$.each(res.response,function(i,v){
									var kode = v.kodedokter
									var nama = v.namadokter
									var jdw = v.jadwal
									var hari = v.namahari
									var kapPas = v.kapasitaspasien
									tab +=`
										<tr>
											<td>${i+1}</td>
											<td>${v.namadokter}</td>
											<td>${v.namahari}</td>
											<td>${v.jadwal}</td>
											<td>${v.namapoli}</td>
											<td class="text-center">${v.kapasitaspasien}</td>
									`;
									// tab +="<td><a href='javascript:void(0)' onclick='editDokter(`"+kode+"#"+nama+"#"+hari+"#"+jdw+"#"+kapPas+"`)'><i class='fa fa-edit'></i></a></td></tr>";
								})
								$('#resulttabel').html(tab)
							}else{
								console.log('gada')
							}
						}else{
							swal('Perhatian','Jadwal Tidak Ditemukan','warning')
						}
						var formattedDate = new Date(date)
						var d = formattedDate.getDate()
						var m =  formattedDate.getMonth()
						m += 1 // Bulan dalam JavaScript => 0-11
						var y = formattedDate.getFullYear()
						resDate = (d<10?"0"+d:d)+"-"+(m<10?"0"+m:m)+"-"+y
						$('#tgltabel').html(resDate)
						$('#container').show()
						$('#resulttabel').show()
						$('#loadingjuga').hide()
					}
				})
			}
		})

		var editDokter = function editDokter(param) {
			var arr = param.split("#")
			var kode   = arr[0]
			var nama   = arr[1]
			var hari   = arr[2]
			var jdw    = arr[3]
			var kapPas = arr[4]
			var html = ''
			html += `
				<tr>
					<td>${hari}</td>
					<td>${jdw}</td>
					<td>
						${kapPas}
						<input type="text" name="buka" id="buka">
						<input type="text" name="tutup" id="tutup">
					</td>
				</tr>`;

			// $('#dataDokter').append(html)
			$('#myModalLabel').text(nama)
			$('#dokterModal').modal({backdrop: 'static', keyboard: false})
			// $('#myModalLabel').modal({backdrop: 'static', keyboard: false})
		}
	</script>
@stop