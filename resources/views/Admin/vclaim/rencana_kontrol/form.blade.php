<!-- MAIN FORM  -->
<div class="row panel-page">
	<!-- BEDA FORM RENCANA KONTROL (rdpilih = 2) / SPRI (rdpilih = 1) -->
	<?php if($request['rdpilih'] == 2) { ?>
		<!-- LEFT DETAIL -->
		<div class="col-md-3">
			<input type="hidden" id="param_1" value="{{ $request['rdpilih'] }}">
			<input type="hidden" id="param_2" value="{{ $request['txtnosep_0'] }}">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-envelope"></i> <b>SEP</b></h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="box-body">
					<ul class="nav nav-pills nav-stacked">
						<li><a title="No.SEP"><i class="fa fa-sort-numeric-asc"></i> <label id="lblnosep">{{ $response->sep->noSep }}</label></a></li>
						<li><a title="Tgl.SEP"><i class="fa fa-calendar"></i> <label id="lbltglsep">{{ (isset($response->sep->tglSep)) ? $response->sep->tglSep : '-' }}</label></a></li>
						<li><a title="Jns.Pelayanan"><i class="fa fa-medkit"></i> <label id="lbljenpel">{{ (isset($response->sep->jnsPelayanan)) ? $response->sep->jnsPelayanan : '-' }}</label></a></li>
						<li><a title="Poli"><i class="fa fa-bookmark-o"></i> <label id="lblpoli">{{ (isset($response->sep->poli)) ? $response->sep->poli : '-' }}</label></a></li>
						<li><a title="Diagnosa"><i class="fa fa-heartbeat"></i> <label id="lbldiagnosa">{{ (isset($response->sep->diagnosa)) ? str_limit($response->sep->diagnosa, $limit = 25, $end = '...') : '-' }}</label></a></li>
					</ul>
				</div>
			</div>
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-hospital-o"></i> <b>Asal Rujukan SEP</b></h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="box-body">
					<ul class="nav nav-pills nav-stacked">
						<li><a title="No.Rujukan"><i class="fa fa-sort-numeric-asc"></i> <label id="lblnorujukan">{{ (isset($response->sep->provPerujuk->noRujukan)) ? $response->sep->provPerujuk->noRujukan : '-' }}</label></a></li>
						<li><a title="Masa Aktif Rujukan"><i class="fa fa-calendar"></i> <label id="lbltglrujukan">{{ (isset($response->sep->provPerujuk->tglRujukan)) ? $response->sep->provPerujuk->tglRujukan : '-' }}</label></a></li>
						<li><a title="Faskes Asal Rujukan"><i class="fa fa-search"></i> <label id="lblfaskesasalrujukan">{{ (isset($response->sep->provPerujuk->nmProviderPerujuk)) ? $response->sep->provPerujuk->nmProviderPerujuk : '-' }} ({{ (isset($response->sep->provPerujuk->kdProviderPerujuk)) ? $response->sep->provPerujuk->kdProviderPerujuk : '-' }})</label></a></li>
					</ul>
				</div>
			</div>
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-user"></i> <b>Peserta</b></h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="box-body">
					<ul class="nav nav-pills nav-stacked">
						<li>
							<a title="No.Kartu"><i class="fa fa-sort-numeric-asc text-blue"></i> <label id="lblnokartu">{{ (isset($response->sep->peserta->noKartu)) ? $response->sep->peserta->noKartu : '-' }}</label></a>
						</li>
						<li>
							<a title="Nama Peserta"><i class="fa fa-user text-light-blue"></i> <label id="lblnmpeserta">{{ (isset($response->sep->peserta->nama)) ? $response->sep->peserta->nama : '-' }}</label></a>
						</li>
						<li>
							<a title="Tgl.Lahir"><i class="fa fa-calendar text-blue"></i> <label id="lbltgllhrpst">{{ (isset($response->sep->peserta->tglLahir)) ? $response->sep->peserta->tglLahir : '-' }}</label></a>
						</li>
						<li>
							<a title="Kelamin"><i class="fa fa-intersex text-blue"></i> <label id="lbljkpst">{{ (isset($response->sep->peserta->kelamin)) ? $response->sep->peserta->kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' : '-' }}</label></a>
						</li>
						<li>
							<a title="Kelas Peserta"><i class="fa fa-user text-blue"></i> <label id="lblklpst">{{ (isset($response->sep->peserta->hakKelas)) ? $response->sep->peserta->hakKelas : '-' }}</label></a>
						</li>
						<li>
							<a title="PPK Asal Peserta"><i class="fa fa-user-md text-blue"></i> <label id="lblppkpst">{{ (isset($response->sep->provPerujuk->nmProviderPerujuk)) ? $response->sep->provPerujuk->nmProviderPerujuk : '-' }}-{{ (isset($response->sep->provPerujuk->kdProviderPerujuk)) ? $response->sep->provPerujuk->kdProviderPerujuk : '-' }}</label></a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<!-- MAIN FORM -->
		<div class="col-md-9">
			<div class="box box-primary">
				<div class="box-header with-border">
					<i class="fa fa-battery-half"></i>
					<!-- <h3 class="box-title">Collapsable</h3> -->
				</div>
				<div class="box-body">
					<form id="form-save" class="form-horizontal">
						<input type="hidden" class="form-control" id="noSEP" value="{{ (isset($response->sep->noSep)) ? $response->sep->noSep : '' }}">
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl. Rencana Kontrol / Inap</label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<div class="input-group date">
									<input type="date" class="form-control datepicker_rencana" id="txttglrencanakontrol" placeholder="yyyy-MM-dd" maxlength="10" value="{{ (isset($response->tglRencanaKontrol)) ? $response->tglRencanaKontrol : '' }}" {{ (isset($request['isEdit']) && $request['isEdit'] == 'edit') ? 'disabled' : '' }}>
									<span class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelayanan</label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<select class="form-control" id="cbpelayanan" onchange="clearFormRencanaKontrol();">
									<option value="2" {{ (isset($response->sep->jnsPelayanan)) ? ($response->sep->jnsPelayanan == 'Rawat Jalan' ? 'selected' : '') : '-' }}>Rawat Jalan</option>
									<option value="1" {{ (isset($response->sep->jnsPelayanan)) ? ($response->sep->jnsPelayanan == 'Rawat Inap' ? 'selected' : '') : '-' }}>Rawat Inap</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Kontrol</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" class="form-control" id="txtnosuratkontrol" placeholder="Nomor Surat Kontrol" value="{{ (isset($response->noSuratKontrol)) ? $response->noSuratKontrol : '-' }}" disabled>
							</div>
						</div>
						<div class="form-group" id="divPoli">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis <label style="color:red;font-size:small">*</label></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="input-group">
									<input type="text" class="form-control" id="txtnmpoli" placeholder="Nama Spesialis/Subspesialis" value="{{ (isset($response->namaPoliTujuan)) ? $response->namaPoliTujuan : '-' }}" disabled>
									<input type="hidden" class="form-control" id="txtkdpoli" value="{{ (isset($response->poliTujuan)) ? $response->poliTujuan : '' }}">
									<span class="input-group-btn">
										<button type="button" id="btnCariPoliRujukanKontrol" class="btn btn-success">
											<span><i class="fa fa-hospital-o"></i></span> &nbsp;
										</button>
									</span>
								</div>

							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">DPJP Tujuan Kontrol / Inap<label style="color:red;font-size:small">*</label></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" class="form-control" id="txtnmdpjp" placeholder="ketik nama dokter DPJP Pemberi Surat Kontrol" value="{{ (isset($response->namaDokter)) ? $response->namaDokter : '' }}" disabled>
								<input type="hidden" class="form-control" id="txtkddpjp" value="{{ (isset($response->kodeDokter)) ? $response->kodeDokter : '' }}">
							</div>
						</div>

					</form>
				</div>
				<div class="box-footer">
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<button id="btnSimpan" type="button" class="btn btn-success" style="{{ (isset($request['isEdit']) && $request['isEdit'] == 'edit') ? 'display:none;' : '' }}"><i class="fa fa-save"></i> Simpan</button>
							@if(isset($request['isEdit']) && $request['isEdit'] == 'edit')
							<button id="btnEdit" type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
							<button id="btnHapus" type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
							<button id="btnCetak" type="button" class="btn btn-info"><i class="fa fa-print"></i> Cetak</button>
							@endif
							<button id="btnBatal" type="button" class="btn btn-danger pull-right"><i class="fa fa-undo"></i> Batal</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<!-- LEFT DETAIL -->
		<div class="col-md-3">
			<input type="hidden" id="param_1" value="{{ $request['rdpilih'] }}">
			<input type="text" id="param_2" value="{{ $response->peserta->noKartu }}">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-user"></i> <b>Peserta</b></h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="box-body">
					<ul class="nav nav-pills nav-stacked">
						<li>
							<a title="No.Kartu"><i class="fa fa-sort-numeric-asc text-blue"></i> <label id="lblnokartu">{{ (isset($response->peserta->noKartu)) ? $response->peserta->noKartu : '-' }}</label></a>
						</li>
						<li>
							<a title="Nama Peserta"><i class="fa fa-user text-light-blue"></i> <label id="lblnmpeserta">{{ (isset($response->peserta->nama)) ? $response->peserta->nama : '-' }}</label></a>
						</li>
						<li>
							<a title="Tgl.Lahir"><i class="fa fa-calendar text-blue"></i> <label id="lbltgllhrpst">{{ (isset($response->peserta->tglLahir)) ? $response->peserta->tglLahir : '-' }}</label></a>
						</li>
						<li>
							<a title="Kelamin"><i class="fa fa-intersex text-blue"></i> <label id="lbljkpst">{{ (isset($response->peserta->sex)) ? $response->peserta->sex == 'L' ? 'Laki-Laki' : 'Perempuan' : '-' }}</label></a>
						</li>
						<li>
							<a title="Kelas Peserta"><i class="fa fa-user text-blue"></i> <label id="lblklpst">{{ (isset($response->peserta->hakKelas->keterangan)) ? $response->peserta->hakKelas->keterangan : '-' }}</label></a>
						</li>
						<li>
							<a title="PPK Asal Peserta"><i class="fa fa-user-md text-blue"></i> <label id="lblppkpst">{{ (isset($response->provPerujuk->nmProviderPerujuk)) ? $response->provPerujuk->nmProviderPerujuk : '-' }}-{{ (isset($response->provPerujuk->kdProviderPerujuk)) ? $response->provPerujuk->kdProviderPerujuk : '-' }}</label></a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<!-- MAIN FORM -->
		<div class="col-md-9">
			<div class="box box-primary">
				<div class="box-header with-border">
					<i class="fa fa-battery-half"></i>
					<!-- <h3 class="box-title">Collapsable</h3> -->
				</div>
				<div class="box-body">
					<form id="form-save" class="form-horizontal">
						<input type="hidden" id="isEdit" value="{{ (isset($request['isEdit'])) ? $request['isEdit'] : '' }}">
						<input type="hidden" class="form-control" id="noSEP" value="{{ (isset($response->peserta->noKartu)) ? $response->peserta->noKartu : '' }}">
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl. Rencana Kontrol / Inap</label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<div class="input-group date">
									<input type="date" class="form-control datepicker_rencana" id="txttglrencanakontrol" placeholder="yyyy-MM-dd" maxlength="10" value="{{ (isset($response->tglRencanaKontrol)) ? $response->tglRencanaKontrol : date('Y-m-d') }}"
									{{(isset($request['isEdit']) && $request['isEdit'] == 'edit') ? 'disabled' : ''}}>
									<span class="input-group-addon">
										<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelayanan</label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<select class="form-control" id="cbpelayanan" disabled>
									<option value="1" selected>Rawat Inap</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">No. Surat Kontrol</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" class="form-control" id="txtnosuratkontrol" placeholder="Nomor Surat Kontrol" value="{{ (isset($response->noSuratKontrol)) ? $response->noSuratKontrol : '-' }}" disabled>
							</div>
						</div>
						<div class="form-group" id="divPoli">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis <label style="color:red;font-size:small">*</label></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="input-group">
									<input type="text" class="form-control" id="txtnmpoli" placeholder="Nama Spesialis/Subspesialis" value="{{ (isset($response->namaPoliTujuan)) ? $response->namaPoliTujuan : '' }}" disabled>
									<input type="hidden" class="form-control" id="txtkdpoli" value="{{ (isset($response->poliTujuan)) ? $response->poliTujuan : '' }}">
									<span class="input-group-btn">
										<button type="button" id="btnCariPoliRujukanKontrol" class="btn btn-success">
											<span><i class="fa fa-hospital-o"></i></span> &nbsp;
										</button>
									</span>
								</div>

							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 col-sm-3 col-xs-12 control-label">DPJP Tujuan Kontrol / Inap<label style="color:red;font-size:small">*</label></label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" class="form-control" id="txtnmdpjp" placeholder="ketik nama dokter DPJP Pemberi Surat Kontrol" value="{{ (isset($response->namaDokter)) ? $response->namaDokter : '' }}" disabled>
								<input type="hidden" class="form-control" id="txtkddpjp" value="{{ (isset($response->kodeDokter)) ? $response->kodeDokter : '' }}">
							</div>
						</div>
					</form>
				</div>
				<div class="box-footer">
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<button id="btnSimpan" type="button" class="btn btn-success" style="{{ (isset($request['isEdit']) && $request['isEdit'] == 'edit') ? 'display:none;' : '' }}"><i class="fa fa-save"></i> Simpan</button>
							<button id="btnEdit" type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
							<button id="btnHapus" type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
							<button id="btnCetak" type="button" class="btn btn-info"><i class="fa fa-print"></i> Cetak</button>
							<button id="btnBatal" type="button" class="btn btn-danger pull-right"><i class="fa fa-undo"></i> Batal</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<input type="hidden" id="tglTerbit" value="{{ (isset($response->tglTerbit)) ? $response->tglTerbit : '' }}">
</div>

<!-- PILIH SPESIALIS/SUB -->
<div class="JadwalRujKontrol" style="display:none;">
	<div class="row" id="divrowPraktek" style="">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<i class="fa fa-calendar"></i>
					<h3 class="box-title">Jadwal Praktek Rumah Sakit</h3>
				</div>
				<div class="box-body">
					<div id="divSpesialis">
						<div class="alert alert-info alert-dismissible">
							<p>
								1. Untuk Melihat Jadwal Praktek Dokter klik Nama Spesialis/SubSpesialis<br>
								2. Jumlah Rencana Kontrol Merupakan Penjumlahan dari Rencana Kontrol Spesialis/Subspesialis Per Tanggal<br>
								3. Kapasitas Merupakan Jumlah Maksimal Layanan yang Dapat dilayani Oleh Spesialis/Subspesialis<br>
							</p>
						</div>
						<div id="tblSpesialis_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<div class="row">
								<div class="col-sm-12">
									<table id="datatblPoli" class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>No</th>
												<th>Nama Spesialis/Sub</th>
												<th>Kapasitas</th>
												<th>Jml.Rencana Kontrol & Rujukan</th>
												<th>Persentase</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												{{-- <td colspan="5" style="text-align:center;">Loading...</td> --}}
												<td>-</td>
												<td>-</td>
												<td>Loading...</td>
												<td>-</td>
												<td>-</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<button class="btn btn-danger" id="btnBatalJadwalRujKontrol" type="button">
				<i class="fa fa-undo"></i> Batal
			</button>
		</div>
	</div>
</div>

<!-- PILIH DOKTER -->
<div class="DokterRujKontrol" style="display:none;">
	<div class="row" id="" style="">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<i class="fa fa-calendar"></i>
					<h3 class="box-title">Pilih Dokter Spesialis/Sub</h3>
				</div>
				<div class="box-body">
					<div id="">
						<div id="" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<div class="row">
								<div class="col-sm-12">
									<table id="datatblDokter" class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>No</th>
												<th>Nama Dokter</th>
												<th>Jadwal Praktek</th>
												<th>Kapasitas</th>
												<th>#</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												{{-- <td colspan="5" style="text-align:center;">Loading...</td> --}}
												<td>-</td>
												<td>-</td>
												<td>Loading...</td>
												<td>-</td>
												<td>-</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<button class="btn btn-danger" id="btnBatalDokterRujKontrol" type="button">
				<i class="fa fa-undo"></i> Batal
			</button>
		</div>
	</div>
</div>

<div class="printRujukan"></div>

<script type="text/javascript">
	{!!  date_default_timezone_set("Asia/Jakarta"); !!}
	//
	if ($('#txtnosuratkontrol').val() == '') {
		$('#btnEdit').hide();
		$('#btnHapus').hide();
		$('#btnCetak').hide();
	}
	// HALAMAN MAIN FORM
	// FUNCTION GET POLI
	function getPoliRujKontrol(){
		var param_1 = $('#param_1').val();
		var param_2 = $('#param_2').val();
		var param_3 = $("#txttglrencanakontrol").val();
		var data = new FormData();
		data.append('param_1',param_1);
		data.append('param_2',param_2);
		data.append('param_3',param_3);
		$.ajax({
			url : "{!! route('cariPoliRujKontrol') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(result) {
			if (result.response != null) {
				var tr = '';
				var no = 0;
				// $('#datatblPoli').DataTable().destroy();
				$('#datatblPoli tbody').html('');

				result.response.list.forEach(element => {
					tr += `<tr>`;

					no++;
					tr += '<td>'+no+'</td>';
					tr += '<td>';
					tr += '<div class="btn-group">';
					tr += '<button type="button" class="btn btn-default btn-xs" title="Klik Lihat Jadwal Praktek" onclick=\'getDokterRujKontrol('+JSON.stringify(result.request)+','+JSON.stringify(element)+');\'>';
					tr += element.namaPoli;
					tr += '</button></div></td>';

					// tr += '<td>'+element.namaPoli+'</td>';
					tr += '<td>'+element.kapasitas+'</td>';
					tr += '<td>'+element.jmlRencanaKontroldanRujukan+'</td>';
					tr += '<td>'+element.persentase+'</td>';

					tr += `</tr>`;
				});
				$("#datatblPoli tbody").html(tr);
				$('#datatblPoli').DataTable();
			}else {
				var tr = '';
				var no = 0;
				// $('#datatblPoli').DataTable().destroy();
				$('#datatblPoli tbody').html('');

				tr += `<tr>`;
				tr += `<td colspan="5" style="text-align:center;">`;
				tr += result.metaData.message;
				tr += `</td>`;
				tr += `</tr>`;
				$("#datatblPoli tbody").html(tr);
				// $('#datatblPoli').DataTable();
			}
		});
	}
	// FUNCTION HAPUS RENCANA KONTROL
	$('#btnHapus').click(function(e){
		var no = $('#txtnosuratkontrol').val();
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
			$.post("{!! route('destroyRencanaKontrol') !!}", {no:no}).done(function(data){
				if(data.metaData.code == '200'){
					swal("Success!", "Hapus Data Berhasil", "success");
					$('.panel-page').fadeOut();
					$('.other-page').fadeOut(function(){
						$('.other-page').empty();
						$('.main-layer').fadeIn();
					});
				} else {
					swal('Perhatian!',result.metaData.message,'warning');
				}
			});
		});
	});
	// BUTTON BATAL MAIN FORM
	$('#btnBatal').click(function(e){
		e.preventDefault();
		$('.panel-page').fadeOut();
		$('.other-page').fadeOut(function(){
			$('.other-page').empty();
			$('.main-layer').fadeIn();
		});
	});
	// SAVE KONTROL
	$('#btnSimpan').click(function(e){
		e.preventDefault();
		var data = new FormData();
		data.append('jenisKontrol',$('#param_1').val());
		data.append('noSuratKontrol', $('#txtnosuratkontrol').val());
		data.append('noSEP', $('#noSEP').val());
		data.append('tglRencanaKontrol', $('#txttglrencanakontrol').val());
		data.append('poliKontrol', $('#txtkdpoli').val());
		data.append('namaPoliKontrol', $('#txtnmpoli').val());
		data.append('kodeDokter', $('#txtkddpjp').val());
		data.append('isEdit', $('#isEdit').val());
		$.ajax({
			url : "{!! route('storeRencanaKontrol') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(result) {
			if (result.response != null) {
				var data = [];
				if ($('#param_1').val() == "1") {
					data.noSuratKontrol = result.response.noSPRI;
				}else if ($('#param_1').val() == "2") {
					data.noSuratKontrol = result.response.noSuratKontrol;
				}
				swal(data.noSuratKontrol,'Nomor Surat Kontrol / SPRI','success');
				$('.panel-page').fadeOut();
				$('.other-page').fadeOut(function(){
					$('.other-page').empty();
					$('.main-layer').fadeIn();
				});
				// PRINT
				// var data = [];
				// if ($('#param_1').val() == "1") {
				// 	data.noSuratKontrol = result.response.noSPRI;
				// }else if ($('#param_1').val() == "2") {
				// 	data.noSuratKontrol = result.response.noSuratKontrol;
				// }
				// data.namaDpjp = result.response.namaDokter;
				// data.namaPoli = result.response.namaPoliKontrol;
				// data.noKartu = result.response.noKartu;
				// data.namaPeserta = result.response.nama;
				// data.jenkel = result.response.kelamin;
				// data.tglLahir = result.response.tglLahir;
				// if (result.response.namaDiagnosa == null){
				// 	data.diagnosa = '-';
				// } else {
				// 	data.diagnosa = result.response.namaDiagnosa;
				// };
				// data.tglKontrol = result.response.tglRencanaKontrol;
				// data.tglTerbit = "@{!! date('Y-m-d') !!}";
				// data.tglCetak = "@{!! date('d-m-Y') !!} @{!! date('h:i A') !!}";
				// cetakPrint(data);
			}else {
				swal('Whoops!',result.metaData.message,'warning');
			}
		});
	});
	// EDIT
	$('#btnEdit').click(function(){
		$('#txttglrencanakontrol').prop("disabled", false);
		$('#btnSimpan').show();
		$('#btnEdit').hide();
		$('#btnHapus').hide();
		$('#btnCetak').hide();
	});
	// FUNCTION PRINT
	function cetakPrint(data) {
		// console.log(data);
		var cetak = '';
		var jenisKontrol = $('#param_1').val();
		if (jenisKontrol == '1') {
			var judul = 'SURAT RENCANA INAP';
		} else {
			var judul = 'SURAT RENCANA KONTROL';
		}
		//
		var stTitle = 'height="10px" style="font-size:11px;"';
		var stIsi = 'height="10px" style="font-size:11px;"';
		var widthJd1 = 'width="190px;"';
		var widthIsi1 = 'width="380px;"';
		var widthJd2 = 'width="140px;"';
		var widthIsi2 = 'width="280px;"';
		var stBerkas = 'style="font-size:11px;padding-left: 50px;"';
		var stPasKel = 'style="font-size: 10px;padding-left: 35px;height:10px;"';
		var stKet = 'style="font-size: 9px;"';
		var stCat2 = 'height="10px" style="font-size:10px;"';
		var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
		//
		cetak += `<div style="border-right:dashed 1px #fff;padding:0px;margin:0px;width:100%;height:250px;">`;
		cetak += `<table border="0" style="margin-top:13px;margin-left:10px;">
					<tr>
						<td>
							<img src="${logoBpjs}" width="300px" style="margin-left:0px;">
						</td>
						<td width="400px">
							<p style="margin:10px;font-size:20px;">${judul}</p>
                            <p style="margin:10px;font-size:20px;">
								RSUD dr. Wahidin Sudiro Husodo</p>
						</td>
						<td ${widthIsi1}>
							<p style="margin:10px;font-size:20px;">No. ${data.noSuratKontrol}</p>
						</td>
					</tr>
				  </table>`;

		cetak += `<div style="margin-bottom: 5px;"></div>`;
		cetak += `<table border="0" style="margin-left:10px;">`;
		if (data.jenisKontrol == '2') {
		cetak += `<tr>
					<td ${widthJd1} ${stIsi}>
						<p style="margin:0px;font-size:17px;">Kepada Yth</p>
					</td>
					<td colspan="2" ${widthIsi1}>
						<p style="margin:0px;font-size:17px;">${data.namaDpjp}</p>
					</td>
				</tr>
				<tr>
					<td ${widthJd1} ${stIsi}><p style="margin:0px;font-size:17px;"></p></td>
					<td colspan="2" ${widthIsi1} ${stIsi}>
						<p style="margin:0px;font-size:17px;"> Sp./Sub. ${data.namaPoli}</p>
					</td>
				</tr>`;}
		cetak +=`	<tr>
						<td colspan="3" ${widthIsi1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">
								Mohon Pemeriksaan dan Penanganan Lebih Lanjut:
							</p>
						</td>
					</tr>
					<tr>
						<td ${widthJd1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">No. Kartu</p>
						</td>
						<td colspan="2" ${widthIsi1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">: ${data.noKartu}</p>
						</td>
					</tr>
					<tr>
						<td ${widthJd1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">Nama Peserta</p>
						</td>
						<td colspan="2" ${widthIsi1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">
								: ${data.namaPeserta} (${data.jenkel})
							</p>
						</td>
					</tr>
					<tr>
						<td ${widthJd1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">Tgl.Lahir</p>
						</td>
						<td colspan="2" ${widthIsi1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">: ${data.tglLahir}</p>
						</td>
					</tr>
					<tr>
						<td ${widthJd1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">Diagnosa</p>
						</td>
						<td colspan="2" ${widthIsi1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">: ${data.diagnosa}</p>
						</td>
					</tr>
					<tr>
						<td ${widthJd1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">Rencana Kontrol</p>
						</td>
						<td colspan="2" ${widthIsi1} ${stIsi}>
							<p style="margin:0px;font-size:17px;">: ${data.tglKontrol}</p>
						</td>
					</tr>
				  </table>`;

		cetak += `<table border="0" style="margin-top:13px;margin-left:10px;">
					<tr>
						<td colspan="2" width="700px">
							<p style="margin:0px;font-size:17px;">
								Demikian atas bantuannya, diucapkan banyak terima kasih.
							</p>
						</td>
						<td ${widthIsi1}>
						</td>
					</tr>
					<tr>
						<td colspan="2" width="710px">&nbsp;
						<p>Silahkan isi survey kepuasan pelayanan dengan akses QR code di bawah ini</p>
							<img src="{!! url('kessan.png') !!}" style="width:auto; height:170px;">
						</td>
						<td ${widthIsi1}>
							<p style="margin:0px;font-size:17px;">Mengetahui DPJP,</p>
						</td>
					</tr>
					<tr>
						<td colspan="2" width="710px">&nbsp;</td>
						<td ${widthIsi1}>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" width="710px">
							<small>Tgl.Entri: ${data.tglTerbit} | Tgl.Cetak: ${data.tglCetak}</small>
						</td>
						<td ${widthIsi1}>${data.namaDpjp}</td>
					</tr>
				  </table>`;

		cetak += `</div>`;
		cetak += `<div style="page-break-after: always;"></div>`;

		$('.printRujukan').html(cetak);
		var printHtml = window.open('', 'PRINT', '');
		printHtml.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">');
		printHtml.document.write($('.printRujukan').html());
		printHtml.document.write('</body></html>');
		printHtml.document.close();
		printHtml.focus();
		printHtml.print();
		// setTimeout(() => {
		// 	printHtml.close();
		// 	$('.printRujukan').html('');
		// }, 500);
	}
	// CETAK
	$('#btnCetak').click(function(){
		var data = [];
		data.noSuratKontrol = $('#txtnosuratkontrol').val();
		data.namaDpjp = $('#txtnmdpjp').val();
		data.namaPoli = $('#txtnmpoli').val();
		data.noKartu = $("#lblnokartu").text();
		data.namaPeserta = $("#lblnmpeserta").text();
		data.jenkel = $("#lbljkpst").text();
		data.tglLahir = $("#lbltgllhrpst").text();
		data.diagnosa = $("#lbldiagnosa").text();
		data.tglKontrol = $('#txttglrencanakontrol').val();
		data.tglTerbit = $('#tglTerbit').val();
		data.tglCetak = "{!! date('d-m-Y') !!} {!! date('h:i A') !!}";
		cetakPrint(data);
	});

	// HALAMAN SPESIALIS/SUB
	// FUNCTION GET DOKTER PER SPESIALIS
	function getDokterRujKontrol(req, obj){
		$('#txtnmpoli').val(obj.namaPoli);
		$('#txtkdpoli').val(obj.kodePoli);
		// console.log(param1);
		$('.JadwalRujKontrol').hide();
		var data = new FormData();
		data.append('param1',req.param_1); // param1 = jenis kontrol
		data.append('param2',obj.kodePoli); // param2 = kode poli
		data.append('param3',req.param_3); // param3 = tgl rencana kontrol;
		data.append('obj',JSON.stringify(obj)); // param3 = tgl rencana kontrol;

		$.ajax({
			url : "{!! route('cariDokterRujKontrol') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(result) {
			if (result.response != null) {
				var tr = '';
				var no = 0;
				// $('#datatblDokter').DataTable().destroy();
				$('#datatblDokter tbody').html('');

				result.response.list.forEach(element => {
					tr += '<tr>';

					no++;
					tr += '<td>'+no+'</td>';
					tr += '<td>'+element.namaDokter+'</td>';
					tr += '<td>'+element.jadwalPraktek+'</td>';
					tr += '<td>'+element.kapasitas+'</td>';

					tr += '<td>';
					tr += '<div class="btn-group">';
					tr += '<button type="button" class="btn btn-success btn-xs" onclick=\'selectDokter('+JSON.stringify(element)+','+JSON.stringify(obj)+')\'>';
					tr += '<i class="fa fa-check"></i>';
					tr += '</button>';
					tr += '</div>';
					tr += '</td>';

					tr += '</tr>';
				});
				$("#datatblDokter tbody").html(tr);
				$('#datatblDokter').DataTable();
			}else {
				var tr = '';
				var no = 0;
				// $('#datatblDokter').DataTable().destroy();
				$('#datatblDokter tbody').html('');

				tr += `<tr>`;
				tr += `<td colspan="5" style="text-align:center;">`;
				tr += result.metaData.message;
				tr += `</td>`;
				tr += `</tr>`;
				$("#datatblDokter tbody").html(tr);
				// $('#datatblDokter').DataTable();
			}
		});

		$('.DokterRujKontrol').show();
	}
	// BUTTON BATAL GET POLI PAGE
	$('#btnBatalJadwalRujKontrol').click(function(e){
		e.preventDefault();
		$('.JadwalRujKontrol').fadeOut(function(){
			$('.panel-page').fadeIn();
		});
	});
	// BUTTON PILIH POLI
	$('#btnCariPoliRujukanKontrol').click(function(e){
		e.preventDefault();
		$('.panel-page').fadeOut(function(){
			getPoliRujKontrol();
			$('.JadwalRujKontrol').fadeIn();
		});
	});

	// HALAMAN DOKTER
	// PILIH DOKTER
	function selectDokter(dokter, obj){
		$('#txtnmdpjp').val(dokter.namaDokter);
		$('#txtkddpjp').val(dokter.kodeDokter);
		// console.log(obj);
		$('.DokterRujKontrol').fadeOut(function(){
			$('.panel-page').fadeIn();
		});
	}
	// BATAL GET POLI PAGE
	$('#btnBatalDokterRujKontrol').click(function(e){
		e.preventDefault();
		$('.DokterRujKontrol').fadeOut(function(){
			$('.JadwalRujKontrol').fadeIn();
		});
	});
</script>
