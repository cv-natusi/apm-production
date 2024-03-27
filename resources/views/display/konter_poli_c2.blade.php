<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>APM | Konter Poli</title>
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<meta name="csrf_token" content="{{ csrf_token() }}">
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

			<!-- nomor antrian panggil -->
			<div class="row text-center" style="min-height: 37rem;">
				<div class="col-lg-6 col-6">
					<div class="row" style="height: 7rem;">
						<div class="col-md-12">
							<div class="card border border-secondary" style="background-color: rgb(44, 186, 68, 0.8); color:#fff; height: 100%;">
								<div class="card-body text-center p-1">
									<h2 class="m-0 text-warning font-weight-bold namaCounterPoli">...</h2>
									<h3 class="m-0 font-weight-bold">LANTAI 1 - SEBELAH KANAN</h3>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-4" style="height: 27rem;">
						<div class="col-md-12">
							<div class="card border border-secondary mb-0" style="background-color: rgb(44, 186, 68, 0); height: 100%;">
								<div class="card-header p-1" style="background-color: rgb(44, 186, 68, 0.8);">
									<h2 class="m-0 font-weight-bold" style="color:#fff;">NOMOR ANTRIAN</h2>
								</div>
								<div class="card-body p-3 dataAntrianSaatIni" style="background-color: rgb(255, 255, 255, 0.8); color:#000;"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-6">
					<div class="row" style="height: 35.5rem;">
						<div class="col-md-12">
							<div class="card bg-success border border-secondary" style="height:100%">
								<div class="embed-responsive embed-responsive-21by9" style="height:100%">
									{{-- <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$data['link']}}?playlist={{$data['link']}}&loop=1&autoplay=1&mute=1" allowfullscreen></iframe> --}}
									<div id="playerId"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-3 antrianBerikutnya" style="height: 16rem;"></div>

			<div class="row mt-3" style="bottom: 0 !important;position: fixed;left: 0;right: 0;">
				<div class="col-lg-12 col-12">
					<div class="card bg-warning" style="height: 4rem; margin-bottom: 0px;">
						<div class="card-body text-center">
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

	<script src="{!! url('asset_display/plugins/jquery/jquery.min.js') !!}"></script>
	<script src="{!! url('asset_display/plugins/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
	<script type='text/javascript' src='http://www.youtube.com/iframe_api'></script>
	<script type="text/javascript">
		var player;
		function onYouTubeIframeAPIReady(){
			player = new YT.Player('playerId',{
				videoId: "{{$data['link']}}", // Video id
				playerVars: {
					'autoplay': 1,
					'controls': 1,
					'showinfo': 0,
					'modestbranding': 0,
					'loop': 1,
					'fs': 0,
					'cc_load_policty': 0,
					'iv_load_policy': 3
				},
				events:{
					onReady: function(event){
						// event.target.mute();
						event.target.setVolume(2);
						event.target.playVideo();
					},
					onStateChange: function(e){
						if(e.data === YT.PlayerState.ENDED){
							e.target.playVideo();
						}
					}
				}
			})
		}

		var csrfToken = $('[name="csrf_token"]').attr('content');
		setInterval(refreshToken, 3600000); // 1 hour 
		function refreshToken(){
			$.get('refresh-csrf').done(function(data){
				csrfToken = data; // the new token
			});
		}
		setInterval(refreshToken, 3600000); // 1 hour 
	</script>
	<script type="text/javascript">
		//indikator paused 
		var isPaused = false
		var time = 0

		//indikator sound
		var ind = 0

		//indikator display
		var initialAntrianSaatIni = false

		//id display
		var id_display = window.location.href.split('/').reverse()[0]

		function renderTime(){
			var currentTime = new Date()
			var h = currentTime.getHours()
			var m = currentTime.getMinutes()
			var s = currentTime.getSeconds()
			if (h == 0){
				h = 24
			}
			if (h < 10){
				h = "0" + h
			}
			if (m < 10){
				m = "0" + m
			}
			if (s < 10){
				s = "0" + s
			}
			var myClock = document.getElementById('clockDisplay')
			$('#clockDisplay').text(h + " : " + m + " : " + s + "")
			setTimeout ('renderTime()',1000)
		}
		renderTime()

		var elem = document.documentElement;
		function fullscreen() {
			if (elem.requestFullscreen) {
				elem.requestFullscreen()
			} else if (elem.webkitRequestFullscreen) { /* Safari */
				elem.webkitRequestFullscreen()
			} else if (elem.msRequestFullscreen) { /* IE11 */
				elem.msRequestFullscreen()
			}
		}

		function emptyDataCounter(poli, type) {
			if(type == "berikutnya"){
				card  = '<div class="card border border-secondary" style="height: 100%;">'
				card +=   '<div class="card-body text-center p-2">'
				card +=     '<h1 class="m-0 text-secondary font-weight-bold">'+poli+'</h1>'
				card +=	    '<h1 class="m-0 font-weight-bold" style="font-size:9rem !important;">0000</h1>'
				card +=	    '<h2 class="m-0 font-weight-bold">Belum Ada Antrian</h2>'
				card +=	  '</div>'
				card += '</div>'
				return card;
			}
			if(type = 'saatIni'){
				return `
					<h1 class="m-0 font-weight-bold" style="font-size:15rem;">0000</h1>
					<hr class="mt-0 mb-0" style="border:2px solid #000; width: 70%;">
					<h1 class="m-0 font-weight-bold">Menunggu Antrian
						<span class="namaPoliAntrianSaatIni">Berikutnya</span>
					</h1>
				`;
			} 
		}

		function loadDataInitialPage() {
			//initial data
			let data = {
				id : id_display,
				"_token" : "{{ csrf_token() }}",
				type : "initialPage"
			}
			$.post("{{ route('antrianCounter') }}", data ).done((response)=>{
				if(response.status == 'success'){
					$(".namaCounterPoli").text(response.data.nama_counter)
				}
			}).fail(()=>{
				console.log("fail")
			})
		}

		function loadDataCounterSaatIni() {
			//initial data
			let data = {
				id : id_display,
				"_token" : "{{ csrf_token() }}",
				type : "saatIni"
			}
			$.post("{{ route('antrianCounter') }}", data ).done((response)=>{
				if(response.status == 'success'){
					let html = "";
					if(response.data != null){
						html += `
							<h1 class="m-0 font-weight-bold" style="font-size:15rem !important;">${response.data.no_antrian}</h1>
							<hr class="mt-0 mb-0" style="border:2px solid #000; width: 70%;">
							<h1 class="m-0 font-weight-bold">MENUJU KE<br><span class="namaPoliAntrianSaatIni">${response.data.nama_poli}</span></h1>
						`;
						$(".dataAntrianSaatIni").html(html)
						isPaused = true
						panggil(response.data.no_antrian,response.data.nama_poli)
					}else{
						if(!initialAntrianSaatIni){
							initialAntrianSaatIni = true
							html += emptyDataCounter('saatIni')
							$(".dataAntrianSaatIni").html(html)
						}
					}
				}
			}).fail(function(){
				console.log("fail")
			})
		}

		function loadDataCounter(){
			//initial data
			let data = {
				id : id_display,
				"_token" : "{{ csrf_token() }}",
				type : "antrian"
			}
			$.post("{{ route('antrianCounter') }}", data ).done((response)=>{
				if(response.status == 'success'){
					if (response.data.length == 8) {
						$ttlCol = 3
					} else if(response.data.length == 4) {
						$ttlCol = 3
					} else {
						$ttlCol = 4
					}
					var html = ''
					var col = $ttlCol
					var row = 1
					var countRow = 0
					var arrLeft = []
					$.each(response.data,(i,val)=>{
						var poli = val[1].tm_poli.NamaPoli
						html +="<div class='col-md-"+col+"' style='margin-top: 3rem;'>"

						if (val[0] != null) {
							html +=`
							<div class="card border border-secondary" style="height: 100%">
								<div class="card-body text-center p-2">
									<h1 class="m-0 text-secondary font-weight-bold">
										${val[0].mapping_poli_bridging.tm_poli.NamaPoli}
									</h1>
									<h1 class="m-0 font-weight-bold" style="font-size:9rem !important;">
										${val[0].nomor_antrian_poli}
									</h1>
									<h2 class="m-0 font-weight-bold">
										Dipanggil
									</h2>
								</div>
							</div>
							`;
						} else {
							html += emptyDataCounter(poli, "berikutnya")
						}
						html +="</div>"
					})
					$(".antrianBerikutnya").html(html)
				}
			}).fail(()=>{
				console.log("fail")
				let html = ""
				$.each(response.data,(i,val)=>{
					var poli = val[1].tm_poli.NamaPoli
					html +="<div class='col-md-"+col+"' style='margin-top: 3rem;'>"
					html += emptyDataCounter(poli, "berikutnya")
					html +="</div>"
				})
				html += emptyDataCounter()
				$(".antrianBerikutnya").html(html)
			})
		}

		setInterval(() => {
			if(!isPaused){
				loadDataCounter()
				loadDataCounterSaatIni()
			}
		}, 2600)
		loadDataCounter()
		loadDataCounterSaatIni()
		loadDataInitialPage()

		//function sound panggil antrian
		function nomorAntrian(noAntrian,namaPoli,time = 500){
			setTimeout(() => {
				//looping nomor antrian
				if(ind < noAntrian.length){
					let path = "{!! asset('aset/sound/Pemanggilan/Antrian/"+noAntrian[ind]+".mp3') !!}"
					new Audio(path).play()
					nomorAntrian(noAntrian,namaPoli,750)
				}
				//panggil tujuan
				if( ind == noAntrian.length){
					let menujuke = "{!! asset('aset/sound/Pemanggilan/silahkanmenujuke.mp3') !!}"
					new Audio(menujuke).play()
					setTimeout(() => {
						let poli = "{!! asset('aset/sound/Pemanggilan/Poli/"+namaPoli+".mp3') !!}"
						new Audio(poli).play()

						$(".btnPanggil").attr('disabled',false)
						let url = '{{route("changeStatusPanggilan")}}'
						setTimeout(()=>{
							$.post(url, {no_antrian:noAntrian, "_token" : "{{ csrf_token() }}"}).done(()=>{
								isPaused = false
								return
							}).fail(()=>{
								isPaused = false
								return
							})
						},2200)
					}, 2250)
					ind = 0
					return
				}
				ind++
			}, time)
		}

		function panggil(noAntrian,namaPoli) {
			$(".btnPanggil").attr('disabled',true)
			isPaused = true
			//penggil sound pemanggilan
			let path = "{!! asset('aset/sound/Pemanggilan/nomorantrian.mp3') !!}"
			setTimeout(()=>{
				new Audio(path).play()
				//panggil nomor dan tujuan
				setTimeout(() => {
					nomorAntrian(noAntrian,namaPoli,900)
				},900)
			},2100)
		}
	</script>
</body>
</html>