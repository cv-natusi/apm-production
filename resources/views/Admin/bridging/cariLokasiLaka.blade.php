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
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> List {{ $title }}</h4>
			</div>
			<div class="modal-body">
				<div>
					<input type="hidden" name="jnsLokasi" id="jnsLokasi" value="{{ $jnsLokasi }}">
				</div>
				<section class="panel panel-default m-b-0">
					<div class="panel-body" style="overflow-y: scroll;height: 400px;">
						<table border="1" class="blok">
							<thead>
								<tr>
									<th width="35">&nbsp;</th>
									<th width="100" ondblclick="nobpjs()">Kode</th>
									<th width="300">Nama {{$title}}</th>
								</tr>
							</thead>
							<tbody id="result">
								<tr>
									<td contentEditable="true" class="edit">&nbsp;</td>
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
		var jnsLokasi = $('#jnsLokasi').val();
		if (jnsLokasi == 'prov') {
			reloadDataDiagnosa(jnsLokasi,0,0,0);
		} else if(jnsLokasi == 'kab') {
			var prov = $('#kdProvinsi').val();
			reloadDataDiagnosa(jnsLokasi,prov,0,0);
		} else if(jnsLokasi = 'kec') {
			var prov = $('#kdProvinsi').val();
			var kab = $('#kdKabupaten').val();
			reloadDataDiagnosa(jnsLokasi,prov,kab,0);
		}
	});

	function reloadDataDiagnosa(jnsLokasi,prov,kab,page) {
		$.post("{!! route('getLokasiLaka') !!}",{jnsLokasi:jnsLokasi, prov:prov, kab:kab}).done(function(result){
			if(result.metaData.code == '200'){
				$('#result').empty();
				$('.pagination').empty();
				if(result.response.list.length > 0){
					$("#loading").text('');
					var dat = '';
					$.each(result.response.list, function(c,v){
						dat += `<tr data-id='${v.kode}'><td ondblclick='getdata(${JSON.stringify(v)})'><center><i class="fa fa-check"></i></center></td>`+
								'<td>'+v.kode+'</td>'+
								'<td>'+v.nama+'</td><tr>';
					});

					// Start Pagination
					var gopage = (result.gopage) ? result.gopage : 1;
					var pg =  '';
					//link prev
					if(gopage == 1){
						page = 1;
						pg += '<li class="disabled"><a href="javascript:void(0)">First</a></li>';
					}else{
						(gopage > 1)? gopage - 1 : 1;
						var beforePage = gopage - 1;
						pg +=   '<li><a href="javascript:void(0)" onclick="pagin(1)">First</a></li>'+
								'<li><a href="javascript:void(0)" onclick="pagin('+beforePage+')">&laquo;</a></li>';
					}

					//link number
					var jumlah_page = Math.ceil(result.sum/15); //jumlah halaman
					var jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
			        var start_number = (gopage > jumlah_number)? gopage - jumlah_number : 1; // Untuk awal link number
			        var bts = jumlah_page - jumlah_number;
			        var end_number = (gopage < bts)?page + jumlah_number : jumlah_page; // untuk end link number
			        for(var i = start_number; i <= end_number; i++){
		          		var link_active = (gopage == i)? 'class="active"' : '';
		          		var number = '"'+i+'"';
						pg += '<li '+link_active+'><a href="javascript:void(0)" onclick="pagin('+i+')">'+i+'</a></li>';
			        }

					//link next
					if (page == jumlah_page) {
						pg += '<li class="disabled"><a href="javascript:void(0)">Last</a></li>';
					}else{
						var nextPage = page + 1;
						pg += '<li><a href="javascript:void(0)" onclick="pagin('+nextPage+')">&raquo;</a></li>'+
						'<li><a href="javascript:void(0)" onclick="pagin('+jumlah_page+')">Last</a></li>';
					}
					$('.pagination').show().html(pg);
					// End Pagination
				}else{
					dat += '<tr><td>&nbsp;</td>'+
								'<td>&nbsp;</td>'+
								'<td>&nbsp;</td><tr>';
				}
				$('#result').html(dat);
			} else {
				$("#loading").text(result.metaData.message);
				dat += '<tr><td>&nbsp;</td>'+
						'<td>&nbsp;</td>'+
						'<td>&nbsp;</td><tr>';
				$('#result').html(dat);
			}
		});
	};

	function getdata(obj){
		var jnsLokasi = $('#jnsLokasi').val();
		$('#detail-dialog').modal('hide');
		if (jnsLokasi == 'prov') {
			$('#kdProvinsi').val(obj.kode);
			$('#nmProvinsi').val(obj.nama);
			$('#nmKabupaten').attr('placeholder', 'Pilih Kecamatan');
			$('#btn-kab').show();
		} else if (jnsLokasi == 'kab') {
			$('#kdKabupaten').val(obj.kode);
			$('#nmKabupaten').val(obj.nama);
			$('#nmKecamatan').attr('placeholder', 'Pilih Kecamatan');
			$('#btn-kec').show();
		} else if (jnsLokasi == 'kec') {
			$('#kdKecamatan').val(obj.kode);
			$('#nmKecamatan').val(obj.nama);
		}
	}
</script>
