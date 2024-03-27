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
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			BRIDGING BPJS
			<!-- <small>Control panel</small> -->
		</h1>
	</section>
	<div style="width: 65%; float: left;">
		<div class="content">
			<div style="width: 100%;">
				<div class="box box-default main-layer">
				<form id="insert-sep">					
					<div class="box-header">
						<div class="form-group">
								<?php // date_default_timezone_set('UTC') ?>
							<label class="pad col-lg-2 col-md-2">Tanggal</label>
							<div class="col-lg-5 col-md-4">
								<input type="date" name="Tanggal" class="" value="{!! date('Y-m-d') !!}" readonly style="width: 84%;">
							</div>
							<label class="pad col-lg-2 col-md-2">Jam</label>
							<div class="col-lg-3 col-md-4">
								<input type="time" name="jam" class="" value="{!! date('H:i:s') !!}" id="clockDisplay" readonly style="width: 100%;"> 
							</div>
						</div>
					</div><hr style="margin-top: 0px; margin-bottom: 0px;">
					<div class="box-main">
						<div class="form-group ">
							<div class="col-lg-2 col-md-2 pad">
								<input type="checkbox" name="karyawan" class="" value="Y" id="karyawan">
								<label for="karyawan">Karyawan</label>
							</div>
						
							<div class="col-lg-5 col-md-4 pad">
								<input type="radio" name="rawat" class="" value="2" id="jalan" checked="">
								<label for="jalan">Rawat Jalan</label>&nbsp;
								<input type="radio" name="rawat" class="" value="1" id="inap">
								<label for="inap">Rawat Inap</label>
								<input type="hidden" name="kelasrawat" value="" id="kelasrawat">
							</div>

							<div class="col-lg-5 col-md-6 pad">
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
							<label class="pad col-lg-2 col-md-2">No. BPJS</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="nokartu" class="" placeholder="Nomor peserta BPJS" id="nokepesertaan" style="width: 84%;">
								<button class="btn-cari-peserta" type="button">:</button>
							</div>
							<label class="pad col-lg-2 col-md-2">Tgl SEP</label>
							<div class="col-lg-3 col-md-4">	
								<input type="date" name="tgl_sep" class="" id="clockDisplaysep" value="{!! date('Y-m-d') !!}"  style="width: 100%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">PPK Rujukan </label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="ppk_rujukan" class="" placeholder="kode fakses rujukan" id="ppk-rujukan" style="width:  84%;">
							</div>
							<label class="pad col-lg-2 col-md-2">Tgl Rujukan</label>
							<div class="col-lg-3 col-md-4">
								<input type="date" name="tgl_rujukan" class="" id="clockDisplaysep" value="{!! date('Y-m-d') !!}" style="width: 100%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Jenis Pasien</label>
							<div class="col-lg-5 col-md-4">
								<select name="jenisPeserta" class="" id="penjamin" style="width: 84%;">
										<option>.: Pilih Jenis Pasien :. </option>
									@foreach($data['jenispasien'] as $asu)
										<option value="{!! $asu->subgroups !!}">{!! $asu->nilaichar !!}</option>
									@endforeach
								</select>
							</div>
							<label class="pad col-lg-2 col-md-2">No. Rujukan</label>
							<div class="col-lg-3 col-md-4">
								<input type="text" name="no_rujukan" id='nRujuk' class="" placeholder="Nomor Rujukan" style="width: 100%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Diagnosa</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="diagnosa" class="" placeholder="Kode Diagnosa Awal" id='valDiagnosa' style="width: 84%;">
								<button class="btn-cari-diagnosa" type="button">:</button>
							</div>
							<span class="col-lg-5 col-md-6" id='textDiagnosa' style="font-style: italic;color: #444;"></span>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Poli Tujuan</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="poli" class="" placeholder="Kd Poli" id="kdpoli" style="width: 84%;">
								<button class="btn-cari-poli" type="button">:</button>
							</div>
							<span class="col-lg-5 col-md-6" id="namapoli" ></span>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">No. RM</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="no_rm" class="" placeholder="Nomor rekam Medis" id="rm" style="width: 84%;">
								<button class="btn-cari-rm" type="button">:</button>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Nama Pasien</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="nama" class="" placeholder="Nama Pasien" id="namapasien" style="width: 84%;">
							</div>
							<label class="pad col-lg-2 col-md-2">Jns Kelamin</label>
							<div class="col-lg-3 col-md-4">
								<input type="radio" name="sex" value="L" id='L'><label for='L'>Laki-laki</label>&nbsp;
								<input type="radio" name="sex" value="P" id='P'><label for='P'>Perempuan</label>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Umur</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="umur" class="" placeholder="Umur Pasien" id="umur" style="width: 84%;">
							</div>
							<label class="pad col-lg-2 col-md-2">Tgl Lahir</label>
							<div class="col-lg-3 col-md-4">
								<input type="date" name="tgl_lahir" class="" id="tgllahir" placeholder="Tanggal Lahir Pasien" style="width: 100%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Alamat</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="alamat" class="" placeholder="Alamat Pasien" id="alamatpasien" style="width: 84%;">
								<input type="hidden" name="cob" id="cob">
							</div>
							<label class="pad col-lg-2 col-md-2">No. Telp</label>
							<div class="col-lg-3 col-md-4">
								<input type="text" name="notelp"  class="" placeholder="Nomor Telepon" value="-" id="notelp" max="14" style="width: 100%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Catatan</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="catatan" class="" placeholder="Catatan" value="-" style="width: 84%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div  id="lokasi_laka">
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2">Pejamin</label>
								<div class="col-lg-5 col-md-8">
									<input type="checkbox" name="penjamin[]" value="1" id="p1"> <label for = "p1">PT. JASA RAHARJA</label>&nbsp;
									<input type="checkbox" name="penjamin[]" value="2" id="p2" checked=""> <label for = "p2">BPJS</label>&nbsp;
									<input type="checkbox" name="penjamin[]" value="3" id="p3"> <label for = "p3">TASPEN</label>&nbsp;
									<input type="checkbox" name="penjamin[]" value="4" id="p4">  <label for = "p4">PT ASBRI</label>&nbsp;
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2">Lokasi Laka</label>
								<div class="col-lg-3 col-md-4">
									<input type="text" name="lokasi" class="" placeholder="Lokasi Laka" id="lokasi" style="width: 100%;">
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<center style='margin-top: 15px'>
							<a href='javascript:void(0)' class="btn btn-lg btn-primary" id='btn-sep' disabled="true"><i class="fa fa-save"></i> SIMPAN</a>
							<!-- <button type='button' class="btn btn-lg btn-success" id='btn-print-sep' disabled=""><i class="fa fa-print"></i> Cetak</button> -->
							<button type='button' class="btn btn-lg btn-success" id='btn-print-sep' disabled="true" ><i class="fa fa-print"></i> Cetak</button>
							<input type="hidden" name="nosepcetak" id="nosepcetak">
							<input type="hidden" name="noarsip" id="noarsip">
							<input type="hidden" name="noKontrol" id="noKontrol" value=''>
						</center>
						<br>
					</div>
					<div class="box-footer"></div>
				</form>
				</div>
			</div>
		</div>
	</div>
	<div style="width: 35%; float: left; ">
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
								<td width="125px;">Nama</td>
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
								<td>No. Telp</td>
								<td>: <span id="telpbpjs"></span></td>
							</tr>
							<tr>
								<td>Cetak Kartu</td>
								<td>: <span id="cetakbpjs"></span></td>
							</tr>
							<tr>
								<td>Jenis Peseta</td>
								<td>: <span id="jnps_bpjs"></span></td>
							</tr>
							<tr>
								<td>Kelas Tanggungan</td>
								<td>: <span id="kltg_bpjs"></span></td>
							</tr>
							<tr>
								<td>Status Peserta</td>
								<td>: <span id="statusbpjs"></span></td>
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
									<th width="200" style="padding-left: 5px;">Asuransi</th> 
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
	        	var kdcb = (result.response.peserta.provUmum.kdCabang) ? result.response.peserta.provUmum.kdCabang : '';
	    		var nmcb = (result.response.peserta.provUmum.nmCabang) ? result.response.peserta.provUmum.nmCabang : '';
	        	$('#nama_bpjs').html(result.response.peserta.nama);
	        	$('#sex_bpjs').html(result.response.peserta.sex);
	        	$('#tgl_bpjs').html(result.response.peserta.tglLahir);
	        	$('#kdprov_bpjs').html(result.response.peserta.provUmum.kdProvider);
	        	$('#nmprov_bpjs').html(result.response.peserta.provUmum.nmProvider);
	        	$('#kdcb_bpjs').html(kdcb);
	        	$('#nmcb_bpjs').html(nmcb);
	        	$('#jnps_bpjs').html(result.response.peserta.jenisPeserta.keterangan);
	        	$('#kltg_bpjs').html(result.response.peserta.hakKelas.keterangan);
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
		$('#p1').removeAttr('checked');
		$('#p2').removeAttr('checked');
		$('#p3').removeAttr('checked');
		$('#p4').removeAttr('checked');
		$('#p2').prop('checked','true');
	});

	$('#btn-print-sep').click(function(e){
		e.preventDefault();
		var nosep = $('#nosepcetak').val();
		var noarsip = $('#noarsip').val();
		$.post("{{ route('cetak_sep') }}",{nosep:nosep, noarsip:noarsip}).done(function(data){
			var telp = $('#notelp').val();
			var fakses = $('#ppk-rujukan').val();
			var penjamin = $('#penjamin').val();
			var diag = $('#valDiagnosa').val();
			var peserta = $('#jnps_bpjs').html();
			var noKontrol = $('#noKontrol').val();
			if(data.status == 'success'){
				var cetak = '';
				var stTitle = 'height="20px" style="font-size:18px;"';
				var stIsi = 'height="20px" style="font-size:16px;"';
				var widthJd1 = 'width="135px;"';
				var widthIsi1 = 'width="400px;"';
				var widthJd2 = 'width="115px;"';
				var widthIsi2 = 'width="300px;"';
				var stBerkas = 'style="font-size:16px;padding-left: 50px;"';
				var stPasKel = 'style="font-size: 14px;padding-left: 35px;"';
				var stKet = 'style="font-size: 12px;"';
				var stCat2 = 'height="20px" style="font-size:12px;"';
				for (var p = 0; p < 3; p++) {
					cetak += '<table border="0">';
					cetak += '<tr>';
					cetak += '<td rowspan="2" width="250px">';
					var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
					cetak += '<img src="'+logoBpjs+'" width="230px" style="margin-left: 5px;">';
					cetak += '</td>';
					cetak += '<td '+stTitle+' colspan="2">SURAT ELEGIBILITAS PESERTA</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+stTitle+'>RSUD DR. W. SUDIROHUSODO</td>';
					cetak += '<td '+stBerkas+'>No.Berkas &nbsp : '+data.data.sepValue["noarsip"]+'</td>';
					cetak += '</tr>';
					cetak += '</table>';
					cetak += '<div style="margin-bottom: 5px;"></div>';
					cetak += '<table border="0">';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>No.SEP</td>';
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["no_sep"]+'</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>Tgl.SEP</td>';
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["tgl_sep"]+'</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>Peserta</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>: '+peserta+'</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>No.Kartu</td>';
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["no_kartu"]+' ( MR. '+data.data.sepValue["noMr"]+' )</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>COB</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>: -</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>Nama Peserta</td>';
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["nama_kartu"]+'</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>Jns.Rawat</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>: '+data.data.sepValue["jenis_rawat"]+'</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>Tgl.Lahir</td>';
					if (data.data.sepValue["jenis_kelamin"] == "L") {
						var JKlamin = 'LAKI - LAKI';
					}else{
						var JKlamin = 'PEREMPUAN'
					}
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["tgl_lahir"]+' &nbsp Kelamin : '+JKlamin+'</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>Kls.Rawat</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>: '+data.data.sepValue["kls_rawat"]+'</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>No.Telepon</td>';
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+telp+'</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>Penjamin</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>: -</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>Poli Tujuan</td>';
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["poli_tujuan"]+'</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>No. Kontrol</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>: '+noKontrol+'</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>Faskes Perujuk</td>';
					cetak += '<td colspan="3" '+stIsi+'>: '+fakses+'</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>Diagnosa Awal</td>';
					cetak += '<td colspan="3" '+stIsi+'>: '+diag+' - '+data.data.sepValue["diagnosa"]+'</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>Catatan</td>';
					cetak += '<td colspan="3" '+stIsi+'>: '+data.data.sepValue["catatan"]+'</td>';
					cetak += '</tr>';
					cetak += '<tr><td colspan="4" height="5"></td></tr>';
					cetak += '<tr>';
					cetak += '<td colspan="3">&nbsp</td>';
					cetak += '<td '+stPasKel+'>Pasien/Keluarga Pasien</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td colspan="3" '+stKet+' valign="bottom">*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan.</td>';
					cetak += '<td '+stPasKel+' valign="bottom" height="25px">_______________</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td colspan="3" '+stKet+' valign="top">SEP Bukan sebagai bukti penjaminan peserta.</td>';
					cetak += '<td>&nbsp</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td colspan="4" '+stCat2+'>Cetakan ke 1 '+data.data.sepValue["jam"]+'</td>';
					cetak += '</tr>';
					cetak += '</table>';
					cetak += '<div style="page-break-before: always;"></div>';
				}
				$('.printSEP').html(cetak);
				$('.printSEP').printArea();
				setTimeout(function tutupCetak() {
					$('.printSEP').empty();
					$('#nokepesertaan').val('');
	        		$('#namapasien').val('');
					$('#rm').val('');
					$('#umur').val('');
					$('#ppk-rujukan').val('');
					$('#nokepesertaan').val('');
					$('#alamatpasien').val('');
					$('#tgllahir').val('mm/dd/yyyy');

		        	$('#btn-sep').attr('disabled','disabled');
		        	$('#jalan').removeAttr('checked');
		        	$('#jalan').prop('checked','true');
		        	$('#tidaklaka').removeAttr('checked');
		        	$('#tidaklaka').prop('checked','true');
					$('#lokasi_laka').hide();
					$('#p1').removeAttr('checked');
					$('#p2').removeAttr('checked');
					$('#p3').removeAttr('checked');
					$('#p4').removeAttr('checked');
		        	$('#p2').prop('checked','true');
		        	$('#lokasi').val('');

		        	$('#valDiagnosa').val('');
		        	$('#textDiagnosa').html('');
		        	$('#kdpoli').val('');
		        	$('#namapoli').html('');
		        	$('#notelp').val('');
		        	$('#noKontrol').val('');
		        	$('#nRujuk').val('');

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
    			$('#noKontrol').val(data.noKontrol);
    			$('#btn-print-sep').removeAttr('disabled');
    		} else if(data.status == 'error'){ 
					var strPesan =  data.messages;
					if(data.update == 'update'){
						$.post('{!! route("formupdatetglpulang") !!}',{nobpjs:data.nobpjs, pesan : strPesan}).done(function(result){
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

 	function cetakulang(noreg){
		$.post("{!! route('getregister') !!}",{noregister:noreg}).done(function(result){
        	if(result.code == '200'){
    			$('#btn-print-sep').removeAttr('disabled');
				$('option[value="'+result.data.Kode_Ass+'"]').prop("selected", true);
				$('#valDiagnosa').val(result.data.kddiagnosa);
				$('#textDiagnosa').html(result.data.diagnosa);
				$('#kdpoli').val(result.data.kdpoli);
				$('#namapoli').html(result.data.namapoli);
    			$('#nosepcetak').val(result.data.NoSEP);
    			$('#noKontrol').val(noreg);
        	}else{
        		swal('Peringatan','Pasien tidak menggunakan BPJS','warning');
        	}
        });
	}
</script>
@stop