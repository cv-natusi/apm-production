<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
	<div class="modal-dialog modal-lg" >
		<div class="modal-content">
			<div class="modal-header" style="background: #00a65a;color: #fff;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> Detail Pasien</h4>
			</div>
			<div class="modal-body">
				<section class="panel panel-default" style="margin-bottom: 0">
					<div class="panel-body">
						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-4 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">Nama </label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">: {{ $reg->Nama_Pasien }}</label>
								</div>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-4 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">Alamat </label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">: {{ $reg->AlamatPasien }}</label>
								</div>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-4 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">Umur</label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">: {{ $reg->Umur }} Tahun</label>
								</div>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-4 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">Nama Asuransi</label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">: {{ $reg->NamaAsuransi }}</label>
								</div>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-4 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">No Register </label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">: {{ $reg->No_Register }}</label>
								</div>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-4 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">Tanggal Register </label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">: {{ $reg->Tgl_Register }}</label>
								</div>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-4 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">No RM </label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">: {{ $reg->No_RM }}</label>
								</div>
								<div class="form-group m-t-0 m-b-20">
									<label class="control-label col-md-4 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">No SEP </label>
									<label class="control-label col-md-6 col-sm-6 col-xs-6" id='modal-label' for="first-name" style="font-style: italic;">: {{ $reg->NoSEP }}</label>
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
			'width'     : '40%'
		});
		$('#detail-dialog').modal('show');
	})();
	$('#detail-dialog').on('hidden.bs.modal', function () {
		$('.modal-dialog').html('');
	})
</script>