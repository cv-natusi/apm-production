<style type="text/css">
	.blok > tbody > tr:hover {
		background: #eee;
	} 
</style>
<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
	<div class="modal-dialog modal-lg" >
		<div class="modal-content">
			<div class="modal-header modalHeaderGreen">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> Update Tanggal Pulang </h4>
			</div>
			<div class="modal-body">
					<span>{!! $messages !!}</span><br><span>Update tanggal pulang pasien.</span><br><br>
					<label>Masukkan Tanggal Pulang</label>
					<form id="pulangupdate">
						<input type="date" name="tglpulang" class="form-control" id="tglpulang" value="{!! date('Y-m-d') !!}">
						<input type="hidden" name="nobpjspulang" id="nobpjspulang" value="{{ $nobpjs }}">
						<button type="button" id="btn-update-pulang" >Simpan</button>
					</form>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var onLoad = (function() {
		$('#detail-dialog').find('.modal-dialog').css({
			'width'     : '35%'
		});
		$('#detail-dialog').modal('show');
		$('.pagination').hide();
	})();
	$('#detail-dialog').on('hidden.bs.modal', function () {
		$('.modal-dialog').html('');
	});
	
	$('#btn-update-pulang').click(function(){
		/*var tgl = $('#tglpulang');
		var nobpjs = $('#nobpjspulang');
		$.post("{!! route('saveChange') !!}",{tgl:tgl, nobpjs:nobpjs}).done(function(result){
			window.alert('berhasil');
		});*/

		var data = new FormData($('#pulangupdate')[0]);

		$.ajax({
	        url: '{!! route("saveChange") !!}',
	        type: 'POST',
	        data: data,
	        async: true,
	        cache: false,
	        contentType: false,
	        processData: false
	     }).done(function(data){
	     	if(data.metadata['code'] == '200'){
	     		swal('Sukses','Update Tanggal Pulang No. SEP '+data.response+" Berhasil",'success');
	     	}else{
	     		swal('warning',data.metaData.message,'warning');
	     	}
				$('#detail-dialog').modal('hide');
	     });
	});
</script>