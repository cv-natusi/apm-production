<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
	<div class="modal-dialog modal-lg" >
		<div class="modal-content">
			<div class="modal-header" style="background: #00a65a;color: #fff;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> Tambah RFID</h4>
			</div>
			<form class='form-add'>
				<div class="modal-body">
					<section class="panel panel-default" style="margin-bottom: 0">
						<div class="panel-body">
							<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
								<div class="form-group">
									<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" id='label-input'>No RM</label>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="text" name='KodeCust' value="{{ $customer->KodeCust }}" autocomplete='off' class="form-control" required='required' readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" id='label-input'>Nama Pasien</label>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="text" name='NamaCust' value="{{ $customer->NamaCust }}" autocomplete='off' class="form-control" required='required' readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" id='label-input'>No Kartu RFID</label>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="text" name='noRfid' autocomplete='off' class="form-control" required='required'>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary pull-right m-l-15" id='btn-submit'>Simpan <span class="fa fa-save"></span></button>
					<button type="button" class="btn btn-warning btn-cancel pull-right" data-dismiss="modal" style="margin-right: 10px"><span class="fa fa-chevron-left"></span> Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	var onLoad = (function() {
		$('#detail-dialog').find('.modal-dialog').css({
			'width'     : '40%'
		});
		$('#detail-dialog').modal('show');
	})();

	$('#detail-dialog').on('hidden.bs.modal', function () {
		$('.modal-dialog').html('');
	})
	$('#detail-dialog').on('shown.bs.modal', function () {
		$('input[name=noRfid]').focus();
	}) 

	$('#btn-submit').click(function(e){
		e.preventDefault();
		$('#btn-submit').html('Please wait...').attr('disabled', true);
		var data  = new FormData($('.form-add')[0]);
		$.ajax({
			url: "{{ route('addRfid') }}",
			type: 'POST',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data){
			$('.form-add').validate(data, 'has-error');
			if(data.status=='success'){
				$('#detail-dialog').modal('hide');
				$('#hasilRM').html(data.content);
				swal('Berhasil !!',data.message,'success');
			}else{
				$('#btn-submit').html('Simpan <span class="fa fa-save"></span>').removeAttr('disabled');
				swal('Whoops !!',data.message,'warning');
			}
		});
	});
</script>