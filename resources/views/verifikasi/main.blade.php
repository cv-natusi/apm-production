<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Verifikasi</title>
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	@php
	$bgImage = ($status=='berhasil')?'dist/verifikasi/berhasil.png':'dist/verifikasi/error.png';
	$bgGradient = ($status=='berhasil')?'0turn, #01bd1b, #0ec423, #01ab04, #019600':'0.5turn, #efa43e, #eb9f39, #e48b2c);';
	@endphp
	<style type="text/css">
		.responsive {
			width: 100%;
			max-width: 150px;
			height: auto;
		}
		.btn.focus, .btn:focus {
			outline: 0;
			box-shadow: unset !important;
		}
		.btn-warning.focus, .btn-warning:focus {
			box-shadow: unset !important; 
		}
		html, body {
			height: 100%;
			width: 100%;
			margin: 0;
			color: white;
		}
		#background{
			background: linear-gradient(<?php echo($status=='berhasil')?'0turn, #01bd1b, #0ec423, #01ab04, #019600':'0.5turn, #efa43e, #eb9f39, #e48b2c';?>);
			background-repeat: no-repeat;
			top: 0px;
			right: 0px;
			left: 0px;
			bottom: 0px;
			position: fixed;
		}
		#second{
			background-image: url(<?php echo($status=='berhasil')?'/dist/verifikasi/berhasil.png':'/dist/verifikasi/error.png';?>);
			background-repeat: no-repeat;
			background-size: auto 120%;
			top: 0px;
			right: 0px;
			left: 0px;
			bottom: 0px;
			position: absolute;
			background-position: center center;
		}
		.row-1{
			height: 45%;
		}
		.row-2{
			left: 0px;
			right: 0px;
			bottom: 50px;
			position: absolute;
			height: 40%;
		}
		.top-sc{
			margin-top: 0%;
		}
		.container{
			top: 0px;
			left: 0px;
			right: 0px;
			bottom: 0px;
			position: absolute;
		}
		.fw6{
			font-weight:600;
		}
	</style>
</head>
<body>
	<div id="background">
		<div id="second" class="mx-auto d-block">
			<div class="container">
				<div class="row row-1">
					<div class="col-md-12">
						<div class="row mt-4">
							<div class="col-sm-12 text-center">
								<h5 style="font-weight:700;">{{$pesan1}}</h5>
								@if($status!='berhasil')
								<h5 style="font-weight:650;">{{$pesan2}}</h5>
								@endif
							</div>
						</div>
						@if($status=='belum')
						<div class="row mt-4">
							<div class="col-sm-12 text-center">
								<p style="margin:0; font-size: 20px;">Tanggal Konfirmasi : <b>{{$tgl}}</b></p>
							</div>
						</div>
						@elseif($status=='berhasil')
						<div class="row mt-4">
							<div class="col-sm-12 text-center">
								<p style="margin:0;font-weight:600; font-size: 17px;">{{$pesan2}}</p>
								<u><p style="margin:0;font-size: 19px;"><b>{{$unik}}</b></p></u>
								<p style="margin:0;font-size: 19px;"><b>{{$poli['NamaPoli']}}</b></p>
							</div>
						</div>
						@elseif($status=='lewat')
						<div class="row mt-4">
							<div class="col-sm-12 text-center">
								<p style="margin:0; font-size: 19px;">Tanggal Konfirmasi : <b>{{$tgl}}</b></p>
								<p style="margin:0; font-size: 19px;">
									{!!$pesan3!!}
								</p>
							</div>
						</div>
						@endif
					</div>
				</div>
				@if($status=='berhasil')
				<div class="row row-2">
					<div class="col-md-12">
						<div class="row top-sc">
							<div class="col-sm-12 text-center">
								<p style="color:#000b0ae8; margin:0; font-weight:600;">Silahkan Tekan Tombol</p>
								<p style="color:#000b0ae8; margin:0; font-weight:600;">Untuk Konfirmasi Nomor Antrian</p>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col-sm-12 text-center">
								<button type="button" onclick="konfirmasis({{json_encode($data,true)}})" class="btn btn-rounded btn-submit btn-warning fw6" id="btn-submit"><span>KONFIRMASI</span></button>
								<!-- <a onclick="konfirmasi({{json_encode($data,true)}})" ondblclick="konfirmasis({{json_encode($data,true)}})" class="btn btn-rounded btn-submit btn-warning"><span style="font-weight:600">KONFIRMASI</span></a> -->
							</div>
						</div>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>
	<!-- <link rel="stylesheet" href="sweetalert2.min.css"> -->
	<!-- <script src="sweetalert2.min.js"></script> -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

	<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script> -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> -->
	<script type="text/javascript">
		async function konfirmasis(data){
			// if(data.phone=='6281335537942'){
			// 	console.log(data.phone)
			// 	$('#btn-submit').attr('disabled',true).html('<div class="spinner-border spinner-border-sm" role="status"></div><span> Loading...</span>')
			// 	setTimeout(()=>{
			// 		$('#btn-submit').attr('disabled',false).html('<span>KONFIRMASI</span>')
			// 	},1000)
			// 	return
			// }

			$('#btn-submit').attr('disabled',true).html('<div class="spinner-border spinner-border-sm" role="status"></div><span> Loading...</span>')
			$.ajax({
				url: '{{route("verifBerhasil")}}',
				method: 'GET',
				data: data
			}).then((res)=>{
				if(res.code==200){
					Swal.fire({
						icon: 'success',
						title: 'Konfirmasi berhasil!',
						showConfirmButton: false,
						timer: 1200
					})
					setTimeout(()=>{
						window.location.reload()
					},500)
				}else{
					Swal.fire({
						icon: 'error',
						html: res.message,
						title: 'Konfirmasi Gagal!',
						showConfirmButton: true
					}).then((res)=>{
						// window.location.reload()
					})
				}
				$('#btn-submit').attr('disabled',false).html('<span>KONFIRMASI</span>')
			})
		}
		// function konfirmasi(data){
		// 	Swal.fire({
		// 		title: 'Anda yakin?',
		// 		text: "Ingin melanjutkan konfirmasi!",
		// 		icon: 'question',
		// 		showCancelButton: true,
		// 		confirmButtonText: 'Konfirmasi',
		// 		confirmButtonColor: '#00ad19',
		// 		cancelButtonText: 'Batal',
		// 		cancelButtonColor: 'rgb(228 43 43)',
		// 		reverseButtons: true
		// 	}).then((is)=>{
		// 		if(is.isConfirmed){
		// 			$.ajax({
		// 				url: '{{route("verifBerhasil")}}',
		// 				method: 'GET',
		// 				data: data
		// 			}).then((res)=>{
		// 				if(res.code==200){
		// 					Swal.fire({
		// 						icon: 'success',
		// 						title: 'Konfirmasi berhasil!',
		// 						showConfirmButton: false,
		// 						timer: 1200
		// 					})
		// 					setTimeout(()=>{
		// 						window.location.reload()
		// 					},1000)
		// 				}else{
		// 					Swal.fire({
		// 						icon: 'error',
		// 						// text: res.message,
		// 						html: res.message,
		// 						title: 'Konfirmasi Gagal!',
		// 						showConfirmButton: true
		// 					}).then((res)=>{
		// 						window.location.reload()
		// 					})
		// 				}
		// 			})
		// 		}else{
		// 		}
		// 	})
		// 	// $(location).prop('href', "{{route('verifBerhasil')}}?id="+data.id+"&unik="+data.random+"&phone="+data.phone)
		// }
	</script>
</body>
</html>