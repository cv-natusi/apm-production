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
		#pilihDokterDpjpLayan_chosen, #tujuan_kunjugan_text, #prosedur_bpjs, #assesment_bpjs {
			width: 84% !important;
		}
		.chosen-single > span, .chosen-single > div {
			margin-top: -4px !important;
		}
		.loader {
			border: 7px solid #f3f3f3;
			border-radius: 50%;
			border-top: 7px solid #3498db;
			width: 20px;
			height: 20px;
			-webkit-animation: spin 2s linear infinite; /* Safari */
			animation: spin 1s linear infinite;
		}
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			Pembuatan SEP (SURAT ELIGIBILITAS PESERTA)
			<!-- <small>Control panel</small> -->
		</h1>
	</section>
	<div style="width: 65%; float: left;">
		<div class="content">
			<div style="width: 100%;">
				<div class="box box-default main-layer">
					<form id="insert-sep">
						<input type="hidden" name="metode" id="metode" value="{{$data['ambil']}}">
						@if ($data['ambil']=='KIOSK')
						<input type="hidden" name="id_antrian" id="id_antrian" value="{{$data['antrian']->id}}">
						@elseif($data['ambil']=='WA')
						<input type="hidden" name="cust_id" id="id_antrian" value="{{$data['antrian']->cust_id}}">
						@elseif($data['ambil']=='SIMAPAN')
						<input type="hidden" name="id_pas" id="id_antrian" value="{{$data['antrian']->id_pas}}">
						@endif
						<div class="box-header">
							<div class="form-group">
									<?php // date_default_timezone_set('UTC') ?>
								<label class="pad col-lg-2 col-md-2">Tanggal</label>
								<div class="col-lg-5 col-md-4">
									<input type="date" name="Tanggal" class="" value="{!! date('Y-m-d') !!}" style="width: 84%;" readonly>
								</div>
								<label class="pad col-lg-2 col-md-2">Jam</label>
								<div class="col-lg-3 col-md-4">
									<input type="time" name="jam" class="" value="{!! date('H:i:s') !!}" id="clockDisplay" readonly style="width: 100%;">
								</div>
							</div>
						</div>
						<hr style="margin-top: 0px; margin-bottom: 0px;">
						<div class="box-main">
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2"></label>
								<div class="col-lg-5 col-md-4">
									<input type="radio" name="jnsRujukan" value="nonIgd" id='nonIgd' checked> <label for='nonIgd'>Rujukan</label>&nbsp;
									<input type="radio" name="jnsRujukan" value="igd" id='igd'> <label for='igd'>Rujukan Manual/IGD</label>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group ">
								<div class="pad col-lg-2 col-md-2">
									<input type="checkbox" name="karyawan" class="" value="Y" id="karyawan">
									<label for="karyawan">Karyawan</label>
								</div>
								<div class="col-lg-5 col-md-4">
									<input type="radio" name="rawat" class="" value="2" id="jalan" checked="">
									<label for="jalan">Rawat Jalan</label>&nbsp;
									<input type="radio" name="rawat" class="" value="1" id="inap">
									<label for="inap">Rawat Inap</label>
								</div>
								<div class="col-lg-5 col-md-6 pad">
									<!--<input type="radio" name="laka" class="" value="1" id="laka">
									<label for="laka">Laka Lantas</label>&nbsp;
									<input type="radio" name="laka" class="" value="0" id="tidaklaka" checked="true">
									<label for="tidaklaka">Tidak Laka Lantas</label> -->
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
									<input type="checkbox" name="cob" class="" value="1" id="cob">
									<label class="" for="cob">Peserta COB</label>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
								<label id="cariLabel" class="pad col-lg-2 col-md-2">No. BPJS</label>
								<div class="col-lg-5 col-md-4">
									<input type="text" name="nokartu" class="cari cariNoka" placeholder="Cari No. Kartu Peserta" 
									id="nokepesertaan" style="width: 84%;" value="{{ (isset($data['noka']) ? $data['noka']:'') }}">

									<input type="text" name="nonik" class="cari cariNik" placeholder="Cari NIK Peserta" 
									id="nonik" style="width: 84%; display: none;" value="{{ (isset($data['antrian']->nik) ? $data['antrian']->nik:'') }}">
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
									<!-- <input type="text" name="ppk_rujukan" id="ppk-rujukan"> -->
									<input type="text" name="ppk_rujukan" class="" placeholder="Cari Faskes Rujukan" id="ppk-rujukan" style="width: 84%;">
									<button class="btn-cari-ppk" type="button">:</button>
								</div>
								<label class="pad col-lg-2 col-md-2">Tgl Rujukan</label>
								<div class="col-lg-3 col-md-4">
									<input type="date" name="tgl_rujukan" class="tglRujuk" id="clockDisplaysep" value="{!! date('Y-m-d') !!}" style="width: 100%;">
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
								<label class="pad col-lg-2 col-md-2">No. Rujukan<font style="color:red;">*</font></label>
								<div class="col-lg-3 col-md-4">
									<input type="text" name="no_rujukan" id='nRujuk' class="" placeholder="Nomor Rujukan" style="width: 100%;" 
									oninput="this.value = this.value.toUpperCase()" value="{{(!empty($data['is_skdp']) && $data['is_skdp']!='Y' && !empty($data['antrian']->nomor_referensi) ? $data['antrian']->nomor_referensi : '')}}">
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
								<label class="pad col-lg-2 col-md-2">Katarak?</label>
								<div class="col-lg-5 col-md-4">
									<input type="radio" name="katarak" value="0" id='T' checked> <label for='T'>Tidak</label>&nbsp;
									<input type="radio" name="katarak" value="1" id='Y'> <label for='Y'>Ya</label>
								</div>
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
									<input type="text" name="no_surat"  class="" id="noSurat" placeholder="Nomor SKDP" value="{{!empty($data['is_skdp']) && $data['is_skdp']=='Y' && !empty($data['antrian']->nomor_referensi)?$data['antrian']->nomor_referensi:''}}"  style="width: 100%;" oninput="this.value = this.value.toUpperCase()">
									<span style="position: absolute;right: 18px;top: 1px;">
										<a href="javascript:void(0)" onclick="cekSkdp()" class="btn btn-xs btn-success btn-cekSkdp"><i class="fa fa-search"></i></a>
									</span>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2">DPJP Layan</label>
								<div class="col-lg-5 col-md-4">
									<input type="hidden" name="dpjpLayan" id='dpjpLayan' class="" placeholder="Kode DPJP Layan" style="width: 84% !important;" readonly="true">
									<span class="panelDokterDpjp3" style="display: show;">
										<input type="text" name="dpjp_layan" class="" placeholder="Dokter DPJP Layan" id="dpjp_layan" style="width: 84%;">
										<button class="btn-cari-dpjp" type="button">:</button>
									</span>
								</div>
								<label class="pad col-lg-2 col-md-2" col-md-4>Dokter Perujuk</label>
								<div class="col-lg-3 col-md-4">
									<input type="hidden" name="kdDpjp" id='kdDpjp' class="" placeholder="Kode DPJP" style="width: 100%;" readonly="true">
									<span class="panelDokterDpjp2" style="display: show;">
										<input type="text" name="dpjp_rujuk" class="" placeholder="Dokter Perujuk" id="dpjp_rujuk" style="width: 89%;">
										<button class="btn-cari-perujuk" type="button">:</button>
									</span>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group">
								<label class="pad col-lg-2 col-md-2">Kelas Rawat </label>
								<div class="col-lg-4 col-md-4">
									{{-- <input type="hidden" name="kelasrawat" value="" id="kelasrawat"> --}}
									<input type="radio" name="kelasrawat" class="" value="1" id="kelas1">
									<label for="kelas1">Kelas 1</label>&nbsp;
									<input type="radio" name="kelasrawat" class="" value="2" id="kelas2">
									<label for="kelas2">Kelas 2</label>
									<input type="radio" name="kelasrawat" class="" value="3" id="kelas3">
									<label for="kelas3">Kelas 3</label>
									<label><input type="checkbox" id="chkNaikKelas"> Naik Kelas Rawat Inap</label>
								</div>
							</div>
							<div class="divNaikKelas" style="display:none">
								<div class="clearfix"></div>
								<div class="form-group">
									<label class="pad col-lg-2 col-md-2">Kelas Rawat Inap</label>
									<div class="col-lg-5 col-md-4">
										{{-- <input type="text" name="kelasRawatInap" class="" id="kelasRawatInap" placeholder="Catatan" value="-" style="width: 84%;"> --}}
										<input type="radio" name="naikKelasRawat" class="" value="1" id="kelasRawatInapVVIP">
										<label for="kelasRawatInapVVIP">VVIP</label>
										<input type="radio" name="naikKelasRawat" class="" value="2" id="kelasRawatInapVIP">
										<label for="kelasRawatInapVIP">VIP</label>
										<input type="radio" name="naikKelasRawat" class="" value="6" id="kelasRawatInapICCU">
										<label for="kelasRawatInapICCU">ICCU</label>
										<input type="radio" name="naikKelasRawat" class="" value="7" id="kelasRawatInap3">
										<label for="kelasRawatInapICU">ICU</label>
										<br>
										<input type="radio" name="naikKelasRawat" class="" value="3" id="kelasRawatInap1">
										<label for="kelasRawatInap1">Kelas 1</label>
										<input type="radio" name="naikKelasRawat" class="" value="4" id="kelasRawatInap2">
										<label for="kelasRawatInap2">Kelas 2</label>
										<input type="radio" name="naikKelasRawat" class="" value="5" id="kelasRawatInap3">
										<label for="kelasRawatInap3">Kelas 3</label>
									</div>
									<label class="pad col-lg-2 col-md-2">Pembiayaan</label>
									<div class="col-lg-3 col-md-4">
										<select class="" name="pembiayaan" id="NaikKelas_cbPembiayaan" style="width: 100%;">
												<option value="" selected disabled>Pilih Pembiayaan</option>
												<option value="1">Pribadi</option>
												<option value="2">Pemberi Kerja</option>
												<option value="3">Asuransi Kesehatan Tambahan</option>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="form-group">
									<label class="pad col-lg-2 col-md-2">Nama Penanggung Jawab</label>
									<div class="col-lg-3 col-md-4">
										<textarea class="form-control" name="namaPenanggungJawab" id="namaPenanggungJawab" rows="2" style="width: 315px;height: 85px;" placeholder="Jika Pembiayaan Oleh [Pemberi Kerja] atau [Asuransi Kesehatan Tambahan]" maxlength="255"></textarea>
									</div>
								</div>
							</div>
							<!-- <div class="form-group">
								<label class="pad col-lg-2 col-md-2">DPJP Layan</label>
								<div class="col-lg-5 col-md-4">
									<input type="hidden" name="dpjpLayan" id='dpjpLayan' class="" placeholder="Dokter DPJP" style="width: 84% !important;" readonly="true">
									<span class="panelDokterDpjp3" style="display: show;">
										<select name="id_dokterDpjpLayan" class="" id="pilihDokterDpjpLayan" style="width: 84% !important;">
											<option>.: Dokter DPJP Yang Melayani :. </option>
										</select>
									</span>
								</div>
								<label class="pad col-lg-2 col-md-2" col-md-4>Dokter Perujuk</label>
								<div class="col-lg-3 col-md-4">
									<input type="hidden" name="namaDpjp" id='namaDpjp' class="" placeholder="Dokter DPJP" style="width: 100%;" readonly="true">
									<span class="panelDokterDpjp2" style="display: show;">
										<select name="id_dokterDpjp" class="" id="pilihDokterDpjp" style="width: 100%;">
											<option>.: Pilih Dokter DPJP :. </option>
										</select>
									</span>
								</div>
							</div> -->
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
								<label class="pad col-lg-2 col-md-2">Status Kecelakaan<font style="color:red;">*</font>
								</label>
								<div class="col-lg-5 col-md-4">
									<select name="statuslaka" class="" id="statuslaka" style="width: 84%;">
											<option value="" disabled="">.: Pilih Status Laka :.</option>
											<option value="0" selected>Bukan Kecelakaan Lalu Lintas (BKLL)</option>
											<option value="1">Kecelakaan Lalu Lintas</option>
											<option value="2">Kecelakaan Lalu Lintas Dan Kecelakaan Kerja</option>
											<option value="3">Kecelakaan Kerja</option>
									</select>
								</div>
								<label class="pad col-lg-2 col-md-2 laka">Tgl Kecelakaan</label>
								<div class="col-lg-3 col-md-4 laka">
									<input type="date" name="tgl_laka" class="" id="tgl_laka" value=""  style="width: 100%;">
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
							<center style='margin-top: 15px'>
								<a href='javascript:void(0)' class="btn btn-lg btn-primary" id='btn-sep' disabled="true"><i class="fa fa-save"></i> SIMPAN</a>
								<a href='javascript:void(0)' class="btn btn-lg btn-primary" id='btn-edit-sep' style="display: none;"><i class="fa fa-save"></i> Update</a>
								<button type='button' class="btn btn-lg btn-success" id='btn-print-sep' disabled="true" ><i class="fa fa-print"></i> Cetak</button>
								<button type='button' class="btn btn-lg btn-danger" id='btn-delete-sep' disabled="true" ><i class="fa fa-trash"></i> Hapus</button>
								<button type='button' class="btn btn-lg btn-info" id='btn-update-pulang-sep' disabled="true" ><i class="fa fa-home"></i> Update Tgl Pulang</button>
								<input type="hidden" name="nosepcetak" id="nosepcetak">
								<input type="hidden" name="noarsip" id="noarsip">
								<input type="hidden" name="noKontrol" id="noKontrol" value=''>
								{{-- <input type="hidden" name="kdDpjp" id="kdDpjp" value=''> --}}
								<input type="hidden" name="tingkatRujuk" id="tingkatRujuk" value=''>
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
			<!-- CARI SEP -->
			<div style="width: 100%;">
				<div class="box box-default main-layer">
					<div class="box-header">
						<h3 style="margin:auto;">Cari No. SEP</h3>
					</div>
					<hr style="margin-top: 0px; margin-bottom: 0px;">
					<div class="box-header">
						<div class="form-group">
							<input type="text" name="cariSep" id="cariSep" class="form-control" placeholder="Masukkan No. SEP" oninput="this.value = this.value.toUpperCase()" style="width: 100%;">
						</div>
					</div>
					<div class='clearfix'></div>
				</div>
			</div>

			<!-- DATA PASIEN -->
			<!-- <div style="width: 100%;">
				<div class="box box-default main-layer">
					<div class='panel-label_header col-lg-12 col-md-12 col-sm-12 col-xs-12' style="margin-top:0px; margin-bottom: 15px; ">
						<h3>Pencarian SEP</h3>
					</div>
					<hr>
					<form>
					<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
						<table class="table table-striped">
							<tr>
								<td>
									<input type="text" name="no_sep" id='no_sep' class="" placeholder="Input Nomor SEP" style="width: 100%;" oninput="this.value = this.value.toUpperCase()">
									<span style="position: absolute;right: 30px;top: 4px;">
										<a href="javascript:void(0)" onclick="cekSEP()" class="btn btn-xs btn-success"><i class="fa fa-search"></i></a>
									</span>
								</td>
							</tr>
						</table>
					</div>
					<hr>
					<div class='clearfix'></div>
				</div>
			</div>
			<div class="clearfix"></div> -->
			<div  style="width: 100%;">
				<div class="box box-default main-layer">
					<div class='panel-label_header col-lg-12 col-md-12 col-sm-12 col-xs-12' style="margin-top:0px; margin-bottom: 15px; ">
						<h3>Data Pasien BPJS</h3>
					</div>
					<hr>
					<form>
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
								<td>: <span id="kdprov_bpjs"></span></td>
							</tr>
							<tr>
								<td>Nama Provider</td>
								<td>: <span id="nmprov_bpjs"></span></td>
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
							<tr>
								<td>Indikasi PRB</td>
								<td>: <span id="prbPeserta"></span></td>
							</tr>
							<tr>
								<td>Dinsos</td>
								<td>: <span id="dinsosPeserta"></span></td>
							</tr>
						</table>
					</div>
					<hr>
					<div class='clearfix'></div>
				</div>
			</div>
			<!-- HISTORY PASIEN -->

			<div class='clearfix'></div>

			<!-- NO SEP INTERNAL -->
			<div style="width: 100%;">
				<div class="box box-default main-layer" style="max-width: 100%; overflow: auto;">
					<div style="width: 900px">
						<table border='1' class="sepInternal">
							<thead>
								<tr style="background: #ccc">
									<th width="20" style="padding-left: 5px;">&nbsp</th>
									<th width="150" style="padding-left: 5px;">No SEP Internal</th>
									<th width="150" style="padding-left: 5px;" id="">No SEP Induk</th>
									<th width="150" style="padding-left: 5px;">Poli Asal</th>
									<th width="150" style="padding-left: 5px;">Tujuan Rujuk</th>
									<th width="130" style="padding-left: 5px;">Tgl Rujuk Internal</th>
									<th width="150" style="padding-left: 5px;">Action</th>
								</tr>
							</thead>
							<tbody id="resultSepInternal">
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
						<tbody id="resulthistory2">
						</tbody>
					</table>
					<div class="clearfix" style="margin-bottom: 20px;"></div>
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
		$('.laka').hide();
		$('.sup').hide();
		$('#btn-delete-sep').hide();
		$('#btn-update-pulang-sep').hide();
		
		var no = $('#nokepesertaan').val();
		var jnsCari =  $('input[name=jnsCari]:checked').val();
		if (jnsCari == 'nik') {
			no = $('#nonik').val();
		}

		if(no){
			$.post("{!! route('cekpeserta') !!}",{nobpjs:no, jnsCari:jnsCari}).done(function(result){
				if(result.metaData.code == '200'){
					if(result.response.peserta.statusPeserta.keterangan != "AKTIF"){
						swal("Peringatan",result.response.peserta.statusPeserta.keterangan,"info");
					}
					
					console.log(result.response.peserta)
					var kdcb = (result.response.peserta.provUmum.kdCabang) ? result.response.peserta.provUmum.kdCabang : '';
					var nmcb = (result.response.peserta.provUmum.nmCabang) ? result.response.peserta.provUmum.nmCabang : '';
					var umur = (result.response.peserta.umur.umurSaatPelayanan) ? result.response.peserta.umur.umurSaatPelayanan.split(" ")[0] : '';
					var notelp = (result.response.peserta.mr.noTelepon) ? result.response.peserta.mr.noTelepon : '000000000';
					$('#nama_bpjs').html(result.response.peserta.nama);
					$('#sex_bpjs').html(result.response.peserta.sex);
					$('#tgl_bpjs').html(result.response.peserta.tglLahir);
					$('#kdprov_bpjs').html(result.response.peserta.provUmum.kdProvider);
					$('#nmprov_bpjs').html(result.response.peserta.provUmum.nmProvider);
					$('#kdcb_bpjs').html(kdcb);
					$('#nmcb_bpjs').html(nmcb);
					$('#telpbpjs').html(result.response.peserta.mr.noTelepon);
					$('#cetakbpjs').html(result.response.peserta.tglCetakKartu);
					$('#jnps_bpjs').html(result.response.peserta.jenisPeserta.keterangan);
					$('#kltg_bpjs').html(result.response.peserta.hakKelas.keterangan);
					$('#statusbpjs').html(result.response.peserta.statusPeserta.keterangan);
					$('#prbPeserta').html(result.response.peserta.informasi.prolanisPRB);
					$('#dinsosPeserta').html(result.response.peserta.informasi.dinsos);
					$('#nonik').val(result.response.peserta.nik);
					$('#nokepesertaan').val(result.response.peserta.noKartu);
					// $('#rm').val(result.response.peserta.mr.noMR);
					$('#btn-sep').attr('disabled', false).html('<i class="fa fa-save"></i> SIMPAN');

					// tambahan
					// $('#kelasrawat').val(result.response.peserta.hakKelas.kode);
					if (result.response.peserta.hakKelas.kode == '1') {
						$('#kelas1').attr('checked', true);
						$('#kelas2').attr('disabled', true);
						$('#kelas3').attr('disabled', true);
					}else if (result.response.peserta.hakKelas.kode == '2') {
						$('#kelas2').attr('checked', true);
						$('#kelas1').attr('disabled', true);
						$('#kelas3').attr('disabled', true);
					}else {
						$('#kelas3').attr('checked', true);
						$('#kelas1').attr('disabled', true);
						$('#kelas2').attr('disabled', true);
					}
					$('#namapasien').val(result.response.peserta.nama);
					$('#umur').val(umur);
					if(result.tm_cust != ""){
						$('#rm').val(result.tm_cust.KodeCust);
						$('#alamatpasien').val(result.tm_cust.Alamat);
					}else{
						$('#alamatpasien').val('-');
					}
					$('#tgllahir').val(result.response.peserta.tglLahir);
					$('#notelp').val(notelp);
					if (result.response.peserta.sex == 'P') {
						$('#P').attr('checked', true);
					} else {
						$('#L').attr('checked', true);
					}
					historySEP(no);
				}else{
					swal("Peringatan!", result.metaData.message, "info");
					$('#nama_bpjs').html('');
					$('#sex_bpjs').html('');
					$('#tgl_bpjs').html('');
					$('#kdprov_bpjs').html('');
					$('#nmprov_bpjs').html('');
					$('#telpbpjs').html('');
					$('#cetakbpjs').html('');
					$('#kdcb_bpjs').html('');
					$('#nmcb_bpjs').html('');
					$('#jnps_bpjs').html('');
					$('#kltg_bpjs').html('');
					$('#statusbpjs').html('');
					$('#prbPeserta').html('');
					$('#dinsosPeserta').html('');
				}
			});
		}else{
			console.log('kosong')
		}
	});
	// JENIS RUJUKAN
	$('#nonIgd').click(function(){
		// $("#ppk-rujukan").val("");
		$("#ppk-rujukan").attr("readonly", false);
		$("#kdpoli").val("");
		$("#namapoli").text("");
		$("#nRujuk").val("");
		$("#nRujuk").attr("readonly", false);
		// $(".btnRefresh").attr("disabled", false);
	});
	$('#igd').click(function(){
		// $("#ppk-rujukan").val("");
		$("#ppk-rujukan").attr("readonly", true);
		$("#kdpoli").val("IGD");
		$("#namapoli").text("INSTALASI GAWAT DARURAT");
		$("#nRujuk").val("");
		$("#nRujuk").attr("readonly", false);
		// $(".btnRefresh").attr("disabled", true);
	});
	// JENIS PELAYANAN
	$('#inap').click(function(){
		$('#ppk-rujukan').val('1320R001');
	});
	$('#jalan').click(function(){
		$('#ppk-rujukan').val('');
	});
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
	// SUPLESI
	$('#suplesi0').click(function(){
		$(".sup").hide();
	});
	$('#suplesi1').click(function(){
		$(".sup").show();
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

	$('#pilihDokterDpjp').chosen();
	$('#pilihDokterDpjpLayan').chosen();

	//Cek No. BPJS / NIK
	$('.cari').change(function () {
		var no =  $('#nokepesertaan').val();
		var jnsCari =  $('input[name=jnsCari]:checked').val();
		if (jnsCari == 'nik') {
			no = $('#nonik').val();
		}

		$.post("{!! route('cekpeserta') !!}",{nobpjs:no, jnsCari:jnsCari}).done(function(result){
			if(result.metaData.code == '200'){
				if(result.response.peserta.statusPeserta.keterangan != "AKTIF"){
					swal("Peringatan",result.response.peserta.statusPeserta.keterangan,"info");
				}

				var kdcb = (result.response.peserta.provUmum.kdCabang) ? result.response.peserta.provUmum.kdCabang : '';
				var nmcb = (result.response.peserta.provUmum.nmCabang) ? result.response.peserta.provUmum.nmCabang : '';
				var umur = (result.response.peserta.umur.umurSaatPelayanan) ? result.response.peserta.umur.umurSaatPelayanan.split(" ")[0] : '';
				var notelp = (result.response.peserta.mr.noTelepon) ? result.response.peserta.mr.noTelepon : '000000000';
				$('#nama_bpjs').html(result.response.peserta.nama);
				$('#sex_bpjs').html(result.response.peserta.sex);
				$('#tgl_bpjs').html(result.response.peserta.tglLahir);
				$('#kdprov_bpjs').html(result.response.peserta.provUmum.kdProvider);
				$('#nmprov_bpjs').html(result.response.peserta.provUmum.nmProvider);
				$('#kdcb_bpjs').html(kdcb);
				$('#nmcb_bpjs').html(nmcb);
				$('#telpbpjs').html(result.response.peserta.mr.noTelepon);
				$('#cetakbpjs').html(result.response.peserta.tglCetakKartu);
				$('#jnps_bpjs').html(result.response.peserta.jenisPeserta.keterangan);
				$('#kltg_bpjs').html(result.response.peserta.hakKelas.keterangan);
				$('#statusbpjs').html(result.response.peserta.statusPeserta.keterangan);
				$('#prbPeserta').html(result.response.peserta.informasi.prolanisPRB);
				$('#dinsosPeserta').html(result.response.peserta.informasi.dinsos);
				$('#nonik').val(result.response.peserta.nik);
				$('#nokepesertaan').val(result.response.peserta.noKartu);
				$('#btn-sep').attr('disabled', false).html('<i class="fa fa-save"></i> SIMPAN');

				// tambahan
				// $('#kelasrawat').val(result.response.peserta.hakKelas.kode);
				if (result.response.peserta.hakKelas.kode == '1') {
					$('#kelas1').attr('checked', true);
					$('#kelas2').attr('disabled', true);
					$('#kelas3').attr('disabled', true);
				}else if (result.response.peserta.hakKelas.kode == '2') {
					$('#kelas2').attr('checked', true);
					$('#kelas1').attr('disabled', true);
					$('#kelas3').attr('disabled', true);
				}else {
					$('#kelas3').attr('checked', true);
					$('#kelas1').attr('disabled', true);
					$('#kelas2').attr('disabled', true);
				}
				$('#namapasien').val(result.response.peserta.nama);
				$('#umur').val(umur);
				$('#alamatpasien').val('-');
				$('#tgllahir').val(result.response.peserta.tglLahir);
				$('#notelp').val(notelp);
				if (result.response.peserta.sex == 'P') {
					$('#P').attr('checked', true);
				} else {
					$('#L').attr('checked', true);
				}
			}else{
				swal("Peringatan!", result.metaData.message, "info");
				$('#nama_bpjs').html('');
				$('#sex_bpjs').html('');
				$('#tgl_bpjs').html('');
				$('#kdprov_bpjs').html('');
				$('#nmprov_bpjs').html('');
				$('#telpbpjs').html('');
				$('#cetakbpjs').html('');
				$('#kdcb_bpjs').html('');
				$('#nmcb_bpjs').html('');
				$('#jnps_bpjs').html('');
				$('#kltg_bpjs').html('');
				$('#statusbpjs').html('');
				$('#prbPeserta').html('');
				$('#dinsosPeserta').html('');
			}
		});
		historySEP(no);
	});

	function historySEP(no) {
		var dateAwal = $('#dateHistoryAwal').val();
		var dateAkhir = $('#dateHistoryAkhir').val();
		$.post("{!! route('cariHistorySEP') !!}", {no:no,dateAwal:dateAwal,dateAkhir:dateAkhir}).done(function(result){
			if (result.metaData.code == '200') {
				var dat ='';
				var i = 1;
				$.each(result.response.histori, function(c,v){
					var jnsPelayanan = v.jnsPelayanan;
					if (jnsPelayanan == 1) {jnsPelayanan = "Rawat Inap";}else {jnsPelayanan = "Rawat Jalan";}
					var poliTujSep = v.poliTujSep;
					if (poliTujSep == null) {poliTujSep = "-";}else {v.poliTujSep}
					dat += '<tr>'+
						'<td>'+i+'</td>'+
						'<td>'+v.poli+'</td>'+
						'<td>'+v.noSep+'</td>'+
						'<td>'+v.namaPeserta+'</td>'+
						'<td>'+v.noKartu+'</td>'+
						'<td>'+jnsPelayanan+'</td>'+
						'<td>'+v.noRujukan+'</td>'+
						'<td>'+v.tglSep+'</td>'+
						'<td>'+v.ppkPelayanan+'</td>'+
						`</tr>`;
					i++;
				});

				$('#resulthistory2').html(dat);
			}else {
				$('#resulthistory2').html('<tr><td colspan="9">'+result.metaData.message+'</td></tr>');
			}
		});
	}
	$('#btnCariHistori').click(function(){
		var no = $('#nokepesertaan').val();
		var dateAwal = $('#dateHistoryAwal').val();
		var dateAkhir = $('#dateHistoryAkhir').val();
		$('#btnCariHistori').html('<div class="loader"></div>').attr('disabled',true)
		$.post("{!! route('cariHistorySEP') !!}", {no:no,dateAwal:dateAwal,dateAkhir:dateAkhir}).done(function(result){
			if (result.metaData.code == '200') {
				var dat ='';
				var i = 1;
				$.each(result.response.histori, function(c,v){
					var jnsPelayanan = v.jnsPelayanan;
					if (jnsPelayanan == 1) {jnsPelayanan = "Rawat Inap";}else {jnsPelayanan = "Rawat Jalan";}
					var poliTujSep = v.poliTujSep;
					if (poliTujSep == null) {poliTujSep = "-";}else {v.poliTujSep}
					dat += '<tr>'+
						'<td>'+i+'</td>'+
						'<td>'+v.poli+'</td>'+
						'<td>'+v.noSep+'</td>'+
						'<td>'+v.namaPeserta+'</td>'+
						'<td>'+v.noKartu+'</td>'+
						'<td>'+jnsPelayanan+'</td>'+
						'<td>'+v.noRujukan+'</td>'+
						'<td>'+v.tglSep+'</td>'+
						'<td>'+v.ppkPelayanan+'</td>'+
						`</tr>`;
					i++;
				});

				$('#resulthistory2').html(dat);
				$('#btnCariHistori').html('<i class="fa fa-search" aria-hidden="true"></i>').attr('disabled',false)
			} else {
				swal('Maaf',result.metaData.message,'error')
				$('#btnCariHistori').html('<i class="fa fa-search" aria-hidden="true"></i>').attr('disabled',false)
			}
		});
	});
	$('#pilihDokterDpjp').change(function () {
		var id =  $('#pilihDokterDpjp').val();
		var rawat = $('input[name=rawat]:checked').val();
		$.post("{!! route('getDokterDpjp') !!}",{idDokterBridg:id, rawat: rawat}).done(function(result){
			console.log(result);
			if(result.status == 'success'){
				$('#namaDpjp').val(result.data.namaDPJP);
				$('#kdDpjp').val(result.data.kodeDPJP);
			} else {
				swal('Perhatian', result.message, 'warning');
			}
		});
	});
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

	$('#btn-update-pulang-sep').click(function(){
		var noka = $('#cariSep').val();
		var tglSEP = $('#clockDisplaysep').val();
		var strPesan = 'belum dipulangkan di RSUD DR. W. SUDIROHUSODO';
		$.post('{!! route("formupdatetglpulang") !!}',{nobpjs:noka,pesan:strPesan,tglSEP:tglSEP}).done(function(result){
			$('#updatetglpulang').html(result.content);
		});
	});
	$('.btn-cari-peserta').click(function(){
		$.post("{!! route('carifromrs') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	// TAMBAHAN
	$('.btn-cari-ppk').click(function(){
		$.post("{!! route('carippk') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('.btn-cari-dpjp').click(function(){
		var poli = $('#namapoli').text();
		$.post("{!! route('caridpjp') !!}",{dpjp:'layan', poli:poli}).done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('.btn-cari-perujuk').click(function(){
		var poli = $('#namapoli').text();
		$.post("{!! route('caridpjp') !!}",{dpjp:'perujuk', poli:poli}).done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('#chkNaikKelas').change(function() {
		if(this.checked) {
			$('.divNaikKelas').show();
		}else{
			$('.divNaikKelas').hide();
		}
	});

	$('#cariSep').change(function () {
		var noSep =  $('#cariSep').val();
		$.post("{!! route('cariSEP') !!}", {noSep:noSep}).done(function(result){
			if (result.metaData.code == '200') {
				if (result.response) {
					$('#nosepcetak').val(result.response.noSep);
					$('#clockDisplaysep').val(result.response.tglSep);
					if (result.response.jnsPelayanan == 'Rawat Jalan') {
						$('input[name=rawat][value="2"]').prop('checked', true);
					} else if(result.response.jnsPelayanan == 'Rawat Inap') {
						$('input[name=rawat][value="1"]').prop('checked', true);
					}
					$('#namapoli').text('POLI SPESIALIS '+result.response.poli);
					$('#textDiagnosa').text(result.response.diagnosa);
					$('#nRujuk').val(result.response.noRujukan);

					$('#nokepesertaan').val(result.response.peserta.noKartu);
					$('#nama_bpjs').text(result.response.peserta.nama);
					$('#tgl_bpjs').text(result.response.peserta.tglLahir);
					$('#rm').val(result.response.peserta.noMr);
					if (result.response.peserta.kelamin == 'L') {
						$('input[name=sex][value="L"]').prop('checked', true);
					} else if(result.response.peserta.kelamin == 'P') {
						$('input[name=sex][value="P"]').prop('checked', true);
					}

					$('#sex_bpjs').text(result.response.peserta.kelamin);
					$('#jnps_bpjs').text(result.response.peserta.jnsPeserta);
					$('#kltg_bpjs').text(result.response.peserta.hakKelas);

					$('#dpjpLayan').val(result.response.dpjp.kdDPJP);
					$('#dpjp_layan').val(result.response.dpjp.nmDPJP);
					$('#kdDpjp').val(result.response.dpjp.kdDPJP);
					$('#dpjp_rujuk').val(result.response.dpjp.nmDPJP);

					$('#valDiagnosa').val(result.kodediagnosa);
					// $('#kdpoli').val(result.kodepoli);
					$('#namapasien').val(result.response.peserta.nama);
					$('#tgllahir').val(result.response.peserta.tglLahir);
					$('#catatan').val(result.response.catatan);
					$('#alamatpasien').val('-');
					$('#notelp').val('00000000');

					// READONLY
					$('input[name=jnsRujukan]').prop('disabled', true);
					// $('input[name=rawat]').prop('disabled', true);
					$('#nokepesertaan').prop('readonly', true);
					$('.btn-cari-peserta').hide();
					$('#clockDisplaysep').prop('readonly', true);
					$('input[name=tgl_rujukan]').prop('readonly', true);
					// $('.btn-cari-ppk').hide();
					$('#kdpoli').prop('readonly', true);
					// $('.btn-cari-poli').hide();
					$('#ppk-rujukan').prop('readonly', true);
					$('#nRujuk').prop('readonly', true);

					// SEKALIAN CARI RUJUKAN
					if(result.response.noRujukan){
						reRujuk();
						countRujukan();
					}

					if(result.response.kontrol.noSurat){
						$('#noSurat').val(result.response.kontrol.noSurat);
						$('#dpjpLayan').val(result.response.kontrol.kdDokter);
						$('#dpjp_layan').val(result.response.kontrol.nmDokter);
						$('#kdDpjp').val(result.response.kontrol.kdDokter);
						$('#dpjp_rujuk').val(result.response.kontrol.nmDokter);
						$('#kdpoli').prop('readonly', false);
						$('.btn-cari-poli').show();
					}

					// $('#btn-sep').hide();
					$('#btn-sep').removeAttr('disabled');
					$('#btn-edit-sep').show();
					$('#btn-print-sep').removeAttr('disabled');
					$('#btn-delete-sep').removeAttr('disabled');
					$('#btn-delete-sep').show();
					if (result.response.jnsPelayanan == 'Rawat Inap') {
						$('#btn-update-pulang-sep').removeAttr('disabled');
						$('#btn-update-pulang-sep').show();
					}else {
						$('#btn-update-pulang-sep').attr('disabled',true);
						$('#btn-update-pulang-sep').hide();
					}
				}
			} else if (result.metaData.code == '201') {
				swal('Maaf', result.metaData.message,'warning')
			}else{
				swal('Maaf', result.metaData.code,'warning')
			}
		});

		SEPInternal(noSep);
	});

	function SEPInternal(noSep) {
		$.post("{!! route('cariSEPInternal') !!}", {noSep:noSep}).done(function(result){
			if (result.metaData.code == '200') {
				var dat ='';
				var i = 1;
				$.each(result.response.list, function(c,v){
					dat += '<tr>'+
						'<td>'+i+'</td>'+
						'<td>'+v.nosurat+'</td>'+
						'<td>'+v.nosep+'</td>'+
						'<td>'+v.nmpoliasal+'</td>'+
						'<td>'+v.nmtujuanrujuk+'</td>'+
						'<td>'+v.tglrujukinternal+'</td>'+
						`<td><a href="#" onclick='destroySEPInternal(${JSON.stringify(v)})'>hapus</a></td></tr>`;
					i++;
				});

				$('#resultSepInternal').html(dat);
			} else if (result.metaData.code == '201') {
				dat += '<tr>'+
					`<td colspan="7">Tidak Ditemukan</td></tr>`;
			$('#resultSepInternal').html(dat);
			}else{
				swal('Maaf', result.metaData.code,'warning')
			}
		});
	}

	function destroySEPInternal(obj){
		swal({
			title: "Hapus Data Ini?",
			text: "Data akan dihapus dari sistem dan tidak dapat dikembalikan!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Hapus",
			cancelButtonText: "Batal",
			closeOnConfirm: false
		}, function(){
			$.post("{!! route('deleteSEPInternal') !!}", {nsep:obj.nosep, nsurat:obj.nosurat, tgl:obj.tglrujukinternal, tuj:obj.tujuanrujuk}).done(function(data){
				if(data.metaData.code == '200'){
					swal("Success!", "Berhasil Menghapus Data!", "success");
					SEPInternal(obj.nosep);
				} else {
					swal('Perhatian!',data.metaData.message,'warning');
				}
			});
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

	$('#btn-edit-sep').click(function(){
		var rawat = $('input[name=rawat]:checked').val();
		var noSep = $('#cariSep').val();
		var data = new FormData($('#insert-sep')[0]);
		data.append('rawat', rawat);
		data.append('noSep', noSep);
		$.ajax({
			url: '{!! route("updateSEP") !!}',
			type: 'POST',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data){
			if (data.metaData.code == '200') {
				swal("Berhasil!", "No. SEP "+data.response.sep.noSep+" Berhasil di Update!", "success");
			} else {
				var n = 0;
				for (key in data) {
					if (n == 0) {
						var dt0 = key;
					}
					n++;
				}
				swal("Perhatian!", data.metaData.message, "warning");
			}
		});
	});

	$('.btn-suplesi').click(function(){
		$.post("{!! route('cariSuplesi') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	function lokasiLaka(jnsLokasi, prov='0', kab='0'){
		$.post("{!! route('cariLokasiLaka') !!}",{jnsLokasi:jnsLokasi, prov:prov, kab:kab}).done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	}
	// END TAMBAHAN

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
		var metode = $('#metode').val();
		var id_antrian = $('#id_antrian').val();
		var nosep = $('#nosepcetak').val();
		var noarsip = $('#noarsip').val();
		var noRujuk = $('#nRujuk').val();
		$.post("{{ route('cetak_sep') }}",{nosep:nosep, noarsip:noarsip, idAntri:id_antrian, metode:metode}).done(function(data){
			// var telp = $('#notelp').val();
			var telp = data.data.sepValue["no_hp"];
			var fakses = $('#ppk-rujukan').val();
			var penjamin = $('#penjamin').val();
			var diag = $('#valDiagnosa').val();
			var peserta = $('#jnps_bpjs').html();
			var noKontrol = $('#noKontrol').val();
			var prb = (data.data.sepValue["prb"]) ? data.data.sepValue["prb"] : '';
			// console.log(data)
			// return
			if(data.status == 'success'){
				var cetak = '';
				var css = '@page { size: auto !important; }',
				head = document.head || document.getElementsByTagName('head')[0],
				style = document.createElement('style');

				style.type = 'text/css';
				style.media = 'print';

				if (style.styleSheet){
					style.styleSheet.cssText = css;
				} else {
					style.appendChild(document.createTextNode(css));
				}

				head.appendChild(style);
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
				var countPrint = 5; // jumlah halaman print
				for (var p = 1; p <= countPrint; p++) {
					cetak += '<!DOCTYPE html><html><head><meta charset="utf-8"><meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">';
					cetak += '<table border="0">';
					cetak += '<tr>';
					cetak += '<td rowspan="2" width="250px">';
					var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
					cetak += '<img src="'+logoBpjs+'" width="230px" style="margin-left: 5px;">';
					cetak += '</td>';
					cetak += '<td '+stTitle+' colspan="2">SURAT ELEGIBILITAS PESERTA</td>';
					cetak += '</tr>';
					cetak += '<tr>';
					cetak += '<td '+stTitle+'>RSUD dr. Wahidin Sudiro Husodo</td>';
					// cetak += '<td '+stBerkas+'>No.Berkas &nbsp : '+data.data.sepValue["noarsip"]+'</td>';
					cetak += '</tr>';
					cetak += '</table>';
					cetak += '<div style="margin-bottom: 5px;"></div>';
					cetak += '<table border="0">';
					cetak += '<tr>';
					cetak += '<td '+widthJd1+' '+stIsi+'>No.SEP</td>';
					cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["no_sep"]+'</td>';
					cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
					cetak += '<td '+widthIsi2+' '+stIsi+'>'+prb+'</td>';
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
					cetak += '<td '+widthIsi2+' '+stIsi+'>: '+((data.data.respon.response.penjamin) ? data.data.respon.response.penjamin : '-' )+'</td>';
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
					cetak += '<tr>';
					cetak += '<td '+widthJd2+' '+stIsi+'>Kls.Naik</td>';
					cetak += '<td colspan="3" '+stIsi+'>: '+data.data.sepValue["kls_rawatNaik"]+'</td>';
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
					if (p != countPrint){
						cetak += '<div style="page-break-before: always;"></div>';
					}
					cetak += '<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"><\/script></body></html>';
				}
				$('.printSEP').html(cetak);
					$('.printSEP').printArea();
					setTimeout(function tutupCetak() {
						$('.printSEP').empty();
						location.reload(true);
					}, 5000);
			} else {
				swal({
					title : "Maaf !!",
					text: data.metaData.message,
					type : "warning",
					timer : 2000,
					showConfirmButton : false
				});
			}
		});
	});

	// create sep
	$('#btn-sep').click(function(){
		$('#btn-sep').html('<i class="fa fa-spinner" aria-hidden="true"></i> Loading...').attr('disabled', true);
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
			console.log(data)
    		$('.btn-save').attr('disabled', false).html('Simpan <span class="fa fa-save"></span>');
    		$('#form-user').validate(data, 'has-error');
    		if(data.status == 'success'){
				swal("Sukses!", "Data berhasil disimpan!", "success");
				var kd =  data.antrian.kode_booking;
				var status = data.antrian.status;
				var antrian = data.antrian;
				
				if (antrian!='' && status == 'counter') {
					$.post('{{route("counterToPoli")}}',{kode:kd}).done((res)=>{
						if(res.status == 'success'){
							swal({
								title: 'Berhasil',
								type: res.status,
								text: res.message,
								showConfirmButton: true,
								// timer: 1500
							})
							$('#nosepcetak').val(data.nosep);
							$('#noarsip').val(data.noarsip);
							$('#noKontrol').val(data.noKontrol);
							$('#btn-print-sep').removeAttr('disabled');
						}else{
							swal({
								title: 'Whoops',
								type: res.status,
								text: res.message,
							})
							// location.reload();
						}
					})
				// } else if(antrian!='' && status !='counter') {
				} else if(antrian!='' && (jQuery.inArray(status, ['belum','panggil'])!==-1)) {
					$.post('{{route("loketToCounter")}}',{kode:kd}).done((res)=>{
						if(res.status == 'success'){
							swal({
								title: 'Berhasil',
								type: res.status,
								text: res.message,
								showConfirmButton: true,
								// timer: 1500
							})
							$('#nosepcetak').val(data.nosep);
							$('#noarsip').val(data.noarsip);
							$('#noKontrol').val(data.noKontrol);
							$('#btn-print-sep').removeAttr('disabled');
						}else{
							swal({
								title: 'Whoops',
								type: res.status,
								text: res.message,
							})
							// location.reload();
						}
					})
				} else {
					swal("Sukses!", "Data berhasil disimpan!", "success");
					$('#nosepcetak').val(data.nosep);
					$('#noarsip').val(data.noarsip);
					$('#noKontrol').val(data.noKontrol);
					$('#btn-print-sep').removeAttr('disabled');
				}
    		} else if(data.status == 'error'){
					var strPesan =  data.messages;
					if(data.update == 'update'){
						$.post('{!! route("formupdatetglpulang") !!}',{nobpjs:data.nobpjs, pesan : strPesan}).done(function(result){
							$('#updatetglpulang').html(result.content);
						});
					}else if(data.update == 'tingkat'){
						$('#tingkatRujuk').val(data.tingkat);
						$('#ppk-rujukan').val('1320R001');
						swal('Perhatian', 'Silahkan tekan ulang Tombol Simpan !!', 'warning');
					} else {
						if (data.messages == "tujuanKunj tidak sesuai") {
							swal('Perhatian', 'Tujuan Kunjungan Tidak Sesuai', 'warning');
						}else if (data.messages == "assesmentPel tidak sesuai") {
							swal('Perhatian', 'Assesment Pelayanan Tidak Sesuai', 'warning');
						}else {
							swal('Perhatian', data.messages, 'warning');
						}
					}
    		} else {
				var n = 0;
				for (key in data) {
					if (n == 0) {
						var dt0 = key;
					}
					n++;
				}
				swal('Maaf', 'Kolom ' + dt0 + ' Tidak Boleh Kosong!!', 'error');
				$('#btn-sep').attr('disabled', false).html('Simpan <span class="fa fa-save"></span>');
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
    			$('#nRujuk').val(result.data.noRujuk);
        	}else{
        		swal('Peringatan','Pasien tidak menggunakan BPJS','warning');
        	}
        });
	}

	function reRujuk() {
		var noRujuk = $('#nRujuk').val();
		var kdpoli = $('#kdpoli').val();
		var rawat = $('input[name=rawat]:checked').val();
		$.post("{!! route('cekrujukan') !!}",{noRujuk:noRujuk, kdpoli:kdpoli, rawat:rawat}).done(function(resultRujuk){
			if (resultRujuk.status == 'success') {
				if(resultRujuk.data.rujukan){
					var titleDpjp = '';
					var umur = (resultRujuk.data.rujukan.response.rujukan.peserta.umur.umurSaatPelayanan) ? resultRujuk.data.rujukan.response.rujukan.peserta.umur.umurSaatPelayanan.split(" ")[0] : '';
					swal('Berhasil', 'Rujukan Berhasil Ditemukan!', 'success');

					$('.tglRujuk').val(resultRujuk.data.rujukan.response.rujukan.tglKunjungan);
					$('#tingkatRujuk').val(resultRujuk.data.tingkatRujuk);
					$('#ppk-rujukan').val(resultRujuk.data.rujukan.response.rujukan.provPerujuk.kode);
					if (resultRujuk.data.rujukan.response.rujukan) {
						if ($('#cariSep').val() == '') {
							$('#valDiagnosa').val(resultRujuk.data.rujukan.response.rujukan.diagnosa.kode);
							$('#textDiagnosa').text(resultRujuk.data.rujukan.response.rujukan.diagnosa.nama);
						}
						$('#kdpoli').val(resultRujuk.data.rujukan.response.rujukan.poliRujukan.kode);
						$('#namapoli').text('POLI SPESIALIS '+resultRujuk.data.rujukan.response.rujukan.poliRujukan.nama);
					}

					// input tambahan
					$('#umur').val(umur);
					if (resultRujuk.data.rujukan.response.rujukan.peserta.hakKelas.kode == '1') {
						$('#kelas1').attr('checked', true);
					}else if (resultRujuk.data.rujukan.response.rujukan.peserta.hakKelas.kode == '2') {
						$('#kelas2').attr('checked', true);
					}else {
						$('#kelas3').attr('checked', true);
					}
					$('#kltg_bpjs').text(resultRujuk.data.rujukan.response.rujukan.peserta.hakKelas.keterangan);
					$('#kdprov_bpjs').text(resultRujuk.data.rujukan.response.rujukan.peserta.provUmum.kdProvider);
					$('#nmprov_bpjs').text(resultRujuk.data.rujukan.response.rujukan.peserta.provUmum.nmProvider);
					$('#statusbpjs').text(resultRujuk.data.rujukan.response.rujukan.peserta.statusPeserta.keterangan);
					$('#asal_rujukan').val(resultRujuk.data.rujukan.response.asalFaskes);
					countRujukan();
				}else{
					swal('Perhatian', resultRujuk.data.rujukan.metaData.message, 'warning');
				}
			}else{
				swal('Perhatian', resultRujuk.message, 'warning');
			}
		});
	}
	function countRujukan() {
		var noRujuk = $('#nRujuk').val();
		$.post("{!! route('countRujukan') !!}",{noRujuk:noRujuk}).done(function(result){
			var hasilCountRujukan = result;
			console.log(hasilCountRujukan);
		});
	}

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

	$('#btn-delete-sep').click(function(e){
		var no = $('#nosepcetak').val();
		swal({
			title: "Hapus Data Ini?",
			text: "Data akan dihapus dari sistem dan tidak dapat dikembalikan!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Hapus",
			cancelButtonText: "Batal",
			closeOnConfirm: false
		}, function(){
			$.post("{!! route('destroySEP') !!}", {no:no}).done(function(data){
				console.log(data);
				if(data.metaData.code == '200'){
					swal("Success!", "Hapus Data Berhasil", "success");
					location.reload();
				} else {
					swal('Perhatian!',data.metaData.message,'warning');
				}
			});
		});
	});
</script>
@stop