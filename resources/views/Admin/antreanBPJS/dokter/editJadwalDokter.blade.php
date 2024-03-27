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
		.btn-default.btn-on.active{
			background-color: #5BB75B;
			color: white;
		}
		.btn-default.btn-off.active{
			background-color: #DA4F49;
			color: white;
		}

		.select2-selection__rendered{
			margin-top: auto !important;
		}

		/*.select2-selection__arrow {
			margin: 10px;
		}*/
	</style>
@stop

@section('content')

	<form id="fSimpan">
		<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
			<div  class="box box-default main-layer">
				<div class="row">
					<div style="padding: 15px;">
						<div class="col-md-1 text-right">
							<h4>Dokter</h4>
						</div>
						<div class="col-md-3">
							<input type="hidden" name="status" value="" id="status">
							<select class="dokter form-control" id="dokter" name="dokter">
								<option value="first">--Pilih Dokter--</option>
								@foreach($dokter as $key => $val)
								<option value="{{$val->kodedokter}}">{{$val->namadokter}}</option>
								@endforeach
							</select>
							{{-- <select name="dokter" class="form-control" id="jenis">
							</select> --}}
						</div>
						<div class="col-md-1 text-right">
							<h4>Poli</h4>
						</div>
						<div class="col-md-3">
							<select name="poli" class="poli form-control" id="poli">
								<option value="first">--Pilih Poli--</option>
								@foreach($poli as $key => $val)
								<option value="{{$val->kdpoli}},{{$val->kdsubspesialis}}">({{$val->nmpoli}}) || {{$val->nmsubspesialis}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-2">
							<a href="javascript:void(0)" class="btn-result btn btn-primary">check</a>
						</div>
					</div>
					<div class='clearfix' style="margin-bottom: 5px"></div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="panelJawal" style="display: none;">
			<div  class="box box-default main-layer">
				<div style="padding: 15px; min-height: 450px;" class="paneltabel">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-3" style="margin-bottom:3rem;">
									<div class="btn-group" id="status" data-toggle="buttons">
										<label class="btn btn-default btn-on btn-xs" id="btn-on1">
											<input type="radio" id="1Hari1" onchange="cekHari('1',1)" value="1,1" name="hariOn1">
										ON</label>
										<label class="btn btn-default btn-off btn-xs active" id="btn-off1">
											<input type="radio" id="1Hari0" onchange="cekHari('1',0)" value="1,0" name="hariOff1" checked="checked">
										OFF</label>
									</div>&nbsp;
									<label>Senin</label>
									<div class="row" style="margin-top:0.7rem;">
										<div class="col-sm-12">
											<label>Buka</label>
											<input type="time" id="1Buka" name="buka1" class="form-control" readonly>
											<label style="margin-top:0.7rem;">Tutup</label>
											<input type="time" id="1Tutup" name="tutup1" class="form-control" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-3" style="margin-bottom:3rem;">
									<div class="btn-group" id="status" data-toggle="buttons">
										<label class="btn btn-default btn-on btn-xs" id="btn-on2">
											<input type="radio" id="2Hari1" onchange="cekHari('2',1)" value="2,1" name="hariOn2">
										ON</label>
										<label class="btn btn-default btn-off btn-xs active" id="btn-off2">
											<input type="radio" id="2Hari0" onchange="cekHari('2',0)" value="2,0" name="hariOff2" checked="checked">
										OFF</label>
									</div>&nbsp;
									<label>Selasa</label>
									<div class="row" style="margin-top:0.7rem;">
										<div class="col-sm-12">
											<label>Buka</label>
											<input type="time" id="2Buka" name="buka2" class="form-control" readonly>
											<label style="margin-top:0.7rem;">Tutup</label>
											<input type="time" id="2Tutup" name="tutup2" class="form-control" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-3" style="margin-bottom:3rem;">
									<div class="btn-group" id="status" data-toggle="buttons">
										<label class="btn btn-default btn-on btn-xs" id="btn-on3">
											<input type="radio" id="3Hari1" onchange="cekHari('3',1)" value="3,1" name="hariOn3">
										ON</label>
										<label class="btn btn-default btn-off btn-xs active" id="btn-off3">
											<input type="radio" id="3Hari0" onchange="cekHari('3',0)" value="3,0" name="hariOff3" checked="checked">
										OFF</label>
									</div>&nbsp;
									<label>Rabu</label>
									<div class="row" style="margin-top:0.7rem;">
										<div class="col-sm-12">
											<label>Buka</label>
											<input type="time" id="3Buka" name="buka3" class="form-control" readonly>
											<label style="margin-top:0.7rem;">Tutup</label>
											<input type="time" id="3Tutup" name="tutup3" class="form-control" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="btn-group" id="status" data-toggle="buttons">
										<label class="btn btn-default btn-on btn-xs" id="btn-on4">
											<input type="radio" id="4Hari1" onchange="cekHari('4',1)" value="4,1" name="hariOn4">
										ON</label>
										<label class="btn btn-default btn-off btn-xs active" id="btn-off4">
											<input type="radio" id="4Hari0" onchange="cekHari('4',0)" value="4,0" name="hariOff4" checked="checked">
										OFF</label>
									</div>&nbsp;
									<label>Kamis</label>
									<div class="row" style="margin-top:0.7rem;">
										<div class="col-sm-12">
											<label>Buka</label>
											<input type="time" id="4Buka" name="buka4" class="form-control" readonly>
											<label style="margin-top:0.7rem;">Tutup</label>
											<input type="time" id="4Tutup" name="tutup4" class="form-control" readonly>
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="margin-top:3rem;">
								<div class="col-md-3" style="margin-bottom:3rem;">
									<div class="btn-group" id="status" data-toggle="buttons">
										<label class="btn btn-default btn-on btn-xs" id="btn-on5">
											<input type="radio" id="5Hari1" onchange="cekHari('5',1)" value="5,1" name="hariOn5">
										ON</label>
										<label class="btn btn-default btn-off btn-xs active" id="btn-off5">
											<input type="radio" id="5Hari0" onchange="cekHari('5',0)" value="5,0" name="hariOff5" checked="checked">
										OFF</label>
									</div>&nbsp;
									<label>Jumat</label>
									<div class="row" style="margin-top:0.7rem;">
										<div class="col-sm-12">
											<label>Buka</label>
											<input type="time" id="5Buka" name="buka5" class="form-control" readonly>
											<label style="margin-top:0.7rem;">Tutup</label>
											<input type="time" id="5Tutup" name="tutup5" class="form-control" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-3" style="margin-bottom:3rem;">
									<div class="btn-group" id="status" data-toggle="buttons">
										<label class="btn btn-default btn-on btn-xs" id="btn-on6">
											<input type="radio" id="6Hari1" onchange="cekHari('6',1)" value="6,1" name="hariOn6">
										ON</label>
										<label class="btn btn-default btn-off btn-xs active" id="btn-off6">
											<input type="radio" id="6Hari0" onchange="cekHari('6',0)" value="6,0" name="hariOff6" checked="checked">
										OFF</label>
									</div>&nbsp;
									<label>Sabtu</label>
									<div class="row" style="margin-top:0.7rem;">
										<div class="col-sm-12">
											<label>Buka</label>
											<input type="time" id="6Buka" name="buka6" class="form-control" readonly>
											<label style="margin-top:0.7rem;">Tutup</label>
											<input type="time" id="6Tutup" name="tutup6" class="form-control" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-3" style="margin-bottom:3rem;">
									<div class="btn-group" id="status" data-toggle="buttons">
										<label class="btn btn-default btn-on btn-xs" id="btn-on7">
											<input type="radio" id="7Hari1" onchange="cekHari('7',1)" value="7,1" name="hariOn7">
										ON</label>
										<label class="btn btn-default btn-off btn-xs active" id="btn-off7">
											<input type="radio" id="7Hari0" onchange="cekHari('7',0)" value="7,0" name="hariOff7" checked="checked">
										OFF</label>
									</div>&nbsp;
									<label>Minggu</label>
									<div class="row" style="margin-top:0.7rem;">
										<div class="col-sm-12">
											<label>Buka</label>
											<input type="time" id="7Buka" name="buka7" class="form-control" readonly>
											<label style="margin-top:0.7rem;">Tutup</label>
											<input type="time" id="7Tutup" name="tutup7" class="form-control" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="btn-group" id="status" data-toggle="buttons">
										<label class="btn btn-default btn-on btn-xs" id="btn-on8">
											<input type="radio" id="8Hari1" onchange="cekHari('8',1)" value="8,1" name="hariOn8">
										ON</label>
										<label class="btn btn-default btn-off btn-xs active" id="btn-off8">
											<input type="radio" id="8Hari0" onchange="cekHari('8',0)" value="8,0" name="hariOff8" checked="checked">
										OFF</label>
									</div>&nbsp;
									<label>Libur Nasional</label>
									<div class="row" style="margin-top:0.7rem;">
										<div class="col-sm-12">
											<label>Buka</label>
											<input type="time" id="8Buka" name="buka8" class="form-control" readonly>
											<label style="margin-top:0.7rem;">Tutup</label>
											<input type="time" id="8Tutup" name="tutup8" class="form-control" readonly>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-right btn-up-show">
									<button class="btn btn-sm btn-success btn-update">Update</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class='clearfix'></div><br>
			</div>
		</div>
		<div class='clearfix'></div>
	</form>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
@stop
@section('script')
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){
			$.fn.select2.defaults.set("theme", "bootstrap");
			$(".dokter").select2({
				width: null
			})
			$(".poli").select2({
				width: null
			})
			// $('.js-example-basic-single').select2();
			// $("#8Hari1").attr("checked",true)
			// $("#8Hari0").attr("checked",false)
			// $('#dokterModal').modal({backdrop: 'static', keyboard: false})
		});

		$('.btn-result').click(function(){
			var kode = $('#dokter').val()
			var poli = $('#poli').val()
			poli = poli.split(",")
			if(kode=='first'){
				swal('Perhatian','Dokter tidak boleh kosong.','warning')
			}else if(poli[0]=='first'){
				swal('Perhatian','Poli tidak boleh kosong.','warning')
			}else{
				$.ajax({
					url: "{{route('getJadDok')}}",
					type: "post",
					data:{
						kode: kode,
						poli: poli
					},
					success:function(res){
						if(res.code==200){
							swal({
								type: 'success',
								title: 'Berhasil',
								showConfirmButton: false,
								timer: 1200
							})
							var data = res.dokter
							var buka = [
								data.seninBuka,
								data.selasaBuka,
								data.rabuBuka,
								data.kamisBuka,
								data.jumatBuka,
								data.sabtuBuka,
								data.mingguBuka,
								data.liburNasionalBuka
							]
							var tutup = [
								data.seninTutup,
								data.selasaTutup,
								data.rabuTutup,
								data.kamisTutup,
								data.jumatTutup,
								data.sabtuTutup,
								data.mingguTutup,
								data.liburNasionalTutup
							]
							for(var i = 1; i <= 8; i++){
								$('#btn-on'+i).removeClass('active')//set button
								$('#btn-off'+i).addClass('active')//set button

								$('#'+i+'Hari1').prop('checked',false)// set radio button
								$('#'+i+'Hari0').prop('checked',true)// set radio button

								$('#'+i+'Buka').prop('readonly',true)
								$('#'+i+'Tutup').prop('readonly',true)

								$('#'+i+'Buka').val(buka[i-1])
								$('#'+i+'Tutup').val(tutup[i-1])
							}
						}else{
							swal({
								type: 'warning',
								title: 'Perhatian',
								text: 'Data tidak ditemukan.',
								showConfirmButton: true,
								// timer: 1400
							})
							var cekHariOn = [];
							for(var i = 1; i <= 8; i++){
								if($('#'+i+'Hari1:checked').length==1){
									cekHariOn.push('1')
								}
							}
							if(cekHariOn.length==0){
								console.log(cekHariOn)
								for(var i = 1; i <= 8; i++){
									$('#btn-on'+i).removeClass('active')
									$('#btn-off'+i).addClass('active')

									$('#'+i+'Hari1').prop('checked',false)
									$('#'+i+'Hari0').prop('checked',true)

									$('#'+i+'Buka').val('')
									$('#'+i+'Tutup').val('')

									$('#'+i+'Buka').prop('readonly',true)
									$('#'+i+'Tutup').prop('readonly',true)
								}
							}
						}
						$('.btn-update').prop('disabled',false)
						$('#panelJawal').show()
						$('#status').val(res.code)
					}
				})
			}
		})

		$('#dokter').change(function(e){
			$('#status').val('')
			// 	var kode = $(this).val()
			// 	if(kode!='first'){
			// 		$.post("{{route('getJadDok')}}",{kode:kode}).then((res)=>{
			// 			if(res.code==200){

			// 			}else{
			// 			}
			// 				$('#status').val(res.code)
			// 				$('#status').val(res.code)
			// 		})
			// 	}
		});

		$('.btn-update').click(function(e){
			e.preventDefault()
			var dokter = $('#dokter').val()
			var poli = $('#poli').val()
			var status = $('#status').val()
			var form = new FormData($('#fSimpan')[0])
			// $('.btn-update').prop('disabled',true)
			if(dokter=='first'){
				$('.btn-update').prop('disabled',false)
				swal('Perhatian','Pilih Dokter Terlebih Dahulu','warning')
			}else if(poli=='first'){
				$('.btn-update').prop('disabled',false)
				swal('Perhatian','Pilih Poli Terlebih Dahulu','warning')
			}else if(!status){
				$('.btn-update').prop('disabled',false)
				// swal('Perhatian','Cek Dokter Terlebih Dahulu.','warning')
				swal('Perhatian','Klik tombol check Terlebih Dahulu.','warning')
			}else{
				$.ajax({
					url: "{{route('editJadDok')}}",
					type: 'POST',
					data: form,
					async: true,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data){
						if('metaData' in data){
							if(data.metaData.code==200){
								$('#dokter').val('first').trigger('change')
								$('#poli').val('first').trigger('change')
								$('#status').val('')
								swal({
									type: 'success',
									title: 'Berhasil',
									text: data.metaData.message,
									timer: 1500,
									showConfirmButton: false
								})
								for(var i = 1; i <= 8; i++) {
									$('#btn-on'+i).removeClass('active')
									$('#btn-off'+i).addClass('active')

									$('#'+i+'Hari1').prop('checked',false)
									$('#'+i+'Hari0').prop('checked',true)

									$('#'+i+'Buka').val('')
									$('#'+i+'Tutup').val('')

									$('#'+i+'Buka').prop('readonly',true)
									$('#'+i+'Tutup').prop('readonly',true)
								}
							}else{
								$('.btn-update').prop('disabled',false)
								swal({
									type: 'warning',
									title: 'Perhatian',
									text: data.metaData.message,
									showConfirmButton: true
								})
							}
						}else{
							swal({
								type: 'warning',
								title: 'Perhatian',
								text: 'Terjadi kesalahan sistem silahkan hubungi admin',
								showConfirmButton: true
							})
						}
					}
				});
			}
			// swal({
				// 	title: "Perhatian",
				// 	text: "Pastikan data sudah benar sebelum dikirim.",
				// 	type: "warning",
				// 	showCancelButton: true,
				// 	confirmButtonColor: '#61d75f',
				// 	confirmButtonText: 'Benar, Kirim!',
				// 	cancelButtonText: "Cek Data!",
				// 	closeOnConfirm: false,
				// 	closeOnCancel: true
				// },function(res){
				// 	if(res){
				// 		$.ajax({
				// 			url: "{{route('editJadDok')}}",
				// 			type: "get",
				// 			data: id,
				// 			success:function(res){
				// 				console.log(res)
				// 			}
				// 		})
				// 		swal({
				// 			title: "Success",
				// 			text: "Data berhasil disimpan.",
				// 			type: "success",
				// 			showConfirmButton: false,
				// 			timer: 1200
				// 		})
				// 	}
				// })
		})

		function cekHari(hari,buka){
			var dokter = $('#dokter').val()
			var poli = $('#poli').val()
			if(dokter!='first' && poli!='first'){
				if(buka==1){
					$("#"+hari+"Hari1").prop("checked",true)
					$("#"+hari+"Hari0").prop("checked",false)
					$("#"+hari+"Buka").attr("readonly",false)
					$("#"+hari+"Tutup").attr("readonly",false)
					// console.log($('#'+hari+'Hari'+buka).val())

				}else{
					// console.log($('#'+hari+'Hari'+buka).val())
					$("#"+hari+"Hari1").prop("checked",false)
					$("#"+hari+"Hari0").prop("checked",true)
					$("#"+hari+"Buka").attr("readonly",true)
					$("#"+hari+"Tutup").attr("readonly",true)
					// $("#"+hari+"Buka").val('')
					// $("#"+hari+"Tutup").val('')
				}
			}
		}
	</script>
@stop