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
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> List Suplesi</h4>
			</div>
			<div class="modal-body">
				<div>
					<div class="form-group">
						<label>Masukkan Tanggal Pelayanan/SEP</label>
						<input type="date" name="tglsep_laka" style="width:200px !important; margin: 10px auto;" value="{{ date('Y-m-d') }}" id="tglsep_laka">
					</div>
				</div>
				<section class="panel panel-default m-b-0">
					<div class="panel-body" style="overflow-y: scroll;height: 400px;">
						<table border="1" class="blok">
							<thead>
								<tr>
									<th width="35">&nbsp;</th>
									<th width="100">No. SEP</th>
									<th width="300">No. SEP Awal</th>
									<th width="100">Tgl. SEP</th>
									<th width="100">Tgl. Kejadian</th>
									<th width="100">No. Register</th>
									<th width="100">Surat Jaminan</th>
								</tr>
							</thead>
							<tbody id="result">
								<tr>
									<td contentEditable="true" class="edit">&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>

					</div>
				</section>
				<ul class="pagination" style="margin: 0px">
					<li class="disabled"><a href="javascript:void(0)">First</a></li>
					<li class="disabled"><a href="javascript:void(0)">&laquo;</a></li>

					<li><a href="javascript:void(0)">&raquo;</a></li>
					<li><a href="javascript:void(0)">Last</a></li>
		        </ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btnGreen" data-dismiss="modal">Ok</button>
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
		$('.pagination').hide();
	})();
	$('#detail-dialog').on('hidden.bs.modal', function () {
		$('.modal-dialog').html('');
	});
	$(document).ready(function(){
		var tglsep_laka = $('#tglsep_laka').val();
		reloadData(tglsep_laka,0);
	});

	$("#tglsep_laka").change(function(){
		var tglsep_laka = $('#tglsep_laka').val();
		reloadData(tglsep_laka,0);
	});

	function reloadData(tglsep_laka,page) {
		var nokepesertaan = $('#nokepesertaan').val();
		$.post("{!! route('getSuplesi') !!}",{tglsep_laka:tglsep_laka, nokepesertaan:nokepesertaan}).done(function(result){
			swal('MAINTENANCE!', 'Coming Soon!', 'info');
		});
	};

	function getdata(obj){
		console.log(obj.nama);
		$('#detail-dialog').modal('hide');
		var jenisDpjp = $('#jenisDpjp').val();
		if (jenisDpjp == 'DPJP Layan') {
			$('#dpjpLayan').val(obj.kode);
			$('#dpjp_layan').val(obj.nama);
		} else if (jenisDpjp == 'Perujuk') {
			$('#kdDpjp').val(obj.kode);
			$('#dpjp_rujuk').val(obj.nama);
		}
	}
</script>
