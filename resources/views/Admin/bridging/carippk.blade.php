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
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> Cari PPK</h4>
			</div>
			<div class="modal-body">
				<div>
					<!-- <form> -->
						<div class="from-group">
							<label>Masukkan Kode/Nama Faskes</label>
							<input type="text" name="nomor" style="width:200px !impoortant; margin: 10px auto;" id="cari">
							<div class="col-md-2">
								<select class="form-control" id="faskesTingkat" name="faskesTingkat">
									<option selected value="1">Faskes Tingkat 1</option>
									<option value="2">Faskes Tingkat 2</option>
								</select>
							</div>
							<i> <span id="loading"></span></i>
						</div>
					<!-- </form> -->
				</div>
				<section class="panel panel-default m-b-0">
					<div class="panel-body" style="overflow-y: scroll;height: 400px;">
						<table border="1" class="blok">
							<thead>
								<tr>
									<th width="35">&nbsp;</th>
									<th width="100" ondblclick="nobpjs()">Kode</th>
									<th width="300">Nama Faskes</th>
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

	$("#cari").keyup(function(){
		var key = $('#cari').val();
		var tingkat = $('#faskesTingkat').val();
		reloadDataDiagnosa(key,tingkat,0);
	});

	function reloadDataDiagnosa(key,tingkat,page) {
		$("#loading").text(' Memuat data ...');
		var key = $('#cari').val();
		$.post("{!! route('getFaskes') !!}",{key:key,tingkat:tingkat, gopage:page}).done(function(result){
			if (result.metaData == null) {
				$("#loading").text('');
				dat += '<tr><td>&nbsp;</td>'+
						'<td>&nbsp;</td>'+
						'<td>&nbsp;</td><tr>';
				$('#result').html(dat);
			}
			if(result.metaData.code == '200'){
				$('#result').empty();
				$('.pagination').empty();
				if(result.response.faskes.length > 0){
					$("#loading").text('');
					var dat = '';
					$.each(result.response.faskes, function(c,v){
						dat += '<tr data-id="'+v.kode+'"><td ondblclick="getdata(\''+v.kode+'\', \''+v.nama+'\')"><center><i class="fa fa-check"></i></center></td>'+
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

	function tgl_indo(tgl) {
		$tanggal = tgl.substr(8,2);
		$bulan	 = tgl.substr(5,2);
		$tahun	 = tgl.substr(0,4);
		return $tanggal+'/'+$bulan+'/'+$tahun;
	}

	function pagin(gopage){
		var key = $('#cari').val();
		var alamat = $('#alamat').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,alamat,gopage);
	}

	function getdata(kode, nama){
		var asalruj = $('#faskesTingkat').val();
		$('#detail-dialog').modal('hide');
		// $('#nama_ppk').val(nama+' ('+kode+')');
		$('#ppk-rujukan').val(kode);
		$('#asal_rujukan').val(asalruj);
	}

	function nobpjs(){
		$('#kat').val('FieldCust1');
		$('#kats').html('No. BPJS');
	}
	function norm(){
		$('#kat').val('KodeCust');
		$('#kats').html('Nomor RM');
	}
	function nama(){
		$('#kat').val('NamaCust');
		$('#kats').html('Nama Pasien');
	}
	function alamat(){
		$('#kat').val('Alamat');
		$('#kats').html('Alamat');
	}
	function tgl(){
		$('#kat').val('TglLahir');
		$('#kats').html('TglLahir');
	}

	function saveChanges(cell){
	        var data = {
		      "kdcust": $.trim(cell.parent().data('id')),
		      "field": $.trim(cell.data('name')),
		      "value": $.trim(cell.html())
		    };
	        $.post("{!! route('changeNokartu') !!}",{ data:data}).done(function(result){

	        });
    };

    function editt(kdcust,no){
	    var OriginalContent = $.trim($('.edit'+kdcust+'').text());
	    $('.edit'+kdcust+'').addClass("cellEditing");
	    $('.edit'+kdcust+'').html("<input type='text' value='" + OriginalContent + "' />");
	    $('.edit'+kdcust+'').children().first().focus();
	    $('.edit'+kdcust+'').children().first().keypress(function(e) {
	      if (e.which == 13) {
	        var newContent = $(this).val();
	        var cell = $(this).parent();
	        cell.text(newContent);
	        cell.removeClass("cellEditing");
	        if ($.trim(newContent) != OriginalContent)
	          saveChanges(cell);
	      }
	    });

	    $('.edit'+kdcust+'').children().first().outfocus(function(){
			var cell = $('.edit'+kdcust+'').parent();
			cell.text(OriginalContent);
			cell.removeClass("cellEditing");
	    });
	}
</script>
