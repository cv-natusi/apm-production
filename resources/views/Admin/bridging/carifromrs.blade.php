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
					<!-- <form> -->
						<div class="from-group">
							<label>Masukkan <span id="kats">No. RM</span></label>
							<input type="text" autofocus name="nomor" style="width:200px !impoortant; margin: 10px auto;" id="cari">
							<label>Masukkan <span id="kats">Alamat</span></label>
							<input type="text" name="alamat" style="width:200px !impoortant; margin: 10px auto;" id="alamat"> <br>
							<input type="hidden" name="kategaori" id="kat" value="KodeCust">
						</div>
					<!-- </form> -->
				</div>
				<section class="panel panel-default m-b-0">
					<div class="panel-body" style="overflow-y: scroll;height: 400px;">
						<table border="1" class="blok">
							<thead>
								<tr>
									<th width="35">&nbsp;</th>
									<th width="110" ondblclick="nobpjs()">No. Kepesertaan</th>
									<th width="100" ondblclick="norm()">No. RM</th>
									<th width="200" ondblclick="nama()">Nama Pasien</th>
									<th width="300">Alamat</th>
									<th width="75">Jenis kel</th>
									<th width="75" ondblclick="tgl()">Tgl Lahir</th>
									<th width="50">Umur</th>
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
	$(document).ready(function(){
		reloadDataDiagnosa('','','',0);
		var autofocus = document.getElementById("cari").focus;
	});
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
		// console.log(key);
		$.post("{!! route('caripasienrs') !!}",{key:key, kat:kat, alamat:alamat, gopage:page}).done(function(result){
			if(result.status == 'success'){
				$('#result').empty();
				$('.pagination').empty();
				if(result.data.length > 0){
					var dat = '';
					$.each(result.data, function(c,v){
						var u = (v.umur) ? v.umur:'';
						var no_bpjs = $('#nobpjs_'+v.KodeCust+'').val();
						var nobpjs = (v.FieldCust1) ? v.FieldCust1  : '-';
						var klik = "'"+v.KodeCust+"','"+nobpjs+"'";
						dat += '<tr data-id="'+v.KodeCust+'"><td  ondblclick="getdata('+klik+')"><center><i class="fa fa-check"></i></center></td>'+
								'<td data-name="FieldCust1" class="edit'+v.KodeCust+'" ondblclick="editt('+klik+')">'+nobpjs+' <input id="nobpjs_'+v.KodeCust+'" readonly type="hidden" value="'+nobpjs+'"></td>'+
								'<td>'+v.KodeCust+'</td>'+
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

	function getdata(KodeCust,nobpjs){
		var rawat = $('input[name=rawat]:checked').val();
		$.post("{!! route('getpasienrs') !!}",{key:KodeCust,nobpjs:nobpjs}).done(function(result){
			$('#detail-dialog').modal('hide');
			if(result.status == 'success'){
				$('#namapasien').val(result.data.NamaCust);
				$('#rm').val(result.data.KodeCust);
				$('#nokepesertaan').val(nobpjs);
				$('#alamatpasien').val(result.data.Alamat);


	        	$('#cek').html(result.data.JenisKel);

				$.post("{!! route('cekpeserta') !!}",{nobpjs:nobpjs}).done(function(result){
					 if(result.metaData.code == 200){
						 if(result.response.peserta.statusPeserta.keterangan != "AKTIF"){
							 swal("Peringatan",result.response.peserta.statusPeserta.keterangan,"warning");
						 }else{
							 var kdcb = (result.response.peserta.provUmum.kdCabang) ? result.response.peserta.provUmum.kdCabang : '';
							 var nmcb = (result.response.peserta.provUmum.nmCabang) ? result.response.peserta.provUmum.nmCabang : '';
							 $('#nama_bpjs').html(result.response.peserta.nama);
							 $('#tgl_bpjs').html(result.response.peserta.tglLahir);
							 $('#sex_bpjs').html(result.response.peserta.sex);
							 $('#kdprov_bpjs').html(result.response.peserta.provUmum.kdProvider);
							 $('#nmprov_bpjs').html(result.response.peserta.provUmum.nmProvider);
							 $('#telpbpjs').html(result.response.peserta.mr.noTelepon);
							 $('#notelp').val(result.response.peserta.mr.noTelepon);
							 $('#cetakbpjs').html(result.response.peserta.tglCetakKartu);
							 $('#jnps_bpjs').html(result.response.peserta.jenisPeserta.keterangan);
							 $('#kltg_bpjs').html(result.response.peserta.hakKelas.keterangan);
							 $('#statusbpjs').html(result.response.peserta.statusPeserta.keterangan);
							 $('#prbPeserta').html(result.response.peserta.informasi.prolanisPRB);
							 if ($('#nonIgd').is(':checked')) {
							 	$('#ppk-rujukan').val(result.response.peserta.provUmum.kdProvider);
							 }
							 $('#nonik').val(result.response.peserta.nik);
							 $('#tgllahir').val(result.response.peserta.tglLahir);
							 $('#kelasrawat').val(result.response.peserta.hakKelas.kode);
							 var u = result.response.peserta.umur.umurSekarang;
							 var cob = (result.response.peserta.noAsuransi) ? '1' : 0;
							 $('#umur').val(u.substr(0,2));
							 $('#cob').val(cob);
							 $('#btn-sep').removeAttr('disabled');

							if (result.response.peserta.hakKelas.kode == '1') {
								$('#kelas1').attr('checked', true);
							}else if (result.response.peserta.hakKelas.kode == '2') {
								$('#kelas2').attr('checked', true);
							}else {
								$('#kelas3').attr('checked', true);
							}
							 if(result.response.peserta.sex == "L"){
								 $('#L').attr('checked','checked');
							 }else{
								 $('#P').attr('checked','checked');
							 }
						 }

			        }else{
		        		swal("Peringatan",result.metaData.message,"warning");
								$('#nokepesertaan').val('');
								$('#namapasien').val('');
								$('#rm').val('');
								$('#nokepesertaan').val('');
								$('#alamatpasien').val('');
								$('#notelp').val('');
								$('#P').attr('checked','false');

		        		// data bpjs
			        	$('#nama_bpjs').html('');
			        	$('#sex_bpjs').html('');
			        	$('#tgl_bpjs').html('');
			        	$('#kdprov_bpjs').html('');
			        	$('#nmprov_bpjs').html('');
			        	$('#telpbpjs').html('');
			        	$('#cetakbpjs').html('');
			        	$('#jnps_bpjs').html('');
			        	$('#kltg_bpjs').html('');
								$('#statusbpjs').html('');
			        	$('#prbPeserta').html('');
			        }
		        });

		        $.post("{!! route('cekrujukan') !!}",{nobpjs:nobpjs,rm:result.data.KodeCust,rawat:rawat}).done(function(resultRujuk){
		        	// console.log(resultRujuk.data.rujukan.response.rujukan.noKunjungan);
		        	// console.log('ada apa?');
		        	if (resultRujuk.status == 'success') {
				        if(resultRujuk.data.rujukan.response){
				        	$('.tglRujuk').val(resultRujuk.data.rujukan.response.rujukan.tglKunjungan);
				        	// if ($('#nonIgd').is(':checked')) {
				        		$('#nRujuk').val(resultRujuk.data.rujukan.response.rujukan.noKunjungan);
					        	$('#ppk-rujukan').val(resultRujuk.data.rujukan.response.rujukan.provPerujuk.kode);
					        	$('#asal_rujukan').val(resultRujuk.data.rujukan.response.asalFaskes);
					        // }
				        	if (resultRujuk.data.rujukan.response.rujukan) {
					        	$('#valDiagnosa').val(resultRujuk.data.rujukan.response.rujukan.diagnosa.kode);
					        	$('#textDiagnosa').text(resultRujuk.data.rujukan.response.rujukan.diagnosa.nama);
					        	$('#kdpoli').val(resultRujuk.data.rujukan.response.rujukan.poliRujukan.kode);
					        	$('#namapoli').text('POLI SPESIALIS '+resultRujuk.data.rujukan.response.rujukan.poliRujukan.nama);
					        }
				        	if (resultRujuk.data.riwayatRegis == '1') {
					        	// $('#namaDpjp').attr('type','text');
					        	// $('.panelDokterDpjp2').hide();
					        	$('#namaDpjp').val(resultRujuk.data.namaDPJP);
					        	$('#kdDpjp').val(resultRujuk.data.kodeDPJP);

					        	var listDokterDpjp = '<option>.: Pilih Dokter DPJP :. </option>';
					        	// if(resultRujuk.data.dokterBridgs.length > 0){
					        	// 	$.each(resultRujuk.data.dokterBridgs, function(k,v){
					        	// 		if (resultRujuk.data.namaDPJP == v.dokter) {
					        	// 			listDokterDpjp += '<option value="'+v.id+'" selected>'+v.dokter+' ('+v.poli+')</option>';
					        	// 		}else{
					        	// 			listDokterDpjp += '<option value="'+v.id+'">'+v.dokter+' ('+v.poli+')</option>';
					        	// 		}
					        	// 	});
					        	// }
					        	// $('#pilihDokterDpjp').html(listDokterDpjp);
					        	// $('#pilihDokterDpjp').trigger('chosen:updated');

					        	// DPJP LAYAN
					        	$('#dpjpLayan').val(resultRujuk.data.kodeDPJP);

					        	var listDokterDpjpLayan = '<option>.: Dokter DPJP Yang Melayani :. </option>';
					        	// if(resultRujuk.data.dpjp.length > 0){
					        	// 	$.each(resultRujuk.data.dpjp, function(k,v){
					        	// 		if (resultRujuk.data.kodeDPJP == v.kode) {
					        	// 			listDokterDpjpLayan += '<option value="'+v.kode+'" selected>'+v.nama+'</option>';
					        	// 		}else{
					        	// 			listDokterDpjpLayan += '<option value="'+v.kode+'">'+v.nama+'</option>';
					        	// 		}
					        	// 	});
					        	// }
					        	// $('#pilihDokterDpjpLayan').html(listDokterDpjpLayan);
					        	// $('#pilihDokterDpjpLayan').trigger('chosen:updated');
				        	}else{
				        		// $('#namaDpjp').attr('type','hidden');
					        	// $('.panelDokterDpjp2').show();

					        	var listDokterDpjp = '<option>.: Pilih Dokter DPJP :. </option>';
					        	// if(resultRujuk.data.dokterBridgs.length > 0){
					        	// 	$.each(resultRujuk.data.dokterBridgs, function(k,v){
					        	// 		listDokterDpjp += '<option value="'+v.id+'">'+v.dokter+' ('+v.poli+')</option>';
					        	// 	});
					        	// }
					        	// $('#pilihDokterDpjp').html(listDokterDpjp);
					        	// $('#pilihDokterDpjp').trigger('chosen:updated');
					        	// DPJP LAYAN
					        	var listDokterDpjpLayan = '<option>.: Dokter DPJP Yang Melayani :. </option>';
					        	// if(resultRujuk.data.dpjp.length > 0){
					        	// 	$.each(resultRujuk.data.dpjp, function(k,v){
					        	// 		listDokterDpjpLayan += '<option value="'+v.kode+'">'+v.nama+'</option>';
					        	// 	});
					        	// }
					        	// $('#pilihDokterDpjpLayan').html(listDokterDpjpLayan);
					        	// $('#pilihDokterDpjpLayan').trigger('chosen:updated');
					        }
					        // $('#noSurat').val(resultRujuk.data.noSurat);
					        $('#tingkatRujuk').val(resultRujuk.data.tingkatRujuk);
				        }else{
				        	$('#nRujuk').val('');
				        	$('#namaDpjp').val('');
				        	$('#kdDpjp').val('');
				        	$('#dpjpLayan').val('');
				        	// $('#noSurat').val('');
					        $('#tingkatRujuk').val('1');
				        }
				    }else{
				    	$('#nRujuk').val('');
			        	$('#namaDpjp').val('');
				        $('#kdDpjp').val('');
			        	$('#dpjpLayan').val('');
			        	// $('#noSurat').val('');
				        $('#tingkatRujuk').val('1');
				        // $('#ppk-rujukan').val('');
				        var listDokterDpjp = '<option>.: Pilih Dokter DPJP :. </option>';
			        	// if(resultRujuk.data.dokterBridgs.length > 0){
			        	// 	$.each(resultRujuk.data.dokterBridgs, function(k,v){
			        	// 		if (resultRujuk.data.namaDPJP == v.dokter) {
			        	// 			listDokterDpjp += '<option value="'+v.id+'" selected>'+v.dokter+' ('+v.poli+')</option>';
			        	// 		}else{
			        	// 			listDokterDpjp += '<option value="'+v.id+'">'+v.dokter+' ('+v.poli+')</option>';
			        	// 		}
			        	// 	});
			        	// }
			        	// $('#pilihDokterDpjp').html(listDokterDpjp);
			        	// $('#pilihDokterDpjp').trigger('chosen:updated');
					    // LAYAN DPJP
					    var listDokterDpjpLayan = '<option>.: Dokter DPJP Yang Melayani :. </option>';
			        	// if(resultRujuk.data.dpjp.length > 0){
			        	// 	$.each(resultRujuk.data.dpjp, function(k,v){
			        	// 		if (resultRujuk.data.kodeDPJP == v.kode) {
			        	// 			listDokterDpjpLayan += '<option value="'+v.kode+'" selected>'+v.nama+'</option>';
			        	// 		}else{
			        	// 			listDokterDpjpLayan += '<option value="'+v.kode+'">'+v.nama+'</option>';
			        	// 		}
			        	// 	});
			        	// }
			        	$('#pilihDokterDpjpLayan').html(listDokterDpjpLayan);
			        	$('#pilihDokterDpjpLayan').trigger('chosen:updated');
				    }
		        });
						var dateAwal = "{{date('Y-m-d', strtotime("-89 day", strtotime(date('Y-m-d'))))}}";
						var dateAkhir = "{{date('Y-m-d')}}";
		        $.post("{!! route('cariHistorySEP') !!}",{no:nobpjs,dateAwal:dateAwal,dateAkhir:dateAkhir}).done(function(result){
							if (result.metaData.code == '200') {
								var dat ='';
								var i = 1;
								$.each(result.response.histori, function(c,v){
									var jnsPelayanan = v.jnsPelayanan;
									if (jnsPelayanan == 1) {jnsPelayanan = "Rawat Inap";}else {jnsPelayanan = "Rawat Jalan";}
									var poliTujSep = v.poliTujSep;
									if (poliTujSep == null) {poliTujSep = "-";}else {v.poliTujSep}
									dat += '<tr>'+
										'<td>'+i+'</td>'+
										'<td>'+v.poli+'</td>'+
										'<td>'+v.noSep+'</td>'+
										'<td>'+v.namaPeserta+'</td>'+
										'<td>'+v.noKartu+'</td>'+
										'<td>'+jnsPelayanan+'</td>'+
										'<td>'+v.noRujukan+'</td>'+
										'<td>'+v.tglSep+'</td>'+
										'<td>'+v.ppkPelayanan+'</td>'+
										`</tr>`;
									i++;
								});

								$('#resulthistory2').html(dat);
							}else {
								$('#resulthistory2').html('<tr><td colspan="9">'+result.metaData.message+'</td></tr>');
							}
		        });
			}
		});
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
						var nocust = data.KodeCust;
						$('#nobpjs_'+nocust+'').val(data.FieldCust1)
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
