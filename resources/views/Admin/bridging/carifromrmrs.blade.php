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
							<label>Masukkan <span id="kats"></span> &nbsp;</label>
							<input type="text" name="nomor" style="width:200px !impoortant; margin: 10px auto;" id="cari">
							<label>Masukkan <span id="kats">Alamat</span></label>
							<input type="text" name="alamat" style="width:200px !impoortant; margin: 10px auto;" id="alamat"> <br>
							<input type="hidden" name="kategaori" id="kat" value="NamaCust">
						</div>	
					</form>
				</div>
				<section class="panel panel-default m-b-0">
					<div class="panel-body" style="overflow-y: scroll;height: 400px;">
						<table border="1" class="blok">
							<thead>
								<tr>
									<th width="100" ondblclick="norm()">No. RM</th>
									<th width="200" ondblclick="nama()">Nama Pasien</th>
									<th width="300" ondblclick="alamat()">Alamat</th>
									<th width="75">Jenis kel</th>
									<th width="75" ondblclick="tgl()">Tgl Lahir</th>
									<th width="50">Umur</th>
								</tr>
							</thead>
							<tbody id="result">
								<!-- <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr> -->
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
		var alamat = $('#alamat').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,alamat,0);
	});
	
	$("#alamat").keyup(function(){
		var key = $('#cari').val();
		var alamat = $('#alamat').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,alamat,0);
	});

	function reloadDataDiagnosa(key,kat,alamat,page) {
		var key = $('#cari').val();
		var kat = $('#kat').val();
		$.post("{!! route('caripasienrs') !!}",{key:key, kat:kat, alamat:alamat, gopage:page}).done(function(result){
			if(result.status == 'success'){
				$('#result').empty();
				$('.pagination').empty();
				if(result.data.length > 0){
					var dat = '';
					$.each(result.data, function(c,v){
						var u = (v.umur) ? v.umur:'';
						var klik = "'"+v.KodeCust+"'";
						dat += '<tr ondblclick="getdata('+klik+')"><td>'+v.KodeCust+'</td>'+
								'<td>'+v.NamaCust+'</td>'+
								'<td>'+v.Alamat+'</td>'+
								'<td><center>'+v.JenisKel+'</center></td>'+
								'<td>'+tgl_indo(v.TglLahir)+'</td>'+
								'<td>'+u+'</td><tr>';
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
								'<td>&nbsp;</td>'+
								'<td>&nbsp;</td>'+
								'<td>&nbsp;</td>'+
								'<td>&nbsp;</td>'+
								'<td>&nbsp;</td><tr>';
				}
				$('#result').html(dat);
			}
		});
	}

	function pagin(gopage){
		var key = $('#cari').val();
		var alamat = $('#alamat').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,alamat,gopage);
	}

	function tgl_indo(tgl) {
		$tanggal = tgl.substr(8,2);
		$bulan	 = tgl.substr(5,2);
		$tahun	 = tgl.substr(0,4);
		return $tanggal+'/'+$bulan+'/'+$tahun;
	}
	function getdata(KodeCust){
		$('#detail-dialog').modal('hide');
		$('#rm').val(KodeCust);
	}

	function norm(){
		$('#kat').val('KodeCust');
		$('#kats').html('KodeCust');
	}
	function nama(){
		$('#kat').val('NamaCust');
		$('#kats').html('Nama Pasien');
	}
	function alamat(){
		$('#kat').val('Alamat');
		$('#kats').html('Alamat Pasien');
	}
	function tgl(){
		$('#kats').html('Tanggal lahir Pasien');
		$('#kat').val('TglLahir');
	}
</script>