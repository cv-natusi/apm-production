<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
	<div class="modal-dialog modal-lg" >
		<div class="modal-content">
			<div class="modal-header modalHeadReg">
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog">{!! $title !!}</h4>
			</div>
			<div class="modal-body">
				<div class="panelNoIden p-t-25">
					<form class="formRegistrasi">
						<input type="hidden" name="jenis_pendaftaran" value="{{ $jenis }}">
						<input type="text" name="no_identitas" class="form-control inputNoIdentitas">
						<div class="clearfix m-b-15"></div>
						<button id='btn-simpan' class="btn btn-hijau btn-besar m-r-15">LANJUTKAN</button>
						<button type="button" class="btn btn-merah btn-besar" data-dismiss="modal">BATAL MENDAFTAR</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var onLoad = (function() {
		$('#detail-dialog').find('.modal-dialog').css({
			'width'     : '50%',
			'margin-top' : '10%'
		});
		$('#detail-dialog').modal('show');
	})();
	$('#detail-dialog').on('hidden.bs.modal', function () {
		$('.modal-dialog').html('');
	})
	$('#detail-dialog').on('shown.bs.modal', function () {
		$('.inputNoIdentitas').focus();
	}) 

	$('#btn-simpan').click(function(e){
		e.preventDefault();
		var data  = new FormData($('.formRegistrasi')[0]);
		$.ajax({
			url: "{{ route('confirmCust') }}",
			type: 'POST',
			data: data,
			enctype: 'multipart/form-data',
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data){
			if(data.status == 'success'){
				$('#detail-dialog').modal('hide');
				$('.modal-second').html(data.content).fadeIn();
			} else if(data.status == 'error') {
				swal({
                    title: "MAAF !",
                    text: data.message,
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#detail-dialog').modal('hide');
			} else {
				$('#mainRegistrasi').show();
			}
		});
	});
</script>