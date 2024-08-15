@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
		/* ALL LOADERS */
		.loader{
			width: 100px;
			height: 100px;
			border-radius: 100%;
			position: relative;
			margin: 0 auto;
		}

		/* LOADER 4 */
		#loader-4 span{
			display: inline-block;
			width: 20px;
			height: 20px;
			border-radius: 100%;
			background-color: #3498db;
			margin: 35px 5px;
			opacity: 0;
		}
		#loader-4 span:nth-child(1){
			animation: opacitychange 1s ease-in-out infinite;
		}
		#loader-4 span:nth-child(2){
			animation: opacitychange 1s ease-in-out 0.33s infinite;
		}
		#loader-4 span:nth-child(3){
			animation: opacitychange 1s ease-in-out 0.66s infinite;
		}
		@keyframes opacitychange{
			0%, 100%{
				opacity: 0;
			}

			60%{
				opacity: 1;
			}
		}
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			DAFTAR ANTRIAN
		</h1>
	</section>

	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		<div class="box box-primary main-layer">
			<div class="panel panel-default">
				<div class="panel-body">
					<table id="dataTable" class="table table-striped dataTable display nowrap" style="width: 100%;">
						<thead class="text-center">
							<tr>
								<th>No Antrian</th>
								<th>No Antrian Poli</th>
								<th>Nama Pasien</th>
								<th>Alamat</th>
								<th>Jenis Pasien</th>
								<th>Poli Tujuan</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="other-page"></div>
		<div class="printSEP"></div>
	</div>
	
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@stop
@section('script')
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.2/js/bootstrap.min.js" integrity="sha512-5BqtYqlWfJemW5+v+TZUs22uigI8tXeVah5S/1Z6qBLVO7gakAOtkOzUtgq6dsIo5c0NJdmGPs0H9I+2OHUHVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
		$(function(){
			$(".datepicker").datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true,
				todayHighlight: true,
			})
		})

		// Action Batal
		function batalkan(kode) {
			swal({
				title: "Konfirmasi Batal!",
				text: "KODE BOOKING :" + kode,
				type: "input",
				showCancelButton: true,
				closeOnConfirm: false,
				inputPlaceholder: "Alasan Batal..."
			}, function (inputValue) {
				if (inputValue === false) return false
				if (inputValue === "") {
					swal.showInputError("Inputan Tidak Boleh Kosong!")
					return false
				} else {
					var input = inputValue
					$.ajax({
						type: "post",
						url: "{{route('batalAntrian')}}",
						data: {
							keterangan:input,
							kodebooking:kode
						},
						success: function (response) {
							if(response.metaData.code == 200){
								swal("Berhasil!", "Antrian Berhasil Dibatalkan", "success")
								location.reload();
							}else{
								swal("Warning!", response.metaData.message, "error")
							}
						}
					})
				}
			})
		}

		function loadTable(){
			var loading = '<div class="loader" id="loader-4"><span></span><span></span><span></span></div>'
			var x = $('#dataTable').dataTable({
				scrollX: true,
				processing: true,
				serverSide: true,
				language: {
					processing: loading,
				},
				columnDefs: [{
					orderable: false,
					targets: -1
				}],
				ajax: {
					url: "{{route('counter.formListCounter')}}",
					type: 'get',
				},
				columns: [
					{data: 'no_antrian', name: 'no_antrian'},
					{
						data: 'nomor_antrian_poli', 
						name: 'nomor_antrian_poli',
						render: function(data, type, row) {
							return '<p style="color:black">' + ((data=='' || data==null) ? '-' : data) + '</p>';
						}
					},
					{
						data: 'tm_customer.NamaCust', 
						name: 'tm_customer.NamaCust',
						render: function(data, type, row) {
							return '<p style="color:black">' + ((data=='' || data==null) ? '-' : data) + '</p>';
						}
					},
					{data: 'tm_customer.Alamat', name: 'Alamat'},
					{data: 'jenis_pasien', name: 'jenis_pasien'},
					{data: 'mapping_poli_bridging.tm_poli.NamaPoli', name: 'NamaPoli'},
					{data: 'action', name: 'status'},
				],
			})
		}

		$(document).ready(()=>{
			loadTable()
		})

		function namaPasien(rowData, rowIndex){
			var namaCust = datagrid.getRowData(rowIndex).NamaCust;
			if(!namaCust){
				var html = "<span>-</span>"
			}else{
				var html = "<span>"+namaCust+"</span>"
			}
			return html
		}

		function statusAntrian(rowData, rowIndex) {
			var status = datagrid.getRowData(rowIndex).status;
			if(status == 'belum'){
				var html = '<span style="background:#e6943ce0; color:white; border-radius:8px; padding:5px;">Dalam Antrian</span>'
			}else if(status == 'panggil'){
				var html = '<span style="background:#309684; color:white; border-radius:8px; padding:5px;">Dilayani</span>'
			}else{
				var html = '<span>-</span>'
			}
			return html
		}

		function action(rowData, rowIndex) {
			var status = datagrid.getRowData(rowIndex).status;
			var booking = rowData.kode_booking
			var url = '{{route("formListAntrian",":id")}}'
			url = url.replace(':id',booking)
			var html = ''
			if(status == 'belum' ){
				html = '<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="batal(`'+rowData.kode_booking+'`)">Batal Antrian</a>&nbsp;&nbsp;'
				html += '<a href="javascript:void(0)" class="btn btn-xs btn-success m-0" onclick="panggil(`'+rowData.kode_booking+'`)">Panggil</a>'
			}else if(status == 'panggil'){
				html = '<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="batal(`'+rowData.kode_booking+'`)">Batal Antrian</a>&nbsp;&nbsp;'
				html += '<a href="'+url+'" class="btn btn-xs btn-warning m-0" >Kelola Pasien</a>'
			}else{
				var html = '<span>-</span>'
			}
			return html;
		}

		function panggil(id) {
			swal({
				title: 'KONFIRMASI !',
				type: 'info',
				text: 'Apakah Antrian Ingin Dipanggil?',
				confirmButtonClass: "btn-primary",
				confirmButtonText: "Panggil",
				cancelButtonText: "Tidak",
				showCancelButton: true,
			}, function (isConfirm) {
				if(isConfirm){
					var url = "{{route('panggilAntrian')}}";
					$.post(url,{id:id}).done(function(data){
						if(data.status == 'success'){
							swal({
								title: 'Berhasil',
								type: data.status,
								text: data.message,
								showConfirmButton: true,
							})
							datagrid.reload();
						}else{
							swal({
								title: 'Whoops',
								type: data.status,
								text: data.message,
							})
							datagrid.reload();
						}
					})
				}
			})
		}

		function arahkan(param){
			var param = JSON.parse(param)
			var kodebooking = param.kodebooking, nomor_antrian_poli = param.nomor_antrian_poli
			if(nomor_antrian_poli!="" && nomor_antrian_poli !=null){
				swal({
					title: 'KONFIRMASI !',
					type: 'info',
					text: 'Yakin ingin mengarahkan ke Poli?',
					confirmButtonClass: "btn-primary",
					confirmButtonText: "Arahkan ke poli",
					cancelButtonText: "Batal",
					showCancelButton: true,
				},(isConfirm)=>{
					if(isConfirm){
						$.post('{{route("counterToPoli")}}',{kode:kodebooking}).done((res)=>{
							if(res.status == 'success'){
								swal({
									title: 'Berhasil',
									type: 'success',
									text: 'Pasien berhasil diarahkan ke poli',
									showConfirmButton: false,
									timer: 1200
								})
								$('#dataTable').DataTable().ajax.reload()
							}else{
								swal({
									title: 'Whoops',
									type: res.status,
									text: res.message,
								})
								$('#dataTable').DataTable().ajax.reload();
							}
						})
					}
				})
			}else{
				swal({
					title: 'KONFIRMASI !',
					type: 'info',
					text: 'Apakah Pasien Ingin Generate No. Antrian?',
					confirmButtonClass: "btn-primary",
					confirmButtonText: "Ya",
					cancelButtonText: "Tidak",
					showCancelButton: true,
				},(isConfirm)=>{
					if(isConfirm){
						$.post("{!! route('generateAntrianCounter') !!}", {id:kodebooking}).done(function(data){
							if(data.status == 'success'){
								var id = data.data.nomor_antrian_poli;
								swal({
									title: 'Berhasil !',
									type: 'success',
									html: true,
									text: 'Nomor Antrian Pasien berhasil di generate<br><b style="font-size: 20pt;">'+id+'<b>',
									confirmButtonClass: "btn-primary",
									confirmButtonText: "Cetak",
								}, function (isConfirm) {
									if(isConfirm){
										$.post('{{route("counterToPoli")}}',{kode:kodebooking}).done((res)=>{
											if(res.status == 'success'){
											}
										})
										let urlD = '{{route("cetakAntrianKonterPoli", ["id" => ":id" ] )}}';
										const url = urlD.replace(":id", id);
										window.open(url);
										$('.other-page').fadeOut(function() {
											$('.other-page').empty();
											$('.main-layer').fadeIn();
											$('#dataTable').DataTable().ajax.reload();
										});
									}
								})

							} else {
								swal('Whoops!', 'Antrian Gagal Generate No. Antrian', 'warning');
							}
						});
					}
				})
			}
		}

		function pasienBaru(param){
			$.ajax({
				url: '{{route("formListAntrian")}}',
				type: 'get',
				data: {id:param},
				success: function (data) {
				}
			});
		}

		function detail(id) {
			$.post("{!! route('detailListCounter') !!}", {id:id}).done(function(data){
				if(data.status == 'success'){
					$('.main-layer').hide()
					$('.other-page').html(data.content).fadeIn()
				} else {
					$('.main-layer').show()
				}
			})
		}

		function generate(id) {
			swal({
				title: 'KONFIRMASI !',
				type: 'info',
				text: 'Apakah Pasien Ingin Generate No. Antrian?',
				confirmButtonClass: "btn-primary",
				confirmButtonText: "Ya",
				cancelButtonText: "Tidak",
				showCancelButton: true,
			},(isConfirm)=>{
				if(isConfirm){
					$.post("{!! route('generateAntrianCounter') !!}", {id:id}).done(function(data){
						if(data.status == 'success'){
							var id = data.data.nomor_antrian_poli
							swal({
								title: 'Berhasil !',
								type: 'success',
								html: true,
								text: 'Nomor Antrian Pasien berhasil di generate<br><b style="font-size: 20pt;">'+id+'<b>',
								confirmButtonClass: "btn-primary",
								confirmButtonText: "Cetak",
							}, function (isConfirm) {
								if(isConfirm){
									let urlD = '{{route("cetakAntrianKonterPoli", ["id" => ":id" ] )}}'
									const url = urlD.replace(":id", id)
									window.open(url)
									$('.other-page').fadeOut(function() {
										$('.other-page').empty()
										$('.main-layer').fadeIn()
										$('#dataTable').DataTable().ajax.reload()
									})
								}
							})
						} else {
							swal('Whoops!', 'Antrian Gagal Generate No. Antrian', 'warning');
						}
					})
				}
			})
		}

		function cetaksep(param) {
			$.post("{{ route('cetak_sep_ulang') }}", {KodeCust:param}).done(function(data){
				var telp = '-';
				var fakses = (data.data.respon.response.peserta.jnsPeserta) ? data.data.respon.response.peserta.jnsPeserta : '-';
				var penjamin = (data.data.respon.response.klsRawat.penanggungJawab) ? data.data.respon.response.klsRawat.penanggungJawab : '-';
				var diag = data.data.sepValue.diagnosa;
				var peserta = (data.data.respon.response.peserta.jnsPeserta) ? data.data.respon.response.peserta.jnsPeserta : '-';
				var noKontrol = (data.data.respon.response.kontrol.noSurat) ? data.data.respon.response.kontrol.noSurat : '-';
				var prb = (data.data.sepValue.prb) ? data.data.sepValue.prb : '-';
				var kelas_naik = (data.data.sepValue.kls_rawatNaik) ? data.data.sepValue.kls_rawatNaik : '-';
				var nosep = data.data.respon.response.noSep;
				var noarsip = data.data.sepValue.noarsip;
				var noRujuk = (data.data.respon.response.noRujukan) ? data.data.respon.response.noRujukan : '-';

				if(data.code == 200){
					var cetak = '';
					var css = '@page { size: auto !important; }',
					head = document.head || document.getElementsByTagName('head')[0],
					style = document.createElement('style');

					style.type = 'text/css';
					style.media = 'print';

					if (style.styleSheet){
						style.styleSheet.cssText = css;
					} else {
						style.appendChild(document.createTextNode(css));
					}

					head.appendChild(style);
					var stTitle = 'height="20px" style="font-size:18px;"';
					var stIsi = 'height="20px" style="font-size:16px;"';
					var widthJd1 = 'width="135px;"';
					var widthIsi1 = 'width="400px;"';
					var widthJd2 = 'width="115px;"';
					var widthIsi2 = 'width="300px;"';
					var stBerkas = 'style="font-size:16px;padding-left: 50px;"';
					var stPasKel = 'style="font-size: 14px;padding-left: 35px;"';
					var stKet = 'style="font-size: 12px;"';
					var stCat2 = 'height="20px" style="font-size:12px;"';
					for (var p = 0; p < 1; p++) {
						cetak += '<!DOCTYPE html><html><head><meta charset="utf-8"><meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">';
						cetak += '<table border="0">';
						cetak += '<tr>';
						cetak += '<td rowspan="2" width="250px">';
						var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
						cetak += '<img src="'+logoBpjs+'" width="230px" style="margin-left: 5px;">';
						cetak += '</td>';
						cetak += '<td '+stTitle+' colspan="2">SURAT ELEGIBILITAS PESERTA</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+stTitle+'>RSUD DR. W. SUDIROHUSODO</td>';
						// cetak += '<td '+stBerkas+'>No.Berkas &nbsp : '+data.data.sepValue["noarsip"]+'</td>';
						cetak += '</tr>';
						cetak += '</table>';
						cetak += '<div style="margin-bottom: 5px;"></div>';
						cetak += '<table border="0">';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>No.SEP</td>';
						cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["no_sep"]+'</td>';
						cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
						cetak += '<td '+widthIsi2+' '+stIsi+'>'+prb+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>Tgl.SEP</td>';
						cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["tgl_sep"]+'</td>';
						cetak += '<td '+widthJd2+' '+stIsi+'>Peserta</td>';
						cetak += '<td '+widthIsi2+' '+stIsi+'>: '+peserta+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>No.Kartu</td>';
						cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["no_kartu"]+' ( MR. '+data.data.sepValue["noMr"]+' )</td>';
						cetak += '<td '+widthJd2+' '+stIsi+'>COB</td>';
						cetak += '<td '+widthIsi2+' '+stIsi+'>: -</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>Nama Peserta</td>';
						cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["nama_kartu"]+'</td>';
						cetak += '<td '+widthJd2+' '+stIsi+'>Jns.Rawat</td>';
						cetak += '<td '+widthIsi2+' '+stIsi+'>: '+data.data.sepValue["jenis_rawat"]+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>Tgl.Lahir</td>';
						if (data.data.sepValue["jenis_kelamin"] == "L") {
							var JKlamin = 'LAKI - LAKI';
						}else{
							var JKlamin = 'PEREMPUAN'
						}
						cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["tgl_lahir"]+' &nbsp Kelamin : '+JKlamin+'</td>';
						cetak += '<td '+widthJd2+' '+stIsi+'>Kls.Rawat</td>';
						cetak += '<td '+widthIsi2+' '+stIsi+'>: '+data.data.sepValue["kls_rawat"]+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>No.Telepon</td>';
						cetak += '<td '+widthIsi1+' '+stIsi+'>: '+telp+'</td>';
						cetak += '<td '+widthJd2+' '+stIsi+'>Penjamin</td>';
						cetak += '<td '+widthIsi2+' '+stIsi+'>: '+((data.data.respon.response.penjamin) ? data.data.respon.response.penjamin : '-' )+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>Poli Tujuan</td>';
						cetak += '<td '+widthIsi1+' '+stIsi+'>: '+data.data.sepValue["poli_tujuan"]+'</td>';
						cetak += '<td '+widthJd2+' '+stIsi+'>No. Kontrol</td>';
						cetak += '<td '+widthIsi2+' '+stIsi+'>: '+noKontrol+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>Faskes Perujuk</td>';
						cetak += '<td '+widthIsi1+' '+stIsi+'>: '+fakses+'</td>';
						cetak += '<td '+widthJd2+' '+stIsi+'>No. Rujukan</td>';
						cetak += '<td '+widthIsi2+' '+stIsi+'>: '+noRujuk+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>Diagnosa Awal</td>';
						cetak += '<td colspan="3" '+stIsi+'>: '+diag+' - '+data.data.sepValue["diagnosa"]+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd1+' '+stIsi+'>Catatan</td>';
						cetak += '<td colspan="3" '+stIsi+'>: '+data.data.sepValue["catatan"]+'</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td '+widthJd2+' '+stIsi+'>Kls.Naik</td>';
						cetak += '<td colspan="3" '+stIsi+'>: '+kelas_naik+'</td>';
						cetak += '</tr>';
						cetak += '<tr><td colspan="4" height="5"></td></tr>';
						cetak += '<tr>';
						cetak += '<td colspan="3">&nbsp</td>';
						cetak += '<td '+stPasKel+'>Pasien/Keluarga Pasien</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td colspan="3" '+stKet+' valign="bottom">*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan.</td>';
						cetak += '<td '+stPasKel+' valign="bottom" height="25px">_______________</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td colspan="3" '+stKet+' valign="top">SEP Bukan sebagai bukti penjaminan peserta.</td>';
						cetak += '<td>&nbsp</td>';
						cetak += '</tr>';
						cetak += '<tr>';
						cetak += '<td colspan="4" '+stCat2+'>Cetakan ke 1 '+data.data.sepValue["jam"]+'</td>';
						cetak += '</tr>';
						cetak += '</table>';
						cetak += '<div style="page-break-before: always;"></div>';
						cetak += '<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"><\/script></body></html>';
					}
						$('.main-layer').hide();
						// $('.other-page').html(data.content).fadeIn();
						$('.printSEP').html(cetak).fadeIn();
						$('.printSEP').printArea();
						setTimeout(function tutupCetak() {
							$('.printSEP').empty();
							location.reload(true);
						}, 1000);
				} else {
					swal({
						title : "Maaf !!",
						text: data.metaData.message,
						type : "warning",
						timer : 2000,
						showConfirmButton : false
					});
				}
			});
		}

		function tracer(param) {
			var arrParam = JSON.parse(param)
			var id = arrParam.id;
			var noPoli = arrParam.nomor_antrian_poli;
			if (noPoli == null) {
				swal('Peringatan!!', 'Antrian Belum Generate No Antrian Poli.', 'warning')
			} else {
				swal({
					title: 'KONFIRMASI !',
					type: 'info',
					text: 'Apakah Pasien Ingin Mencetak Tracer ?',
					confirmButtonClass: "btn-primary",
					confirmButtonText: "Ya",
					cancelButtonText: "Tidak",
					showCancelButton: true,
				},(isConfirm)=>{
					if(isConfirm){
						let urlD = '{{route("cetakTracerPasienPoli", ["id" => ":id" ] )}}'
						const url = urlD.replace(":id", id)
						window.open(url)
						swal('Berhasil', 'Antrian Berhasil Mencetak Tracer.', 'success')
						location.reload()
					}
				})
				
			}
		}
	</script>
@stop