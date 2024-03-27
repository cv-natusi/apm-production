@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
	<section class="content-header">
		<h1>
			Data RFID 
		</h1>
	</section>
	<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<div class="loading" align="center" style="display: none;">
			<img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}" width="45%">
		</div>
	</div>
	<section class="content">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="box box-primary main-layer">
					<div class="clearfix" style="margin-bottom: 10px"></div>
					<form class="form-cek-rm">
						<div class="col-lg-6 col-md-8 col-sm-12 col-xs-12" style="padding: 0px;">
							<div class="form-group">
								<label class="col-lg-2 col-md-3 col-sm-12 col-xs-12" style="padding-top: 5px;">No RM</label>
								<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
									<input type="text" name="noRM" class="form-control">
								</div>
								<div class="col-lg-3 col-md-2" style="margin-top: 2px;">
									<button class="btn btn-sm btn-primary" type="submit" id="btn-cek-rm"><i class="fa fa-search" style="margin-right: 5px;"></i> Cek</button>
								</div>
							</div>
						</div>
						<div class="clearfix" style="margin-bottom: 10px"></div>
					</form>
					<div class="clearfix" style="margin-bottom: 10px"></div>
					<hr style="margin: 10px 0px;">
					<div class="col-lg-12 col-md-12" style="padding: 0px;">
						<h3 style="margin-top: 5px; margin-bottom: 0px; padding: 0px 15px">Informasi Pasien</h3><hr style="margin: 10px 0px;">
						<div id="hasilRM" style="padding: 0px 15px"></div>
						<div class="clearfix" style="margin-bottom: 10px"></div>
					</div>
					<div class="clearfix" style="margin-bottom: 10px"></div>
				</div>
			</div>
		</div>
	</section>
	<div class="modal-rfid"></div>
@stop

@section('script')
	<script type="text/javascript">
		$('#btn-cek-rm').click(function(e){
			e.preventDefault();
			$('#btn-cek-rm').html('Please wait...').attr('disabled', true);
			var data  = new FormData($('.form-cek-rm')[0]);
			$.ajax({
				url: "{{ route('cekRM') }}",
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				$('.form-cek-rm').validate(data, 'has-error');
				var html = '';
				if(data.status=='success'){
					$('#hasilRM').html(data.content);
					$('#btn-cek-rm').html('<i class="fa fa-search" style="margin-right: 5px;"></i> Cek').removeAttr('disabled');
				}else{
					$('#btn-cek-rm').html('<i class="fa fa-search" style="margin-right: 5px;"></i> Cek').removeAttr('disabled');
					html+='<center style="margin-bottom:25px;"><h2><i>--== Data Pasien Tidak Ditemukan ==--</i></h2></center>';
					$('#hasilRM').html(html);
					swal('Whoops !!',data.message,'warning');
				}
			});
		});

		function printCardRfid() {
			cetak();
			addCardRfid();
		}

		function cetak(){
			var id = $('#noRmPas').val();
			$.post("{!! route('cetakRfid') !!}", {id:id}).done(function(data){
				if(data.status == 'success'){
					window.open(data.url,'_blank')
				}
			});
		}

		function addCardRfid() {
			var id = $('#noRmPas').val();
			$.post("{!! route('formAddRfid') !!}", {id:id}).done(function(data){
				if(data.status == 'success'){
					$('.modal-rfid').html(data.content);
				}
			});
		}
		function editCardRfid() {
			window.alert('Edit Kartu RFID');
		}
	</script>
@stop