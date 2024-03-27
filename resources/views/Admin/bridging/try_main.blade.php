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
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			BRIDGING BPJS
			<!-- <small>Control panel</small> -->
		</h1>
	</section>
	<div style="width: 50%; float: left;">
		<div class="content">
			<div style="width: 100%;">
				<div class="box box-default main-layer">
				<form id="insert-sep">					
					<div class="box-header">
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">NO. Registrasi BPJS</label>
							<label class="label-control col-lg-9 col-md-8">&nbsp;</label>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
								<?php // date_default_timezone_set('UTC') ?>
							<label class="label-control col-lg-3 col-md-3">Tanggal</label>
							<div class="col-lg-6 col-md-7">
								<input type="date" name="Tanggal" class="form-control" value="{!! date('Y-m-d') !!}" readonly>
							</div>
						</div>
						<div class="clearfix"></div><br>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-3">Jam</label>
							<div class="col-lg-6 col-md-7">
								<input type="time" name="jam" class="form-control" value="{!! date('H:i:s') !!}" id="clockDisplay" readonly> </div>
						</div>
					</div><hr>
					<div class="box-main">
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">&nbsp;</label>
							<div class="col-lg-6 col-md-6">
								<input type="checkbox" name="karyawan" class="" value="Y" id="karyawan">
								<label for="karyawan">Karyawan</label>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">&nbsp;</label>
							<div class="col-lg-6 col-md-8">
								<input type="radio" name="rawat" class="" value="2" id="jalan" checked="">
								<label for="jalan">Rawat Jalan</label>&nbsp;
								<input type="radio" name="rawat" class="" value="1" id="inap">
								<label for="inap">Rawat Inap</label>
								<input type="hidden" name="kelasrawat" value="" id="kelasrawat">
								<br>
								<input type="radio" name="laka" class="" value="1" id="laka">
								<label for="laka">Laka Lantas</label>&nbsp;
								<input type="radio" name="laka" class="" value="0" id="tidaklaka" checked="true">
								<label for="tidaklaka">Tidak Laka Lantas</label>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="box-main">
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">No. Kepersertaan</label>
							<div class="col-lg-6 col-md-6">
								<input type="text" name="nokartu" class="form-control" placeholder="Nomor peserta BPJS" id="nokepesertaan">
							</div>
							<button class="col-md-1 btn btn-primary btn-cari-peserta" type="button">:</button>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Tanggal SEP</label>
							<div class="col-lg-6 col-md-6">	
								<input type="date" name="tgl_sep" class="form-control" id="clockDisplaysep" value="{!! date('Y-m-d') !!}">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Tanggal Rujukan</label>
							<div class="col-lg-6 col-md-6">
								<input type="date" name="tgl_rujukan" class="form-control" id="clockDisplaysep" value="{!! date('Y-m-d') !!}">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">PPK Rujukan </label>
							<div class="col-lg-6 col-md-6">
								<input type="text" name="ppk_rujukan" class="form-control" placeholder="kode fakses rujukan" id="ppk-rujukan">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">No. Rujukan</label>
							<div class="col-lg-6 col-md-6">
								<input type="text" name="no_rujukan" class="form-control" placeholder="Nomor Rujukan" id="no_rujukan">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Diagnosa</label>
							<div class="col-lg-3 col-md-3">
								<input type="text" name="diagnosa" class="form-control" placeholder="Kode Diagnosa Awal" id='valDiagnosa'>
							</div>
							<button class="col-md-1 btn btn-primary btn-cari-diagnosa" type="button">:</button>
							<span class="col-lg-5" id='textDiagnosa' style="font-style: italic;color: #444;"></span>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Jenis Pasien</label>
							<div class="col-lg-6 col-md-6">
								<select name="jenisPeserta" class="form-control">
									@foreach($data['jenispasien'] as $asu)
										<option value="{!! $asu->subgroups !!}">{!! $asu->nilaichar !!}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Poli Tujuan</label>
							<div class="col-lg-2 col-md-3">
								<input type="text" name="poli" class="form-control" placeholder="Kd Poli" id="kdpoli">
							</div>
							<button class="col-md-1 btn btn-primary btn-cari-poli" type="button">:</button>
							<span class="col-lg-6" id="namapoli" ></span>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">No. RM</label>
							<div class="col-lg-6 col-md-6">
								<input type="text" name="no_rm" class="form-control" placeholder="Nomor rekam Medis" id="rm">
							</div>
							<button class="col-md-1 btn btn-primary btn-cari-rm" type="button">:</button>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Nama Pasien</label>
							<div class="col-lg-6 col-md-6">
								<input type="text" name="nama" class="form-control" placeholder="Nama Pasien" id="namapasien">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Umur</label>
							<div class="col-lg-6 col-md-6">
								<input type="text" name="umur" class="form-control" placeholder="Umur Pasien" id="umur">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Tanggal Lahir</label>
							<div class="col-lg-6 col-md-6">
								<input type="date" name="tgl_lahir" class="form-control" id="tgllahir" placeholder="Tanggal Lahir Pasien">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Jenis Kelamin</label>
							<div class="col-lg-6 col-md-6">
								<input type="radio" name="sex" value="L" id='L'><label for='L'>Laki-laki</label>&nbsp;
								<input type="radio" name="sex" value="P" id='P'><label for='P'>Perempuan</label>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Alamat</label>
							<div class="col-lg-9 col-md-8">
								<input type="text" name="alamat" class="form-control" placeholder="Alamat Pasien" id="alamatpasien">
								<input type="hidden" name="cob" id="cob">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="label-control col-lg-3 col-md-4">Catatan</label>
							<div class="col-lg-9 col-md-8">
								<input type="text" name="catatan" class="form-control" placeholder="Catatan" value="-" id="catatan">
							</div>
						</div>
						<div class="clearfix"></div>
						<div  id="lokasi_laka">
							<div class="form-group">
								<label class="label-control col-lg-3 col-md-4">Lokasi Laka</label>
								<div class="col-lg-9 col-md-80">
									<input type="text" name="lokasi" class="form-control" placeholder="Lokasi Laka" id="lokasi">
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
								<label class="label-control col-lg-3 col-md-4">Pejamin</label>
								<div class="col-lg-9 col-md-80">
									<input type="checkbox" name="penjamin[]" value="1" id="p1"> <label for = "p1">PT. JASA RAHARJA</label>
									<input type="checkbox" name="penjamin[]" value="2" id="p2" checked=""> <label for = "p2">BPJS</label>
									<input type="checkbox" name="penjamin[]" value="3" id="p3"> <label for = "p3">TASPEN</label>
									<input type="checkbox" name="penjamin[]" value="4" id="p4">  <label for = "p4">PT ASBRI</label>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<center style='margin-top: 15px'>
							<a href='javascript:void(0)' class="btn btn-lg btn-primary" id='btn-sep'><i class="fa fa-save"></i> SIMPAN</a>
							<!-- <button type='button' class="btn btn-lg btn-success" id='btn-print-sep' disabled=""><i class="fa fa-print"></i> Cetak</button> -->
							<button type='button' class="btn btn-lg btn-success" id='btn-print-sep' ><i class="fa fa-print"></i> Cetak</button>
							<input type="hidden" name="nosepcetak" id="nosepcetak" valu="1320R00109150002088">
							<input type="hidden" name="noarsip" id="noarsip">
						</center>
						<br>
					</div>
					<div class="box-footer"></div>
				</form>
				</div>
			</div>
		</div>
	</div>
	<div style="width: 50%; float: left; ">
		<div class="content">
			<div  style="width: 100%;">
				<div class="box box-default main-layer">
					<div class='panel-label_header col-lg-12 col-md-12 col-sm-12 col-xs-12' style="margin-top:0px; margin-bottom: 15px; ">
						<h3>Data Pasien BPJS</h3>
					</div>
					<hr><form>
					<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">	
						<table class="table table-striped">
							<tr>
								<td width="200px;">Nama</td>
								<td>: <span id="nama_bpjs"></span></td>
							</tr>
							<tr>
								<td>Jenis Kelamin</td>
								<td>: <span id="sex_bpjs"></span></td>
							</tr>
							<tr>
								<td>Tanggal Lahir</td>
								<td>: <span id="tgl_bpjs"></span></td>
							</tr>
							<tr>
								<td>Kode Provider</td>
								<td>: <span id="kdprovbpjs"></span></td>
							</tr>
							<tr>
								<td>Nama Provider</td>
								<td>: <span id="nmprovbpjs"></span></td>
							</tr>
							<tr>
								<td>Kode Cabang</td>
								<td>: <span id="kdcb_bpjs"></span></td>
							</tr>
							<tr>
								<td>Nama Cabang</td>
								<td>: <span id="nmcb_bpjs"></span></td>
							</tr>
							<tr>
								<td>Jenis Peseta</td>
								<td>: <span id="jnps_bpjs"></span></td>
							</tr>
							<tr>
								<td>Kelas Tanggungan</td>
								<td>: <span id="kltg_bpjs"></span>

									<span id="tampilstatus"></span></td>
							</tr>
							<tr>
								<td>Status</td>
								<td>: <span class="sta"></span></td>
							</tr>
						</table>
					</div>
					<hr>
					<div class='clearfix'></div>
				</div>
			</div>
		
			<div  style="width: 100%;">
				<div class="box box-default main-layer" style="max-width: 100%; overflow: auto;">
					<div style="width: 1770px">
						<table border='1' class="history">
							<thead>
								<tr style="background: #ccc">
									<th width="20" style="padding-left: 5px;">&nbsp</th>
									<th width="200" style="padding-left: 5px;">Nama Pasien</th>
									<th width="50" style="padding-left: 5px;" id="">Tipe</th>
									<th width="300" style="padding-left: 5px;">Nama Unit</th>
									<th width="100" style="padding-left: 5px;">No Daftar</th>
									<th width="150" style="padding-left: 5px;">Tgl Masuk</th>
									<th width="200" style="padding-left: 5px;">No. SEP</th> 
									<!-- <th width="200" style="padding-left: 5px;">Tabel Lanjut</th>
									<th width="200" style="padding-left: 5px;">Tabel Lanjut</th>
									<th width="200" style="padding-left: 5px;">Tabel Lanjut</th>
									<th width="200" style="padding-left: 5px;">Tabel Lanjut</th> -->
								</tr>
							</thead>
							<tbody id="resulthistory">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="modal-dialog"></div>
	<div class="printSEP"></div>
	<div id="updatetglpulang"></div>
@stop

@section('script')
<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	$('#lokasi_laka').hide();
    });

    //Cek No. BPJS
    $('#nokepesertaan').change(function () {
    	var no =  $('#nokepesertaan').val();

        $.post("{!! route('cekpeserta') !!}",{nobpjs:no}).done(function(result){
	        if(result.response){
	        	if(result.response.peserta.statusPeserta.keterangan != "AKTIF"){
	        		swal("Peringatan",result.response.peserta.statusPeserta.keterangan,"warning");
	        	}else{
		        	var kdcb = (result.response.peserta.provUmum.kdCabang) ? result.response.peserta.provUmum.kdCabang : '';
		    		var nmcb = (result.response.peserta.provUmum.nmCabang) ? result.response.peserta.provUmum.nmCabang : '';
		        	$('#tampilstatus').html(result.response.peserta.statusPeserta.keterangan);
		        	$('#nama_bpjs').html(result.response.peserta.nama);
		        	$('#sex_bpjs').html(result.response.peserta.sex);
		        	$('#tgl_bpjs').html(result.response.peserta.tglLahir);
		        	$('#kdprov_bpjs').html(result.response.peserta.provUmum.kdProvider);
		        	$('#nmprov_bpjs').html(result.response.peserta.provUmum.nmProvider);
		        	$('#kdcb_bpjs').html(kdcb);
		        	$('#nmcb_bpjs').html(nmcb);
		        	$('#jnps_bpjs').html(result.response.peserta.jenisPeserta.keterangan);
		        	$('#kltg_bpjs').html(result.response.peserta.hakKelas.keterangan);
		        }
	        }else{
	        	$('#nama_bpjs').html('');
	        	$('#sex_bpjs').html('');
	        	$('#tgl_bpjs').html('');
	        	$('#kdprov_bpjs').html('');
	        	$('#nmprov_bpjs').html('');
	        	$('#kdcb_bpjs').html('');
	        	$('#nmcb_bpjs').html('');
	        	$('#jnps_bpjs').html('');
	        	$('#kltg_bpjs').html('');
	        }
        });
	});

	$('.btn-cari-peserta').click(function(){
		$.post("{!! route('carifromrs') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('.btn-cari-poli').click(function(){
		$.post("{!! route('cariformpolirs') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('.btn-cari-diagnosa').click(function(){
		$.post("{!! route('carifromdiagnosars') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('.btn-cari-rm').click(function(){
		$.post("{!! route('cariformrmrs') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('#laka').click(function(){
		$('#lokasi_laka').show();
	});

	$('#tidaklaka').click(function(){
		$('#lokasi_laka').hide();
		$('#lokasi').val('');
	});

	$('#btn-print-sep').click(function(e){
		e.preventDefault();
		var nosep = $('#nosepcetak').val();
		var noarsip = $('#noarsip').val();
		$.post("{{ route('cetak_sep') }}",{nosep:nosep, noarsip:noarsip}).done(function(data){
			if(data.status == 'success'){
				// var cetak = '';
				// cetak += '<table border="0">';
				// cetak += '<tr>';
				// cetak += '<td height="38" colspan="4">&nbsp</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td height="20px" width="340px" style="font-size: 16px">'+data.data.sepValue["no_sep"]+'</td>';
				// cetak += '<td height="20px" style="font-size: 16px">NO Berkas </td>';
				// cetak += '<td height="20px" style="font-size: 16px">: '+data.data.sepValue["noarsip"]+'</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td colspan="3" height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["tgl_sep"]+'</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td colspan="3" height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["no_kartu"]+'</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td colspan="3" height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["nama_kartu"]+'</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td colspan="3" height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["tgl_lahir"]+'</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td colspan="3" height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["jenis_kelamin"]+'</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td colspan="3" height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["poli_tujuan"]+'</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td width="440px" colspan="2" height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["diagnosa"]+'</td>';
				// cetak += '<td height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["jenis_rawat"]+'</td>';
				// cetak += '</tr>';
				// cetak += '<tr>';
				// cetak += '<td width="125px" height="20px">&nbsp</td>';
				// cetak += '<td width="440px" colspan="2" height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["catatan"]+'</td>';
				// cetak += '<td height="20px" style="font-size: 16px;line-height: 23px">'+data.data.sepValue["kls_rawat"]+'</td>';
				// cetak += '</tr>';
				// cetak += '</table>';
				var setFont = 'style="font-size: 20px;line-height: 23px"';
				var ukFont = 'style="font-size:20px;"';
				var cetak = '';
				cetak += '<table border="0">';
				cetak += '<tr>';
				cetak += '<td height="10" colspan="4">&nbsp</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				cetak += '<td height="20px" width="550px" style="font-size: 25px;line-height: 23px">'+data.data.sepValue["no_sep"]+'</td>';
				cetak += '<td height="20px" '+ukFont+'>NO Berkas </td>';
				cetak += '<td height="20px" '+ukFont+'>: '+data.data.sepValue["noarsip"]+'</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				cetak += '<td colspan="3" height="20px" '+setFont+'>'+data.data.sepValue["tgl_sep"]+'</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				cetak += '<td colspan="3" height="20px" '+setFont+'>'+data.data.sepValue["no_kartu"]+'</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				cetak += '<td colspan="3" height="20px" '+setFont+'>'+data.data.sepValue["nama_kartu"]+'</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				cetak += '<td colspan="3" height="20px" '+setFont+'>'+data.data.sepValue["tgl_lahir"]+'</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				if (data.data.sepValue["jenis_kelamin"] == "L") {
					var JKlamin = 'LAKI - LAKI';
				}else{
					var JKlamin = 'PEREMPUAN'
				}
				cetak += '<td colspan="3" height="20px" '+setFont+'>'+JKlamin+'</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				cetak += '<td colspan="3" height="20px" '+setFont+'>'+data.data.sepValue["poli_tujuan"]+'</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				cetak += '<td width="760px" colspan="2" height="20px" '+setFont+'>'+data.data.sepValue["diagnosa"]+'</td>';
				cetak += '<td height="20px" '+setFont+'>'+data.data.sepValue["jenis_rawat"]+'</td>';
				cetak += '</tr>';
				cetak += '<tr>';
				cetak += '<td width="185px" height="20px">&nbsp</td>';
				cetak += '<td width="760px" colspan="2" height="20px" '+setFont+'>'+data.data.sepValue["catatan"]+'</td>';
				cetak += '<td height="20px" '+setFont+'>'+data.data.sepValue["kls_rawat"]+'</td>';
				cetak += '</tr>';
				cetak += '</table>';
				$('.printSEP').html(cetak);
				$('.printSEP').printArea();
				setTimeout(function tutupCetak() {
					$('.printSEP').empty();

					var tgl = new Date( month, day, year);
						$('#karyawan').removeAttr('checked');
						$('#inap').removeAttr('checked');
						$('#laka').removeAttr('checked');
						$('#jalan').attr('checked','checked');
						$('#tidaklaka').attr('checked','checked');
						$('#nokepesertaan').val('');
						$('#clockDisplaysep').val(tgl);
						$('#ppk-rujukan').val('');
						$('#no_rujukan').val('');
						$('#valDiagnosa').val('');
						// jenis peserta
						$('#kdpoli').val('');
						$('#rm').val('');
						$('#namapasien').val('');
						$('#umur').val('');
						$('#tgllahir').val(tgl);
						$('#alamatpasien').val('');
						$('#catatan').val('-');
						$('#lokasi').val('');
						$('#noarsip').val('');
						$('#nosepcetak').val('');
				}, 100);
			} else {
				swal({
					title : "Maaf !!",
					type : "warning",
					timer : 2000,
					showConfirmButton : false
				});
			}
		});
	});

	//create sep
	$('#btn-sep').click(function(){
		var data = new FormData($('#insert-sep')[0]);

		$.ajax({
	        url: '{!! route("insertsep") !!}',
	        type: 'POST',
	        data: data,
	        async: true,
	        cache: false,
	        contentType: false,
	        processData: false
	     }).done(function(data){
    		// $('.btn-save').attr('disabled', false).html('Simpan <span class="fa fa-save"></span>');
    		$('#form-user').validate(data, 'has-error');
    		if(data.status == 'success'){
    			swal("Sukses!", "Data berhasil disimpan!", "success");
    			$('#nosepcetak').val(data.nosep);
    			$('#noarsip').val(data.noarsip);
    			$('#btn-print-sep').removeAttr('disabled');
    		} else if(data.status == 'error'){ 
					var strPesan =  data.messages;
					var rs = strPesan.match('RSUD DR. W. SUDIROHUSODO/i');
					var pulang = strPesan.match('belum di pulangkan/i');
					if(rs && pulang){
						$.post('{!! route("formupdatetglpulang") !!}').done(function(result){
							$('#updatetglpulang').html(result.content);
						});
					} else {
						swal('Perhatian', data.messages, 'warning');
    				}
    		}else{
				swal('Perhatian', 'Mohon periksa inputan anda', 'warning');
    		}
    	});

	});

	function tgl_indo(tgl) {
		$tanggal = tgl.substr(8,2);
		$bulan	 = tgl.substr(5,2);
		$tahun	 = tgl.substr(0,4);
		return $tanggal+'/'+$bulan+'/'+$tahun;
	}

	function renderTime(){
		var currentTime = new Date();
		var h = currentTime.getHours();
		var m = currentTime.getMinutes();
	 	var s = currentTime.getSeconds();
		 if (h == 0){
		  h = 24;
		   }
		   if (h < 10){
		    h = "0" + h;
		    }
		      if (m < 10){
		    m = "0" + m;
		    }
		    if (s < 10){
		    s = "0" + s;
		    }
		 var myClock = document.getElementById('clockDisplay');
		 $('#clockDisplay').val(h + ":" + m + ":" + s + "");    
		 setTimeout ('renderTime()',1000);
	 }

	renderTime();

	function renderTimeSEP(){
		var currentTime = new Date();

		var h = currentTime.getHours();
		var m = currentTime.getMinutes();
	 	var s = currentTime.getSeconds();
		 if (h == 0){
		  h = 24;
		   }
		   if (h < 10){
		    h = "0" + h;
		    }
		      if (m < 10){
		    m = "0" + m;
		    }
		    if (s < 10){
		    s = "0" + s;
		    }
		 var myClock = document.getElementById('clockDisplay');   
		 setTimeout ('renderTime()',1000);
	 }
</script>
@stop