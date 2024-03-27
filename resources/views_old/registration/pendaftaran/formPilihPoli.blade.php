<div class="col-lg-12">
	<div class="pull-left">
		<a href="{{ route('registration') }}" class="btn btnBack m-l-0">
			<i class="fa fa-chevron-left"></i>
			<span>Kembali</span>
		</a>
	</div>
	<div class="pull-right">
		<button id='btn-simpan' class="btn btnBack">
			<span class="m-l-0 m-r-15">Cetak</span>
			<i class="fa fa-print"></i>
		</button>
	</div>
	<div class="col-xs-12 m-t-15 m-b-5 panelPilihPolis">
		<form class="formPoli">
			<label class="judulPilihPoli m-b-0 m-t-15">Pilih Poli Tujuan Anda !!</label>
			<input type="hidden" name="jenis_pendaftaran" value="{{ $jenis_pendaftaran }}">
			<input type="hidden" name="no_identitas" value="{{ $no_identitas }}">
			<input type="hidden" name="KodeCust" value="{{ $KodeCust }}">
			<input type="hidden" name="statusInput" value="{{ $statusInput }}">
			<input type="hidden" name="tanggal" value="{{ $tanggal }}">
			<input type="hidden" name="noRujuk" value="{{ $noRujuk }}">
			<div class="clearfix"></div>
			<div class="boxPoli">
				@foreach ($polis as $poli)
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
						<input type="radio" name="KodePoli" value="{{ $poli->KodePoli }}" class="cstRadio" id='poli{{$poli->KodePoli}}'>
						<label class='namePoli' for="poli{{$poli->KodePoli}}">
							{{ ucwords(strtolower($poli->NamaPoli)) }}
						</label>
					</div>
				@endforeach
			</div>
		</form>
	</div>
</div>
<div class="printReceipt"></div>

<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
<script type="text/javascript">
	$('#btn-simpan').click(function(e){
		e.preventDefault();
		var today = new Date();
		var curr_hour = today.getHours();
		var jnsPendaftaran = '{{ $jenis_pendaftaran }}';
		var tglPeriksa = '{{ date("Y-m-d", strtotime($tanggal)) }}';
		var tglNow = '{{ $tglNow }}';
		var statusSubmit = '';
		var messageStatus = '';
		if (jnsPendaftaran == 'UMUM' && tglPeriksa == tglNow) {
			// if (curr_hour < 11) {
			if (curr_hour < 15) {
				statusSubmit = 'Ready';
			}else{
				statusSubmit = 'notReady';
				messageStatus = 'Saat ini sudah melebihi jam Operasional !!';
			}
		}else{
			statusSubmit = 'Ready';
		}

		if (statusSubmit == 'Ready') {
			var data = new FormData($('.formPoli')[0]);
			$.ajax({
				url: "{{ route('doRegistrasi') }}",
				type: 'POST',
				data: data,
				enctype: 'multipart/form-data',
				async: true,
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				if(data.status == 'success'){
					var cetak = '';
					if (data.waktuDaftar == 'today') {
						cetak += '<div style="color:#000;padding:10px;width:280px;margin-top:10px;">';
						cetak += '<div style="float: left;padding-top: 5px">';
						cetak += '<img src="{!! url("AssetsAdmin/dist/img/logo hitam.png") !!}" width="35px">';
						cetak += '</div>';
						cetak += '<label style="margin: 0px;line-height: 10px;font-size: 10px;">Rumah Sakit</label><br>';
						cetak += '<label style="margin: 0px;line-height: 15px;font-size: 14px;">Dr. WAHIDIN SUDIRO HUSODO</label><br>';
						cetak += '<label style="margin: 0px;line-height: 20px;font-size: 12px;">KOTA MOJOKERTO</label><br>';
						cetak += '<div style="border-bottom: solid 2px #777;margin-bottom: 10px;"></div>';
						cetak += '<center>';
						cetak += '<label style="margin: 0px;line-height: 10px;font-weight:normal;font-size:12px;margin-bottom:10px;">Selamat Datang</label><br>';
						cetak += '</center>';
						cetak += '<label style="font-size: 12px;margin: 0px;">No RM : '+data.data.No_RM+'</label><br>';
						cetak += '<label style="font-size: 12px;margin: 0px;">Nama : '+data.data.Nama_Pasien+'</label><br>';
						cetak += '<table style="width: 100%;color: #000;margin-top:5px;">';
						cetak += '<tr>';
						cetak += '<td width="50%" align="left" style="font-size:11px;">Tanggal : '+data.tgl+'</td>';
						cetak += '<td width="50%" align="right" style="font-size:11px;">Jam : '+data.jam+'</td>';
						cetak += '</tr>';
						cetak += '</table>';
						cetak += '<div style="border-bottom: solid 2px #777;margin-bottom: 10px;"></div>';
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
						cetak += '<div style="border-bottom: solid 2px #777;padding-bottom: 10px;"></div>';
						cetak += '<label style="margin: 0px;font-weight:normal;margin-left:35px;font-size:12px;">Terima Kasih Atas Kunjungan Anda.</label>';
						cetak += '</div>';
					}else{
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
					}
					$('.printReceipt').html(cetak);
					$('.printReceipt').printArea();
					setTimeout(function tutupCetak() {
						$('.printReceipt').empty();
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
				} else {
					swal({
						title : "Maaf !!",
						text: data.message,
						type : "warning",
						timer : 2000,
						showConfirmButton : false
					});
				}
			});
		}else{
			swal({
				title: "MAAF !",
				text: messageStatus,
				type: "error",
				timer: 2000,
				showConfirmButton: false
			});
		}
	});
</script>