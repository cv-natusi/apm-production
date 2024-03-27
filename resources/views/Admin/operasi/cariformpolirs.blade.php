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
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> Cari Data Pasien </h4>
			</div>
			<div class="modal-body">
				<div>
					<form>
						<div class="from-group">
							<label>Masukkan <span id="kats">Kode Poli</span> &nbsp;</label>
							<input type="text" name="nomor" style="width:200px !impoortant; margin: 10px auto;" id="cari">
							<input type="hidden" name="kategaori" id="kat" value="KodePoli">
						</div>	
					</form>
				</div>
				<section class="panel panel-default m-b-0">
					<div class="panel-body" style="overflow-y: scroll;height: 400px;">
						<table border="1" class="blok">
							<thead>
								<tr>
									<th width="200" ondblclick="nmpolibpjs()">Kode Poli BPJS</th>
									<th width="200" ondblclick="kdpoli()">Kode Poli</th>
									<th width="80%" ondblclick="nmpoli()">Nama Poli</th>
								</tr>
							</thead>
							<tbody id="resultpoli">
								<tr>
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

	$("#cari").keyup(function(){
		var key = $('#cari').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,0);
	});

	function reloadDataDiagnosa(key,kat,page) {
		var key = $('#cari').val();
		var kat = $('#kat').val();
		$.post("{!! route('caripolirs') !!}",{key:key, kat:kat,gopage:page}).done(function(result){
			if(result.status == 'success'){
				$('#result').empty();
				$('.pagination').empty();
				if(result.data.length > 0){
					var dat = '';
					$.each(result.data, function(c,v){
						var klik = "'"+v.kdpoli+"','"+v.NamaPoli+"'";
						dat += '<tr ondblclick="getdata('+klik+')"><td> '+v.kdpoli+'</td><td> '+v.KodePoli+'</td><td>'+v.NamaPoli+'</td></tr>';
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
					dat += '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><tr>';
				}
				$('#resultpoli').html(dat);
			}
		});
	}

	function pagin(gopage){
		var key = $('#cari').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,gopage);
	}

	function getdata(KodePoli, NamaPoli){
		$('#kdpoli').val(KodePoli);
		$('#namapoli').val(NamaPoli);
		$('#detail-dialog').modal('hide');
	}

	function kdpoli(){
		$('#kat').val('KodePoli');
		$('#kats').html('Kode Poli');
	}
	function nmpoli(){
		$('#kat').val('NamaPoli');
		$('#kats').html('Nama Poli');
	}
	function nmpolibpjs(){
		$('#kat').val('bp.kdpoli');
		$('#kats').html('Kode Poli Bpjs');
	}
	
</script>