@extends('registration.layoutNew')
<style>
	.modal-dialog {
		margin: 20vh auto 0px auto
	}

	.bg{
		height: 350px;
		margin-left: 10px;
		margin-top: -30px;
		margin-right: 10px;
		margin-bottom: 10px;
		background: rgba(217, 236, 214, 0.75);
		border-radius: 20px;
	}

	.loader {
		border: 7px solid #f3f3f3;
		border-radius: 50%;
		border-top: 7px solid #3498db;
		width: 100px;
		height: 100px;
		margin-left: 635px;
		margin-top: 250px;
		-webkit-animation: spin 2s linear infinite; /* Safari */
		animation: spin 1s linear infinite;
	}

	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

@section('content-antrian-registration')
<div class="col-lg-12 col-md-12 panelContentRegistration">
	<div class="row text-center" id="antrian">
		<div class="col-md-12 text-center" style="top: 10rem">

			<!-- GET WAKTU DAN TANGGAL -->
			<input type="hidden" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}">
			<input type="hidden" name="waktukini" id="waktukini">
			<input type="hidden" name="waktubuka" id="waktubuka" value="{{$jambuka}}">
			<input type="hidden" name="waktututup" id="waktututup" value="{{$jamtutup}}">

			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6 text-center">
					<button type="button" class="btn btn-success btn-lg btn-block" style="width: 100%; height: 60px;" onclick="ambil()">
						<b>AMBIL ANTRIAN</b>
					</button>
				</div>
				<div class="col-md-3"></div>
			</div>
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6 text-center" style="top: 2rem">
					<button type="button" class="btn btn-primary btn-lg btn-block" style="width: 100%; height: 60px;" onclick="konfirmasi()">
						<b>KONFIRMASI ANTRIAN ONLINE</b>
					</button>
				</div>
				<div class="col-md-3"></div>
			</div>
			<div class="row" style="margin-top: 4rem">
				<div class="col-md-3"></div>
				<div class="col-md-6 text-center">
					<button type="button" class="btn btn-warning btn-lg btn-block" style="width: 100%; height: 60px;" onclick="cetakUlang()">
						<b>CETAK ULANG NOMOR ANTRIAN</b><p id="tests"></p>
					</button>
				</div>
				<div class="col-md-3"></div>
			</div>

		</div>
	</div>
</div>
<div class="loader"></div>

<!-- ambil antrian -->
@include('registration.pendaftaranNew.jenis_pasien')
@include('registration.pendaftaranNew.konsisi_pasien')
@include('registration.pendaftaranNew.cari_pasien')
@include('registration.pendaftaranNew.dialog_pasienlama')
{{-- @include('registration.pendaftaranNew.lahir_pasien') --}}
@include('registration.pendaftaranNew.pembayaran_pasien')
@include('registration.pendaftaranNew.poli_pasien')
@include('registration.pendaftaranNew.dokter_pasien')
@include('registration.pendaftaranNew.nik_pasien')
@include('registration.pendaftaranNew.jenis_kunjunganbpjs')
@include('registration.pendaftaranNew.dialog_kunjungan')
@include('registration.pendaftaranNew.no_antri_pasien')

<!-- konfirmasi -->
@include('registration.pendaftaranNew.konfirmasi_pasien')
@include('registration.pendaftaranNew.pasien_manual')
@include('registration.pendaftaranNew.no_antri_konfirmasi')

<!-- cetak ulang-->
@include('registration.pendaftaranNew.cetak_ulang')
@stop

@section('script-registration')
<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
<script type="text/javascript">
	var id_kiosk = window.location.href.split('/').reverse()[0]; // Id kiosk
	document.star
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	})

	function timeKiosk(){
		// var timeCur = new Date('April 17, 2023 01:01:00')
		var timeCur = new Date()
		var h = timeCur.getHours()
		var m = timeCur.getMinutes()
		var s = timeCur.getSeconds()
		time = (h<10 ? '0'+h : h)+':'+(m<10 ? '0'+m : m)
		$('#waktukini').val(time)
	}

	$(document).ready(function(){
		setInterval(timeKiosk,200)
		setTimeout(function(){
			$('.btn-cari').hide()
			$('#bpjslama').hide()
			$('#niklama').hide()
			$('.loader').hide()
			// $('#btnLanjut').hide();
			// $('#btn-aa').hide();
			if(id_kiosk==3||id_kiosk==4||id_kiosk==5||id_kiosk==6||id_kiosk==7||id_kiosk==8){
				var now = $('#waktukini').val()
				var waktubuka = $('#waktubuka').val()
				var waktututup = $('#waktututup').val()

				$('.antrian').hide()
				$('#KonfirmasiPasienModal').modal('show')
				$('.btn-kembali-konfirmasi').hide()
				$('.btnKembali').hide()
			}
		},500)
		refreshQr()
		if(id_kiosk == 1){
			setInterval(refreshQr, 2000)
		}else if(id_kiosk == 2){
			setInterval(refreshQr, 2500)
		}else if(id_kiosk == 3){
			setInterval(refreshQr, 2600)
		}else if(id_kiosk == 4){
			setInterval(refreshQr, 3700)
		}else if(id_kiosk == 5){
			setInterval(refreshQr, 2800)
		}else if(id_kiosk == 6){
			setInterval(refreshQr, 2900)
		}else if(id_kiosk == 7){
			setInterval(refreshQr, 3000)
		}else{
			setInterval(refreshQr, 2400)
		}
	})

	function refreshQr() {
		$.post("{{ route('refreshQR') }}", {"_token" : "{{ csrf_token() }}", id_kiosk: id_kiosk})
		.done(function (data) {
			if(data.status == "success"){
				let cekSudahPrint = data.data;
				let dataPasien = data.dataPasien;
				if(cekSudahPrint.sudah_print == 0){
					$('#KonfirmasiPasienModal').hide();
					$('#NoAntriKonfirmasiModal').modal('show');
					$('#id_antrian').val(dataPasien.idAntrian);
					$('#NoAntriKonfirmasiModal #token').val(cekSudahPrint.token);
					$('#NoAntriKonfirmasiModal #namaPoli').text(dataPasien.poli);
					$('#NoAntriKonfirmasiModal #noAntrian').text(dataPasien.noAntrian);
					$('#NoAntriKonfirmasiModal #kodeBooking').text(dataPasien.kodeBooking);
					$('#NoAntriKonfirmasiModal #tujuan').text(dataPasien.tujuan);
				}
				$("#qrBarcode").remove();
				$(".brcode").html(`<img id="qrBarcode" style="height: 200px; width: 250px" src="{{ asset("aset/images/tokenQR/`+data.token+`.svg") }}" alt="qr-code_antrian">`);
			}else{
				$(".brcode").html(`<img id="qrBarcode" style="height: 200px; width: 250px" src="{{ asset("aset/images/tokenQR/loadingqr.gif") }}" alt="qr-code_antrian">`);
			}
		})
		// .fail(function () {
		// 	deleteAllCookies();
		// 	window.location.reload();
		// 	$(".brcode").html(`<img id="qrBarcode" style="height: 200px; width: 250px" src="{{ asset("aset/images/tokenQR/loadingqr.gif") }}" alt="qr-code_antrian">`);
		// });
	}

	function deleteAllCookies() {
		const cookies = document.cookie.split(";");
		for (let i = 0; i < cookies.length; i++) {
			const cookie = cookies[i];
			const eqPos = cookie.indexOf("=");
			const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
			document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
		}
	}

	// Start Ambil Antrian 
	function ambil() {
		var now = $('#waktukini').val()
		var waktubuka = $('#waktubuka').val()
		var waktututup = $('#waktututup').val()

		if(now >= waktubuka && now <= waktututup){
			$('#antrian').hide()
			$('#JenisPasienModal').modal('show')
		}else{
			if(now <= waktututup){
				swal('Whoops!', 'Tidak bisa mengambil antrian sebelum jam '+waktubuka, 'warning')
			}else{
				swal('Whoops!', 'Pengambilan antrian sudah tutup jam '+waktututup, 'warning')
			}
		}
	}

	var pasien = ''
	var nik = ''
	var no_bpjs = ''
	var no_rm = ''
	var jenis_pasien = ''
	var geriatri = ''
	var metode = 'KIOSK'
	var status = 'belum'

	function kembali(params) {
		if (params == 'jenis_pasien') {
			$('#JenisPasienModal').modal('hide');
			$('#antrian').show();
		} else if (params == 'konsisi') {
			if (pasien == 'Y') {
				$('.titleHeaderRegistration').show();
				$('#KonsisiPasienModal').modal('hide');
				$('#JenisPasienModal').modal('show');
			} else {
				$('#KonsisiPasienModal').modal('hide');
				$('#DialogPasienLamaModal').modal('show');
			}
		} else if (params == 'lahir_pasien') {
			$('#LahirPasienModal').modal('hide');
			$('#KonsisiPasienModal').modal('show');
		} else if(params == 'pembayaran') {
			if (pasien == 'Y') {
				$('#PembayaranPasienModal').modal('hide');
				$('#KonsisiPasienModal').modal('show');
			} else {
				$('#PembayaranPasienModal').modal('hide');
				$('#KonsisiPasienModal').modal('show');
			}
		}else if(params=='cariPasien'){
			$('#CariPasienModal').modal('hide');
			$('#JenisPasienModal').modal('show');
		} else if(params=='pilih_dokter'){
			$('#DokterPasienModal').modal('hide');
			$('#PoliPasienModal').modal('show');
		} else if(params=='dialog-pasien-lama'){
			$('#DialogPasienLamaModal').modal('hide');
			$('#CariPasienModal').modal('show');
		} else if(params=='pasien_poli'){
			$('#PoliPasienModal').modal('hide');
			$('#PembayaranPasienModal').modal('show');
		} else if(params=='nikpasien'){
			$('#NikPasienModal').modal('hide');
			$('#DokterPasienModal').modal('show');
		} else if(params=='jenis_kunjungan'){
			$('#noreferensi').val('');
			$('#nikkunjungan').val('');
			$('#JenisKunjunganBpjsModal').modal('hide');
			$('#DokterPasienModal').modal('show');
		} else if(params=='kmanual'){
			$('#PasienManualModal').modal('hide');
			$('.antrian').hide();
			$('#KonfirmasiPasienModal').modal('show');
		} else if(params=='konfirmasi_pasien'){
			$('#KonfirmasiPasienModal').modal('hide');
			$('.antrian').show();
		}else if(params=='cetakUlang'){
			$('#cetakUlang').modal('hide');
			$('#antrian').show();
		}
	}
    
	function pasienbaru() {
		pasien = 'Y';

		$('.titleHeaderRegistration').hide();
		$('#JenisPasienModal').modal('hide');
		$('#KonsisiPasienModal').modal('show');
	}

	function pasienlama() {
		pasien = 'N';

		$('.titleHeaderRegistration').hide();
		$('#JenisPasienModal').modal('hide');
		$('#CariPasienModal').modal('show');
	}

	function cariPasien() {
		var cari = $('#dtpasien').val();
		if ($("#rm").is(":checked")) {
			if(cari.length >= 9){
				$.post("{{route('cari')}}", {"_token" : "{{ csrf_token() }}", no_rm:cari},function(data){
					if (data.code==200) {
						var nama = data.antrian.NamaCust;
						var nik = data.antrian.NoKtp;
						var norm = data.antrian.KodeCust;
						var nobpjs = data.antrian.FieldCust1;
						if(data.antrian.TglLahir=="" || data.antrian.TglLahir==null){
							var lahir = ""
						}else{
							var lahir = data.antrian.TglLahir.substring(0,10);
						}

						$('#CariPasienModal').modal('hide');
						$('#DialogPasienLamaModal').modal('show');
						$('#DialogPasienLamaModal #kode').val(norm);
						$('#DialogPasienLamaModal #plamanama').html(nama);
						$('#DialogPasienLamaModal #plamanik').html(nik);
						$('#DialogPasienLamaModal #plamarm').html(norm);
						$('#DialogPasienLamaModal #plamabpjs').html(nobpjs);
						$('#DialogPasienLamaModal #plamalahir').html(lahir);
					} else {
						swal('Whoops', 'Data Tidak Ditemukan!', 'error')
					}
				});
			}else{
				swal('Whoops', 'Silahkan Periksa Inputan Anda!', 'error')
			}
		} else if ($("#bpjs").is(":checked")) {
			if(cari.length == 13){
				$.post("{{route('cari')}}", {"_token" : "{{ csrf_token() }}", bpjs:cari},function(data){
					if (data.code==200) {
						var nama = data.antrian.NamaCust;
						var nik = data.antrian.NoKtp;
						var norm = data.antrian.KodeCust;
						var nobpjs = data.antrian.FieldCust1;
						var lahir = data.antrian.TglLahir.substring(0,10);

						$('#CariPasienModal').modal('hide');
						$('#DialogPasienLamaModal').modal('show');
						$('#DialogPasienLamaModal #kode').val(norm);
						$('#DialogPasienLamaModal #plamanama').html(nama);
						$('#DialogPasienLamaModal #plamanik').html(nik);
						$('#DialogPasienLamaModal #plamarm').html(norm);
						$('#DialogPasienLamaModal #plamabpjs').html(nobpjs);
						$('#DialogPasienLamaModal #plamalahir').html(lahir);
					} else {
						swal('Whoops', 'Data Tidak Ditemukan!', 'error')
					}
				});
			}else{
				swal('Whoops', 'Silahkan Periksa Inputan Anda!', 'error')
			}
		} else if ($("#nik").is(":checked")) {
			if(cari.length == 16){
				$.post("{{route('cari')}}", {"_token" : "{{ csrf_token() }}", nik:cari},function(data){
					if (data.code==200) {
						var nama = data.antrian.NamaCust;
						var nik = data.antrian.NoKtp;
						var norm = data.antrian.KodeCust;
						var nobpjs = data.antrian.FieldCust1;
						var lahir = data.antrian.TglLahir.substring(0,10);

						$('#CariPasienModal').modal('hide');
						$('#DialogPasienLamaModal').modal('show');
						$('#DialogPasienLamaModal #plamanama').html(nama);
						$('#DialogPasienLamaModal #plamanik').html(nik);
						$('#DialogPasienLamaModal #plamarm').html(norm);
						$('#DialogPasienLamaModal #plamabpjs').html(nobpjs);
						$('#DialogPasienLamaModal #plamalahir').html(lahir);
					} else {
						swal('Whoops', 'Data Tidak Ditemukan!', 'error')
					}
				});
			}else{
				swal('Whoops', 'Silahkan Periksa Inputan Anda!', 'error')
			}
		} else if ($("#jkn").is(":checked")) {
			if(cari.length == 16){
				$.post("{{route('cari')}}", {"_token" : "{{ csrf_token() }}", jkn:cari},function(data){
					if (data.code==200) {
						var nama = data.antrian.NamaCust;
						var nik = data.antrian.NoKtp;
						var norm = data.antrian.KodeCust;
						var nobpjs = data.antrian.FieldCust1;
						var lahir = data.antrian.TglLahir;

						$('#CariPasienModal').modal('hide');
						$('#DialogPasienLamaModal').modal('show');
						$('#DialogPasienLamaModal #plamanama').html(nama);
						$('#DialogPasienLamaModal #plamanik').html(nik);
						$('#DialogPasienLamaModal #plamarm').html(norm);
						$('#DialogPasienLamaModal #plamabpjs').html(nobpjs);
						$('#DialogPasienLamaModal #plamalahir').html(lahir).substring(0,10);
					} else {
						swal('Whoops', 'Data Tidak Ditemukan!', 'error')
					}
				});
			}else{
				swal('Whoops', 'Silahkan Periksa Inputan Anda!', 'error')
			}
		} else {
			swal('whoops', 'Silahkan memilih kategori pencarian terlebih dahulu!', 'warning');
		}
	}

	function btndialog() {
		if (pasien=='Y') {
			$('#DialogPasienLamaModal').modal('hide');
			$('#KonsisiPasienModal').modal('show');
		} else {
			var nik = $('#plamanik').html();
			var nobpjs = $('#plamabpjs').html();
			var norm = $('#plamarm').html();

			$('#DialogPasienLamaModal').modal('hide');
			$('#KonsisiPasienModal').modal('show');
			$('#KonsisiPasienModal #konsisinik').val(nik);
			$('#KonsisiPasienModal #konsisirm').val(norm);
			$('#KonsisiPasienModal #konsisibpjs').val(nobpjs);
		}
	}

	function ya() {
		geriatri = 'Y';
		var nik = $('#konsisinik').val();
		var norm = $('#konsisirm').val();
		var nobpjs = $('#konsisibpjs').val();

		$('#KonsisiPasienModal').modal('hide');
		$('#PembayaranPasienModal').modal('show');
		$('#PembayaranPasienModal #pembayarannik').val(nik);
		$('#PembayaranPasienModal #pembayaranrm').val(norm);
		$('#PembayaranPasienModal #pembayaranbpjs').val(nobpjs);
	}

	function tidak() {
		geriatri = 'N';
		var nik = $('#konsisinik').val();
		var norm = $('#konsisirm').val();
		var nobpjs = $('#konsisibpjs').val();

		$('#KonsisiPasienModal').modal('hide');
		$('#PembayaranPasienModal').modal('show');
		$('#PembayaranPasienModal #pembayarannik').val(nik);
		$('#PembayaranPasienModal #pembayaranrm').val(norm);
		$('#PembayaranPasienModal #pembayaranbpjs').val(nobpjs);
	}

	function umum() {
		jenis_pasien = 'UMUM';
		var nik = $('#pembayarannik').val();
		var norm = $('#pembayaranrm').val();
		var bpjs = $('#pembayaranbpjs').val();

		$('#PembayaranPasienModal').modal('hide');
		$('#PoliPasienModal').modal('show');
		$('#PoliPasienModal #polinik').val(nik);
		$('#PoliPasienModal #polirm').val(norm);
		$('#PoliPasienModal #polibpjs').val(bpjs);
	}

	function bpjs() {
		jenis_pasien = 'BPJS';
		var nik = $('#pembayarannik').val();
		var norm = $('#pembayaranrm').val();
		var bpjs = $('#pembayaranbpjs').val();

		$('#PembayaranPasienModal').modal('hide');
		$('#PoliPasienModal').modal('show');
		$('#PoliPasienModal #polinik').val(nik);
		$('#PoliPasienModal #polirm').val(norm);
		$('#PoliPasienModal #polibpjs').val(bpjs);
	}

	function asuransi() {
		jenis_pasien = 'ASURANSILAIN';
		var nik = $('#pembayarannik').val();
		var norm = $('#pembayaranrm').val();
		var bpjs = $('#pembayaranbpjs').val();

		$('#PembayaranPasienModal').modal('hide');
		$('#PoliPasienModal').modal('show');
		$('#PoliPasienModal #polinik').val(nik);
		$('#PoliPasienModal #polirm').val(norm);
		$('#PoliPasienModal #polibpjs').val(bpjs);
	}

	function btnpoli(kode) {
		var nik = $('#polinik').val();
		var norm = $('#polirm').val();
		var bpjs = $('#polibpjs').val();
		var tanggal = $('#tanggal').val();

		$('#PoliPasienModal').hide()
		$('.loader').show();
		$('#tempatData').empty();
		$.post("{{route('api-dokter')}}", {"_token" : "{{ csrf_token() }}", kodePoli:kode, tanggal:tanggal},function(res){
			if ((res.code==200)) {
				if (res.dokter.length>0) {
					$.each(res.dokter, function (index, value)  
					{  
						var kdsubpoli = value.kodesubspesialis;
						var kdpoli   = value.kodepoli;
						var kddokter = value.kodedokter;
						var nama     = value.namadokter;
						var jadwal   = value.jadwal;

						var html = ''

						html += '<tr id="kd_'+kdsubpoli+'">'
						html += '<td id="kdpoli'+kdsubpoli+'">'+kdsubpoli+'</td>'
						html += '<td id="namadokter">'+nama+'</td>'
						html += '<td id="jadwaldokter">'+jadwal+'</td>'
						html += '<td>'
						html += '<a href="javascript:void(0)" class="btn btn-sm btn-rounded btn-primary mr-2 text-center" onclick="pilihdokter(`'+kdsubpoli+','+jadwal+','+kddokter+'`)">Pilih</a>'
						html += '</td>'
						html += '</tr>'

						$('#tempatData').append(html)
					});

					$('.loader').hide();

					if (pasien=='Y') {
						$('#PoliPasienModal').modal('hide');
						$('#DokterPasienModal').modal('show');
					} else {
						$('#PoliPasienModal').modal('hide');
						$('#DokterPasienModal').modal('show');
						$('#DokterPasienModal #dokternik').val(nik);
						$('#DokterPasienModal #dokterrm').val(norm);
						$('#DokterPasienModal #dokterbpjs').val(bpjs);
					}
				} else {
					swal('Sorry!','Tidak Ada Jadwal Dokter Pada Poli Tersebut Hari Ini','warning')
					$('.loader').hide();
					$('#PoliPasienModal').show();
				}
			} else {
				swal('Sorry!','Tidak Ada Jadwal Dokter Pada Poli Tersebut Hari Ini','warning')
				$('.loader').hide();
				$('#PoliPasienModal').show();
			}
		})
	}

	function pilihdokter (kdpoli){
		var nik = $('#dokternik').val();
		var no_rm = $('#dokterrm').val();
		var no_bpjs = $('#dokterbpjs').val();
		var tanggal = $('#tanggal').val();
		var result = kdpoli.split(',');
		var kodepoli = result[0];
		var jadwal = result[1];
		var kddokter = result[2];

		$('#DokterPasienModal').hide();
		$('.loader').show();

		$.post("{{route('politujuan')}}", {"_token" : "{{ csrf_token() }}",
			nik:nik,
			no_rm:no_rm, 
			no_bpjs:no_bpjs,
			kodepoli:kodepoli, 
			kddokter:kddokter, 
			jadwal:jadwal, 
			pasien:pasien, 
			tglperiksa:tanggal
		},function(data){
			if (jenis_pasien=='BPJS') {
				var kodepoli = data.poli.kdpoli;
				var kddokter = data.kddokter;
				var jadwal = data.jadwal;
				// var kdbooking = data.kdbooking;
				// var no_antrian = data.no_antrian;

				$('#DokterPasienModal').modal('hide');
				$('#JenisKunjunganBpjsModal').modal('show');
				$('#JenisKunjunganBpjsModal #nikkunjungan').val(nik);
				$('#JenisKunjunganBpjsModal #nobpjskunjungan').val(no_bpjs);
				$('#JenisKunjunganBpjsModal #normkunjungan').val(no_rm);
				$('#JenisKunjunganBpjsModal #kodepolibpjs').val(kodepoli);
				$('#JenisKunjunganBpjsModal #kddokterbpjs').val(kddokter);
				$('#JenisKunjunganBpjsModal #jadwalbpjs').val(jadwal);
				// $('#JenisKunjunganBpjsModal #kdbookingbpjs').val(kdbooking);
				// $('#JenisKunjunganBpjsModal #no_antrianbpjs').val(no_antrian);
				$('.loader').hide();
			} else {
				var kode = data.kode;
				var kodepoli = data.poli.kdpoli;
				var kddokter = data.kddokter;
				var jadwal = data.jadwal;
				// var kdbooking = data.kdbooking;
				// var no_antrian = data.no_antrian;

				$('#DokterPasienModal').modal('hide');
				$('#NikPasienModal').modal('show');
				$('#NikPasienModal #nikpasien').val(nik);
				$('#NikPasienModal #bpjspasienlama').val(no_bpjs);
				$('#NikPasienModal #rmpasienlama').val(no_rm);
				$('#NikPasienModal #kodepoli').val(kodepoli);
				$('#NikPasienModal #kddokter').val(kddokter);
				$('#NikPasienModal #jadwal').val(jadwal);
				// $('#NikPasienModal #kdbooking').val(kdbooking);
				// $('#NikPasienModal #no_antrian').val(no_antrian);
				$('.loader').hide();
			}
		});
	}

	$('#btn-next-umum').click(function (e) { 
		e.preventDefault();
		var nik = $('#nikpasien').val();
		var no_rm = $('#rmpasienlama').val();
		var no_bpjs = $('#bpjspasienlama').val();
		var kdpoli = $('#kodepoli').val();
		var kddokter = $('#kddokter').val();
		var jadwal = $('#jadwal').val();
		var tanggal = $('#tanggal').val();

		$('#NikPasienModal').hide();
		$('.loader').show();

		if (nik!='') {
			$('#btn-next-umum').attr('disabled',true);
			$.post("{{route('ambil-antrian-save')}}", {"_token" : "{{ csrf_token() }}",
				kodepoli:kdpoli, 
				pasien:pasien, 
				tglperiksa:tanggal,
				jadwal:jadwal,
				kddokter:kddokter,
				geriatri:geriatri,
				metode:metode,
				status:status,
				jenis_pasien:jenis_pasien,
				nik:nik,
				no_bpjs:no_bpjs,
				no_rm:no_rm,
			},function(data){
				if(data.code==200){
					var kdpoli = data.data.kode_poli;
					var id_antrian = data.data.id;
					var poli = data.poli.tm_poli.NamaPoli;
					var kat_pasien = data.data.is_pasien_baru;
					var no_antrian = data.data.no_antrian_pbaru;
					var no_antrian_poli = data.data.nomor_antrian_poli;
					var kodebooking = data.data.kode_booking;

					$('#NikPasienModal').modal('hide');
					$('#NoAntrianPasienModal').modal('show');
					$('#NoAntrianPasienModal #kodepoli').val(kdpoli);
					$('#NoAntrianPasienModal #id_antrian').val(id_antrian);
					$('#NoAntrianPasienModal #is_pasien').val(kat_pasien);
					$('#NoAntrianPasienModal #poli').html(poli);
					if (kat_pasien == 'Y') {
						$('#NoAntrianPasienModal #no_antrian').html(no_antrian);
					} else {
						$('#NoAntrianPasienModal #no_antrian').html(no_antrian_poli);
					}
					$('#NoAntrianPasienModal #kodebooking').html(kodebooking);
					$('.loader').hide();
				}else{
					swal('Whoops '+data.code,data.message,'warning')
					$('.loader').hide();
					$('#NikPasienModal').show();
				}
				$('#btn-next-umum').attr('disabled',false)
			}).fail(()=>{
				$('#btn-next-umum').attr('disabled',false)
				$('.loader').hide();
				$('#NikPasienModal').show();
			});
		} else {
			swal('Whoops', 'NIK Tidak Boleh Kosong!', 'warning')
			$('.loader').hide();
			$('#NikPasienModal').show();
		}
	});

	function btnCariRujukan() {
		var valRujuk = $('#noreferensi').val();
		var jenisKunjungan = $('input[name=pkkunjungan]:checked').val();

		if (!valRujuk) {
			swal('Whoops!', 'Silahkan mengisi no. rujukan Terlebih dahulu', 'warning');
		} else {
			$.post("{{route('cari-rujukan')}}", {"_token" : "{{ csrf_token() }}", noRujuk:valRujuk,jenisKunjungan:jenisKunjungan},function(data){
				if(data.code==200){
					var nik = data.nik;

					swal('Berhasil! '+data.code, data.message,'success')
                    if(nik!='kontrol'){
    					$('#nikkunjungan').val(nik);
                    }
					$('#btnCariRujukan').hide();
					$('#btnLanjut').show();
					$("#fktp").removeAttr('disabled');
					$("#internal").removeAttr('disabled');
					$("#kontrol").removeAttr('disabled');
					$("#antarrs").removeAttr('disabled');
					$("#noreferensi").removeAttr('disabled');
				}else{
					swal('Whoops! '+data.code, data.message,'error')
				}
			});
		}
	}

	$('#btnLanjut').click(function (e) { 
		e.preventDefault();
		var nik = $('#nikkunjungan').val();
		var no_rm = $('#normkunjungan').val();
		var no_bpjs = $('#nobpjskunjungan').val();
		var kdpoli = $('#kodepolibpjs').val();
		var kddokter = $('#kddokterbpjs').val();
		var jadwal = $('#jadwalbpjs').val();
		var tanggal = $('#tanggal').val();
		var referensi = $('#noreferensi').val();
		var check = $('input:radio:checked').length;
		var cekNoRujuk = $('#nmrRujuk').val();
		$('.loader').show();

		if (nik!='' && referensi!='' && check!='') {
			if ($("#fktp").is(":checked")) {
				var jenis_kunjungan = 1;
			} else if($("#internal").is(":checked")) {
				var jenis_kunjungan = 2;
			} else if($("#kontrol").is(":checked")) {
				var jenis_kunjungan = 3;
			} else if($("#antarrs").is(":checked")) {
				var jenis_kunjungan = 4;
			} else {
				var jenis_kunjungan = '';
			}
			$('#btnLanjut').attr('disabled',true);
			$.post("{{route('ambil-antrian-save')}}", {"_token" : "{{ csrf_token() }}",
				kodepoli:kdpoli, 
				pasien:pasien, 
				tglperiksa:tanggal,
				jadwal:jadwal,
				kddokter:kddokter,
				// kodebooking:kdbooking,
				geriatri:geriatri,
				metode:metode,
				// no_antrian:no_antrian,
				status:status,
				jenis_pasien:jenis_pasien,
				nik:nik,
				no_bpjs:no_bpjs,
				no_rm:no_rm,
				jenis_kunjungan:jenis_kunjungan,
				no_referensi:referensi,
				cekNoRujuk:cekNoRujuk
			},function(data){
				if(data.code==200){
					var kdpoli = data.data.kode_poli;
					var id_antrian = data.data.id;
					var poli = data.poli.tm_poli.NamaPoli;
					var no_antrian = data.data.no_antrian;
					var no_antrian_poli = data.data.nomor_antrian_poli;
					var kodebooking = data.data.kode_booking;
					var kat_pasien = data.data.is_pasien_baru;

					console.log(kat_pasien)
					$('.loader').hide();
					$('#JenisKunjunganBpjsModal').modal('hide');
					$('#NoAntrianPasienModal').modal('show');
					$('#NoAntrianPasienModal #kodepoli').val(kdpoli);
					$('#NoAntrianPasienModal #id_antrian').val(id_antrian);
					$('#NoAntrianPasienModal #is_pasien').val(kat_pasien);
					$('#NoAntrianPasienModal #poli').html(poli);
					if (kat_pasien == 'Y') {
						$('#NoAntrianPasienModal #no_antrian').html(no_antrian);
					} else {
						$('#NoAntrianPasienModal #no_antrian').html(no_antrian_poli);
					}
					$('#NoAntrianPasienModal #kodebooking').html(kodebooking);
				}else{
					swal('Whoops '+data.code,data.message,'warning')
					$('.loader').hide();
					$('#JenisKunjunganBpjsModal').show();
				}
				$('#btnLanjut').attr('disabled',false);
			}).fail(()=>{
				$('#btnLanjut').attr('disabled',false);
			})
		} else {
			swal('Whoops', 'NIK Atau No Referensi Tidak Boleh Kosong!', 'warning')
			$('.loader').hide();
			$('#JenisKunjunganBpjsModal').show();
		}
	});

	function cetak() {
		var id = $('#id_antrian').val();
		var kodepoli = $('#kodepoli').val();
		var is_pasien = $('#is_pasien').val();
		var is_pasien_confirm = $('#is_pasien_konfirmasi').val();
		if(is_pasien != '') {
			var katPasien = is_pasien;
		} else {
			var katPasien = is_pasien_confirm;
		}
		var noPoli = $('#no_antrian').text();
		var token = $('#NoAntriKonfirmasiModal #token').val();
		if (katPasien == 'Y') { // P. Baru
			var url = '{{route("cetak-antrian", ["id" => ":id" ] )}}';
			url = url.replace(":id", id);
		} else {
			var url = '{{route("cetakAntrianKonterPoli", ":id")}}';
			url = url.replace(":id", id);
		}

		if(token != null && token != ""){
			let urlC = '{{route("ubahStatusPrint", ["token" => ":token" ] )}}';
			const urlCetak = urlC.replace(":token", token);

			$.post(urlCetak)
			.done(function(data){
				window.open(url);
				location.reload(true);
			})
			.fail(function(){
				swal('Error', 'Silahkan Coba Lagi', 'error');
			});
		}else{
			window.open(url);
			location.reload(true);
		}
	}
	// End Ambil Antrian

	// Konfirmasi Antrian
	function konfirmasi() {
		var now = $('#waktukini').val()
		var waktubuka = $('#waktubuka').val()
		var waktututup = $('#waktututup').val()

		if(now >= waktubuka && now <= waktututup){
			$('.antrian').hide()
			$('#KonfirmasiPasienModal').modal('show')
		}else{
			if(now <= waktututup){
				swal('Whoops!', 'Tidak bisa mengambil antrian sebelum jam '+waktubuka, 'warning')
			}else{
				swal('Whoops!', 'Pengambilan antrian sudah tutup jam '+waktututup, 'warning')
			}
		}
	}

	function manual() {
		$('#KonfirmasiPasienModal').modal('hide');
		$('#PasienManualModal').modal('show');
	}
	
	$('#k_manual').click(function(e){
		e.preventDefault()
		var now = $('#waktukini').val()
		var waktubuka = $('#waktubuka').val()
		var waktututup = $('#waktututup').val()

		if(now >= waktubuka && now <= waktututup){
			let kode = $("#kodeUnik").val()
			if(kode == "" || kode == null){
				swal('Error', 'Kode Booking Tidak Boleh Kosong', 'error')
				return
			}
			$('#k_manual').attr('disabled',true)
			$('.loader').show()
			$.post("{{ route('konfirmasiManual') }}", {"_token" : "{{ csrf_token() }}",kode:kode}).done(function(data){
				if(data.status == "success"){
					let dataRes = data.data
					console.log(dataRes)
					$('.loader').hide()
					$('#KonfirmasiPasienModal').modal('hide')
					$('#NoAntriKonfirmasiModal').modal('show')
					$('#id_antrian').val(dataRes.idAntrian)
					$('#NoAntriKonfirmasiModal #namaPoli').text(dataRes.poli)
					$('#NoAntriKonfirmasiModal #is_pasien_konfirmasi').val(dataRes.is_pasien)
					$('#NoAntriKonfirmasiModal #noAntrian').text(dataRes.noAntrian)
					$('#NoAntriKonfirmasiModal #kodeBooking').text(dataRes.kodeBooking)
					$('#NoAntriKonfirmasiModal #tujuan').text(dataRes.tujuan)
					$('#k_manual').attr('disabled',false)
				}else{
					$('#k_manual').attr('disabled',false)
					if(typeof(data.message) !="undefined" && data.message !==null){
						swal('Whoops', data.message, data.status)
					}
					$('.loader').hide()
				}
			}).fail(function(){
				$('#k_manual').attr('disabled',false)
				swal('Whoops', 'Kesalahan Ketika Konfirmasi', 'error')
				$('.loader').hide()
			})
		}else{
			if(now <= waktututup){
				swal('Whoops!', 'Tidak bisa mengambil antrian sebelum jam '+waktubuka, 'warning')
			}else{
				swal('Whoops!', 'Pengambilan antrian sudah tutup jam '+waktututup, 'warning')
			}
		}
	})

	function cetakUlang(){
		var now = $('#waktukini').val()
		var waktubuka = $('#waktubuka').val()
		var waktututup = $('#waktututup').val()

		if(now >= waktubuka && now <= waktututup){
			$('#antrian').hide()
			$('#cetakUlang').modal('show')
		}else{
			if(now <= waktututup){
				swal('Whoops!', 'Tidak bisa mengambil antrian sebelum jam '+waktubuka, 'warning')
			}else{
				swal('Whoops!', 'Pengambilan antrian sudah tutup jam '+waktututup, 'warning')
			}
		}
	}

	$('#cariCetakUlang').click(()=>{
		var kode = $('#kodeCetakUlang').val()
		if(kode){
			$('#cariCetakUlang').attr('disabled',true)
			$.post('{{route("cariCetakUlang")}}',{kode:kode}).done((res)=>{
				console.log(res)
				if(res.code==200){
					$('#cetakUlang').modal('hide')
					$('#NoAntriKonfirmasiModal').modal('show')
					$('#id_antrian').val(res.data.idAntrian)
					$('#NoAntriKonfirmasiModal #namaPoli').text(res.data.poli)
					$('#NoAntriKonfirmasiModal #is_pasien_konfirmasi').val(res.data.is_pasien)
					$('#NoAntriKonfirmasiModal #noAntrian').text(res.data.noAntrian)
					$('#NoAntriKonfirmasiModal #kodeBooking').text(res.data.kodeBooking)
					$('#NoAntriKonfirmasiModal #tujuan').text(res.data.tujuan)
					// swal('Berhasil', res.message, 'success')
				}else{
					swal('Gagal', res.message, 'error')
				}
				$('#cariCetakUlang').attr('disabled',false)
			}).fail(()=>{
				$('#cariCetakUlang').attr('disabled',false)
				swal('Gagal', 'Terjadi kesalahan, silahkan coba lagi', 'error')
			})
		}else{
			swal('Error', 'Kode Booking Tidak Boleh Kosong', 'error')
		}
	})

	$('#nikbaru').keyup(() => {
		var check = $('#nikbaru').val();
		var lcheck = check.length;

		if (lcheck <= 16) {
		} else {
			$('#nikbaru').val('');
			swal('Whooops','NIK Tidak Boleh Lebih Dari 16 Digit','warning');
		}
	})

	$('input[name="nikpasien"]').keyup(() => {
		var nik = $('input[name="nikpasien"]').val();
		if (nik.length < 16) {
			$('.btn-store').attr('disabled','disabled');
			$('.msg-nik').html('Maaf, NIK tidak boleh kurang dari 16');
		}else {
			$('.msg-nik').html('');
			$('input[name="nikpasien"]').val(nik);            
			$('.btn-store').removeAttr('disabled');
		}
	})

	$('input[name="nikkunjungan"]').keyup(() => {
		var nik = $('input[name="nikkunjungan"]').val();
		if (nik.length < 16) {
			$('.msg-nikkunjungan').html('Maaf, NIK tidak boleh kurang dari 16');
		}else {
			$('.msg-nikkunjungan').html('');
			$('input[name="nikkunjungan"]').val(nik);
		}
	})

	$('input[name="noreferensi"]').keyup(() => {
		var noref = $('input[name="noreferensi"]').val();
		if (noref.length < 19) {
			$('.btn-store').attr('disabled','disabled');
			$('.msg-noref').html('Maaf, No rujukan tidak boleh kurang dari 19');
		}else {
			$('.msg-noref').html('');
			$('input[name="noreferensi"]').val(noref);            
			// $('.btn-store').removeAttr('disabled');
		}
	})
	function convertToUppercase(input) {
        input.value = input.value.toUpperCase();
    }
</script>
@stop