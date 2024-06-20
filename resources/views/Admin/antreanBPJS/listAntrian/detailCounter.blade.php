<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
	<div class="box box-primary detailCounter">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="card strpied-tabled-with-hover detail-counter">
					<div class="card-header ">
						<h4 style="text-decoration: underline; text-decoration-color: #3c8dbc;">
							Detail Pasien Konter Poli
						</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<input type="hidden" name="id_antrian" id="id_antrian" value="{{ (isset($data) ? $data->id : '')}}">
							<div class="col-md-1"></div>
							<div class="col-10">
								<table class="table table-striped">
									<tr>
										<td>No. Antrian  :</td>
										<td>{{$data->no_antrian}}</td>
									</tr>
									<tr>
										<td>No. Antrian Poli :</td>
										<input type="hidden" id="nomorAntrianPoli" value="{{(($data->nomor_antrian_poli==null||$data->nomor_antrian_poli=='')?'':$data->nomor_antrian_poli )}}">
										<td>
											<div class="row">
												<div class="col-md-1" id="nopoli">
													{{ (($data->nomor_antrian_poli==null || $data->nomor_antrian_poli=='') ? '-':$data->nomor_antrian_poli  ) }}
												</div>
												<div class="col-md-11" id="tempat-generate">
													@if ($data->nomor_antrian_poli==null || $data->nomor_antrian_poli=='')
													<button style="margin-left: 50px;" type="button" class="btn btn-sm btn-success btn-generate" onclick="generate(`{{$data->id}}`)"> Generate No</button>
													@endif
												</div>
											</div>
										</td>
										{{-- <td><button type="button" class="btn btn-success"> Generate No</button></td> --}}
									</tr>
									<tr>
										<td>Kode Booking  :</td>
										<td>{{$data->kode_booking}}</td>
									</tr>
									<tr>
										<td>No. RM  :</td>
										<td>{{$data->no_rm}}</td>
									</tr>
									<tr>
										<td>NIK  :</td>
										<td>{{$data->nik}}</td>
									</tr>
									<tr>
										<td>Nama  :</td>
										<td>{{$data->tm_customer->NamaCust}}</td>
									</tr>
									<tr>
										<td>Tanggal Lahir  :</td>
										<td>{{$data->tm_customer->TglLahir}}</td>
									</tr>
									<tr>
										<td>Alamat  :</td>
										<td>{{$data->tm_customer->Alamat}}</td>
									</tr>
									<tr>
										<td>No. Telepon  :</td>
										<td>{{$data->tm_customer->Telp}}</td>
									</tr>
									<tr>
										<td>Jenis Pasien  :</td>
										<td>{{$data->jenis_pasien}}</td>
									</tr>
									<tr>
										<td>Poli Tujuan  :</td>
										<td>
											<select name="poli" id="poli" class="form-control" style="width: 50%">
												<option value="">.:: Pilih Poli ::.</option>
												@if($poli->count()!=0)
												@foreach($poli as $poli)
												<option @if($poli->kdpoli==$data->kode_poli) selected @endif value="{{$poli->kdpoli}}">{{$poli->NamaPoli}}</option>
												@endforeach
												@endif
											</select>
										</td>
									</tr>
									<tr>
										<td>Kategori Pasien  :</td>
										<td>{{ ($data->is_geriatri=='Y') ? 'Disabilitas':'Normal' }}</td>
									</tr>
									<tr>
										<td>Sumber Daftar  :</td>
										<td>{{$data->metode_ambil}}</td>
									</tr>
								</table>
							</div>
							<div class="col-md-1"></div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<button class="btn btn-secondary btn-cancel" style="width: 100%">Kembali</button>
								</div>
								@if ($data->jenis_pasien=='BPJS')
								<div class="col-md-3">
									<button class="btn btn-warning btn-sep" style="width: 100%" onclick="buatsep({{$data->id}})">Buat SEP</button>
								</div>
								@else
								<div class="col-md-3"></div>
								@endif
								<div class="col-md-3">
									<button class="btn btn-danger" style="width: 100%" onclick="batalkan(`{{$data->kode_booking}}`)">Batal</button>
								</div>
								<div class="col-md-3">
									<button class="btn btn-success" style="width: 100%" onclick="arahkan(`{{$data->id}}`)">Kirim Ke Poli</button>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="panel-footer">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// KEMBALI
	$('.btn-cancel').click(function(e) {
		e.preventDefault();
		$('detailCounter').fadeOut();
		$('.other-page').fadeOut(function() {
			$('.other-page').empty();
			$('.main-layer').fadeIn();
		});
		location.reload();
	});

	// GENERATE
	function generate(id) {
		var poli = $('#poli').val();
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
				$.post("{!! route('generateAntrianCounter') !!}", {id:id, poli:poli}).done(function(data){
					if(data.status == 'success'){
						var nopoli = data.data.nomor_antrian_poli
						var id = data.data.id
						swal('Success', 'Antrian Berhasil Generate No. Antrian', 'success')
						$('#nomorAntrianPoli').val(nopoli)
						$('#nopoli').html(nopoli)
						$('.btn-generate').hide();
						swal({
							title: 'Berhasil !',
							type: 'success',
							html: true,
							text: 'Nomor Antrian Pasien berhasil di generate<br><b style="font-size: 20pt;">'+nopoli+'<b>',
							confirmButtonClass: "btn-primary",
							confirmButtonText: "Cetak",
						}, function (isConfirm) {
							if(isConfirm){
								let urlD = '{{route("cetakAntrianKonterPoli", ["id" => ":id" ] )}}'
								const url = urlD.replace(":id", id)
								window.open(url)
							}
						})
					} else {
						swal('Whoops!', 'Antrian Gagal Generate No. Antrian', 'warning')
					}
				})
			}
		})
	}

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

	// BUAT SEP
	function buatsep(id) {
		window.location.href = '{{ route("bridging") }}?id='+id;
	}

	// KIRIM KE KONTER POLI
	function arahkan(param) {
		var poli = $('#poli').val();
		var nomorAntrianPoli = $('#nomorAntrianPoli').val()
		if(nomorAntrianPoli){
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
					$.post('{{route("counterToPoli")}}',{kode:param, kode_poli:poli}).done((res)=>{
						if(res.status == 'success'){
							swal({
								title: 'Berhasil!',
								type: 'success',
								text: 'Antrian Berhasil Diarahkan Ke Poli',
							})
							$('.other-page').fadeOut(function() {
								$('.other-page').empty();
								$('.main-layer').fadeIn();
								$('#dataTable').DataTable().ajax.reload();
							});
						}else{
							swal({
								title: 'Whoops',
								type: res.status,
								text: res.message,
							})
						}
					})
				}
			})
		}else{
			swal({
				type: 'warning',
				title: 'Gagal mengarahkan',
				text: 'Nomor antrian poli belum di generate'
			})
		}
	}

	$('#poli').change(function(){
		var id = $('#id_antrian').val();
		var noPoli = $('#nomorAntrianPoli').val();
		swal({
			title:"Konfirmasi !",
			text:"Apakah anda yakin ingin merubah poli tujuan?",
			type:"info",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Saya yakin!",
			cancelButtonText: "Batal!",
		},
		function(){
			$.post("{!! route('resetCounter') !!}",{id:id, noPoli:noPoli}).done(function(data){
				if (data.status == 'success') {
					$('#nopoli').html('-')
					// $('#tempat-generate').empty()
					$('#tempat-generate').html('<button style="margin-left: 50px;" type="button" class="btn btn-sm btn-success btn-generate" onclick="generate(`'+id+'`)"> Generate No</button>')
					$('.btn-generate').show()
					swal('Berhasil', data.message, 'success');
				}else{
					swal('Gagal', data.message, 'error');
				}
			})
		})
	});
</script>