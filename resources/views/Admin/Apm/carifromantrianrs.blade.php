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
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i> Cari Antrian APM Hari Ini </h4>
			</div>
			<div class="modal-body">
				<div>
					<form>
						<div class="from-group">
							<label>Masukkan <span class="filterSearch">No RM</span> &nbsp;</label>
							<input type="text" name="nomor" style="width:200px !impoortant; margin: 10px auto;" id="cari">
							<input type="hidden" name="kategaori" id="kat" value="norm">
						</div>
					</form>
				</div>
				<section class="panel panel-default m-b-0">
					<div class="panel-body" style="overflow-y: scroll;height: 400px;">
						<table border="1" class="blok">
							<thead>
								<tr>
									<th width="100" ondblclick="noAntrian()">No Urut Poli</th>
									<th width="50%" ondblclick="nama()">Nama Pasien</th>
									<th width="150" ondblclick="pendaftaran()">Pendaftaran</th>
									<th width="250" ondblclick="noRM()">No RM</th>
									<th width="250" ondblclick="noBPJS()">No BPJS</th>
									<th width="50%" ondblclick="tjPoli()">Tujuan Poli</th>
								</tr>
							</thead>
							<tbody id="result"></tbody>
						</table>
					</div>
				</section>
				<ul class="pagination" style="margin: 0px"></ul>
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
		reloadDataDiagnosa('','',0);
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
		$.post("{!! route('cariAntrianApmrs') !!}",{key:key, kat:kat,gopage:page}).done(function(result){
			if(result.status == 'success'){
				$('#result').empty();
				$('.pagination').empty();
				if(result.data.length > 0){
					var dat = '';
					$.each(result.data, function(c,v){
						var nobpjs = (v.nobpjs) ? v.nobpjs  : '-';
						var klik = "'"+v.norm+"','"+nobpjs+"','"+v.nourut+"','"+v.tanggal+"','"+v.poli+"','"+v.urutpoli+"','"+v.norujukan+"','"+v.KodeDPJP+"','"+v.namaDPJP+"','"+v.notelp+"'";
						var klikUmum = "'"+v.norm+"','"+v.nourut+"','"+v.tanggal+"'";
						if (v.status == 'Sudah') {
							var stProc = 'style="background:#ccc;"';
						}else{
							// if (v.nobpjs != '') {
							if (v.penanggung != 'UMUM' || v.penanggung != '' || v.penanggung != 'JAMKESDA' || v.penanggung != 'PT POS' || v.penanggung != 'PT MENTARI' || v.penanggung != 'ACC DIREKTUR' || v.penanggung != 'JAMKESMAS' || v.penanggung != 'PT KAI' || v.penanggung != 'INHEALTH' || v.penanggung != 'PERUSAHAAN' || v.penanggung != 'JAMKESPROV' || v.penanggung != 'JASA RAHARJA' || v.penanggung != 'JKD KAB MJK' || v.penanggung != 'RENC BPJS' || v.penanggung != 'AMBIL OBAT' || v.penanggung != 'BPJS TENAGA KERJA') {
								var stProc = 'ondblclick="getdata('+klik+')"';
							}else{
								var stProc = 'ondblclick="prosesUmum('+klikUmum+')"';
							}
						}
						dat += '<tr '+stProc+'><td>'+v.urutpoli+'</td>'+
								'<td>'+v.nama+'</td>'+
								'<td>'+v.pendaftaran+'</td>'+
								'<td>'+v.norm+'</td>'+
								'<td>'+nobpjs+'</td>'+
								'<td>'+v.poli+'</td>'+
								'<tr>';
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
					dat += '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><tr>';
				}
				$('#result').html(dat);
			}
		});
	}

	function pagin(gopage){
		var key = $('#cari').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,gopage);
	}

	function getdata(KodeCust,nobpjs, nourut, tgl, poli, urutpoli, norujukan, kodeDPJP, namaDPJP, tlpPas){
		$.post("{!! route('getpasienrs') !!}",{key:KodeCust,nobpjs:nobpjs}).done(function(result){
			$('#detail-dialog').modal('hide');
			if(result.status == 'success'){
				$('#namapasien').val(result.data.NamaCust);
				$('#noUrut').val(nourut);
				$('#urutpoli').val(urutpoli);
				$('#tgl_apm').val(tgl);
				$('#rm').val(result.data.KodeCust);
				$('#nokepesertaan').val(nobpjs);
				$('#nRujuk').val(norujukan);
				$('#kdDpjp').val(kodeDPJP);
				$('#dpjpLayan').val(kodeDPJP);
				$('#dpjp_rujuk').val(namaDPJP);
				$('#alamatpasien').val(result.data.Alamat);
				if(result.data.JenisKel == "L"){
	        		$('#L').attr('checked','checked');
	        	}else{
	        		$('#P').attr('checked','checked');
	        	}

	        	$('#cek').html(result.data.JenisKel);
	        	$('#notelp').val(tlpPas);

				$.post("{!! route('cekpeserta') !!}",{nobpjs:nobpjs}).done(function(result){
					 if(result.response){
			        	var kdcb = (result.response.peserta.provUmum.kdCabang) ? result.response.peserta.provUmum.kdCabang : '';
			    		var nmcb = (result.response.peserta.provUmum.nmCabang) ? result.response.peserta.provUmum.nmCabang : '';
			        	$('#nama_bpjs').html(result.response.peserta.nama);
			        	$('#tgl_bpjs').html(result.response.peserta.tglLahir);
			        	$('#sex_bpjs').html(result.response.peserta.sex);
			        	$('#kdprovbpjs').html(result.response.peserta.provUmum.kdProvider);
			        	$('#nmprovbpjs').html(result.response.peserta.provUmum.nmProvider);
			        	$('#ppk-rujukan').val(result.response.peserta.provUmum.kdProvider);
			        	// $('#kdcb_bpjs').html(kdcb);
			        	// $('#nmcb_bpjs').html(nmcb);
			        	$('#jnps_bpjs').html(result.response.peserta.jenisPeserta.keterangan);
			        	$('#kltg_bpjs').html(result.response.peserta.hakKelas.keterangan);
						$('#tgllahir').val(result.response.peserta.tglLahir);
			        	$('#kelasrawat').val(result.response.peserta.hakKelas.kode);
			        	var u = result.response.peserta.umur.umurSekarang;
			        	var cob = (result.response.peserta.noAsuransi) ? '1' : 0;
			        	$('#umur').val(u.substr(0,2));
			        	$('#cob').val(cob);

			        	// $('#notelp').val(result.response.peserta.mr.noTelepon);
			        	$('#telpbpjs').html(result.response.peserta.mr.noTelepon);
			        	$('#cetakbpjs').html(result.response.peserta.tglCetakKartu);
			        	$('#statusbpjs').html(result.response.peserta.statusPeserta.keterangan);

			        	$('#btn-sep').removeAttr('disabled');

			        }else{
			        	$('#nama_bpjs').html('');
			        	$('#sex_bpjs').html('');
			        	$('#tgl_bpjs').html('');
			        	$('#kdprov_bpjs').html('');
			        	$('#nmprov_bpjs').html('');
			        	$('#kdcb_bpjs').html('');
			        	$('#nmcb_bpjs').html('');
			        	$('#jnps_bpjs').html('');
			        	$('#kltg_bpjs').html('');
			        }
		        });

		        // $.post("{!! route('getNoSurat') !!}",{noRujuk:norujukan,rm:result.data.KodeCust}).done(function(result){
					// if(result.status == 'success'){
					// 	$('#noSurat').val(result.noSurat);
			        // }else{
			        // 	$('#noSurat').val('');
			        // }
		        // });

		        $.post("{!! route('cekrujukan') !!}",{noRujuk:norujukan}).done(function(resultRujuk){
			        if(resultRujuk.data.rujukan.response){
			        	$('.tglRujuk').val(resultRujuk.data.rujukan.response.rujukan.tglKunjungan);
			        	$('#ppk-rujukan').val(resultRujuk.data.rujukan.response.rujukan.provPerujuk.kode);
			        	$('#tingkatRujuk').val(resultRujuk.data.tingkatRujuk);
			        	$('#valDiagnosa').val(resultRujuk.data.rujukan.response.rujukan.diagnosa.kode);
			        	$('#textDiagnosa').text(resultRujuk.data.rujukan.response.rujukan.diagnosa.nama);
			        }else{
			        	$('.tglRujuk').val('');
			        	$('#tingkatRujuk').val('1');
			        }
			        // var listDokterDpjp = '<option>.: Pilih Dokter DPJP :. </option>';
		        	// if(resultRujuk.data.dokterBridgs.length > 0){
		        		// $.each(resultRujuk.data.dokterBridgs, function(k,v){
		        		// 	if (namaDPJP == v.dokter) {
		        		// 		listDokterDpjp += '<option value="'+v.id+'" selected>'+v.dokter+' ('+v.poli+')</option>';
		        		// 	}else{
		        		// 		listDokterDpjp += '<option value="'+v.id+'">'+v.dokter+' ('+v.poli+')</option>';
		        		// 	}
		        		// });
		        	// }
		        	// $('#pilihDokterDpjp').html(listDokterDpjp);
		        	// $('#pilihDokterDpjp').trigger('chosen:updated');
		        });

		        $.post("{!! route('gethistorypasien') !!}",{norm:KodeCust}).done(function(result){
		        	if(result.history){
		        		var dat ='';
		        		var i = 1;

			        	$.each(result.history, function(c,v){
							dat += '<tr>'+
									'<td>'+i+'</td>'+
									'<td>'+v.Nama_Pasien+'</td>'+
									'<td>'+v.RJ+'</td>'+
									'<td>'+v.NAMAPOLI+'</td>'+
									'<td>'+v.No_Register+'</td>'+
									'<td>'+v.TANGGAL+'</td>'+
									'<td>'+v.NamaAsuransi+'</td></tr>';
									i++;
						});

						$('#resulthistory').html(dat);
		        	}
		        });

						$.post("{!! route('getpolinama') !!}",{key:poli}).done(function(result){
							$('#kdpoli').val(result.data.kdpoli);
							$('#namapoli').html(result.data.NamaPoli);
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

	function prosesUmum(KodeCust,noUrut,tanggal) {
		$.post("{!! route('prosesAntrianUmum') !!}",{key:KodeCust,noUrut:noUrut,tanggal:tanggal}).done(function(result){
			if(result.status == 'success'){
				swal({
					title : "Berhasil !!",
					text: result.messages,
					type : "success",
					timer : 2000,
				});
				var key = $('#cari').val();
				var kat = $('#kat').val();
				reloadDataDiagnosa(key,kat,0);
			}else{
				swal({
					title : "Maaf !!",
					text: result.messages,
					type : "warning",
					timer : 2000,
				});
			}
		});
	}

	function noAntrian(){
		$('#kat').val('urutpoli');
		$('.filterSearch').html('No Urut Poli');
	}
	function nama(){
		$('#kat').val('nama');
		$('.filterSearch').html('Nama ');
	}
	function pendaftaran(){
		$('#kat').val('pendaftaran');
		$('.filterSearch').html('Pendaftaran');
	}
	function noRM(){
		$('#kat').val('norm');
		$('.filterSearch').html('No RM');
	}
	function noBPJS(){
		$('#kat').val('nobpjs');
		$('.filterSearch').html('No BPJS');
	}
	function tjPoli(){
		$('#kat').val('poli');
		$('.filterSearch').html('Tujuan Poli');
	}
</script>
