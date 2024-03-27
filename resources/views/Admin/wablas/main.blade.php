@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
	<section class="content-header">
		<h1>
			WaBlas
		</h1>
	</section>
	<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<div class="loading" align="center" style="display: none;">
			<img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}" width="45%">
		</div>
	</div>
	<section class="content"> 
		<div class="row">
			<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
				<div class="box box-primary main-layer">
					<div class="container">
						<div class="row">
							<div class="col-sm-4" style="margin-bottom: 13rem;">
								<form id="formSave">
									<div class="form-group">
										<label for="\">No WA</label>
										<input type="text" name="nomor" class="form-control" id="nomor" placeholder="No WA">
									</div>
									<div class="form-group">
										<label for="\">Pesan</label>
										<textarea name="pesan" class="form-control" id="pesan" cols="30" rows="5" placeholder="Pesan"></textarea>
									</div>
								</form>
								<button class="btn btn-primary btn-simpan">Submit</button>
							</div>
						</div>
					</div>
				</div>
				<div class="other-page"></div>
				<div class="modal-dialog"></div>
			</div>
		</div>
	</section>
@stop

@section('script')
	<script type="text/javascript">
		$(".btn-simpan").click(function(e){
			e.preventDefault()
			var data = new FormData($('#formSave')[0])
			// console.log(data)
			$.ajax({
				url: "{{route('sendData')}}",
				type: "POST",
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					console.log('tes')
				}
			})
			// // var data = new FormData($(".saveData")[0])
			// $.post("{{route('sendData')}}",{data:data}).done(function(data){
			// 	console.log('tes')
			// })
		})
		// function simpan(){
		// 	$('.main-layer').hide()
		// 	$.post("{!! route('formUpdateUsers') !!}", {id:rowData.id}).done(function(data){
		// 		if(data.status == 'success'){
		// 			$('.loading').hide()
		// 			$('.other-page').html(data.content).fadeIn()
		// 		} else {
		// 			$('.main-layer').show()
		// 		}
		// 	})
		// }
	</script>
@stop