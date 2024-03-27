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
					<span>{!! $messages !!}, dengan NO SEP {{ $nobpjs }} pada tanggal {{ $tglSEP }}</span><br><span>Update tanggal pulang pasien.</span><br><br>
					<label>Masukkan Tanggal Pulang</label>
					<form id="pulangupdate">
						<input type="date" name="tglpulang" class="form-control" id="tglpulang" value="{!! date('Y-m-d') !!}">
						<label>No SEP</label>
						<input type="text"  name="nobpjspulang" class="form-control" id="nobpjspulang" value="{{ $nobpjs }}">
						<label>Status Pulang</label>
						<select class="form-control" name="statusPulang" id="statusPulang">
							<option value="1">Atas Persetujuan Dokter</option>
							<option value="2">Dirujuk</option>
							<option value="3">Atas Permintaan Sendiri</option>
							<option value="4">Meninggal</option>
							<option value="5">Lain-lain</option>
						</select>
						<div id="ifMeninggal" style="display:none;">
							<label>Tanggal Meninggal <span style="color:red">*</span></label>
							<input type="date" name="tglMeninggal" class="form-control" id="tglMeninggal" value="" placeholder="diisi jika Status Pulang Meninggal">
							<label>No Surat Meninggal</label>
							<input type="text" name="noSuratMeninggal" class="form-control" id="noSuratMeninggal" value="" placeholder="diisi jika Status Pulang Meninggal">
						</div>
						<label>No LP Manual</label>
						<input type="text" name="noLPManual" class="form-control" id="noLPManual" value="" placeholder="diisi jika SEPnya adalah KLL">
						<div class="clearfix" style="margin-bottom:10px;"></div>
						<button type="button" id="btn-update-pulang" class="btn btn-primary">Simpan</button>
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
	$('#statusPulang').change(function () {
		if ($('#statusPulang').val() == '4') {
			$('#ifMeninggal').show();
		} else {
			$('#ifMeninggal').hide();
			$('#tglMeninggal').val("");
			$('#noSuratMeninggal').val("");
		}
	});

	$('#btn-update-pulang').click(function(){
		/*var tgl = $('#tglpulang');
		var nobpjs = $('#nobpjspulang');
		$.post("{!! route('saveChange') !!}",{tgl:tgl, nobpjs:nobpjs}).done(function(result){
			window.alert('berhasil');
		});*/
		var noSepPascaInap = $('#nobpjspulang').val();
		var tglNow = '{{ date("Y-m-d") }}';

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
	     	if(data.metaData['code'] == '200'){
	     		swal('Sukses','Update Tanggal Pulang No. SEP '+data.response+" Berhasil",'success');
	     		$('#nRujuk').val(noSepPascaInap);
	     		$('#tingkatRujuk').val('2');
	     		$('#ppk-rujukan').val('1320R001');
	     		$('.tglRujuk').val(tglNow);
	     	}else{
	     		swal('warning',data.metaData.message,'warning');
	     	}
			$('#detail-dialog').modal('hide');
	     });
	});
</script>
