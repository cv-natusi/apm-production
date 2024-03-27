<div class="modal fade" id="confirm-dialog" tabindex="-1" role="dialog" aria-labelledby="confirm-detail-dialog">
	<div class="modal-confirm modal-lg" >
		<div class="modal-content">
			<div class="modal-header modalHeadReg">
				<h4 class="modal-title modalHeaderTitle" id="confirm-detail-dialog">Konfirmasi Identitas Pasien</h4>
			</div>
			<div class="modal-body">
				<div class="panelNoIden p-t-25">
					<form class="formRegistrasi" style="color: #000;text-align: left;">
						<div class="col-lg-6">
							<input type="hidden" name="jenis_pendaftaran" value="{{ $jenis_pendaftaran }}">
							<input type="hidden" name="no_identitas" value="{{ $no_identitas }}">
							<input type="hidden" name="KodeCust" value="{{ $KodeCust }}">
							<input type="hidden" name="statusInput" value="{{ $statusInput }}">
							<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">No RM</label>
							<label class="col-lg-8 col-md-8 col-sm-6 col-xs-6">: {{ $customer->KodeCust }}</label>
							@if($jenis_pendaftaran == 'BPJS')
								<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">No BPJS</label>
								<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="showNoBpjs">: {{ $noBpjs }}</label>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<a href="javascript:void(0)" onclick="updateNoBpjs()" class="btn btn-info btn-xs btnUpdateNoBpjs"><i class="fa fa-pencil"></i> Ubah</a>
								</div>
								<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6"> 
									<input type="hidden" name="noBpjs" id="noBpjsPas" value="{{ $noBpjs }}">
									<a href="javascript:void(0)" onclick="changeNoBpjs()" class="btn btn-success btn-xs btnSaveNoBpjs" style="display: none;background: #5cb85c"><i class="fa fa-save"></i> save</a>
								</div>
								<div class="clearfix"></div>
							@endif
							<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Nama</label>
							<label class="col-lg-8 col-md-8 col-sm-6 col-xs-6">: {{ $customer->NamaCust }}</label>
							<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Jenis Kelamin</label>
							<label class="col-lg-8 col-md-8 col-sm-6 col-xs-6">: 
								@if($customer->JenisKel == 'L')
									LAKI - LAKI
								@elseif($customer->JenisKel == 'P')
									PEREMPUAN
								@else
									-
								@endif
							</label>
							<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Tanggal Lahir</label>
							<label class="col-lg-8 col-md-8 col-sm-6 col-xs-6">: {{ date('d-m-Y', strtotime($customer->TglLahir)) }}</label>
							<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Alamat</label>
							<label class="col-lg-8 col-md-8 col-sm-6 col-xs-6">: {{ $customer->Alamat }}</label>
							@if($jenis_pendaftaran == 'BPJS')
								<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">No Telp</label>
								<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="showTelpPas">: {{ $tlpPas }}</label>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<a href="javascript:void(0)" onclick="updatePhonePas()" class="btn btn-info btn-xs btnUpdatePhone"><i class="fa fa-pencil"></i> Ubah</a>
								</div>
								<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6"> 
									<input type="hidden" name="telpPas" id="telpPas" value="{{ $tlpPas }}">
									<a href="javascript:void(0)" onclick="changePhonePas()" class="btn btn-success btn-xs btnSavePhone" style="display: none;background: #5cb85c"><i class="fa fa-save"></i> save</a>
								</div>
								<div class="clearfix"></div>
							@endif
							@if($jenis_pendaftaran == 'BPJS')
								<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">No Rujukan</label>
								<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="showNoRujuk">: {{ $noRujuk }}</label>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
									<a href="javascript:void(0)" onclick="updateNoRujuk()" class="btn btn-info btn-xs btnUpdateNoRujuk"><i class="fa fa-pencil"></i> Ubah</a>
								</div>
								<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
									<input type="hidden" name="noRujuk" id="noRujuk" value="{{ $noRujuk }}">
									<a href="javascript:void(0)" onclick="changeNoRujuk()" class="btn btn-success btn-xs btnSaveNoRujuk" style="display: none;background: #5cb85c"><i class="fa fa-save"></i> save</a>
								</div>
								<div class="clearfix"></div>
							@endif
							<!-- @if($tglRujukan != '')
								<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Tanggal Rujukan</label>
								<label class="col-lg-8 col-md-8 col-sm-6 col-xs-6">: {{ date('d-m-Y', strtotime($tglRujukan)) }}</label>
								<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Batas Rujukan</label>
								<label class="col-lg-8 col-md-8 col-sm-6 col-xs-6">: {{ date('d-m-Y', strtotime($tglAkhir)) }} ({{ $batasRujukan }})</label>
							@endif -->
							@if($tglRujukan != '')
								<div id="showTglRujuk">
									<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Tgl Rujukan</label>
									<label class="col-lg-8 col-md-8 col-sm-6 col-xs-6" id='showViewTglRujuk'></label>
								</div>
							@endif
							<input type="hidden" name="nmPoli" value="{{ $nmPoli }}" id="nmPoli">
							<input type="hidden" name="kdSelectedPoli" value="{{ $kdPoli }}" id="kdSelectedPoli">
							<input type="hidden" name="tglRujukan" value="{{ $tglRujukan }}" id="tglRujukan">
							<input type="hidden" name="btsRujukan" value="{{ $batasRujukan }}" id="btsRujukan">
						</div>
						<div class="col-lg-6">
							<div class="form-group m-b-10 m-l-0 p-0">
								<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" id='label-input'>Tanggal Pemeriksaan <span style='color:red'>*</span></label>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group p-t-0 p-b-0 p-l-15 p-r-15">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" name='tanggal' autocomplete='off' placeholder='dd-mm-yyyy' class="form-control cus-form-in pull-right"  id='form_datetime_today' data-date-format="dd-mm-yyyy" required='required'>
								</div>
							</div>
							@if($jenis_pendaftaran == 'BPJS')
								<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Tujuan Poli</label>
								<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="showViewPoliRujuk">: {{ $nmPoli }}</label>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btnUpdatePoli" <?php if ($record == 'nothing') { echo "style='display:none;'";} ?>>
									<a href="javascript:void(0)" onclick="updatePoli()" class="btn btn-info btn-xs">
										<i class="fa fa-pencil"></i> Ubah
									</a>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 listPolis pull-left" style="display: none;">
									<select name="kodePoli" id="poliList" class="form-control input-sm customInput col-md-7 col-xs-12">
										<option value='' disabled selected> .:: Pilih Poli ::. </option>
										@foreach($polis as $poli)
											<option value='{{ $poli->kdpoli }}' <?php if($poli->kdpoli == $kdPoli){echo "selected";} ?>>{{ $poli->NamaPoli }}</option>
										@endforeach
									</select>
								</div>
								<a href="javascript:void(0)" onclick="changePoli()" class="btn btn-success btn-xs btnSavePoli pull-right m-r-20" style="display: none;background: #5cb85c">
									<i class="fa fa-save"></i> save
								</a>
								<div class="clearfix"></div>
								<div class="showDokter"  <?php if ($record == 'nothing') { echo "style='display:none;'";} ?>>
									<label class="col-lg-4 col-md-4 col-sm-6 col-xs-6">Nama Dokter</label>
									<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="showViewDokter">: {{ $dokterSebelum }}</label>
								</div>
								<input type="hidden" name="record" value="{{ $record }}" id="record">
								<input type="hidden" name="kodeDPJP" value="{{ $kodeDPJP }}" id="kodeDPJP">
								<input type="hidden" name="namaDokterDPJP" value="{{ $dokterSebelum }}" id="namaDokterDPJP">
								<input type="hidden" name="noSurat" value="{{ $noSurat }}" id="noSurat">
							@endif
						</div>
						<div class="clearfix m-b-15"></div>
						<center>
							<button type="button" id='btn-lanjut' class="btn btn-hijau btn-besar m-r-15">LANJUTKAN</button>
							<button type="button" class="btn btn-merah btn-besar" data-dismiss="modal">BATAL MENDAFTAR</button>
						</center>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var onLoad = (function() {
		$('#confirm-dialog').find('.modal-confirm').css({
			'width'     : '80%',
			'margin-left' : '10%',
			'margin-top' : '5%'
		});
		$('#confirm-dialog').modal('show');
	})();
	$('#confirm-dialog').on('hidden.bs.modal', function () {
		$('.modal-confirm').html('');
	})
	$('#confirm-dialog').on('shown.bs.modal', function () {
		$('.inputNoIdentitas').focus();
	}) 
	cekNmPoli();

	function updatePhonePas() {
		$('#showTelpPas').hide();
		$('.btnUpdatePhone').hide();
		$('.btnSavePhone').show();
		document.getElementById('telpPas').type = 'text';
	}

	function changePhonePas() {
		var newPhone = $('#telpPas').val();
		if (newPhone != '' && newPhone != '0') {
			$('#showTelpPas').html(': '+newPhone);
			$('#showTelpPas').show();
			$('.btnUpdatePhone').show();
			$('.btnSavePhone').hide();
			document.getElementById('telpPas').type = 'hidden';
		}else{
			swal({
				title: "MAAF !",
				text: "No Telepon Tidak Boleh Kosong !!",
				type: "error",
				timer: 2000,
				showConfirmButton: false
			});
		}
	}

	function cekNmPoli() {
		var tglRujukan = '{{ $tglRujukan }}';
		if (tglRujukan != '') {
			var resultTglRujuk = ": {{ date('d-m-Y', strtotime($tglRujukan)) }}";
			$('#showViewTglRujuk').html(resultTglRujuk);
			$('#showTglRujuk').show();
		}else{
			$('#showTglRujuk').hide();
		}
	}

	function updateNoBpjs() {
		$('#showNoBpjs').hide();
		$('.btnUpdateNoBpjs').hide();
		$('.btnSaveNoBpjs').show();
		document.getElementById('noBpjsPas').type = 'text';
	}

	function changeNoBpjs() {
		var noBpjsPas = $('#noBpjsPas').val();
		var noRM = '{{ $KodeCust }}';
		if (noBpjsPas != '' && noBpjsPas != '0') {
			$.post("{!! route('reloadCekRujukan') !!}",{noBpjs:noBpjsPas,noRM:noRM}).done(function(result){
				if(result.status == 'success'){

					$('#showNoBpjs').html(': '+noBpjsPas);
					$('#showNoBpjs').show();
					$('.btnUpdateNoBpjs').show();
					$('.btnSaveNoBpjs').hide();
					document.getElementById('noBpjsPas').type = 'hidden';
					// ganti no telpon
					$('#showTelpPas').html(': '+result.data.tlpPas);
					$('#telpPas').val(result.data.tlpPas);

					// ganti nama poli
					$('#nmPoli').val(result.data.nmPoli);
					$('#showViewPoliRujuk').html(": "+result.data.nmPoli);

					// ganti tanggal Rujukan
					var tglRujukan = $('#tglRujukan').val(result.data.tglRujukan);
					var resultTglRujuk = result.data.tglRujukan;
					$('#showViewTglRujuk').html(resultTglRujuk);
					// ganti batas rujukan
					var btsRujukan = $('#btsRujukan').val(result.data.batasRujukan);
					//ganti no rujukan
					$('#showNoRujuk').html(': '+result.data.noRujuk);
					$('#noRujuk').val(result.data.noRujuk);
				}else{
					swal({
						title : "Maaf !!",
						text: result.messages,
						type : "warning",
						timer : 2000,
					});
				}
			});
		}else{
			swal({
				title: "MAAF !",
				text: "No BPJS Tidak Boleh Kosong !!",
				type: "error",
				timer: 2000,
				showConfirmButton: false
			});
		}
	}

	function updateNoRujuk() {
		$('#showNoRujuk').hide();
		$('.btnUpdateNoRujuk').hide();
		$('.btnSaveNoRujuk').show();
		document.getElementById('noRujuk').type = 'text';
	}

	function changeNoRujuk() {
		var noBpjsPas = $('#noBpjsPas').val();
		var noRujuk = $('#noRujuk').val();
		var noRM = '{{ $KodeCust }}';
		if (noRujuk != '' && noRujuk != '0') {
			$.post("{!! route('reloadNoRujukan') !!}",{noBpjs:noBpjsPas,noRM:noRM,noRujuk:noRujuk}).done(function(result){
				if(result.status == 'success'){

					$('#showNoRujuk').html(': '+result.data.noRujuk);
					$('#showNoRujuk').show();
					$('.btnUpdateNoRujuk').show();
					$('.btnSaveNoRujuk').hide();
					document.getElementById('noRujuk').type = 'hidden';
					// ganti no telpon
					$('#showTelpPas').html(': '+result.data.tlpPas);
					$('#telpPas').val(result.data.tlpPas);

					// ganti nama poli
					$('#nmPoli').val(result.data.nmPoli);
					$('#showViewPoliRujuk').html(": "+result.data.nmPoli);

					// ganti tanggal Rujukan
					var tglRujukan = $('#tglRujukan').val(result.data.tglRujukan);
					var resultTglRujuk = result.data.tglRujukan;
					$('#showViewTglRujuk').html(resultTglRujuk);
					// ganti batas rujukan
					var btsRujukan = $('#btsRujukan').val(result.data.batasRujukan);

					var listPoli = '<option value="" disabled selected> .:: Pilih Poli ::. </option>';
					if(result.data.polis.length > 0){
						var stl = '';
						$.each(result.data.polis, function(k,v){
							if (v.kdpoli == result.data.kdPoli) {
								stl = 'selected';
							}else{
								stl = '';
							}
							listPoli += '<option value="'+v.kdpoli+'" '+stl+'>'+v.NamaPoli+'</option>';
						});
					}
					$('#poliList').html(listPoli);
					$('#poliList').trigger('chosen:updated');

					$('#kdSelectedPoli').val(result.data.kdPoli);
					if (result.data.record == 'nothing') {
						$('.btnUpdatePoli').hide();
						$('.showDokter').hide();
					}else{
						$('.btnUpdatePoli').show();
						$('.showDokter').show();
					}
					$('#kdSelectedPoli').val(result.data.kdPoli);
					$('#showViewDokter').html(': '+result.data.dokterSebelum);
					$('#record').val(result.data.record);
					$('#kodeDPJP').val(result.data.kodeDPJP);
					$('#namaDokterDPJP').val(result.data.dokterSebelum);
					$('#noSurat').val(result.data.noSurat);
				}else{
					swal({
						title : "Maaf !!",
						text: result.messages,
						type : "warning",
						timer : 2000,
					});
				}
			});
		}else{
			swal({
				title: "MAAF !",
				text: "No Rujukan Tidak Boleh Kosong !!",
				type: "error",
				timer: 2000,
				showConfirmButton: false
			});
		}
	}

	function updatePoli() {
		$('#showViewPoliRujuk').hide();
		$('.btnUpdatePoli').hide();
		$('.btnSavePoli').show();
		$('.listPolis').show();
	}

	function changePoli() {
		var valKdPoli = $('#poliList').val();
    	$.post("{!! route('getKdPoliBridging') !!}",{valKdPoli:valKdPoli}).done(function(result){
    		if(result.status == 'success'){
    			var listPoli = '<option value="" disabled selected> .:: Pilih Poli ::. </option>';
	    		if(result.data.polis.length > 0){
	    			var stl = '';
	    			$.each(result.data.polis, function(k,v){
	    				if (v.kdpoli == result.data.kdPoli) {
	    					stl = 'selected';
	    				}else{
	    					stl = '';
	    				}
	    				listPoli += '<option value="'+v.kdpoli+'" '+stl+'>'+v.NamaPoli+'</option>';
	    			});
	    		}
	    		$('#poliList').html(listPoli);
	    		$('#poliList').trigger('chosen:updated');

	    		$('#nmPoli').val(result.data.nmPoli);
	    		$('#showViewPoliRujuk').html(": "+result.data.nmPoli);
	    		$('#kdSelectedPoli').val(result.data.kdPoli)

	    		$('#showViewPoliRujuk').show();
				$('.btnUpdatePoli').show();
				$('.btnSavePoli').hide();
				$('.listPolis').hide();
    		}else{
				swal({
					title : "Maaf !!",
					text: result.messages,
					type : "warning",
					timer : 2000,
				});
			}
    	});
	}

	$('#poliList').chosen();

	$('#btn-lanjut').click(function(e){
		var today = new Date();
		var curr_hour = today.getHours();
		var tglPeriksa = $('#form_datetime_today').val();
		var jnsPendaftaran = '{{ $jenis_pendaftaran }}';
		var tglNow = '{{ $tglNow }}';
		// var nmPoli = '{{ $nmPoli }}';
		var nmPoli = $('#nmPoli').val();
		// var tglRujukan = '{{ $tglRujukan }}';
		var tglRujukan = $('#tglRujukan').val();
		// var btsRujukan = '{{ $batasRujukan }}';
		var btsRujukan = $('#btsRujukan').val();
		var phonePasien = $('#telpPas').val();
		if (jnsPendaftaran == 'BPJS') {
			if (nmPoli != 'Gagal' && nmPoli != '') {
				if (tglPeriksa == tglNow) {
					// if (curr_hour < 11) {
					if (curr_hour > 6) {
						// window.alert(btsRujukan);
						if (btsRujukan == 'Ready') {
							var pesan = 'Apa anda yakin mendaftar ke poli '+ nmPoli +'??'
							swal(
								{
									title: pesan,
									type: "warning",
									showCancelButton: true,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "Saya yakin!",
									cancelButtonText: "Batal!",
									closeOnConfirm: false
								},
								function(){
									var data = new FormData($('.formRegistrasi')[0]);
									data.append("phonePasien", phonePasien);
									$.ajax({
										url: "{{ route('bridgingApm') }}",
										type: 'POST',
										data: data,
										enctype: 'multipart/form-data',
										async: true,
										cache: false,
										contentType: false,
										processData: false
									}).done(function(data){
										if(data.status == 'success'){
											$('#confirm-dialog').modal('hide');
											var cetak = '';
											var stTitle = 'height="10px" style="font-size:11px;"';
											var stIsi = 'height="10px" style="font-size:11px;"';
											var widthJd1 = 'width="125px;"';
											var widthIsi1 = 'width="250px;"';
											var widthJd2 = 'width="95px;"';
											var widthIsi2 = 'width="180px;"';
											var stBerkas = 'style="font-size:11px;padding-left: 50px;"';
											var stPasKel = 'style="font-size: 10px;padding-left: 35px;height:10px;"';
											var stKet = 'style="font-size: 9px;"';
											var stCat2 = 'height="10px" style="font-size:10px;"';
											// for (var p = 0; p < 3; p++) {
												// if (p == 2) {
													cetak += '<div style="border-right:dashed 1px #fff;padding:0px;margin:0px;width:650px;height:250px;">';
												// }else{
												// 	cetak += '<div style="border-right:dashed 1px #777;padding:0px;margin:0px;width:650px;height:250px;">';
												// }
												cetak += '<table border="0" style="margin-top:13px;margin-left:10px;">';
												cetak += '<tr>';
												cetak += '<td rowspan="2" width="250px">';
												var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
												cetak += '<img src="'+logoBpjs+'" width="150px" style="margin-left: 5px;">';
												cetak += '</td>';
												cetak += '<td '+stTitle+' colspan="2">SURAT ELEGIBILITAS PESERTA</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td '+stTitle+'>RSUD DR. W. SUDIROHUSODO</td>';
												cetak += '<td '+stBerkas+'>No.Berkas &nbsp : '+data.data.sepValue["noarsip"]+'</td>';
												cetak += '</tr>';
												cetak += '</table>';
												cetak += '<div style="margin-bottom: 5px;"></div>';
												cetak += '<table border="0" style="margin-left:10px;">';
												cetak += '<tr>';
												cetak += '<td '+widthJd1+' '+stIsi+'>No.SEP</td>';
												cetak += '<td '+widthIsi1+' height="10px" style="font-size:12px;">: '+data.data.sepValue["no_sep"]+'</td>';
												cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
												cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td '+widthJd1+' '+stIsi+'>Tgl.SEP</td>';
												cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["tgl_sep"]+'</td>';
												cetak += '<td '+widthJd2+' '+stIsi+'>Peserta</td>';
												cetak += '<td '+widthIsi2+' '+stIsi+'>: '+data.data.sepValue["jnsPeserta"]+'</td>';
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
													var JKlamin = 'PEREMPUAN';
												}
												cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["tgl_lahir"]+' &nbsp Kelamin : '+JKlamin+'</td>';
												cetak += '<td '+widthJd2+' '+stIsi+'>Kls.Rawat</td>';
												cetak += '<td '+widthIsi2+' '+stIsi+'>: '+data.data.sepValue["kls_rawat"]+'</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td '+widthJd1+' '+stIsi+'>No.Telepon</td>';
												cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["noTelepon"]+'</td>';
												cetak += '<td '+widthJd2+' '+stIsi+'>Penjamin</td>';
												cetak += '<td '+widthIsi2+' '+stIsi+'>: -</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td '+widthJd1+' '+stIsi+'>Poli Tujuan</td>';
												cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["poli_tujuan"]+'</td>';
												cetak += '<td '+widthJd2+' '+stIsi+'>No. Kontrol</td>';
												cetak += '<td '+widthIsi2+' '+stIsi+'>: '+data.data.sepValue["noRegister"]+'</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td '+widthJd1+' '+stIsi+'>Faskes Perujuk</td>';
												cetak += '<td colspan="3" '+stIsi+'>: '+data.data.sepValue["fakses"]+'</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td '+widthJd1+' '+stIsi+'>Diagnosa Awal</td>';
												cetak += '<td colspan="3" '+stIsi+'>: '+data.data.sepValue["kdDiagnosa"]+' - '+data.data.sepValue["diagnosa"]+'</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td '+widthJd1+' '+stIsi+'>Catatan</td>';
												cetak += '<td colspan="3" '+stIsi+'>: '+data.data.sepValue["catatan"]+'</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td colspan="3">&nbsp</td>';
												cetak += '<td '+stPasKel+'>Pasien/Keluarga Pasien</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td colspan="3" '+stKet+' valign="bottom">*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan.</td>';
												cetak += '<td '+stPasKel+' valign="bottom" height="25px">_______________</td>';
												cetak += '</tr>';
												cetak += '<tr>';
												cetak += '<td colspan="3" '+stKet+' valign="top">SEP Bukan sebagai bukti penjaminan peserta.<br>Cetakan ke 1 '+data.data.sepValue["jam"]+'</td>';
												cetak += '<td align="right">.</td>';
												cetak += '</tr>';
												cetak += '</table>';
												cetak += '</div>';
												cetak += '<div style="page-break-after: always;"></div>';
											// }

											$('.printSEP').html(cetak);
											$('.printSEP').printArea();

											setTimeout(function awal() {
												cetakKedua();
											}, 1000);
											// swal({
											// 	title : "Terima Kasih !!",
											// 	text: data.statusReg,
											// 	type : "success",
											// 	// timer : 2000,
											// 	showConfirmButton : true
											// });
											// setTimeout(function tutupCetak() {
											// 	$('.printSEP').empty();
											// }, 100);
											// setTimeout(function awal() {
											// 	window.location.href = "{!! route('registration') !!}";
											// }, 1000);
										} else if(data.status == 'error') {
											swal({
												title: "MAAF !",
												text: data.messages,
												type: "warning",
												// timer: 2000,
												showConfirmButton: true
											});
										}
									});
								}
							);
						}else{
							swal({
								title: "MAAF !",
								text: "Masa Berlaku Rujukan Telah Habis !!",
								type: "error",
								// timer: 2000,
								showConfirmButton: true
							},
							function(){
								window.location.href = "{!! route('registration') !!}";
							});
							// setTimeout(function awal() {
							// 	window.location.href = "{!! route('registration') !!}";
							// }, 3000);
						}
					}else{
						swal({
							title: "MAAF !",
							text: "Saat ini sudah melebihi jam Operasional !!",
							type: "error",
							// timer: 2000,
							showConfirmButton: true
						},
						function(){
							window.location.href = "{!! route('registration') !!}";
						});
						// setTimeout(function awal() {
						// 	window.location.href = "{!! route('registration') !!}";
						// }, 5000);
					}
				// }else if(tglPeriksa != tglNow){
				}else{
					// alert('Tanggal Setelah');
					var pesan = 'Apa anda yakin mendaftar ke poli '+ nmPoli +' pada tanggal '+tglPeriksa+' ??';
					swal(
						{
							title: pesan,
							type: "warning",
							showCancelButton: true,
							confirmButtonColor: "#DD6B55",
							confirmButtonText: "Saya yakin!",
							cancelButtonText: "Batal!",
							closeOnConfirm: false
						},
						function(){
							var data = new FormData($('.formRegistrasi')[0]);
							data.append("phonePasien", phonePasien);
							$.ajax({
								url: "{{ route('bridging2Cc') }}",
								type: 'POST',
								data: data,
								enctype: 'multipart/form-data',
								async: true,
								cache: false,
								contentType: false,
								processData: false
							}).done(function(data){
								if(data.status == 'success'){
									$('#confirm-dialog').modal('hide');
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
									cetak += '<label style="font-size: 12px;margin: 0px;">Nomor Antrian Anda :</label><br>';
									cetak += '<label style="font-size: 28px;margin: 0px;line-height: 30px;">'+data.data.nourut+'</label><br>';
									cetak += '</center>';
									cetak += '<table style="width: 100%;color: #000;">';
									cetak += '<tr>';
									cetak += '<td width="50%" align="left" style="font-size:11px;">Tanggal : '+data.tgl+'</td>';
									cetak += '<td width="50%" align="right" style="font-size:11px;">Jam : '+data.jam+'</td>';
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
									cetak += '</table>';
									cetak += '<div style="border-bottom: solid 2px #777;padding-bottom: 5px;"></div>';
									cetak += '<label style="margin: 0px;font-weight:normal;margin-left:35px;font-size:12px;">Terima Kasih Atas Kunjungan Anda.</label>';
									cetak += '</div>';

									$('.printSEP').html(cetak);
									$('.printSEP').printArea();

									setTimeout(function tutupCetak() {
										$('.printSEP').empty();
									}, 100);
									setTimeout(function awal() {
										swal({
											title : "Terima Kasih !!",
											type : "success",
											timer : 2000,
											showConfirmButton : false
										});
										window.location.href = "{!! route('registration') !!}";
									}, 1000);
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
					);
				}
			}else if (nmPoli == 'Gagal') {
				swal({
					title: "MAAF !",
					text: "Tidak Ada Rujukan Ditemukan, Silahkan hubungi ASKES/Puskesmas Terdekat/Dr Pribadi Anda !!",
					type: "error",
					// timer: 2000,
					showConfirmButton: true
				},
				function(){
					window.location.href = "{!! route('registration') !!}";
				});
				// setTimeout(function awal() {
				// 	window.location.href = "{!! route('registration') !!}";
				// }, 5000);
			}else{
				swal({
					title: "MAAF !",
					text: "Anda Tidak Memiliki No BPJS !!",
					type: "error",
					// timer: 2000,
					showConfirmButton: true
				},
				function(){
					window.location.href = "{!! route('registration') !!}";
				});
				// setTimeout(function awal() {
				// 	window.location.href = "{!! route('registration') !!}";
				// }, 5000);
			}
		}else{
			var data = new FormData($('.formRegistrasi')[0]);
			$.ajax({
				url: "{{ route('formPilihPoli') }}",
				type: 'POST',
				data: data,
				enctype: 'multipart/form-data',
				async: true,
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				if(data.status == 'success'){
					$('#confirm-dialog').modal('hide');
					$('#mainRegistrasi').hide();
					$('.loading').hide();
					$('.other-page').html(data.content).fadeIn();
				} else if(data.status == 'error') {
					swal({
						title: "MAAF !",
						text: data.message,
						type: "warning",
						// timer: 2000,
						showConfirmButton: true
					});
					// $('#confirm-dialog').modal('hide');
				} else {
					$('#mainRegistrasi').show();
				}
			});
		}
	});

	$('#form_datetime_today').datetimepicker({
        weekStart: 2,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
    });

    function cetakKedua() {
    	$('.printSEP').printArea();

    	setTimeout(function lanjut() {
			cetakKetiga();
		}, 1000);
    }

    function cetakKetiga() {
    	$('.printSEP').printArea();

    	swal({
			title : "Terima Kasih !!",
			type : "success",
			timer : 2000,
			showConfirmButton : false
		});
		setTimeout(function tutupCetak() {
			$('.printSEP').empty();
		}, 100);
		setTimeout(function awal() {
			window.location.href = "{!! route('registration') !!}";
		}, 1000);
    }
</script>