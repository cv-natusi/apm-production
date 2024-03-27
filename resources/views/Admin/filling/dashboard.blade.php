@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
		.boxColor{
			margin-right:10px;
			width:40px;
			height:15px;
			border-radius:10px;
			float:left;
		}
		.blk{
			background:#000000;
		}
		.red{
			background:#FF0000;
		}
		.ylw{
			background:#FFC500;
		}
		.grn{
			background:#00c800;
		}
		.gry{
			background:#9F9F9F;
		}
	</style>
@stop

@section('content')
	{{-- <section class="content-header">
		<h1>
			DAFTAR ANTRIAN
		</h1>
	</section> --}}

	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		<div class="box box-primary main-layer">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row" style="margin-bottom:1.2rem;">
						<label class="col-md-12" style="font-size:2.3rem">Dashboard</label>
					</div>
					<div class="row">
						<label class="col-md-12">Akses Cepat <span style="text-decoration:none;">(nomorRM)</span></label>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="row">
								<div class="col-md-8">
									<input type="text" name="norm" class="form-control" id="norm" onkeyup="norm($(this))">
								</div>
								<div class="col-md-4" style="margin-left: -10px;">
									<button class="btn btn-primary btn-sm btn-cari" type="button" style="padding: 6px 10px;" title="Cari"><i class="fa fa-search" aria-hidden="true"></i></button>
								</div>
							</div>
						</div>
						<div class="col-md-7">
							<div class="row">
								{{-- <div class="col-md-6 col-md-12">
									<div class="boxColor red mr-2"></div> Berkas Sudah Keluar <b>(K)</b>
								</div> --}}
								<div class="col-md-6 col-md-12">
									<div class="boxColor ylw mr-2"></div> Berkas Sedang diCari <b>(C)</b>
								</div>
								<div class="col-md-6 col-md-12">
									<div class="boxColor gry mr-2"></div> Berkas Tidak diTemukan <b>(T)</b>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-md-12">
									<div class="boxColor grn mr-2"></div> Berkas Ada di Rak <b>(R)</b>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<span>Keterangan:</span>
							<div id="text"></div>
							{{-- <div class="row">
								<div class="col-md-7" id="text"> --}}
									{{-- <span style="color:#ff0000;"><b>W1601083222 - Berkas Sudah keluar</b></span> --}}
								{{-- </div>
							</div> --}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='clearfix'></div>
@stop
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.2/js/bootstrap.min.js" integrity="sha512-5BqtYqlWfJemW5+v+TZUs22uigI8tXeVah5S/1Z6qBLVO7gakAOtkOzUtgq6dsIo5c0NJdmGPs0H9I+2OHUHVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
	$(document).ready(()=>{
		var c='', r='', k='', t=''
	})

	function norm(param){
		var rm = param.val()
		if(rm.length>=10 && rm.length<=14){
			$.post("{{route('cariFilling')}}",{rm:rm}).done((res)=>{
				if(res.code==200){
					var html = ''
					$('#text').empty()
					$.each(res.data,(key,val)=>{
						html += template(val.id,val.no_rm,val.status)
					})
					$('#text').append(html)
					if(getBy=='norm'){
						swal({
							title: 'Berhasil',
							type: res.status,
							text: res.message,
							showConfirmButton: false,
							timer: 1200
						})
					}
				}else{
					$('#text').empty()
					$('#text').html('Data tidak ditemukan')
				}
			})
		}
	}

	$('.btn-cari').click(()=>{
		var rm = $('#norm').val()
		if(rm){
			getData(rm)
		}else{
			swal({
				title: 'Whoops',
				type: 'warning',
				text: 'silahkan masukkan Nomor RM',
				showConfirmButton: true
			})
		}
	})

	function getData(rm,getBy='norm'){
		$.post("{{route('cariFilling')}}",{rm:rm,getBy:getBy}).done((res)=>{
			if(res.code==200){
				var html = ''
				$('#text').empty()
				$.each(res.data,(key,val)=>{
					html += template(val.id,val.no_rm,val.status)
				})
				$('#text').append(html)
				if(getBy=='norm'){
					swal({
						title: 'Berhasil',
						type: res.status,
						text: res.message,
						showConfirmButton: false,
						timer: 1200
					})
				}
			}else{
				if(getBy=='norm'){
					swal({
						title: 'Gagal',
						type: res.status,
						text: res.message,
						showConfirmButton: true
					})
				}
			}
		})
	}

	function changeStatFilling(idFilling){
		var rm = $('#norm').val()
		let select = $("#"+idFilling)
		let val = select.val()
		let valPrev = select.attr('data-init')
		styling(idFilling,val)
		swal({
			title: 'KONFIRMASI !',
			type: 'info',
			text: 'Anda Ingin Mengubah Status Data Ini?',
			confirmButtonClass: "btn-primary",
			confirmButtonText: "Ya, Benar",
			cancelButtonText: "Tidak",
			showCancelButton: true
		},function(isConfirm){
			if(isConfirm){
				$.post("{{route('changeStatFilling')}}",{idFilling:idFilling,status:val}).done(function(data){
					swal({
						title:data.status,
						type: data.status,
						text: data.message,
						showConfirmButton: false,
						timer:1200
					})
					getData(rm,'id')
				}).fail(function(data){
					swal({
						title:data.status,
						type: data.status,
						text: data.message
					})
				})
			}else{
				styling(idFilling,val,valPrev);
			}
		})
	}

	function styling(idFilling,value,valPrev = null) {
		if(valPrev != null){
			if(valPrev == "belum"){
				console.log("u here")
				console.log("now"+value)
				console.log("old"+valPrev)
				$("#"+idFilling).css('background-color', 'black')
				$("#"+idFilling).css('color', 'white')
				$("#"+idFilling).val(valPrev).change()
			}else if( valPrev == "dicari" ){
				$("#"+idFilling).css('background-color', 'orange')
				$("#"+idFilling).css('color', 'white')
				$("#"+idFilling).val(valPrev).change()
			}else if( valPrev == "kosong" ){
				$("#"+idFilling).css('background-color', 'grey')
				$("#"+idFilling).css('color', 'white')
				$("#"+idFilling).val(valPrev).change()
			}else if( valPrev == "keluar" ){
				$("#"+idFilling).css('background-color', 'red')
				$("#"+idFilling).css('color', 'white')
				$("#"+idFilling).val(valPrev).change()
			}else if( valPrev == "ada" ){
				$("#"+idFilling).css('background-color', 'green')
				$("#"+idFilling).css('color', 'white')
				$("#"+idFilling).val(valPrev).change()
			}
		}else{
			if(value == "belum"){
				$("#"+idFilling).css('background-color', 'black')
				$("#"+idFilling).css('color', 'white')
			}else if( value == "dicari" ){
				$("#"+idFilling).css('background-color', 'orange')
				$("#"+idFilling).css('color', 'white')
			}else if( value == "kosong" ){
				$("#"+idFilling).css('background-color', 'grey')
				$("#"+idFilling).css('color', 'white')
			}else if( value == "keluar" ){
				$("#"+idFilling).css('background-color', 'red')
				$("#"+idFilling).css('color', 'white')
			}else if( value == "ada" ){
				$("#"+idFilling).css('background-color', 'green')
				$("#"+idFilling).css('color', 'white')
			}
		}
	}

	function template(id,rm,status){
		// C Berkas Sedang diCari
		// R Berkas Ada diRak
		// K Berkas Sudah Keluar
		// T Berkas Sudah Kosong
		// B Belum
		// status [belum, dicari, ada, keluar, kosong]
		var text = `<b>${rm} - Belum</b>`
		var color = 'FFF'
		var bg = '000'
		var select='',c='',r='',k='',t='',b=''
		if(status=='dicari'){
			text = `<b>${rm} - C</b>`
			select = 'selected'
			bg = 'FFC500'
			c = 'selected'
		}else if(status=='ada'){
			text = `<b>${rm} - R</b>`
			select = 'selected'
			bg = '00C800'
			r = 'selected'
		}else if(status=='keluar'){
			text = `<b>${rm} - K</b>`
			select = 'selected'
			bg = 'FF0000'
			k = 'selected'
		}else if(status=='kosong'){
			text = `<b>${rm} - T</b>`
			select = 'selected'
			bg = '9F9F9F'
			t = 'selected'
		}else if(status=='belum'){
			b = 'selected'
		}
		var html = `
		<div class="row" style="height:3.5rem">
			<div class="col-md-3" style="min-height:100%; color:#${bg};">`
		html += text
		html += `
			</div>
			<div class="col-md-2">
				<select id="${id}" data-init="${status}" onchange="changeStatFilling('${id}')" class="form-control btn" style="color:#${color};background-color:#${bg}">'
				<option value="dicari" ${c} class="btn btn-warning">C</option>'
				<option value="kosong" ${t} class="btn" style="color:white;background-color:grey">T</option>'
				<option value="ada" ${r} class="btn btn-success">R</option>'
				</select>
			</div>
		</div>`
				// <option value="belum" ${b} class="btn" style="color:white;background-color:black">Pilih</option>'
				// <option value="keluar" ${k} class="btn btn-danger">K</option>'
		return html
	}
</script>
@stop