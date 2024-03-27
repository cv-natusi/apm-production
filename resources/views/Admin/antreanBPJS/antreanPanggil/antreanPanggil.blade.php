@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
		.size-head{
			min-height:150px;
			border-radius:6px;
		}
		.bghead{
			background:  #a18c7785 !important;
		}
		.txt-weight{
			font-weight: 600;
		}
		.btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus{
			outline: unset;
		}
		#reCallPasien:active {
			box-shadow: inset 0px 0px 4px 5px rgb(0 0 0 / 12%);
		}
		#callPasien:active {
			box-shadow: inset 0px 0px 4px 5px rgb(0 0 0 / 12%);
		}
		#callGeriatri:active {
			box-shadow: inset 0px 0px 4px 5px rgb(0 0 0 / 12%);
		}
		#reCallGeriatri:active {
			box-shadow: inset 0px 0px 4px 5px rgb(0 0 0 / 12%);
		}
		.btn:hover{
			box-shadow: unset;
		}
		.btn-call{
			min-height: 40px;
			margin: 0;
			background: #ff2f2f;
			border-radius:7px;
		}
		.btn-first{
			min-height: 40px;
			margin: 0;
			background: #ff4b4b;
			border-radius:7px;
		}
		.dis-center{
			display:grid;
			align-items:center;
		}
		.setMarPar{
			margin: 0;
			padding: 0;
		}
		.bgRepeat{
			background-repeat: no-repeat !important;
		}
		.dilayani{
			background: url({{asset('AssetsAdmin/imageAntrian/dilayani.png')}});
			background-size: auto 133px !important;
			background-position: right -5px bottom -9px !important;
		}
		.panggil{
			background: url({{asset('AssetsAdmin/imageAntrian/panggil.png')}});
			background-size: auto 110px !important;
			background-position: right -2px bottom -4px !important;
		}
		.jumlah{
			background: url({{asset('AssetsAdmin/imageAntrian/jumlah.png')}});
			background-size: auto 120px !important;
			background-position: right -22px bottom -23px !important;
		}
		.size-content{
			min-height:35px;
			display:grid;
			align-items:center;
			/*vertical-align: center;*/
		}
	</style>
@stop

@section('content')

	<form id="fSimpan">
		<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" id="panelJawal">
			<div  class="box box-default main-layer">
				<div style="padding: 15px; min-height: 450px;" class="paneltabel">
					<div class="row">
						<div class="col-md-4">
							<div class="panel panel-default size-head dilayani bgRepeat">
								<div class="panel-heading bghead text-center txt-weight">Sedang Dilayani</div>
								<div class="panel-body text-center">
									<h3 id="dilayani">-</h3>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="panel panel-default size-head panggil bgRepeat">
								<div class="panel-heading bghead text-center txt-weight">Belum Dipanggil</div>
								<div class="panel-body text-center">
									<h3 id="belumPanggil">-</h3>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="panel panel-default size-head jumlah bgRepeat">
								<div class="panel-heading bghead text-center txt-weight">Jumlah Antrian</div>
								<div class="panel-body text-center">
									<h3 id="totalAntrian">-</h3>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="panel panel-default size-head">
								<div class="panel-body">
									<div class="row">
										<div class="col-md-12">
											<div class="panel text-center txt-weight" style="background: #17e717; min-height: 50px;">
												<input type="hidden" name="nomorAntrian" id="nomorAntrian">
												<h3 style="margin: 10px; font-weight:600;">No. Antrian : <span id="noAntrian">-</span></h3>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<div class="panel text-center txt-weight" style="background: #ff00006b;">
														<div id="reCallPasien" class="row" style="margin:0; cursor:pointer;">
															<div class="col-md-3 setMarPar" style="margin: 4px 0 0 0; padding:1px">
																<button class="btn btn-call" type="button">
																	<i class="fa fa-bullhorn" style="font-size:20px; color:white;"></i>
																</button>
															</div>
															<div class="col-md-6 dis-center setMarPar" style="min-height:48px;">
																<span id="reCallPasien1" style="margin:2px 0 0 0; font-size: 13px;">
																	ULANGI PANGGILAN
																</span>
																<span id="reCallPasien2" style="margin:2px 2px; font-size: 13px;">
																	PASIEN
																</span>
															</div>
															<div class="col-md-3">
																<div class="dis-center setMarPar" style="min-height:48px;">
																	<span>
																		<i class="fa fa-repeat" style="font-size:23px;"></i>
																	</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-12">
													<div class="panel text-center txt-weight" style="background: #ff00006b;">
														<div id="callPasien" class="row" style="margin:0; cursor:pointer;">
															<div class="col-md-3 setMarPar" style="margin: 4px 0 0 0; padding:1px">
																<button class="btn btn-call" type="button">
																	<i class="fa fa-bullhorn" style="font-size:20px; color:white;"></i>
																</button>
															</div>
															<div class="col-md-6 dis-center setMarPar" style="min-height:48px;">
																<span id="callPasien1" style="margin:2px 0 0 0; font-size: 13px;">
																	MULAI PANGGILAN
																</span>
																<span id="callPasien2" style="margin:2px 2px; font-size: 13px;">
																	PASIEN
																</span>
															</div>
															<div class="col-md-3">
																<div class="dis-center setMarPar" style="min-height:48px;">
																	<span>
																		<i class="fa fa-angle-double-right" style="font-size:30px;"></i>
																	</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<div id="reCallGeriatri" class="panel text-center txt-weight" style="cursor:pointer; background: #ff00006b;">
														<div class="row" style="margin:0;">
															<div class="col-md-3 setMarPar" style="margin: 4px 0 0 0; padding:1px">
																<button class="btn btn-call" type="button">
																	<i class="fa fa-bullhorn" style="font-size:20px; color:white;"></i>
																</button>
															</div>
															<div class="col-md-6 dis-center setMarPar" style="min-height:48px;">
																<span id="reCallGeriatri1" style="margin:2px 0 0 0; font-size: 13px;">ULANGI PANGGILAN</span>
																<span id="reCallGeriatri2" style="margin:2px 2px; font-size: 13px;">PASIEN GERIATRI</span>
															</div>
															<div class="col-md-3">
																<div class="dis-center setMarPar" style="min-height:48px;">
																	<span>
																		<i class="fa fa-repeat" style="font-size:23px;"></i>
																	</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div id="callGeriatri" class="panel text-center txt-weight" style="cursor:pointer; background: #ff00006b;">
														<div class="row" style="margin:0;">
															<div class="col-md-3 setMarPar" style="margin: 4px 0 0 0; padding:1px">
																<button class="btn btn-call" type="button">
																	<i class="fa fa-bullhorn" style="font-size:20px; color:white;"></i>
																</button>
															</div>
															<div class="col-md-6 dis-center setMarPar" style="min-height:48px;">
																<span id="callGeriatri1" style="margin:2px 0px 0px 0px; font-size: 13px;">
																	MULAI PANGGILAN
																</span>
																<span id="callGeriatri2" style="font-size: 13px;">
																	PASIEN GERIATRI
																</span>
															</div>
															<div class="col-md-3">
																<div class="dis-center setMarPar" style="min-height:48px;">
																	<span>
																		<i class="fa fa-angle-double-right" style="font-size:30px;"></i>
																	</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="flex">
											<div class="col-md-6">
												<div class="panel panel-default">
													<div class="panel-heading bghead txt-weight">Antrian Pasien :</div>
													<div class="panel-body">
														<div id="antrianNonGeriatri" class="text-center" style="font-size:15px;">
															<div class="row" style="background-color:#cbcbcb85;">
																<div class="col-md-4 size-content">....</div>
																<div class="col-md-4 size-content">....</div>
																<div class="col-md-4 size-content">....</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="panel panel-default">
													<div class="panel-heading bghead txt-weight">Antrian Pasien Geriatri :</div>
													<div class="panel-body">
														<div id="antrianGeriatri" class="text-center" style="font-size:15px;">
															<div class="row" style="background-color:#cbcbcb85;">
																<div class="col-md-4 size-content">....</div>
																<div class="col-md-4 size-content">....</div>
																<div class="col-md-4 size-content">....</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="panel panel-default">
								<div class="panel-heading bghead txt-weight">Sudah Terlayani :</div>
								<div class="panel-body">
									<div id="sudahDilayani" class="text-center" style="font-size: 15px;">
										<div class="row" style="background-color:#cbcbcb85;">
											<div class="col-md-4 size-content">....</div>
											<div class="col-md-4 size-content">....</div>
											<div class="col-md-4 size-content">....</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class='clearfix'></div><br>
			</div>
		</div>
		<div class='clearfix'></div>
	</form>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">

@stop
@section('script')
	<script type="text/javascript">
		//indikator untuk sound panggil
		let ind = 0;
		let jalan = 0;

		function nomorAntrian(noAntrian,time = 500){
			setTimeout(() => {
				//looping nomor antrian
				if(ind < noAntrian.length){
					let path = "{!! asset('aset/sound/Pemanggilan/Antrian/"+noAntrian[ind]+".mp3') !!}";
					new Audio(path).play();
					nomorAntrian(noAntrian,1250);
				}
				//panggil tujuan
				if( ind == noAntrian.length){
					let path = "{!! asset('aset/sound/Pemanggilan/menujukeloket.mp3') !!}";
					new Audio(path).play();
					ind = 0;
					jalan = 0;
					return;
				}
				ind++;
			}, time);
		}

		$('#callPasien').click(function (e) { 
			e.preventDefault();
			panggil()
		});

		async function panggil() {
			let result;
			var url = "{{route('panggilSelanjutnya')}}";
			jalan += 1;
			if(jalan > 1) return;

			try {
				result = await $.ajax({
					url: url,
					type: 'POST',
					data: {
						status : 'pasien'
					},
				});

				if (result.status == 'success') {
					let result2;
					$('#noAntrian').text(result.data.no_antrian_pbaru)
					$('#nomorAntrian').val(result.data.no_antrian_pbaru)
					let no_antrian = result.data.no_antrian_pbaru;
					let url2 = '{{route("insertDataPemanggilan")}}';

					try {
						result2 = await $.ajax({
							url: url2,
							type: 'POST',
							data: {
								no_antrian:no_antrian
							},
						});

						jalan = 0;
						ind = 0;
						return;
						swal('Berhasil !', 'Pasien Berhasil Di Panggil', 'success')
						$('#dataTable').DataTable().ajax.reload();
					} catch (error) {
						jalan = 0;
						ind = 0;
						return;
						swal('Maaf !', 'Pasien Gagal Di Panggil', 'success')
					}
				} else {
					jalan = 0;
					ind = 0;
					return;
					console.error('Error !', result.message, result.type)
				}
			} catch (error) {
				jalan = 0;
				ind = 0;
				return;
				swal('Error !', error, 'errror')
			}
		}

		$('#reCallPasien').click((e)=>{
			jalan += 1;
			if(jalan > 1) return;
			var antrian = $('#nomorAntrian').val()
			console.log(antrian)
			$.post("{{route('panggilUlang')}}",{status:'pasien',antrian:antrian}).done((res)=>{
				if(res.status=='success'){
					console.log(res.status)
					$('#noAntrian').text(res.data.no_antrian_pbaru)
					$('#nomorAntrian').val(res.data.no_antrian_pbaru)

					let no_antrian = res.data.no_antrian_pbaru;

					//update data ke table pemanggilan
					let url = '{{route("ulangiDataPemanggilan")}}';
					$.post(url, {no_antrian : no_antrian}).done(function(){
						jalan = 0;
						ind = 0;
						return;
					}).fail(function(){
						jalan = 0;
						ind = 0;
						return;
					});

					//dimatikan sementara
					// //penggil sound pemanggilan
					// let path = "{!! asset('aset/sound/Pemanggilan/nomorantrian.mp3') !!}";
					// new Audio(path).play();
					// //panggil nomor dan tujuan
					// setTimeout(() => {
					// 	nomorAntrian(no_antrian,1000);
					// }, 500);
					swal({
						type: 'success',
						title: 'Berhasil',
						text: res.message,
						showConfirmButton: false,
						timer: 1000
					})
				}else if(res.status=='error'){
					jalan = 0
					// $('#noAntrian').text('-')
					// $('#nomorAntrian').val('')
					swal({
						type: 'warning',
						title: 'Gagal',
						text: res.message,
						showConfirmButton: true
					})
				}else{
					jalan = 0
					swal({
						type: 'warning',
						title: 'Gagal',
						text: res.message,
						showConfirmButton: true
					})
				}
			})
		})

		$('#callGeriatri').click((e)=>{
			jalan += 1;
			if(jalan > 1) return;
			$.post("{{route('panggilSelanjutnya')}}",{status:'geriatri'}).done((res)=>{
				if(res.status=='success'){
					$('#noAntrian').text(res.data.no_antrian_pbaru)
					$('#nomorAntrian').val(res.data.no_antrian_pbaru)

					let no_antrian = res.data.no_antrian_pbaru;

					//insert data ke table pemanggilan
					let url = '{{route("insertDataPemanggilan")}}';
					$.post(url, {no_antrian : no_antrian}).done(function(){
						jalan = 0;
						ind = 0;
						return;
					}).fail(function(){
						jalan = 0;
						ind = 0;
						return;
					});

					swal({
						type: 'success',
						title: 'Berhasil',
						text: 'Panggil antrian berhasil',
						showConfirmButton: false,
						timer: 1000
					});
					// //penggil sound pemanggilan
					// let path = "{!! asset('aset/sound/Pemanggilan/nomorantrian.mp3') !!}";
					// new Audio(path).play();
					// //panggil nomor dan tujuan
					// setTimeout(() => {
					// 	nomorAntrian(no_antrian,1000);
					// }, 500);
				}else{
					jalan = 0
					// $('#noAntrian').text('-')
					// $('#nomorAntrian').val('')
					swal({
						type: 'warning',
						title: 'Gagal',
						text: res.message,
						showConfirmButton: true
					})
				}
			})
		})

		$('#reCallGeriatri').click((e)=>{
			jalan += 1;
			if(jalan > 1) return;
			var antrian = $('#nomorAntrian').val()
			$.post("{{route('panggilUlang')}}",{status:'geriatri',antrian:antrian}).done((res)=>{
				if(res.status=='success'){
					$('#noAntrian').text(res.data.no_antrian_pbaru)
					$('#nomorAntrian').val(res.data.no_antrian_pbaru)

					let no_antrian = res.data.no_antrian_pbaru;

					//update data ke table pemanggilan
					let url = '{{route("ulangiDataPemanggilan")}}';
					$.post(url, {no_antrian : no_antrian}).done(function(){
						jalan = 0;
						ind = 0;
						return;
					}).fail(function(){
						jalan = 0;
						ind = 0;
						return;
					});
					// //penggil sound pemanggilan
					// let path = "{!! asset('aset/sound/Pemanggilan/nomorantrian.mp3') !!}";
					// new Audio(path).play();
					// //panggil nomor dan tujuan
					// setTimeout(() => {
					// 	nomorAntrian(no_antrian,1000);
					// }, 500);
					swal({
						type: 'success',
						title: 'Berhasil',
						text: res.message,
						showConfirmButton: true,
						timer: 1000
					})
				}else if(res.status=='error'){
					jalan = 0
					// $('#noAntrian').text('-')
					// $('#nomorAntrian').val('')
					swal({
						type: 'warning',
						title: 'Gagal',
						text: res.message,
						showConfirmButton: true
					})
				}else{
					jalan = 0
					swal({
						type: 'warning',
						title: 'Gagal',
						text: res.message,
						showConfirmButton: true
					})
				}
			})
		})

		// function disableReCall(){
		// 		var dilayani = $('#dilayani').text()
		// 		var belumPanggil = $('#belumPanggil').text()
		// 		var totalAntrian = $('#totalAntrian').text()
		// 		belumPanggil = parseInt(belumPanggil)
		// 		totalAntrian = parseInt(totalAntrian)
		// 		if(belumPanggil==totalAntrian){
		// 			// $('#reCallPasien').attr('disabled',true).off('click')
		// 			// $('#reCallGeriatri').attr('disabled',true).off('click')
		// 			$('#callGeriatri1').text('MULAI PANGGILAN')
		// 			$('#callGeriatri2').text('PASIEN GERIATRI')

		// 			$('#callPasien1').text('MULAI PANGGILAN')
		// 			$('#callPasien2').text('PASIEN')
		// 		}else{
		// 			// $('#reCallPasien').prop('disabled',false).on('click')
		// 			// $('#reCallGeriatri').prop('disabled',false).on('click')

		// 			$('#callGeriatri1').text('PANGGILAN PASIEN')
		// 			$('#callGeriatri2').text('GERIATRI SELANJUTNYA')

		// 			$('#callPasien1').text('PANGGILAN PASIEN')
		// 			$('#callPasien2').text('SELANJUTNYA')
		// 		}
		// 	}

		function getAntrian(data){
			var res = data.getAntrian
			var belumPanggil = res[1]
			var totalAntrian = res[2]
			$('#dilayani').text(res[0])
			$('#belumPanggil').text(res[1])
			$('#totalAntrian').text(res[2])

			var dilayani = $('#dilayani').text()
			if(belumPanggil==totalAntrian){
				$('#callGeriatri1').text('MULAI PANGGILAN')
				$('#callGeriatri2').text('PASIEN GERIATRI')

				$('#callPasien1').text('MULAI PANGGILAN')
				$('#callPasien2').text('PASIEN')
			}else{
				$('#callGeriatri1').text('PANGGILAN PASIEN')
				$('#callGeriatri2').text('GERIATRI SELANJUTNYA')

				$('#callPasien1').text('PANGGILAN PASIEN')
				$('#callPasien2').text('SELANJUTNYA')
			}
		}

		function cekAntrianGeriatri(data){
			var res = data.cekAntrianGeriatri
			var html = ''
			var col = 4
			var row = 1
			var countRow = 0
			var arrLeft = []
			var params = [col,row,countRow]
			$('#antrianGeriatri').empty()
			html +="<div class='row' style='background:#cbcbcb85;'>"
			if(res.length>0){
				html += cardAntrian(params,res)
			}else{
				html += emptyData(params)
			}
			html +="</div>"
			$('#antrianGeriatri').append(html)
		}

		function cekAntrianNonGeriatri(data){
			var res = data.cekAntrianPasien
			var html = ''
			var col = 4
			var row = 1
			var countRow = 0
			// var arrLeft = []
			var params = [col,row,countRow]
			$('#antrianNonGeriatri').empty()
			html +="<div class='row' style='background:#cbcbcb85;'>"
			if(res.length>0){
				html += cardAntrian(params,res)
			}else{
				html += emptyData(params)
			}
			html +="</div>"
			$('#antrianNonGeriatri').append(html)
		}

		function sudahDilayani(data){
			var res = data.dilayani
			var html = ''
			var col = 4
			var row = 1
			var countRow = 0
			var params = [col,row,countRow]
			$('#sudahDilayani').empty()
			html +="<div class='row' style='background:#cbcbcb85;'>"
			if(res.length>0){
				$.each(res,(i,val)=>{
					html +=`
						<div class='col-md-${col}'>
							<div style='min-height:35px; display:grid; align-items:center'>
								<span>${val.no_antrian_pbaru}</span>
							</div>
						</div>
					`;

					row++
					if(row==col){
						html +="</div>"
						countRow++
						html += "<div class='row' style='background:"+((countRow%2==0)?'#cbcbcb85':'white')+";'>"
						row=1 // RESET VALUE
					}
				})
			}else{
				html += emptyData(params)
			}
			html +="</div>"
			$('#sudahDilayani').append(html)
		}

		function cardAntrian(params,res){
			var html = ''
			var col = params[0]
			var row = params[1]
			var countRow = params[2]
			var arrLeft = []
			console.log(col)
			console.log(row)
			console.log(countRow)
			console.log(arrLeft)
			$.each(res,(i,val)=>{
				html +="<div class='col-md-"+col+"'>"
					if(arrLeft.length==0){
						html +=`
							<div style='display:grid; align-items:center'>
								<span class='btn-first btn-sm' style='font-size:15px; font-weight:600; color:white; min-height:35px; padding:auto;'>
									${val.no_antrian_pbaru}
								</span>
							</div>
						`;
						arrLeft.push(1)
					}else{
						html +=`
							<div style='min-height:35px; display:grid; align-items:center'>
								<span>
									<i class='fa fa-angle-double-left' style='font-size:16px; font-weight: 500;'></i> &nbsp${val.no_antrian_pbaru}
								</span>
							</div>`;
					}
				html +="</div>"

				row++
				if(row==4){
					html +="</div>"
					countRow++
					html += "<div class='row' style='background:"+((countRow%2==0)?'#cbcbcb85':'white')+";'>"
					row=1 // RESET VALUE
				}
			})
			return html
		}

		function emptyData(params){
			var html = ''
			var col = params[0]
			var row = params[1]
			var countRow = params[2]
			for (var i=0; i<3; i++) {
				html +=`
					<div class='col-md-${col}'>
						<div style='min-height:35px; display:grid; align-items:center'>
							<span>....</span>
						</div>
					</div>
				`;

				row++
				if(row==col){
					html +="</div>"
					countRow++
					html += "<div class='row' style='background:"+((countRow%2==0)?'#cbcbcb85':'white')+";'>"
					row=1 // RESET VALUE
				}
			}
			return html
		}

		var arrRes = []
		var apd = function antreanPanggilData(){
			$.ajax({
				url: '{{route("antreanPanggilData")}}',
				method: 'POST',
			}).then((res)=>{
				getAntrian(res)
				cekAntrianGeriatri(res)
				cekAntrianNonGeriatri(res)
				sudahDilayani(res)
				if(arrRes.length==0){
					arrRes.push(res)
				}else{
					arrRes[0] = res
				}
			})
		}

		var handle = setInterval(()=>{
			apd()
			// if(arrRes.length>0){
			// 	if(arrRes[0].cekAntrianPasien.length==0 && arrRes[0].cekAntrianGeriatri==0){
			// 		clearInterval(handle)
			// 	}
			// }
		},2000)
	</script>
@stop