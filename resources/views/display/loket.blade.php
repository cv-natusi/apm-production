<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>APM | Display Loket</title>
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="{!! url('asset_display/plugins/fontawesome-free/css/all.min.css') !!}">
	<link rel="stylesheet" href="{!! url('asset_display/dist/css/adminlte.min.css') !!}">
	<style type="text/css">
		.lockscreen {
			background-image: url("{!! url('aset/images/DSC02323.jpg') !!}");
			background-repeat: no-repeat;
			background-size: cover;
			background-position: center;
			height: 100%;
		}
	</style>
</head>
<body class="hold-transition lockscreen">
	<section class="content">
		<div class="container-fluid">
			<div class="row mt-2">
				<div class="col-lg-12 col-12">
					<div class="small-box bg-white">
						<div class="inner p-2">
							<nav class="navbar navbar-expand-lg justify-content-between p-0">
								<a class="navbar-brand" href="#">
									<img src="{{ url('uploads/identitas') }}/{!! $data['identitas']->logo_kiri !!}" height="50" alt="">
								</a>

								<div class="my-2 my-lg-0 text-center">
									<h4 class="m-0 font-weight-bold">
										<strong>
											<span id="clockDisplay">{{date('H:i:s')}}</span>
										</strong>
									</h4>
									<span class="text-muted">{{$data['tanggal']}}</span>
								</div>
							</nav>
						</div>
						<a href="#" class="float-left" role="button" onclick="fullscreen();">
							<i class="fas fa-expand-arrows-alt"></i>
						</a>
					</div>
				</div>
			</div>

			<div class="row text-center">
				<div class="col-lg-4 col-4">
					<div class="card border border-secondary" style="background-color: rgb(44, 186, 68, 0.8); color:#fff;">
						<div class="card-body text-center p-1">
							<h2 class="m-0 text-warning font-weight-bold namaCounterPoli">LOKET</h2>
							<h4 class="m-0 font-weight-bold">LOKET PENDAFTARAN</h4>
						</div>
					</div>
					<div class="card border border-secondary mb-0" style="background-color: rgb(44, 186, 68, 0);">
						<div class="card-header p-1" style="background-color: rgb(44, 186, 68, 0.8);">
							<h2 class="m-0 font-weight-bold" style="color:#fff;">NOMOR ANTRIAN</h2>
						</div>
						<div class="card-body p-3 dataAntrianSaatIni" style="background-color: rgb(255, 255, 255, 0.8); color:#000;">
							{{-- <h1 class="m-0 font-weight-bold" style="font-size:100px">B 080</h1>
							<hr class="mt-0 mb-0" style="border:2px solid #000; width: 70%;">
							<h4 class="m-0 font-weight-bold">MENUJU KE<br>NAMA POLI</h4> --}}
						</div>
					</div>
				</div>
				<div class="col-lg-8 col-8" style="max-height: 350px;">
					<div class="card bg-success border border-secondary" style="height:100%">
	                    <div class="embed-responsive embed-responsive-21by9" style="height:100%">
	                      <iframe class="embed-responsive-item" id="ytembed" src="https://www.youtube.com/embed/E-GnOLKx_7Y?playlist=E-GnOLKx_7Y&loop=1&autoplay=1&mute=1" allowfullscreen></iframe>
	                    </div>
					</div>
				</div>
			</div>

			<div class="row mt-3 tampilDataLoket">
			</div>

			<div class="row mt-3">
				<div class="col-lg-12 col-12">
					<div class="card bg-warning">
						<div class="card-body text-center p-2">
							<h5 class="m-0 font-weight-bold">
								<marquee>
									TETAP PATUHI PROTOKOL KESEHATAN.&nbsp;&nbsp;&nbsp;&nbsp;
									MEMAKAI MASKER DAN MENCUCI TANGAN.&nbsp;&nbsp;&nbsp;&nbsp;
									SEMOGA LEKAS SEMBUH. :)
								</marquee>
							</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- jQuery -->
	<script src="{!! url('asset_display/plugins/jquery/jquery.min.js') !!}"></script>
	<!-- Bootstrap 4 -->
	<script src="{!! url('asset_display/plugins/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>

	<!-- EXTRA JAVASCRIPT -->
	<script type="text/javascript">

		// $(document).ready(()=>{
		// 	setInterval(()=>{
		// 		location.reload(true);
		// 	},120000)
		// })
		//indikator paused 
		var isPaused = false;
		var time = 0;

		//indikator sound
		var ind = 0;

		//indikator initial display
		var initialAntrianSaatIni = false;

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
			$('#clockDisplay').text(h + " : " + m + " : " + s + "");
			setTimeout ('renderTime()',1000);
		}
		renderTime();

		var elem = document.documentElement;
		function fullscreen() {
			if (elem.requestFullscreen) {
				elem.requestFullscreen();
			} else if (elem.webkitRequestFullscreen) { /* Safari */
				elem.webkitRequestFullscreen();
			} else if (elem.msRequestFullscreen) { /* IE11 */
				elem.msRequestFullscreen();
			}
		}

		function emptyDataLoket(type) {
			if(type == "loket1"){
				return `
					<div class="col-lg-4 col-4">
						<div class="card border border-secondary">
							<div class="card-body text-center p-2">
								<h5 class="m-0 text-secondary font-weight-bold">LOKET 1</h5>
								<h1 class="m-0 font-weight-bold">0000</h1>
								<h3 class="m-0 font-weight-bold">Belum Ada Antrian</h3>
							</div>
						</div>
					</div>
				`;
			}
			if(type == "loket2"){
				return `
					<div class="col-lg-4 col-4">
						<div class="card border border-secondary">
							<div class="card-body text-center p-2">
								<h5 class="m-0 text-secondary font-weight-bold">LOKET 2</h5>
								<h1 class="m-0 font-weight-bold">0000</h1>
								<h3 class="m-0 font-weight-bold">Belum Ada Antrian</h3>
							</div>
						</div>
					</div>
				`;
			}
			if(type == "loket3"){
				return `
					<div class="col-lg-4 col-4">
						<div class="card border border-secondary">
							<div class="card-body text-center p-2">
								<h5 class="m-0 text-secondary font-weight-bold">LOKET 3</h5>
								<h1 class="m-0 font-weight-bold">0000</h1>
								<h3 class="m-0 font-weight-bold">Belum Ada Antrian</h3>
							</div>
						</div>
					</div>
				`;
			}
			if(type == "loket4"){
				return `
					<div class="col-lg-4 col-4">
						<div class="card border border-secondary">
							<div class="card-body text-center p-2">
								<h5 class="m-0 text-secondary font-weight-bold">LOKET 4(PASIEN UMUM)</h5>
								<h1 class="m-0 font-weight-bold">0000</h1>
								<h3 class="m-0 font-weight-bold">Belum Ada Antrian</h3>
							</div>
						</div>
					</div>
				`;
			}
			if(type == "loket5"){
				return `
					<div class="col-lg-4 col-4">
						<div class="card border border-secondary">
							<div class="card-body text-center p-2">
								<h5 class="m-0 text-secondary font-weight-bold">LOKET 5</h5>
								<h1 class="m-0 font-weight-bold">0000</h1>
								<h3 class="m-0 font-weight-bold">Belum Ada Antrian</h3>
							</div>
						</div>
					</div>
				`;
			}
			if(type == "daftarAntrian"){
				return `
					<div class="col-lg-4 col-4">
						<div class="card border border-secondary">
							<div class="card-body text-center p-2">
								<h5 class="m-0 text-secondary font-weight-bold" >SISA ANTRIAN</h5>
								<h1 class="m-0 font-weight-bold">0000/0000</h1>
								<h3 class="m-0 font-weight-bold">Dipanggil</h3>
							</div>
						</div>
					</div>
				`;
			}
			if(type = 'saatIni'){
				return `
					<h1 class="m-0 font-weight-bold" style="font-size:100px">0000</h1>
					<hr class="mt-0 mb-0" style="border:2px solid #000; width: 70%;">
					<h4 class="m-0 font-weight-bold">Menunggu Antrian<br><span class="namaPoliAntrianSaatIni">Berikutnya</span></h4>
				`;
			}
		}

		function loadDataLoket(){
			//initial data
			let data = {
				id : 6,
				"_token" : "{{ csrf_token() }}",
				type : "antrian"
			};
			$.post("{{ route('antrianLoket') }}", data )
				.done(function(response) {
					if(response.status == 'success'){
						let html = "";
						//data untuk loket 1
						if(response.data.loket1 != null){
							html += `
								<div class="col-lg-4 col-4">
									<div class="card border border-secondary">
										<div class="card-body text-center p-2">
											<h5 class="m-0 text-secondary font-weight-bold">LOKET 1</h5>
											<h1 class="m-0 font-weight-bold">${response.data.loket1.no_antrian}</h1>
											<h4 class="m-0 font-weight-bold">Sedang Dilayani<br></span></h4>
										</div>
									</div>
								</div>
							`;
						}else{
							html += emptyDataLoket('loket1');
						}
						//data untuk loket 2
						if(response.data.loket2 != null){
							html += `
								<div class="col-lg-4 col-4">
									<div class="card border border-secondary">
										<div class="card-body text-center p-2">
											<h5 class="m-0 text-secondary font-weight-bold">LOKET 2</h5>
											<h1 class="m-0 font-weight-bold">${response.data.loket2.no_antrian}</h1>
											<h4 class="m-0 font-weight-bold">Sedang Dilayani<br></span></h4>
										</div>
									</div>
								</div>
							`;
						}else{
							html += emptyDataLoket('loket2');
						}
						//data untuk loket 3
						if(response.data.loket3 != null){
							html += `
								<div class="col-lg-4 col-4">
									<div class="card border border-secondary">
										<div class="card-body text-center p-2">
											<h5 class="m-0 text-secondary font-weight-bold">LOKET 3</h5>
											<h1 class="m-0 font-weight-bold">${response.data.loket3.no_antrian}</h1>
											<h4 class="m-0 font-weight-bold">Sedang Dilayani<br></span></h4>
										</div>
									</div>
								</div>
							`;
						}else{
							html += emptyDataLoket('loket3');
						}
						//data untuk loket 4
						if(response.data.loket4 != null){
							html += `
								<div class="col-lg-4 col-4">
									<div class="card border border-secondary">
										<div class="card-body text-center p-2">
											<h5 class="m-0 text-secondary font-weight-bold">LOKET 4(PASIEN UMUM)</h5>
											<h1 class="m-0 font-weight-bold">${response.data.loket4.no_antrian}</h1>
											<h4 class="m-0 font-weight-bold">Sedang Dilayani<br></span></h4>
										</div>
									</div>
								</div>
							`;
						}else{
							html += emptyDataLoket('loket4');
						}
						//data untuk loket 5
						if(response.data.loket5 != null){
							html += `
								<div class="col-lg-4 col-4">
									<div class="card border border-secondary">
										<div class="card-body text-center p-2">
											<h5 class="m-0 text-secondary font-weight-bold">LOKET 5</h5>
											<h1 class="m-0 font-weight-bold">${response.data.loket5.no_antrian}</h1>
											<h4 class="m-0 font-weight-bold">Sedang Dilayani<br></span></h4>
										</div>
									</div>
								</div>
							`;
						}else{
							html += emptyDataLoket('loket5');
						}
						//data untuk daftar antrian
						if(response.dataDaftarAntrian != null){
							html += `
								<div class="col-lg-4 col-4">
									<div class="card border border-secondary">
										<div class="card-body text-center p-2">
											<h5 class="m-0 text-secondary font-weight-bold">SISA ANTRIAN</h5>
											<h1 class="m-0 font-weight-bold">${response.dataDaftarAntrian.belumDipanggil} / ${response.dataDaftarAntrian.totalPasien}</h1>
											<h4 class="m-0 font-weight-bold">Dipanggil</h4>
										</div>
									</div>
								</div>
							`;
						}else{
							html += emptyDataLoket('daftarAntrian');
						}

						//menampilkan data antrian yang diload
						$(".tampilDataLoket").html(html);

						let htmlSaatIni = "";
						//data loket saat ini
						if(response.dataPanggilan != null){
							let loket = "";
							if(response.dataPanggilan.loket == "loket1"){
								loket = "Loket 1";
							}else if(response.dataPanggilan.loket == "loket2"){
								loket = "Loket 2";
							}else if(response.dataPanggilan.loket == "loket3"){
								loket = "Loket 3";
							}else if(response.dataPanggilan.loket == "loket4"){
								loket = "Loket 4(PASIEN UMUM)";
							}else if(response.dataPanggilan.loket == "loket5"){
								loket = "Loket 5";
							}else{
								loket = "Loket 1";
							}

							htmlSaatIni += `
								<h1 class="m-0 font-weight-bold" style="font-size:100px">${response.dataPanggilan.no_antrian}</h1>
								<hr class="mt-0 mb-0" style="border:2px solid #000; width: 70%;">
								<h4 class="m-0 font-weight-bold">MENUJU KE<br>${loket}</h4>
							`;
							isPaused = true;
							panggil(response.dataPanggilan.no_antrian, response.dataPanggilan.loket);
							$(".dataAntrianSaatIni").html(htmlSaatIni);
						}else{
							if(!initialAntrianSaatIni){
								initialAntrianSaatIni = true;
								htmlSaatIni += emptyDataLoket('saatIni');
								$(".dataAntrianSaatIni").html(htmlSaatIni);
							}
						}
						//end data loket saat ini
					}
				}).fail(function(){
					console.error("fail server error")
				});
		}

		//function sound panggil antrian
		function nomorAntrian(noAntrian,loket,time = 500){
			setTimeout(() => {
				//looping nomor antrian
				if(ind < noAntrian.length){
					let path = "{!! asset('aset/sound/Pemanggilan/Antrian/"+noAntrian[ind]+".mp3') !!}";
					new Audio(path).play();
					// nomorAntrian(noAntrian,loket,850);
					nomorAntrian(noAntrian,loket,800);
				}
				//panggil tujuan
				if( ind == noAntrian.length){
					if(loket == "loket1"){
						let path = "{!! asset('aset/sound/Pemanggilan/loket1.mp3') !!}";
						new Audio(path).play();
					}else if(loket == "loket2"){
						let path = "{!! asset('aset/sound/Pemanggilan/loket2.mp3') !!}";
						new Audio(path).play();
					}else if(loket == "loket3"){
						let path = "{!! asset('aset/sound/Pemanggilan/loket3.mp3') !!}";
						new Audio(path).play();
					}else if(loket == "loket4"){
						let path = "{!! asset('aset/sound/Pemanggilan/loket4.mp3') !!}";
						new Audio(path).play();
					}else if(loket == "loket5"){
						let path = "{!! asset('aset/sound/Pemanggilan/loket5.mp3') !!}";
						new Audio(path).play();
					}else{
						let path = "{!! asset('aset/sound/Pemanggilan/loket1.mp3') !!}";
						new Audio(path).play();
					}
					ind = 0;
					$(".btnPanggil").attr('disabled',false);
					let url = '{{route("changeStatusPanggilan")}}';
					$.post(url, {no_antrian:noAntrian, "_token" : "{{ csrf_token() }}"})
						.done(function(){
							isPaused = false;
							return;
						})
						.fail(function(){
							isPaused = false;
							return;
						});
					return;
				}
				ind++;
			}, time);
		}

		function panggil(noAntrian,loket) {
			$(".btnPanggil").attr('disabled',true);
			isPaused = true;
			setTimeout(() => {
				//penggil sound pemanggilan
				let path = "{!! asset('aset/sound/Pemanggilan/nomorantrian.mp3') !!}";
				new Audio(path).play();
				//panggil nomor dan tujuan
				setTimeout(() => {
					nomorAntrian(noAntrian,loket,1000);
				}, 500);
			}, 2500);
		}

		setInterval(() => {
			if(!isPaused) {
				time++;
				loadDataLoket();
			}
		}, 2500);
		loadDataLoket();
	</script>
	<!-- END EXTRA JAVASCRIPT -->
</body>
</html>
