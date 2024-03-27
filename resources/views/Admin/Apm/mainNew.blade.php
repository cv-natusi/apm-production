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
		.chosen-single > span, .chosen-single > div {
			margin-top: -4px !important;
		}
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			Anjungan Pendaftaran Mandiri (APM) & Call Center
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
							<label class="pad col-lg-2 col-md-2 pull-left">
								<button class="btn-cari-antrian" type="button">Cari Antrian</button>
								<input type="hidden" name="id_antrian" id="id_antrian" value="{{(isset($data['getAntrian'])) ? $data['getAntrian']->id : ''}}">
							</label>
						</div>
						<div class="clearfix" style="margin-bottom: 5px;"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Tgl</label>
							<div class="col-lg-5 col-md-4">
								<input type="date" id="Tgl" name="Tgl" class="tgl" value="{!! date('Y-m-d') !!}"  style="width:84%;">
							</div>
							<div class="pad col-lg-2 col-md-2">
								<label class="label-control">Jam</label>
							</div>
							<div class="col-lg-3 col-md-4">
								<input type="time" name="jam" class="" value="{!! date('H:i:s') !!}" id="clockDisplay" style="width: 100%;" readonly >
							</div>
						</div>
						<div class="clearfix" style="margin-bottom: 5px;"></div>
						<div id="jenisPendaftar" class="form-group" style="display: none;">
							<label class="pad col-lg-2 col-md-2">Jenis Pendaftar<font color="red">*</font></label>
							<div class="col-lg-5 col-md-4">
								<select name="jenis_pendaftaran" id="jenis_pendaftaran" class="" style="width: 84%;">
									<option value="" disabled>.: Pilih :.</option>
									<option value="UMUM" selected>UMUM</option>
									<option value="BPJS">BPJS</option>
								</select>
							</div>
						</div>
					</div><hr style="margin-bottom: 0; margin-top: 0; ">
					<div class="box-main">
						<div class="form-group">
							<div class="pad col-lg-2 col-md-2">
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
							<label class="pad col-lg-2 col-md-2">Cari Berdasarkan</label>
							<div class="col-lg-5 col-md-4">
								<input type="radio" name="jnsCari" value="noka" id='noka' checked> <label for='noka'>No. BPJS</label>&nbsp;
								<input type="radio" name="jnsCari" value="nik" id='nik'> <label for='nik'>NIK</label>
							</div>
							<div class="col-lg-5 col-md-6 pad">
								{{-- <input type="checkbox" name="cob" class="" value="1" id="cob">
								<label class="" for="cob">Peserta COB</label> --}}
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label id="cariLabel" class="pad col-lg-2 col-md-2">No. BPJS</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="nokartu" class="cari cariNoka" placeholder="Cari No. Kartu Peserta" id="nokepesertaan" style="width: 84%;">
								<input type="text" name="nonik" class="cari cariNik" placeholder="Cari NIK Peserta" id="nonik" style="width: 84%; display: none;">
								<button class="btn-cari-peserta" type="button">:</button>
							</div>
							<label class="pad col-lg-2 col-md-2">Tgl SEP</label>
							<div class="col-lg-3 col-md-4">
								<input type="date" name="tgl_sep" class="" id="clockDisplaysep" value="{!! date('Y-m-d') !!}"  style="width: 100%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">PPK Rujukan<font style="color:red;">*</font></label>
							<div class="col-lg-5 col-md-4">
								<input type="hidden" name="asal_rujukan" id="asal_rujukan">
								<input type="text" name="ppk_rujukan" class="" placeholder="Cari Faskes Rujukan" id="ppk-rujukan" style="width: 84%;">
								<button class="btn-cari-ppk" type="button">:</button>
							</div>
							<label class="pad col-lg-2 col-md-2">Tgl Rujukan</label>
							<div class="col-lg-3 col-md-4">
								<input type="date" name="tgl_rujukan" class="tglRujuk" id="clockDisplaysep" value="{!! date('Y-m-d') !!}" style="width: 100%;" readonly>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Jenis Pasien<font style="color:red;">*</font></label>
							<div class="col-lg-5 col-md-4">
								<select name="jenisPeserta" class="" id="penjamin" style="width: 84%;">
									<option value="">.: Pilih Jenis Pasien :. </option>
									@foreach($data['jenispasien'] as $j)
									<option value="{!! $j->subgroups !!}">{!! $j->nilaichar !!}</option>
									@endforeach
								</select>
							</div>
							<label class="pad col-lg-2 col-md-2">No. Rujukan</label>
							<div class="col-lg-3 col-md-4">
								<input type="text" name="no_rujukan" id='nRujuk' class="" placeholder="Nomor Rujukan" style="width: 100%;" oninput="this.value = this.value.toUpperCase()">
								<span style="position: absolute;right: 18px;top: 1px;">
									<a href="javascript:void(0)" onclick="reRujuk()" class="btn btn-xs btn-success btnRefresh"><i class="fa fa-refresh"></i></a>
								</span>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Diagnosa<font style="color:red;">*</font></label>
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
							<label class="pad col-lg-2 col-md-2">No. SKDP/SPRI</label>
							<div class="col-lg-3 col-md-4">
								<input type="text" name="no_surat"  class="" id="noSurat" placeholder="Nomor SKDP" value="" style="width: 100%;" oninput="this.value = this.value.toUpperCase()">
								<span style="position: absolute;right: 18px;top: 1px;">
									<a href="javascript:void(0)" onclick="cekSkdp()" class="btn btn-xs btn-success btn-cekSkdp"><i class="fa fa-search"></i></a>
								</span>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">DPJP Layan</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="dpjpLayan" id='dpjpLayan' class="" placeholder="Kode DPJP Layan" style="width: 84% !important;" readonly="true">
								<span class="panelDokterDpjp3" style="display: show;">
									<input type="text" name="dpjp_layan" class="" placeholder="Dokter DPJP Layan" id="dpjp_layan" style="width: 84%;">
									<button class="btn-cari-dpjp" type="button">:</button>
								</span>
							</div>
							<label class="pad col-lg-2 col-md-2 col-md-4">Dokter Perujuk</label>
							<div class="col-lg-3 col-md-4">
								<input type="hidden" name="kdDpjp" id='kdDpjp' class="" placeholder="Kode DPJP" style="width: 100%;" readonly="true">
								<span class="panelDokterDpjp2" style="display: show;">
									<input type="text" name="dpjp_rujuk" class="" placeholder="Dokter Perujuk" id="dpjp_rujuk" style="width: 89%;">
									<button class="btn-cari-perujuk" type="button">:</button>
								</span>
							</div>
						</div>
						<div class="clearfix" style="margin-bottom: 15px;"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Nama Pasien</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="nama" class="" placeholder="Nama Pasien" id="namapasien" style="width: 84%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Umur</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="umur" class="" placeholder="Umur Pasien" id="umur" style="width: 84%;">
							</div>
							<label class="pad col-lg-2 col-md-2">Jns Kelamin</label>
							<div class="col-lg-3 col-md-4">
								<input type="radio" name="sex" value="L" id='L'><label for='L'>Laki-laki</label>&nbsp;
								<input type="radio" name="sex" value="P" id='P'><label for='P'>Perempuan</label>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Alamat</label>
							<div class="col-lg-5 col-md-4">
								<input type="text" name="alamat" class="" placeholder="Alamat Pasien" id="alamatpasien" style="width: 84%;">
							</div>
							<label class="pad col-lg-2 col-md-2">Tgl Lahir</label>
							<div class="col-lg-3 col-md-4">
								<input type="date" name="tgl_lahir" class="" id="tgllahir" placeholder="Tanggal Lahir Pasien" style="width: 100%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Catatan</label>
							<div class="col-lg-5 col-md-4">
								<input id="catatan" type="text" name="catatan" class="" placeholder="Catatan" value="-" style="width: 84%;">
							</div>
							<label class="pad col-lg-2 col-md-2">No. Telp</label>
							<div class="col-lg-3 col-md-4">
								<input type="text" name="notelp"  class="" placeholder="Nomor Telepon" value="-" id="notelp" max="14" style="width: 100%;">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Tujuan Kunjungan<font style="color:red;">*</font></label>
							<div class="col-lg-5 col-md-4">
								<select name="tujuan_kunjugan" onchange="tujuan_kunjungan(this)" id="tujuan_kunjugan_text" class="">
									<option value="" disabled selected>.: Pilih Jenis Kunjungan :.</option>
									<option value="0">Normal</option>
									<option value="1">Prosedur</option>
									<option value="2">Konsul Dokter</option>
								</select>
							</div>
						</div>
						<div class="clearfix"></div>
						<div id="tujuan_kunjungan_form"></div>
						<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Status Kecelakaan<font style="color:red;">*</font></label>
							<div class="col-lg-5 col-md-4">
								<select name="statuslaka" class="" id="statuslaka" style="width: 84%;">
									<option value="" disabled="">.: Pilih Status Laka :.</option>
									<option value="0" selected>Bukan Kecelakaan Lalu Lintas (BKLL)</option>
									<option value="1">Kecelakaan Lalu Lintas</option>
									<option value="2">Kecelakaan Lalu Lintas Dan Kecelakaan Kerja</option>
									<option value="3">Kecelakaan Kerja</option>
								</select>
							</div>
							<div class="laka" style="display:none;">
								<label class="pad col-lg-2 col-md-2">Tgl Kecelakaan</label>
								<div class="col-lg-3 col-md-4">
									<input type="date" name="tgl_laka" class="" id="tgl_laka" value=""  style="width: 100%;">
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="laka" style="display:none;">
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2">Lokasi Kecelakaan</label>
								<div class="col-lg-5 col-md-4">
									<input type="hidden" name="kdProvinsi" id="kdProvinsi">
									<input id="nmProvinsi" type="text" name="nmProvinsi" class="" placeholder="Pilih Provinsi" value="" style="width: 84%;" disabled="">
									<button class="btn-lokasi-laka" type="button" onclick="lokasiLaka('prov')">:</button>
								</div>
								<label class="pad col-lg-2 col-md-2">No. Laporan Polisi</label>
								<div class="col-lg-3 col-md-4">
									<input type="text" name="no_lp" class="" id="no_lp" value="" placeholder="Ketik No. Laporan Polisi" style="width: 100%;">
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2"></label>
								<div class="col-lg-5 col-md-4">
									<input type="hidden" name="kdKabupaten" id="kdKabupaten">
									<input id="nmKabupaten" type="text" name="nmKabupaten" class="" placeholder="Pilih Provinsi terlebih dahulu" value="" style="width: 84%;" disabled="">
									<button id="btn-kab" class="btn-lokasi-laka" type="button" onclick="lokasiLaka('kab')" style="display:none;">:</button>
								</div>
								<label class="pad col-lg-2 col-md-2">Suplesi</label>
								<div class="col-lg-3 col-md-4">
									<input type="radio" name="suplesi" value="0" id='suplesi0' checked=""> <label for='suplesi0'>Tidak</label>
									<input type="radio" name="suplesi" value="1" id='suplesi1'> <label for='suplesi1'>Ya</label>&nbsp;
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2"></label>
								<div class="col-lg-5 col-md-4">
									<input type="hidden" name="kdKecamatan" id="kdKecamatan">
									<input id="nmKecamatan" type="text" name="nmKecamatan" class="" placeholder="Pilih Kabupaten/Kota terlebih dahulu" value="" style="width: 84%;" disabled="">
									<button id="btn-kec" class="btn-lokasi-laka" type="button" onclick="lokasiLaka('kec')" style="display:none;">:</button>
								</div>
								<label class="pad col-lg-2 col-md-2 sup">No. SEP Suplesi</label>
								<div class="col-lg-3 col-md-4 sup">
									<input type="text" name="nosep_suplesi" class="" id="nosep_suplesi" value="" placeholder="Ketik No. SEP Suplesi" style="width: 89%;">
									<button id="btn-suplesi" class="btn-suplesi sup" type="button" style="display:none;">:</button>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2">Ket. Kejadian</label>
								<div class="col-lg-5 col-md-4">
									<textarea id="keterangan_laka" name="keterangan_laka" class="" placeholder="Ketik Keterangan Kejadian" value="" style="width: 84%;"></textarea>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="box-main">
						<center style='margin-top: 15px'>
							{{-- <a href='javascript:void(0)' class="btn btn-lg btn-primary" id='btn-sep' disabled="true"><i class="fa fa-save"></i> SIMPAN</a> --}}
                            <a href='javascript:void(0)' class="btn btn-lg btn-primary" id='btn-sep'><i class="fa fa-save"></i> SIMPAN</a>
							<button type='button' class="btn btn-lg btn-success" id='btn-print-sep' disabled="true"><i class="fa fa-print"></i> Cetak</button>
							<input type="hidden" name="nosepcetak" id="nosepcetak">
							<input type="hidden" name="noarsip" id="noarsip">
							<input type="hidden" name="apm" id="apm" value='apm'>
							<input type="hidden" name="noUrut" id="noUrut" value=''>
							<input type="hidden" name="urutpoli" id="urutpoli" value=''>
							<input type="hidden" name="tgl_apm" id="tgl_apm" value=''>
							<input type="hidden" name="noKontrol" id="noKontrol" value=''>
							<!-- <input type="text" name="tingkatRujuk" id="tingkatRujuk" value='1'> -->
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
								<td width="200px;">Nama</td>
								<td>: <span id="nama_bpjs"></span></td>
							</tr>
							<tr>
								<td>Jenis Kelamin</td>
								<td>: <span id="sex_bpjs"></span></td>
							</tr>
							<tr>
								<td>Tgl Lahir</td>
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
	{{--  --}}
	<div style="width: 100%; float: left; ">
		<div class="content" style="margin-top: -45px;">
			<div style="width: 100%;">
				<div class="box box-default main-layer" style="max-width: 100%; overflow: auto;">
					<div class="box-header">
						<div class="form-group" style="margin-top: 5px;margin-bottom: 50px;">
							<label class="col-md-2 col-sm-2 col-xs-12 control-label"><b>HISTORY SEP</b></label>
							<div class="col-md-5 col-sm-5 col-xs-12">
								<div class="input-group date">
									<input type="date" class="form-control datepicker" id="dateHistoryAwal" value="{{date('Y-m-d', strtotime("-89 day", strtotime(date('Y-m-d'))))}}" placeholder="yyyy-MM-dd" maxlength="10">
									<span class="input-group-addon">
										<span class="fa fa-calendar">
										</span>
									</span>
									<span class="input-group-addon">
										s.d
									</span>
									<input type="date" class="form-control datepicker" id="dateHistoryAkhir" value="{{date('Y-m-d')}}" placeholder="yyyy-MM-dd" maxlength="10">
									<span class="input-group-addon">
										<span class="fa fa-calendar">
										</span>
									</span>
								</div>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<button type="button" id="btnCariHistori" class="btn btn-primary pull-left"><i class="fa fa-search"></i> Cari</button>
							</div>
						</div>
					</div>
					<!-- HISTORY SEP  -->
					<table border='1' class="history" style="margin-left: 40px;">
						<thead>
							<tr style="background: #ccc">
								<th width="20" style="padding-left: 5px;">&nbsp</th>
								<th width="250" style="padding-left: 5px;">Poli</th>
								<th width="200" style="padding-left: 5px;">No SEP</th>
								<th width="200" style="padding-left: 5px;">Nama Pasien</th>
								<th width="100" style="padding-left: 5px;">No Kartu</th>
								<th width="150" style="padding-left: 5px;" id="">Jenis Pelayanan</th>
								<th width="200" style="padding-left: 5px;">No Rujukan</th>
								<th width="150" style="padding-left: 5px;">Tgl SEP</th>
								<th width="150" style="padding-left: 5px;">PPK Pelayanan</th>
								<!-- <th width="200" style="padding-left: 5px;">Tabel Lanjut</th>
								<th width="200" style="padding-left: 5px;">Tabel Lanjut</th>
								<th width="200" style="padding-left: 5px;">Tabel Lanjut</th> -->
							</tr>
						</thead>
						<tbody id="resulthistory2"></tbody>
					</table>
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
		$('.sup').hide();
		$('#btn-delete-sep').hide();
		$('#btn-update-pulang-sep').hide();
    });

    // JENIS PENDAFTARAN
	$('#Tgl').change(function () {
		var tgl = $('#Tgl').val();
		var now = "{!! date('Y-m-d') !!}";
		if (tgl == now) {
			$('#jenisPendaftar').hide();
		} else {
			$('#jenisPendaftar').show();
		}
	});

	// STATUS LAKA
	$('#statuslaka').change(function () {
		var status =  $('#statuslaka').val();
		if (status == '0') {
			$('.laka').hide();
		} else {
			$('.laka').show();
		}
	});

	// TUJUAN KUNJUNGAN
	// $('#tujuan_kunjugan_text').change(function () {
	// 	var tujuan = $('#tujuan_kunjugan_text').val();
	// 	if (tujuan == '1') {
	// 		$('#tujuan_kunjungan_form').show();
	// 		$('#prosedur1').show();
	// 	} else if(tujuan == '2') {
	// 		$('#tujuan_kunjungan_form').show();
	// 		$('#prosedur1').hide();
	// 	} else {
	// 		$('#tujuan_kunjungan_form').hide();
	// 		$('#prosedur1').hide();
	// 	}
	// });

	// PPK RUJUKAN
	$('.btn-cari-ppk').click(function(){
		$.post("{!! route('carippk') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

    //Cek No. BPJS
	 //    $('#nokepesertaan').change(function () {
	 //    	var no =  $('#nokepesertaan').val();

	 //        $.post("{!! route('cekpeserta') !!}",{nobpjs:no}).done(function(result){
		//         if(result.response){
		//         	var kdcb = (result.response.peserta.provUmum.kdCabang) ? result.response.peserta.provUmum.kdCabang : '';
		//     		var nmcb = (result.response.peserta.provUmum.nmCabang) ? result.response.peserta.provUmum.nmCabang : '';
		//         	$('#nama_bpjs').html(result.response.peserta.nama);
		//         	$('#sex_bpjs').html(result.response.peserta.sex);
		//         	$('#tgl_bpjs').html(result.response.peserta.tglLahir);
		//         	$('#kdprov_bpjs').html(result.response.peserta.provUmum.kdProvider);
		//         	$('#nmprov_bpjs').html(result.response.peserta.provUmum.nmProvider);
		//         	$('#kdcb_bpjs').html(kdcb);
		//         	$('#nmcb_bpjs').html(nmcb);
		//         	$('#jnps_bpjs').html(result.response.peserta.jenisPeserta.keterangan);
		//         	$('#kltg_bpjs').html(result.response.peserta.hakKelas.keterangan);
		//         }else{
		//         	$('#nama_bpjs').html('');
		//         	$('#sex_bpjs').html('');
		//         	$('#tgl_bpjs').html('');
		//         	$('#kdprov_bpjs').html('');
		//         	$('#nmprov_bpjs').html('');
		//         	$('#kdcb_bpjs').html('');
		//         	$('#nmcb_bpjs').html('');
		//         	$('#jnps_bpjs').html('');
		//         	$('#kltg_bpjs').html('');
		//         }
	 //        });
		// });

	// $('.btn-cari-antrian').click(function(){
	// 	$.post("{!! route('carifromantrianrs') !!}").done(function(data){
	// 		if(data.status == 'success'){
	// 			$('.modal-dialog').html(data.content);
	// 		}
	// 	});
	// });

	// $('.btn-cari-peserta').click(function(){
	// 	$.post("{!! route('carifromrs') !!}").done(function(data){
	// 		if(data.status == 'success'){
	// 			$('.modal-dialog').html(data.content);
	// 		}
	// 	});
	// });

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

	// $('.btn-cari-rm').click(function(){
	// 	$.post("{!! route('cariformrmrs') !!}").done(function(data){
	// 		if(data.status == 'success'){
	// 			$('.modal-dialog').html(data.content);
	// 		}
	// 	});
	// });

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
		var urut = $('#noUrut').val();
		var urutpoli = $('#urutpoli').val();
		var noRujuk = $('#nRujuk').val();
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
					cetak += '<td '+widthJd2+' '+stIsi+'>No. urut</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>: '+urut+'</td>';
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
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+fakses+'</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>No. Rujukan</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>: '+noRujuk+'</td>';
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
		        	$('#urutpoli').val('');
		        	$('#nRujuk').val('');
		        	$('#kdDpjp').val('');
		        	$('#namaDpjp').val('');
		        	$('#noKontrol').val('');
		        	$('#noSurat').val('');
		        	var tglSkrg = "{!! date('Y-m-d') !!}";
		        	$('.tglRujuk').val(tglSkrg);
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

	// $('.btn-cari-perujuk').click(function(){
	// 	var poli = $('#namapoli').text();
	// 	$.post("{!! route('caridpjp') !!}",{dpjp:'perujuk', poli:poli}).done(function(data){
	// 		if(data.status == 'success'){
	// 			$('.modal-dialog').html(data.content);
	// 		}
	// 	});
	// });

	// create sep
	$('#btn-sep').click(function(){
		// $('#btn-sep').attr('disabled', true).html('Loading ...');
		// $('#btn-sep').html('<i class="fa fa-spinner" aria-hidden="true"></i> Loading...').attr('disabled', true);
		var data = new FormData($('#insert-sep')[0]);
		var tglSkrg = "{!! date('Y-m-d') !!}";
		var tglPeriksa = $('.tgl').val();

		if (tglPeriksa == tglSkrg) {
			// LANGSUNG BUAT SEP
			$.ajax({
				url: '{!! route("insertsep") !!}',
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				// $('#btn-sep').attr('disabled', false).html('<i class="fa fa-save"></i> SIMPAN');
				// $('#form-user').validate(data, 'has-error');
				if(data.status == 'success'){
					swal("Sukses!", "Data berhasil disimpan!", "success");
					var kd =  data.antrian.kode_booking;
					$.post('{{route("loketToPoli")}}',{kode:kd}).done((res)=>{
						if(res.status == 'success'){
							swal({
								title: 'Berhasil',
								type: res.status,
								text: res.message,
								showConfirmButton: true,
								// timer: 1500
							})
							window.location.href = '{{ route("listAntrian") }}';
							// window.location.href = '{{ route("listAntrianPoli") }}';
						}else{
							swal({
								title: 'Whoops',
								type: res.status,
								text: res.message,
							})
							location.reload();
						}
					})
					// $('#nosepcetak').val(data.nosep);
					// $('#noarsip').val(data.noarsip);
					// $('#noKontrol').val(data.noKontrol);
					// $('#btn-print-sep').removeAttr('disabled');
				} else if(data.status == 'error'){
					var strPesan =  data.messages;
					if(data.update == 'update'){
						$.post('{!! route("formupdatetglpulang") !!}',{nobpjs:data.nobpjs,pesan : strPesan}).done(function(result){
							$('#updatetglpulang').html(result.content);
						});
					}else if(data.update == 'tingkat'){
						$('#tingkatRujuk').val(data.tingkat);
						$('#ppk-rujukan').val('1320R001');
						swal('Perhatian', 'Silahkan tekan ulang Tombol Simpan !!', 'warning');
					} else {
						swal('Perhatian', data.messages, 'warning');
					}
				}else{
					swal('Perhatian', 'Mohon periksa inputan anda', 'warning');
				}
			});
		} else {
			// BUATIN NO ANTRIAN
			$.ajax({
				url: "{{ route('bridging2Cc_apm') }}",
				type: 'POST',
				data: data,
				enctype: 'multipart/form-data',
				async: true,
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				$('#btn-sep').attr('disabled', false).html('<i class="fa fa-save"></i> SIMPAN');
				if(data.status == 'success'){
					var cetak = '';
					cetak += '<div style="color:#000;padding:10px;width:280px;margin-top:10px;">';
					cetak += '<div style="float: left;padding-top: 5px">';
					cetak += '<img src="{!! url("AssetsAdmin/dist/img/logo hitam.png") !!}" width="35px">';
					cetak += '</div>';
					cetak += '<label style="margin: 0px;line-height: 10px;font-size: 10px;">Rumah Sakit</label><br>';
					cetak += '<label style="margin: 0px;line-height: 15px;font-size: 14px;">Dr. WAHIDIN SUDIRO HUSODO</label><br>';
					cetak += '<label style="margin: 0px;line-height: 20px;font-size: 12px;">KOTA MOJOKERTO</label><br>';
					cetak += '<div style="border-bottom: solid 2px #777;margin-bottom: 10px;"></div>';
					cetak += '<center>';
					cetak += '<label style="margin: 0px;line-height: 10px;font-weight:normal;font-size:12px;margin-bottom:5px;">Selamat Datang</label><br>';
					cetak += '<label style="font-size: 12px;margin: 0px;">Nomor Antrian Poli Anda :</label><br>';
					cetak += '<label style="font-size: 28px;margin: 0px;line-height: 30px;">'+data.data.urutpoli+'</label><br>';
					cetak += '</center>';
					cetak += '<table style="width: 100%;color: #000;">';
					cetak += '<tr>';
					cetak += '<td width="50%" align="left" style="font-size:11px;">Tanggal : '+data.tgl+'</td>';
					cetak += '<td width="50%" align="right" style="font-size:11px;">NoRM : '+data.data.norm+'</td>';

					cetak += '</tr>';
					cetak += '</table>';
					cetak += '<div style="border-bottom: solid 2px #777;margin-bottom: 5px;"></div>';
					cetak += '<table style="width: 100%;color: #000;">';
					cetak += '<tr>';
					cetak += '<td width="100%" colspan="2" style="font-size:12px;">Pendaftaran : <b>'+data.pendaftaran+'</b></td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td width="100%" colspan="2" style="font-size:12px;">Poli Tujuan :</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td width="5%">&nbsp</td>';
					cetak += '<td width="95%" style="font-size:12px;"><b>'+data.data.poli+'</b></td>';
					cetak += '</tr>';
					// Estimasi Pelayanan Start
					cetak += '<tr>';
					if(parseInt(data.estimasipelayanan) >= 12){
						cetak += '<td width="100%" colspan="2" style="font-size:12px;">Estimasi Pelayanan: <b>12:00</b></td>';
					} else {
						cetak += '<td width="100%" colspan="2" style="font-size:12px;">Estimasi Pelayanan: <b>'+data.estimasipelayanan+'</b></td>';
					}
					cetak += '</tr>';
					// Estimasi Pelayanan End
					cetak += '</table>';
					cetak += '<div style="border-bottom: solid 2px #777;padding-bottom: 5px;"></div>';
					cetak += '<label style="margin: 0px;font-weight:normal;margin-left:35px;font-size:12px;">Terima Kasih Atas Kunjungan Anda.</label>';
					cetak += '</div>';

					$('.printSEP').html(cetak);
					$('.printSEP').printArea();

					// setTimeout(function tutupCetak() {
					// 	$('.printSEP').empty();
					// }, 100);
					// setTimeout(function awal() {
					// 	swal({
					// 		title : "Terima Kasih !!",
					// 		type : "success",
					// 		timer : 2000,
					// 		showConfirmButton : false
					// 	});
					// 	window.location.href = "{!! route('apm') !!}";
					// }, 1000);
				} else if(data.status == 'error') {
					swal({
						title: "MAAF !",
						text: data.messages,
						type: "warning",
						showConfirmButton: true
					});
				}
			});
		}
	});

	function lokasiLaka(jnsLokasi, prov='0', kab='0'){
		$.post("{!! route('cariLokasiLaka') !!}",{jnsLokasi:jnsLokasi, prov:prov, kab:kab}).done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	}

	function cekSkdp(){
		var noSurat = $('#noSurat').val();
		var rawat = $('input[name=rawat]:checked').val();
		$.post("{!! route('cekSkdp') !!}",{noSurat:noSurat, rawat:rawat}).done(function(result){
			if (result.metaData.code == '200') {
				if(result.response){
					swal(result.metaData.message, 'No. SKDP/SPRI Ditemukan!', 'success');

					$('#kdpoli').val(result.response.poliTujuan);
					$('#namapoli').html(result.response.namaPoliTujuan);
					$('#kdDpjp').val(result.response.kodeDokterPembuat);
					$('#dpjp_rujuk').val(result.response.namaDokterPembuat);
					$('#dpjpLayan').val(result.response.kodeDokter);
					$('#dpjp_layan').val(result.response.namaDokter);
					if (result.response.jnsKontrol == '1') {
						$('input[name=rawat][value="1"]').prop('checked', true);
						// $('#ppk-rujukan').val('1320R001');
					} else if(result.response.jnsKontrol == '2') {
						$('input[name=rawat][value="2"]').prop('checked', true);
						// $('#ppk-rujukan').val(result.response.sep.provPerujuk.kdProviderPerujuk);
					}
				}else{
					swal('Perhatian', result.metaData.message, 'warning');
				}
			}else{
				swal('Perhatian', result.metaData.message, 'warning');
			}
		});
	}


	// function reRujuk() {
	// 	var noRujuk = $('#nRujuk').val();
	// 	var kdpoli = $('#kdpoli').val();
	// 	var rawat = $('input[name=rawat]:checked').val();
	// 	$.post("{!! route('cekrujukan') !!}",{noRujuk:noRujuk, kdpoli:kdpoli, rawat:rawat}).done(function(resultRujuk){
	// 		if (resultRujuk.status == 'success') {
	// 			if(resultRujuk.data.rujukan){
	// 				var titleDpjp = '';
	// 				var umur = (resultRujuk.data.rujukan.response.rujukan.peserta.umur.umurSaatPelayanan) ? resultRujuk.data.rujukan.response.rujukan.peserta.umur.umurSaatPelayanan.split(" ")[0] : '';
	// 				swal('Berhasil', 'Rujukan Berhasil Ditemukan!', 'success');

	// 				$('.tglRujuk').val(resultRujuk.data.rujukan.response.rujukan.tglKunjungan);
	// 				$('#tingkatRujuk').val(resultRujuk.data.tingkatRujuk);
	// 				$('#ppk-rujukan').val(resultRujuk.data.rujukan.response.rujukan.provPerujuk.kode);
	// 				if (resultRujuk.data.rujukan.response.rujukan) {
	// 					$('#valDiagnosa').val(resultRujuk.data.rujukan.response.rujukan.diagnosa.kode);
	// 					$('#textDiagnosa').text(resultRujuk.data.rujukan.response.rujukan.diagnosa.nama);
	// 					$('#kdpoli').val(resultRujuk.data.rujukan.response.rujukan.poliRujukan.kode);
	// 					$('#namapoli').text('POLI SPESIALIS '+resultRujuk.data.rujukan.response.rujukan.poliRujukan.nama);
	// 				}

	// 				// input tambahan
	// 				$('#umur').val(umur);
	// 				$('#kltg_bpjs').text(resultRujuk.data.rujukan.response.rujukan.peserta.hakKelas.keterangan);
	// 				$('#kdprov_bpjs').text(resultRujuk.data.rujukan.response.rujukan.peserta.provUmum.kdProvider);
	// 				$('#nmprov_bpjs').text(resultRujuk.data.rujukan.response.rujukan.peserta.provUmum.nmProvider);
	// 				$('#statusbpjs').text(resultRujuk.data.rujukan.response.rujukan.peserta.statusPeserta.keterangan);
	// 				$('#asal_rujukan').val(resultRujuk.data.rujukan.response.asalFaskes);
	// 			}else{
	// 				swal('Perhatian', resultRujuk.data.rujukan.metaData.message, 'warning');
	// 			}
	// 		}else{
	// 			swal('Perhatian', resultRujuk.message, 'warning');
	// 		}
	// 	});
	// }

	function tgl_indo(tgl) {
		$Tgl = tgl.substr(8,2);
		$bulan	 = tgl.substr(5,2);
		$tahun	 = tgl.substr(0,4);
		return $Tgl+'/'+$bulan+'/'+$tahun;
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
	// CARI BERDASARKAN
	$('#noka').click(function(){
		$("#cariLabel").text("No. BPJS");
		$(".cariNoka").show();
		$(".cariNik").hide();
	});
	$('#nik').click(function(){
		$("#cariLabel").text("NIK");
		$(".cariNoka").hide();
		$(".cariNik").show();
	});
	//Cek No. BPJS / NIK
	// $('.cari').change(function () {
	// 	var no =  $('#nokepesertaan').val();
	// 	var jnsCari =  $('input[name=jnsCari]:checked').val();
	// 	if (jnsCari == 'nik') {
	// 		no = $('#nonik').val();
	// 	}


	// 	$.post("{!! route('cekpeserta') !!}",{nobpjs:no, jnsCari:jnsCari}).done(function(result){
	// 		if(result.metaData.code == '200'){
	// 			if(result.response.peserta.statusPeserta.keterangan != "AKTIF"){
	// 				swal("Peringatan",result.response.peserta.statusPeserta.keterangan,"info");
	// 			}

	// 			var kdcb = (result.response.peserta.provUmum.kdCabang) ? result.response.peserta.provUmum.kdCabang : '';
	// 			var nmcb = (result.response.peserta.provUmum.nmCabang) ? result.response.peserta.provUmum.nmCabang : '';
	// 			var umur = (result.response.peserta.umur.umurSaatPelayanan) ? result.response.peserta.umur.umurSaatPelayanan.split(" ")[0] : '';
	// 			var notelp = (result.response.peserta.mr.noTelepon) ? result.response.peserta.mr.noTelepon : '000000000';
	// 			$('#nama_bpjs').html(result.response.peserta.nama);
	// 			$('#sex_bpjs').html(result.response.peserta.sex);
	// 			$('#tgl_bpjs').html(result.response.peserta.tglLahir);
	// 			$('#kdprov_bpjs').html(result.response.peserta.provUmum.kdProvider);
	// 			$('#nmprov_bpjs').html(result.response.peserta.provUmum.nmProvider);
	// 			$('#kdcb_bpjs').html(kdcb);
	// 			$('#nmcb_bpjs').html(nmcb);
	// 			$('#telpbpjs').html(result.response.peserta.mr.noTelepon);
	// 			$('#cetakbpjs').html(result.response.peserta.tglCetakKartu);
	// 			$('#jnps_bpjs').html(result.response.peserta.jenisPeserta.keterangan);
	// 			$('#kltg_bpjs').html(result.response.peserta.hakKelas.keterangan);
	// 			$('#statusbpjs').html(result.response.peserta.statusPeserta.keterangan);
	// 			$('#prbPeserta').html(result.response.peserta.informasi.prolanisPRB);
	// 			$('#dinsosPeserta').html(result.response.peserta.informasi.dinsos);
	// 			$('#nonik').val(result.response.peserta.nik);
	// 			$('#nokepesertaan').val(result.response.peserta.noKartu);
	// 			$('#btn-sep').attr('disabled', false).html('<i class="fa fa-save"></i> SIMPAN');

	// 			// tambahan
	// 			// $('#kelasrawat').val(result.response.peserta.hakKelas.kode);
	// 			if (result.response.peserta.hakKelas.kode == '1') {
	// 				$('#kelas1').attr('checked', true);
	// 				$('#kelas2').attr('disabled', true);
	// 				$('#kelas3').attr('disabled', true);
	// 			}else if (result.response.peserta.hakKelas.kode == '2') {
	// 				$('#kelas2').attr('checked', true);
	// 				$('#kelas1').attr('disabled', true);
	// 				$('#kelas3').attr('disabled', true);
	// 			}else {
	// 				$('#kelas3').attr('checked', true);
	// 				$('#kelas1').attr('disabled', true);
	// 				$('#kelas2').attr('disabled', true);
	// 			}
	// 			$('#namapasien').val(result.response.peserta.nama);
	// 			$('#umur').val(umur);
	// 			$('#alamatpasien').val('-');
	// 			$('#tgllahir').val(result.response.peserta.tglLahir);
	// 			$('#notelp').val(notelp);
	// 			if (result.response.peserta.sex == 'P') {
	// 				$('#P').attr('checked', true);
	// 			} else {
	// 				$('#L').attr('checked', true);
	// 			}
	// 		}else{
	// 			swal("Peringatan!", result.metaData.message, "info");
	// 			$('#nama_bpjs').html('');
	// 			$('#sex_bpjs').html('');
	// 			$('#tgl_bpjs').html('');
	// 			$('#kdprov_bpjs').html('');
	// 			$('#nmprov_bpjs').html('');
	// 			$('#telpbpjs').html('');
	// 			$('#cetakbpjs').html('');
	// 			$('#kdcb_bpjs').html('');
	// 			$('#nmcb_bpjs').html('');
	// 			$('#jnps_bpjs').html('');
	// 			$('#kltg_bpjs').html('');
	// 			$('#statusbpjs').html('');
	// 			$('#prbPeserta').html('');
	// 			$('#dinsosPeserta').html('');
	// 		}
	// 	});
	// 	// historySEP(no);
	// });

	// CARI HISTORY SEP
	// $('#btnCariHistori').click(function(){
	// 	var no = $('#nokepesertaan').val();
	// 	var dateAwal = $('#dateHistoryAwal').val();
	// 	var dateAkhir = $('#dateHistoryAkhir').val();
	// 	$.post("{!! route('cariHistorySEP') !!}", {no:no,dateAwal:dateAwal,dateAkhir:dateAkhir}).done(function(result){
	// 		if (result.metaData.code == '200') {
	// 			var dat ='';
	// 			var i = 1;
	// 			$.each(result.response.histori, function(c,v){
	// 				var jnsPelayanan = v.jnsPelayanan;
	// 				if (jnsPelayanan == 1) {jnsPelayanan = "Rawat Inap";}else {jnsPelayanan = "Rawat Jalan";}
	// 				var poliTujSep = v.poliTujSep;
	// 				if (poliTujSep == null) {poliTujSep = "-";}else {v.poliTujSep}
	// 				dat += '<tr>'+
	// 					'<td>'+i+'</td>'+
	// 					'<td>'+v.poli+'</td>'+
	// 					'<td>'+v.noSep+'</td>'+
	// 					'<td>'+v.namaPeserta+'</td>'+
	// 					'<td>'+v.noKartu+'</td>'+
	// 					'<td>'+jnsPelayanan+'</td>'+
	// 					'<td>'+v.noRujukan+'</td>'+
	// 					'<td>'+v.tglSep+'</td>'+
	// 					'<td>'+poliTujSep+'</td>'+
	// 					`</tr>`;
	// 				i++;
	// 			});

	// 			$('#resulthistory2').html(dat);
	// 		}
	// 	});
	// });

	function tujuan_kunjungan(e) {
		$('#tujuan_kunjungan_form').html('');
		if (e.value == 1) {
			var flagProcedure = [
				['0','Prosedur Tidak Berkelanjutan'],
				['1','Prosedur dan Terapi Berkelanjutan']
			];
			var penunjang = [
					['1','Radioterapi'],
					['2','Kemoterapi'],
					['3','Rehabilitasi Medik'],
					['4','Rehabilitasi Psikososial'],
					['5','Transfusi Darah'],
					['6','Pelayanan Gigi'],
					['7','Laboratorium'],
					['8','USG'],
					['9','Farmasi'],
					['10','Lain-Lain'],
					['11','MRI'],
					['12','HEMODIALISA'],
			];
			var assesment = [
				['1','Poli spesialis tidak tersedia pada hari sebelumnya'],
				['2','Jam Poli telah berakhir pada hari sebelumnya'],
				['3','Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya'],
				['4','Atas Instruksi RS']
			];
			var selects = '';
			selects += `<div class="form-group">
							<label class="pad col-lg-2 col-md-2">Flag Prosedur*</label>
							<div class="col-lg-5 col-md-4">
								<select name="prosedur_bpjs" id="prosedur_bpjs" class="">`;
								flagProcedure.forEach(element => {
								selects += `<option value="${element[0]}">${element[1]}</option>`;
								});
			selects += `	</select>
							</div>`;
			selects += `  <label class="pad col-lg-2 col-md-2">Penunjang</label>
							<div class="col-lg-3 col-md-4">
							<select name="penunjang_bpjs" id="penunjang_bpjs" class="">`;
								penunjang.forEach(element => {
									selects += `<option value="${element[0]}">${element[1]}</option>`;
								});
			selects += `	</select>
							</div>`;
							selects += `<div class="clearfix"></div>
										<div class="form-group">
											<label class="pad col-lg-2 col-md-2" style="font-size: 13px;">Assesment Pelayanan</label>
											<div class="col-lg-5 col-md-4">
												<select name="assesment_bpjs" id="assesment_bpjs" class="">`;
												assesment.forEach(element => {
												selects += `<option value="${element[0]}">${element[1]}</option>`;
												});
							selects += `	</select>
											</div>
										</div>`;
			$('#tujuan_kunjungan_form').append(selects);
		}
		if (e.value == 0) {
			var noRujuk = $('#nRujuk').val();
			// $.post("{!! route('countRujukan') !!}",{noRujuk:noRujuk}).done(function(result){
				// if (result > 0) {
					var assesment = [
						['','Rujukan Baru'],
						['1','Poli spesialis tidak tersedia pada hari sebelumnya'],
						['2','Jam Poli telah berakhir pada hari sebelumnya'],
						['3','Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya'],
						['4','Atas Instruksi RS']
						// ['5','Tujuan Kontrol']
					];
					var selects = '';
					selects += `<div class="clearfix"></div>
								<div class="form-group">
									<label class="pad col-lg-2 col-md-2" style="font-size: 13px;">Assesment Pelayanan</label>
									<div class="col-lg-5 col-md-4">
										<select name="assesment_bpjs" id="assesment_bpjs" class="">`;
										assesment.forEach(element => {
										selects += `<option value="${element[0]}">${element[1]}</option>`;
										});
					selects += `	</select>
									</div>
								</div>`;
					$('#tujuan_kunjungan_form').append(selects);
				// }
			// });
		}
		if (e.value == 2) {
			var assesment = [
				['1','Poli spesialis tidak tersedia pada hari sebelumnya'],
				['2','Jam Poli telah berakhir pada hari sebelumnya'],
				['3','Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya'],
				['4','Atas Instruksi RS'],
				['5','Tujuan Kontrol']
			];
			var selects = '';
			selects += `<div class="clearfix"></div>
						<div class="form-group">
							<label class="pad col-lg-2 col-md-2" style="font-size: 13px;">Assesment Pelayanan</label>
							<div class="col-lg-5 col-md-4">
								<select name="assesment_bpjs" id="assesment_bpjs" class="">`;
								assesment.forEach(element => {
								selects += `<option value="${element[0]}">${element[1]}</option>`;
								});
			selects += `	</select>
							</div>
						</div>`;
			$('#tujuan_kunjungan_form').append(selects);
		}

			// flagProcedure != 0
			// {"0": Prosedur Tidak Berkelanjutan,
			//                                                       "1": Prosedur dan Terapi Berkelanjutan}
			// kdPenunjang != 0
			// kdPenunjang = {"1": Radioterapi,
			//                                                      "2": Kemoterapi,
			//                                                      "3": Rehabilitasi Medik,
			//                                                      "4": Rehabilitasi Psikososial,
			//                                                      "5": Transfusi Darah,
			//                                                      "6": Pelayanan Gigi,
			//                                                      "7": Laboratorium,
			//                                                      "8": USG,
			//                                                      "9": Farmasi,
			//                                                     "10": Lain-Lain,
			//                                                     "11": MRI,
			//                                                     "12": HEMODIALISA}
			// assesmentPel ==2 / 0 (politujuan beda dengan poli rujukan dan hari beda)
			// {"1": Poli spesialis tidak tersedia pada hari sebelumnya,
			//                                                       "2": Jam Poli telah berakhir pada hari sebelumnya,
			//                                                       "3": Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya,
			//                                                       "4": Atas Instruksi RS}
			// dpjpLayan
			// console.log(e.value);
	}
	// $('.btn-cari-dpjp').click(function(){
	// 	var poli = $('#namapoli').text();
	// 	console.log(poli);
	// 	$.post("{!! route('caridpjp') !!}",{dpjp:'layan', poli:poli}).done(function(data){
	// 		if(data.status == 'success'){
	// 			$('.modal-dialog').html(data.content);
	// 		}
	// 	});
	// });
	$('#pilihDokterDpjpLayan').change(function () {
		$('#dpjpLayan').val(this.value);
		// var jnsDpjp = 'pilihDokterDpjpLayan';
		// var id =  $('#pilihDokterDpjpLayan').val();
		// var rawat =  $('input[name=rawat]:checked', '#insert-sep').val();
		// $.post("{!! route('getDokterDpjp') !!}",{idDokterBridg:id, rawat:rawat, jnsDpjp:jnsDpjp}).done(function(result){
		// 	if(result.status == 'success'){
		// 		$('#dpjpLayan').val(result.data.kodeDPJP);
		// 	} else {
		// 		swal('Perhatian', result.message, 'warning');
		// 	}
		// });
	});
</script>
@stop
