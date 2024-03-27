<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
	<div class="modal-dialog modal-lg" >
		<div class="modal-content">
			<div class="modal-header" style="background: #00a65a;color: #fff;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> Detail Saran</h4>
			</div>
			<div class="modal-body">
				<section class="panel panel-default" style="margin-bottom: 0">
					<div class="panel-body">
						<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1'>
							<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">Tanggal : {{ date('d-m-Y', strtotime($saran->tanggal)) }}</label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">Jam : {{ date('H:i:s', strtotime($saran->tanggal)) }}</label>
								</div>
							</div>
							<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-12 col-sm-12 col-xs-12" id='modal-label' for="first-name" style="font-style: italic;">Judul : </label>
									<div style="padding: 0 25px">{{ $saran->judul }}</div>
								</div>
							</div>
							<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-12 col-sm-12 col-xs-12" id='modal-label' for="first-name" style="font-style: italic;">Saran :</label>
									<div style="padding: 0 15px;">
										{!! $saran->saran !!}
									</div>
								</div>
							</div>
					</div>
				</section>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var onLoad = (function() {
		$('#detail-dialog').find('.modal-dialog').css({
			'width'     : '70%'
		});
		$('#detail-dialog').modal('show');
	})();
	$('#detail-dialog').on('hidden.bs.modal', function () {
		$('.modal-dialog').html('');
	})
</script>