<div class="modal fade" id="pindah-poli" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" 
	{{-- data-bs-backdrop="static" --}}
	data-backdrop="static"
	{{-- data-keyboard="false" --}}
	{{-- data-bs-keyboard="true" --}}
>
	<div class="modal-dialog" role="document" style="width:70%;">
		<div class="modal-content">
			<div class="modal-header modalHeaderGreen">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<input type="hidden" name="id_antrian" id="id_antrian" value="{{ (isset($data) ? $data->id : '')}}">
					<div class="col-md-12">
						<table class="table table-striped">
							{{-- <tr>
								<td colspan="3"><div class="loader-edit-antrian" id="loader-2"><span></span><span></span><span></span></div></td>
							</tr> --}}
							<tr> {{-- nomor antrian --}}
								<td>No. Antrian</td>
								<td>:</td>
								<td>{{$data->no_antrian}}</td>
							</tr>
							<tr> {{-- nomor antrian poli --}}
								<td>No. Antrian Poli</td>
								<td>:</td>
								<input type="hidden" id="nomorAntrianPoli" value="{{(($data->nomor_antrian_poli==null||$data->nomor_antrian_poli=='')?'':$data->nomor_antrian_poli )}}">
								<td>
									<div class="row">
										<div class="col-md-1" id="nopoli">
											{{ (($data->nomor_antrian_poli==null || $data->nomor_antrian_poli=='') ? '-':$data->nomor_antrian_poli  ) }}
										</div>
										<div class="col-md-11" id="tempat-generate">
											{{-- @if ($data->nomor_antrian_poli==null || $data->nomor_antrian_poli=='') --}}
											<button style="margin-left: 50px; {{$data->nomor_antrian_poli==null || $data->nomor_antrian_poli=='' ? '' : 'display: none;'}}" type="button" class="btn btn-sm btn-success btn-generate" onclick="generate(`{{$data->id}}`)"> Generate No</button>
											{{-- @endif --}}
										</div>
									</div>
								</td>
							</tr>
							<tr> {{-- kode booking --}}
								<td>Kode Booking</td>
								<td>:</td>
								<td>{{$data->kode_booking}}</td>
							</tr>
							<tr> {{-- nomor rm --}}
								<td>No. RM</td>
								<td>:</td>
								<td>{{$data->no_rm}}</td>
							</tr>
							<tr> {{-- nik --}}
								<td>NIK</td>
								<td>:</td>
								<td>{{$data->nik}}</td>
							</tr>
							<tr> {{-- nama --}}
								<td>Nama</td>
								<td>:</td>
								<td>{{$data->tm_customer ? $data->tm_customer->NamaCust : '-'}}</td>
							</tr>
							<tr> {{-- tanggal lahir --}}
								<td>Tanggal Lahir</td>
								<td>:</td>
								<td>{{$data->tm_customer ? $data->tm_customer->TglLahir : '-'}}</td>
							</tr>
							<tr> {{-- alamat --}}
								<td>Alamat</td>
								<td>:</td>
								<td>{{$data->tm_customer ? $data->tm_customer->Alamat : '-'}}</td>
							</tr>
							<tr> {{-- nomor telepon --}}
								<td>No. Telepon</td>
								<td>:</td>
								<td>{{$data->tm_customer ? $data->tm_customer->Telp : '-'}}</td>
							</tr>
							{{-- jenis pasien --}}
							<tr>
								<td>Jenis Pasien</td>
								<td>:</td>
								<td>
									<input type="hidden" id="jenis-pasien" value="{{$data->jenis_pasien}}">
									<input type="hidden" id="jenis-pasien-temp" value="{{$data->jenis_pasien}}">
									<select name="jenis_pasien" id="jenis_pasien" class="form-control select2" style="width: 75%;">
										<option value="" disabled>-- JENIS PASIEN --</option>
										<option @if(isset($data) && $data->jenis_pasien == 'UMUM') selected @endif value="UMUM">UMUM</option>
										<option @if(isset($data) && $data->jenis_pasien == 'BPJS') selected @endif value="BPJS">BPJS</option>
										<option @if(isset($data) && $data->jenis_pasien == 'ASURANSILAIN') selected @endif value="ASURANSILAIN">ASURANSI LAINNYA</option>
									</select>
								</td>
							</tr>
							{{-- pembayaran pasien --}}
							<tr>
								<td>PEMBAYARAN PASIEN</td>
								<td>:</td>
								<td>
									<input type="hidden" id="pembayaran-pasien" value="{{$data->pembayaran_pasien}}">
									<input type="hidden" id="pembayaran-pasien-temp" value="{{$data->pembayaran_pasien}}">
									<select name="pembayaran_pasien" id="pembayaran_pasien" class="form-control select2" style="width: 75%;">
										<option value="">-- PEMBAYARAN PASIEN --</option>
										{{-- @foreach($jenis_pasien as $jp) --}}
										@foreach($jenis_pasien_detail as $jp)
											<option
												value="{{$jp->subgroups}}"
												{{
													(
														// ($data->jenis_pasien=='BPJS' && $data->pembayaran_pasien=="" && $jp->nilaichar=='BPJS NON PBI ')
														// ||
														($data->pembayaran_pasien==$jp->subgroups)
													)
													? 'selected' : ''
												}}
											>{{$jp->nilaichar}}</option>
										@endforeach
									</select>
								</td>
							</tr>
							<tr> {{-- poli tujuan --}}
								<td>Poli Tujuan</td>
								<td>:</td>
								<td>
									<input type="hidden" id="kode-poli" value="{{$data->kode_poli}}">
									<input type="hidden" id="kode-poli-temp" value="{{$data->kode_poli}}">
									<select name="poli" id="poli" class="form-control select2" style="width: 75%">
										<option value="" disabled>-- PILIH POLI --</option>
										@if($poli->count()!=0)
										@foreach($poli as $poli)
										<option @if($poli->kdpoli==$data->kode_poli) selected @endif value="{{$poli->kdpoli}}">{{$poli->kdpoli}} - {{$poli->NamaPoli}}</option>
										@endforeach
										@endif
									</select>
								</td>
							</tr>
							<tr> {{-- sumber daftar --}}
								<td>Sumber Daftar</td>
								<td>:</td>
								<td>{{$data->metode_ambil}}</td>
							</tr>
						</table>
					</div>
				</div>
				{{-- <div class="row">
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
				</div> --}}
				{{-- <div>
					<!-- <form> -->
					<div class="from-group">
						<div class="row">
							<div class="col-md-5">
								<label>Masukkan <span id="kats">No. RM</span></label>
								<br>
								<input type="text"  name="nomor" style="width:200px !impoortant; margin: 10px auto;" id="cari">
							</div>
							<div class="col-md-5">
								<label>Masukkan <span id="kats">Alamat</span></label>
								<br>
								<input type="text" name="alamatCari" style="width:200px !impoortant; margin: 10px auto;" id="alamatCari"> <br>
								<input type="hidden" name="kategaori" id="kat" value="KodeCust">
							</div>
							<div class="col-md-2">
								<label>Cek Pasien</label>
								<br>
								<button type="button" class="btn btn-info btn-cari-pas">
									<i class="fa fa-search" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
					<!-- </form> -->
				</div> --}}
				{{-- <section class="panel panel-default m-b-0">
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
				</ul> --}}
			</div>
			<div class="modal-footer">
				<div
					style="
						display: flex;
						justify-content: space-between;
					"
				>
					<button type="button" class="btn btn-secondary" id="btn-batal" data-dismiss="modal">Tutup</button>
					{{-- <button type="button" class="btn btn-secondary" id="btn-batal">Tutup</button> --}}
					<button type="button" class="btn btn-success" id="btn-simpan" style="display: none;">Simpan Perubahan</button>
				</div>
				{{-- <button type="button" class="btn btnGreen" data-dismiss="modal">Ok</button> --}}
			</div>
		</div>
	</div>
</div>


<div class="modal fade loading-modal" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog modal-sm">
		{{-- <div class="modal-content" style="width: 48px">
			<span class="fa fa-spinner fa-spin fa-3x"></span> --}}
		<div class="modal-content">
			<div class="loader-edit-antrian" id="loader-2"><span></span><span></span><span></span></div>
		</div>
	</div>
</div>
{{-- <div class="modal" id="modal-loading" data-backdrop="static">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-body text-center">
				<div class="loader-edit-antrian" id="loader-2"><span></span><span></span><span></span></div>
			</div>
		</div>
	</div>
</div> --}}

{{-- <div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" id="pindah-poli" style="min-height: 0px;">
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
										<td>No. Antrian</td>
								<td>:</td>
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
									</tr>
									<tr>
										<td>Kode Booking</td>
								<td>:</td>
										<td>{{$data->kode_booking}}</td>
									</tr>
									<tr>
										<td>No. RM</td>
								<td>:</td>
										<td>{{$data->no_rm}}</td>
									</tr>
									<tr>
										<td>NIK</td>
								<td>:</td>
										<td>{{$data->nik}}</td>
									</tr>
									<tr>
										<td>Nama</td>
								<td>:</td>
										<td>{{$data->tm_customer->NamaCust}}</td>
									</tr>
									<tr>
										<td>Tanggal Lahir</td>
								<td>:</td>
										<td>{{$data->tm_customer->TglLahir}}</td>
									</tr>
									<tr>
										<td>Alamat</td>
								<td>:</td>
										<td>{{$data->tm_customer->Alamat}}</td>
									</tr>
									<tr>
										<td>No. Telepon</td>
								<td>:</td>
										<td>{{$data->tm_customer->Telp}}</td>
									</tr>
									<tr>
										<td>Jenis Pasien</td>
								<td>:</td>
										<td>{{$data->jenis_pasien}}</td>
									</tr>
									<tr>
										<td>Poli Tujuan</td>
								<td>:</td>
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
										<td>Kategori Pasien</td>
								<td>:</td>
										<td>{{ ($data->is_geriatri=='Y') ? 'Disabilitas':'Normal' }}</td>
									</tr>
									<tr>
										<td>Sumber Daftar</td>
								<td>:</td>
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
</div> --}}

<script type="text/javascript">
	var date = new Date()
	var day = date.getDate()
	var month = date.getMonth() + 1
	var year = date.getFullYear()

	if (month < 10) month = "0" + month
	if (day < 10) day = "0" + day

	const today = `${year}-${month}-${day}`

	$(document).ready(()=>{
		$('.select2').select2()
		$('#pindah-poli').modal('show')
	})


	// Remove focus select2
	function hideSelect2Keyboard(e){
		$('.select2-search input, :focus,input').prop('focus',false).blur();
	}

	$('#btn-simpan').click((e)=>{
		console.log('berhasil simpan')
	})

	$('#jenis_pasien').change(async(e)=>{
		const $this = $(e.currentTarget)
			jenisPasienAsli = $('#jenis-pasien').val()
			jenisPasienTemp = $('#jenis-pasien-temp').val()
			pembayaranPasienAsli = $('#pembayaran-pasien').val()
			pembayaranPasienTemp = $('#pembayaran-pasien-temp').val()
			jenisPasien = $this.val()
		let data = {!!$jenis_pasien!!}
		if(jenisPasien=='UMUM'){
			data = $.grep(data, function(v){
				// console.log(`${v.subgroups} - ${v.nilaichar}`)
				if(v.subgroups=='1001'){
					return v
				}
			})
		}else if(jenisPasien=='BPJS'){
			data = $.grep(data, function(v){
				// console.log(`${v.subgroups} - ${v.nilaichar}`)
				if(jQuery.inArray(v.subgroups,['1007','1008'])!==-1){
					return v
				}
			})
		}else if(jenisPasien=='ASURANSILAIN'){
			data = $.grep(data, function(v){
				// console.log(`${v.subgroups} - ${v.nilaichar}`)
				if(jQuery.inArray(v.subgroups,['1001','1007','1008'])===-1){
					return v
				}
			})
		}

		$("#pembayaran_pasien").empty().trigger('change')
		let newStateVal = '-- PEMBAYARAN PASIEN --'
			newState = new Option(newStateVal, '', true, true);
		// Append it to the select
		$("#pembayaran_pasien").append(newState)
		$.each(data,(i,val)=>{
			// Create the DOM option that is pre-selected by default
			newStateStr = val.nilaichar
			newStateVal = val.subgroups
			newState = new Option(newStateStr, newStateVal, false, false);
			// Append it to the select
			$("#pembayaran_pasien").append(newState)
		})
		$("#pembayaran_pasien").val('').trigger('change')
	})
	$('#pembayaran_pasien').change((e)=>{
		const $this = $(e.currentTarget)
			idAntrian = $('#id_antrian').val()

		let jenisPasien = $('#jenis_pasien').val()
			pembayaranPasienAsli = $('#pembayaran-pasien').val()
			pembayaranPasienTemp = $('#pembayaran-pasien-temp').val()

		if($this.val() && $this.val()!=pembayaranPasienTemp){
			swal({
				title:"Konfirmasi!",
				text:"Apakah anda yakin ingin merubah penjamin pasien?",
				type:"info",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Saya yakin!",
				cancelButtonText: "Batal!",
				allowOutsideClick: false,
			},
			function(isConfirm){
				if(isConfirm){
					const obj = {
						id_antrian: idAntrian,
						jenis_pasien: jenisPasien,
						pembayaran_lama: pembayaranPasienAsli,
						pembayaran_baru: $this.val()
					}
					$('.loading-modal').modal('show')
					$.post('{{route("counter.gantiPenjamin")}}',obj).done((data, status, xhr)=>{
						$('#pembayaran-pasien-temp').val($this.val())
						setTimeout(() => {
							$('.loading-modal').modal('hide')
							let title = xhr.status==204 ? 'Whoops..' : 'Berhasil'
								message = xhr.status==204 ? 'Antrian tidak ditemukan' : 'Penjamin berhasil dirubah'
								type = xhr.status==204 ? 'warning' : 'success'
							swal({
								title: title,
								text: message,
								type: type,
								showCancelButton: false,
							})
						}, 300)
					}).fail((e)=>{
						console.log(e)
						$this.val(pembayaranPasienTemp).trigger('change')
						setTimeout(() => {
							$('.loading-modal').modal('hide')
							swal({
								title: 'Whoops..',
								text: e.responseJSON.metadata.message,
								type: e.status==500?'error':'warning',
								showCancelButton: false,
							})
						}, 300)
					})
				}else{
					$this.val(pembayaranPasienTemp).trigger('change')
				}
			})
		}
	})

	$('#poli').change(async(e)=>{
		const $this = $(e.currentTarget)
		const kodePoliAsli = $('#kode-poli').val()
		const kodePoliTemp = $('#kode-poli-temp').val()
		const idAntrian = $('#id_antrian').val()

		await hideSelect2Keyboard

		if($this.val()!=kodePoliTemp){
			swal({
				title:"Konfirmasi!",
				text:"Apakah anda yakin ingin merubah poli tujuan?",
				type:"info",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Saya yakin!",
				cancelButtonText: "Batal!",
				allowOutsideClick: false,
			},
			function(isConfirm){
				if(isConfirm){
					$('#kode-poli-temp').val($this.val())
					const obj = {
						id_antrian: idAntrian,
						poli_bpjs_lama: kodePoliAsli,
						// poli_bpjs_lama: kodePoliTemp,
						poli_bpjs_baru: $this.val()
						// poli_bpjs_baru: $('#poli').val()
					}
					$('.loading-modal').modal('show')
					$.post('{{route("counter.resetNomorAntrianPoli")}}',obj).done((data, status, xhr)=>{
						$('#nopoli').html('-')
						$('.btn-generate').show()
						setTimeout(() => {
							$('.loading-modal').modal('hide')
							let title = xhr.status==204 ? 'Whoops..' : 'Berhasil'
								message = xhr.status==204 ? 'Antrian tidak ditemukan' : 'Poli berhasil dipindah, silahkan generate nomor antrian poli!'
								type = xhr.status==204 ? 'warning' : 'success'
							swal({
								title: title,
								text: message,
								type: type,
								showCancelButton: false,
							})
						}, 300)
					}).fail((e)=>{
						console.log(e)
						setTimeout(() => {
							$('.loading-modal').modal('hide')
							swal({
								title: 'Whoops..',
								text: e.responseJSON.metadata.message,
								type: e.status==500?'error':'warning',
								showCancelButton: false,
							})
						}, 300)
					})
				}else{
					$this.val(kodePoliTemp).trigger('change')
				}

				// $.post("{!! route('resetCounter') !!}",{id:id, noPoli:noPoli}).done(function(data){
				// 	if (data.status == 'success') {
				// 		$('#nopoli').html('-')
				// 		// $('#tempat-generate').empty()
				// 		$('#tempat-generate').html('<button style="margin-left: 50px;" type="button" class="btn btn-sm btn-success btn-generate" onclick="generate(`'+id+'`)"> Generate No</button>')
				// 		$('.btn-generate').show()
				// 		swal('Berhasil', data.message, 'success');
				// 	}else{
				// 		swal('Gagal', data.message, 'error');
				// 	}
				// })
			})
		}
	})
	$('#btn-batal,.close').click(()=>{
		loadTable($("#namaCounter").val(), today , today)
		setTimeout(()=>{
			$('.other-page').empty()
		},500)
	})

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
						$('.btn-generate').hide()
						setTimeout(()=>{
							swal({
								title: 'Berhasil !',
								type: 'success',
								html: true,
								text: 'Nomor Antrian Pasien berhasil di generate<br><b style="font-size: 20pt;">'+nopoli+'<b>',
								confirmButtonClass: "btn-primary",
								confirmButtonText: "Cetak",
							}, function (isConfirm) {
								let urlD = '{{route("cetakAntrianKonterPoli", ["id" => ":id" ] )}}'
								const url = urlD.replace(":id", id)
								window.open(url)
							})
						},500)
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
</script>