<style type="text/css">
	.loader {
		border: 7px solid #f3f3f3;
		border-radius: 50%;
		border-top: 7px solid #3498db;
		width: 20px;
		height: 20px;
		-webkit-animation: spin 2s linear infinite; /* Safari */
		animation: spin 1s linear infinite;
	}

	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
</style>

<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
	<div class="box box-default add-form">
		<div class="panel-body">
			<form class="formAdd" style="padding-top: 10px; padding-left: 20px;">
				<h5>KELOLA ANTRIAN</h5>
				<hr>

				<input type="hidden" name="id" id="id" value="{{$getAntrian->cust_id}}">
				<input type="hidden" name="id_antrian" id="id_antrian" value="{{$getAntrian->id}}">
				<div class="row mb-3" style="margin-top: 1rem;">
					<div class="col-md-{{$view==0 ? '3' : '2'}}">
						<label>Nomor Antrian</label>
						<input type="text" class="form-control" name="no_antrian" id="no_antrian" placeholder="Nomor Antrian" value="{{$getAntrian->no_antrian_pbaru?:$getAntrian->no_antrian}}" readonly>
					</div>
					<div class="col-md-{{$view==0 ? '3' : '2'}}">
						<label>Kode Booking</label>
						<input type="text" class="form-control" name="kodebooking" id="kodebooking" placeholder="Kode Booking" value="{{$getAntrian->kode_booking}}" readonly>
					</div>
					@if ($view!=0)
					<div class="col-md-2">
						<label>Nomor RM</label>
						<input type="text" class="form-control" name="no_rm" id="no_rm" placeholder="Nomor RM" value="{{($getAntrian->no_rm=='00000000000')?'':$getAntrian->no_rm}}" readonly>
					</div>
					@endif
					<input type="hidden" name="nomor_rm" id="nomor_rm">
					<div class="col-md-3">
						<label>Pendaftaran Melalui</label>
						<input type="text" class="form-control" name="metode" id="metode" placeholder="Pendaftaran Melalui" value="{{$getAntrian->metode_ambil}}" readonly>
					</div>
					<div class="col-md-3">
						<label>Jenis Pasien</label>
						<select name="jenis_pasien" id="jenis_pasien" class="form-control select2" @if($view == 1) readonly disabled @endif>
							<option value="">-- PILIH OPSI --</option>
							<option @if(isset($getAntrian) && $getAntrian->jenis_pasien == 'UMUM') selected @endif value="UMUM">UMUM</option>
							<option @if(isset($getAntrian) && $getAntrian->jenis_pasien == 'BPJS') selected @endif value="BPJS">BPJS</option>
							<option @if(isset($getAntrian) && $getAntrian->jenis_pasien == 'ASURANSILAIN') selected @endif value="ASURANSILAIN">ASURANSI LAINNYA</option>
						</select>
					</div>
				</div>
	 
				<div class="row mb-3" style="margin-top: 1rem;">
					<div class="col-md-6">
						<label>Nama Pasien <span class="text-red" style="font-size: 14px;">*</span></label>
						<div class="row">
							<div class="@if($view == 1) col-md-12 @else col-md-9 @endif">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pasien" value="{{(isset($getAntrian->tm_customer)) ? $getAntrian->tm_customer->NamaCust : ($from=='SIMAPAN'?$getAntrian->nama:($from=='WA'?$getAntrian->nama:''))}}" @if($view == 1) readonly @endif>
							</div>
							<div @if($view == 1) style="display:none;" @else class="col-md-3" @endif>
								<button type="button" class="btn btn-info btnCaPas" data-toggle="modal" data-target="#detail-dialog">
									Cek Peserta
								</button>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<label>Pembayaran Pasien</label>
						<select name="pembayaran_pasien" id="pembayaran_pasien" class="form-control select2" @if($view == 1) readonly disabled @endif>
							<option value="">-- PILIH OPSI --</option>
							@foreach($jenis_pasien as $jp)
								<option
									value="{{$jp->subgroups}}"
									{{
										(
											($getAntrian->jenis_pasien=='UMUM' && $jp->subgroups=='1001')
											|| ($getAntrian->jenis_pasien=='BPJS' && $jp->subgroups=='1008')
											|| ($getAntrian->pembayaran_pasien==$jp->subgroups)
										)
										? 'selected' : ''
									}}
								>{{$jp->nilaichar}}</option>
							@endforeach
							{{-- @if (!empty($getAntrian->jenis_pasien) && $getAntrian->jenis_pasien == 'ASURANSILAIN')
								@if(!empty($jenis_pasien) && $jenis_pasien->count()!=0)
									@foreach($jenis_pasien as $jp)
										<option value="{{$jp->nilaichar}}">{{$jp->nilaichar}}</option>
									@endforeach
								@endif
							@else
								<option @if($getAntrian->jenis_pasien == 'UMUM') selected @endif value="UMUM">UMUM</option>
								<option @if($getAntrian->jenis_pasien == 'BPJS') selected @endif value="BPJS">BPJS</option>								
							@endif --}}
						</select>
					</div>
					<!-- <div class="col-md-6">
						<label>Kewarganegaraan <span class="text-red" style="font-size: 14px;">*</span></label>
						<?php
							if(isset($getAntrian->tm_customer)){
								if($getAntrian->tm_customer->warganegara=='WNI'){
									$ck_status = ['checked',''];
								}else if($getAntrian->tm_customer->warganegara=='WNA'){
									$ck_status = ['','checked'];
								}else{
									$ck_status = ['',''];
								}
							}else{
								$ck_status = ['',''];
							}
						?>
						<div class="row">
							<div class="col-md-6">
								<label><input type="radio" @if($view == 1) disabled @endif {{$ck_status[0]}} name="kewarganegaraan" id="wni"  value="WNI" checked>&nbsp; Warga Negara Indonesia (WNI) </label>
							</div>
							<div class="col-md-6">
								<label><input type="radio" @if($view == 1) disabled @endif {{$ck_status[1]}} name="kewarganegaraan" id="wna"  value="WNA">&nbsp; Warga Negara Asing (WNA) </label>
							</div>
						</div>
					</div> -->
				</div>

				<div class="row mb-3" style="margin-top: 1rem;">
					<div class="col-md-6">
						<label>NIK <span class="text-red" style="font-size: 14px;">*</span></label>
						<input type="text" class="form-control" name="nik" id="nik" value="{{(isset($getAntrian)) ? $getAntrian->nik : ''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-6">
						<label>Kewarganegaraan <span class="text-red" style="font-size: 14px;">*</span></label>
						<?php
							if(isset($getAntrian->tm_customer)){
								if($getAntrian->tm_customer->warganegara=='WNI'){
									$ck_status = ['checked',''];
								}else if($getAntrian->tm_customer->warganegara=='WNA'){
									$ck_status = ['','checked'];
								}else{
									$ck_status = ['',''];
								}
							}else{
								$ck_status = ['',''];
							}
						?>
						<div class="row">
							<div class="col-md-6">
								<label><input type="radio" @if($view == 1) disabled @endif {{$ck_status[0]}} name="kewarganegaraan" id="wni"  value="WNI" checked>&nbsp; Warga Negara Indonesia (WNI) </label>
							</div>
							<div class="col-md-6">
								<label><input type="radio" @if($view == 1) disabled @endif {{$ck_status[1]}} name="kewarganegaraan" id="wna"  value="WNA">&nbsp; Warga Negara Asing (WNA) </label>
							</div>
						</div>
					</div>
				</div>
	 
				<div class="row mb-3" style="margin-top: 1rem;">
					<div class="col-md-2">
						<label>Tempat Lahir <span class="text-red" style="font-size: 14px;">*</span></label>
						<input type="text" class="form-control" name="tmpt_lahir" id="tmpt_lahir" placeholder="Tempat Lahir" value="{{(isset($getAntrian->tm_customer)) ? $getAntrian->tm_customer->Tempat : ''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-2">
						<label>Tanggal Lahir <span class="text-red" style="font-size: 14px;">*</span></label>
						<input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" placeholder="Tanggal Lahir" value="{{isset($getAntrian->tm_customer->TglLahir) ? date("Y-m-d",strtotime($getAntrian->tm_customer->TglLahir)):''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-1">
						<label>Rt</label>
						<input type="text" class="form-control" name="rt" id="rt" placeholder="Rt" value="{{(isset($getAntPasBaru->rt)) ? $getAntPasBaru->rt : ''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-1">
						<label>Rw</label>
						<input type="text" class="form-control" name="rw" id="rw" placeholder="Rw" value="{{(isset($getAntPasBaru->rw)) ? $getAntPasBaru->rw : ''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-6">
						<label>Alamat Tinggal <span class="text-red" style="font-size: 14px;">*</span></label>
						<input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat Tinggal" value="{{(isset($getAntrian->tm_customer)) ? $getAntrian->tm_customer->Alamat : ''}}" @if($view == 1) readonly @endif>
					</div>
				</div>
	 
				<div class="row mb-3" style="margin-top: 1rem;">
					<div class="col-md-3">
						<label>Jenis Kelamin <span class="text-red" style="font-size: 14px;">*</span></label>
						<div class="row">
							<div class="col-md-6">
								<label><input type="radio" @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->JenisKel == 'P') checked @endif name="jenis_kelamin" id="jenis_kelamin_P" value="P" @if($view == 1) disabled @endif >&nbsp; Perempuan </label>
							</div>
							<div class="col-md-6">
								<label><input type="radio" @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->JenisKel == 'L') checked @endif name="jenis_kelamin" id="jenis_kelamin_L" value="L" @if($view == 1) disabled @endif >&nbsp; Laki - Laki </label>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<label>No. Telepon <span class="text-red" style="font-size: 14px;">*</span></label>
						<input type="text" class="form-control" name="telp" id="telp" placeholder="No. Telepon" value="{{(isset($getAntrian->tm_customer)) ? $getAntrian->tm_customer->Telp : ''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-3">
						<label>Provinsi <span class="text-red" style="font-size: 14px;">*</span></label>
						<select name="provinsi_id" class="form-control select2" id="provinsi" @if($view == 1) disabled @endif>
							<option value="" readonly="">..:: Pilih Provinsi ::..</option>
							@if (!empty($prov))
								@foreach ($data_provinsi as $row)
								<option @if ($prov==$row->name) selected @endif value="{{$row->id}}">{{$row->name}}</option>
								@endforeach
							@else
								@foreach ($data_provinsi as $row)
								<option value="{{$row->id}}">{{$row->name}}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="col-md-3">
						<label>Kabupaten / Kota <span class="text-red" style="font-size: 14px;">*</span></label>
						<select name="kabupaten_id" class="form-control select2" id="kabupaten" @if($view == 1) disabled @endif>
							<option value="" readonly="">..:: Pilih Kab/Kota ::..</option>
						</select>
					</div>
				</div>

				<div class="row mb-3" style="margin-top: 1rem;">
					<div class="col-md-3">
						<label>Gol. Darah</label>
						<select name="gol_darah" id="gol_darah" class="form-control select2" @if($view == 1) disabled @endif>
							<option value="" readonly="">-- PILIH OPSI --</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->goldarah == 'A') selected @endif value="A">A</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->goldarah == 'B') selected @endif value="B">B</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->goldarah == 'AB') selected @endif value="AB">AB</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->goldarah == 'O') selected @endif value="O">O</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Agama <span class="text-red" style="font-size: 14px;">*</span></label>
						<select name="agama"  id="agama" class="form-control select2" @if($view == 1) disabled @endif>
							<option value="" readonly="">-- PILIH OPSI --</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->Agama == 'Islam') selected @endif value="Islam">Islam</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->Agama == 'Protestan') selected @endif value="Protestan">Protestan</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->Agama == 'Katolik') selected @endif value="Katolik">Katolik</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->Agama == 'Hindu') selected @endif value="Hindu">Hindu</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->Agama == 'Buddha') selected @endif value="Buddha">Buddha</option>
							<option @if(isset($getAntrian->tm_customer) && $getAntrian->tm_customer->Agama == 'Khonghucu') selected @endif value="Khonghucu">Khonghucu</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Kecamatan <span class="text-red" style="font-size: 14px;">*</span></label>
						<select name="kecamatan_id" class="form-control select2" id="kecamatan" @if($view == 1) disabled @endif>
							<option value="" readonly="">..:: Pilih Kecamatan ::..</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Desa / Kelurahan <span class="text-red" style="font-size: 14px;">*</span></label>
						<select name="desa_id" class="form-control select2" id="desa" @if($view == 1) disabled @endif>
							<option value="" readonly="">..:: Pilih Desa / Kelurahan ::..</option>
						</select>
					</div>
				</div>

				<div class="row mb-3" style="margin-top: 1rem;">
					<div class="col-md-3">
						<label>Pendidikan Terakhir</label>
						<select name="pend_terakhir" id="pend_terakhir" class="form-control select2" @if($view == 1) disabled @endif>
							<option value="" readonly="">-- PILIH OPSI --</option>
							<option @if(isset($getAntPasBaru->pend_terakhir) && $getAntPasBaru->pend_terakhir == 'SD') selected @endif value="SD">SD</option>
							<option @if(isset($getAntPasBaru->pend_terakhir) && $getAntPasBaru->pend_terakhir == 'SMP') selected @endif value="SMP">SMP</option>
							<option @if(isset($getAntPasBaru->pend_terakhir) && $getAntPasBaru->pend_terakhir == 'SMA / SMK') selected @endif value="SMA / SMK">SMA / SMK</option>
							<option @if(isset($getAntPasBaru->pend_terakhir) && $getAntPasBaru->pend_terakhir == 'Diploma') selected @endif value="Diploma">Diploma</option>
							<option @if(isset($getAntPasBaru->pend_terakhir) && $getAntPasBaru->pend_terakhir == 'S1') selected @endif value="S1">S1</option>
							<option @if(isset($getAntPasBaru->pend_terakhir) && $getAntPasBaru->pend_terakhir == 'S2') selected @endif value="S2">S2</option>
							<option @if(isset($getAntPasBaru->pend_terakhir) && $getAntPasBaru->pend_terakhir == 'S3') selected @endif value="S3">S3</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Status Perkawinan <span class="text-red" style="font-size: 14px;">*</span></label>
						<select name="s_perkawinan" id="s_perkawinan" class="form-control select2" @if($view == 1) disabled @endif>
							<option value="" readonly="">-- PILIH OPSI --</option>
							<option @if(isset($getAntrian->tm_customer->status) && $getAntrian->tm_customer->status == 'Belum Menikah') selected @endif value="Belum Menikah">Belum Menikah</option>
							<option @if(isset($getAntrian->tm_customer->status) && $getAntrian->tm_customer->status == 'Cerai') selected @endif value="Cerai"> Cerai </option>
							<option @if(isset($getAntrian->tm_customer->status) && $getAntrian->tm_customer->status == 'Menikah') selected @endif value="Menikah">Sudah Menikah</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Pekerjaan</label>
						<input type="text" class="form-control" name="pekerjaan" id="pekerjaan" placeholder="Pekerjaan" value="{{(isset($getAntrian->tm_customer)) ? $getAntrian->tm_customer->Pekerjaan : ''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-3">
						<label>Penanggung Jawab</label>
						<select name="pen_jawab" id="pen_jawab" class="form-control select2" @if($view == 1) disabled @endif>
							<option value="" readonly="">-- PILIH OPSI --</option>
							<option @if(isset($getAntPasBaru->pen_jawab) && $getAntPasBaru->pen_jawab == 'Suami / Istri') selected @endif value="Suami / Istri">Suami / Istri</option>
							<option @if(isset($getAntPasBaru->pen_jawab) && $getAntPasBaru->pen_jawab == 'Orang Tua') selected @endif value="Orang Tua">Orang Tua</option>
							<option @if(isset($getAntPasBaru->pen_jawab) && $getAntPasBaru->pen_jawab == 'Saudara') selected @endif value="Saudara">Saudara</option>
							<option @if(isset($getAntPasBaru->pen_jawab) && $getAntPasBaru->pen_jawab == 'Teman') selected @endif value="Teman">Teman</option>
							<option @if(isset($getAntPasBaru->pen_jawab) && $getAntPasBaru->pen_jawab == 'Diri Sendiri') selected @endif value="Diri Sendiri">Diri Sendiri</option>
						</select>
					</div>
					{{-- <div class="col-md-3">
						<label>Nama Penanggung Jawab</label>
						<input type="text" class="form-control" name="nama_pen_jawab" id="nama_pen_jawab" value="{{(isset($getAntPasBaru->nama_pen_jawab)) ? $getAntPasBaru->nama_pen_jawab : ''}}" @if($view == 1) readonly @endif>
					</div> --}}
				</div>

				<div class="row" style="margin-top: 1rem;">
					<div class="col-md-3">
						<label>No. BPJS</label>
						<input type="text" class="form-control" name="nobpjs" id="nobpjs" placeholder="No. BPJS" value="{{(isset($getAntrian)) ? $getAntrian->nomor_kartu : ''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-3">
						<label>No. Rujukan</label>
						<input type="text" class="form-control" name="norujukan" placeholder="No. Rujukan" value="{{(isset($getAntrian)) ? $getAntrian->nomor_referensi : ''}}" @if($view == 1) readonly @endif>
					</div>
					<div class="col-md-6">
						<label>Poli Tujuan</label>
						<select name="poli" class="form-control select2" @if($view == 1) disabled @endif>
							<option value="">-- PILIH POLI --</option>
							@if($poli->count()!=0)
							@foreach($poli as $poli)
							<option @if($poli->kdpoli==$getAntrian->kode_poli) selected @endif value="{{$poli->kdpoli}}">{{$poli->kdpoli}} - {{$poli->NamaPoli}}</option>
							@endforeach
							@endif
						</select>
					</div>
				</div>

				<hr style="border-top: 2px solid #dcdcdc">

				<div class="row" style="margin-top: 1rem;">
					<?php $col = $getAntrian->jenis_pasien=='BPJS' ? 3 : 4; ?>
					<div class="col-md-<?=$col?>" style="margin-bottom: 1rem;">
						<button type="button" style="width: 100%;" class="btn btn-secondary btn-cancel">KEMBALI</button>
					</div>
					@if($view==0)
						<div class="col-md-<?=$col?>" style="margin-bottom: 1rem;">
							<button type="button" style="width: 100%;" class="btn btn-danger btn-batal" onclick="batalkan(`{{$getAntrian->kode_booking}}`)">BATALKAN ANTRIAN</button>
						</div>
						@if($getAntrian->jenis_pasien=='BPJS')
						<div class="col-md-3" style="margin-bottom: 1rem;">
							<button type="button" style="width: 100%;" class="btn btn-primary btn-sep">BUAT SEP</button>
						</div>
						@endif
						<div class="col-md-<?=$col?>" style="margin-bottom: 1rem;">
							<button type="button" style="width: 100%;" class="btn btn-success btn-store">KIRIM KE KONTER POLI</button>
						</div>
					@endif

					{{-- <div class="col-md-12" style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem; grid-template-columns: repeat(4, 1fr);">
						<button type="button" class="btn btn-secondary btn-cancel">KEMBALI</button>
						@if($view==0) --}}
							{{-- @if(($getAntrian->no_rm=='00000000000'||$getAntrian->no_rm==null||$getAntrian->no_rm=="") && $getAntrian->jenis_pasien=='BPJS')
							<div class="col-md-2 text-center">
								<button type="button" class="btn btn-warning btn-cetak-rm" onclick="cetakrm(`{{json_encode($getAntrian)}}`)" style="width: 100%">GENERATE NO RM</button>
							</div>
							@endif --}}
								{{-- <button type="button" onclick="batalkan(`{{$getAntrian->kode_booking}}`)" class="btn btn-danger btn-batal">BATALKAN ANTRIAN</button>
								@if ($getAntrian->jenis_pasien=='BPJS')
									<button type="button" class="btn btn-primary btn-sep">BUAT SEP</button>
								@endif
								<button type="button" class="btn btn-success btn-store">KIRIM KE KONTER POLI</button> --}}
							{{-- <div class="col-md-2 text-center">
								<button type="button" onclick="batalkan(`{{$getAntrian->kode_booking}}`)"  class="btn btn-danger btn-batal" style="width: 100%">BATALKAN ANTRIAN</button>
							</div>
							@if ($getAntrian->jenis_pasien=='BPJS')
							<div class="col-md-3" class="text-center">
								<button type="button" class="btn btn-primary btn-sep" style="width: 100%">BUAT SEP</button>
							</div>
							@endif --}}
							{{-- @if ($getAntrian->jenis_pasien != 'BPJS')
							<div class="col-md-3 text-center">
								<button type="button" class="btn btn-primary btn-tracer" style="width: 100%">TRACER PASIEN</button>
							</div>
							@endif --}}
							{{-- <div class="col-md-3 text-center">
								<button type="button" class="btn btn-success btn-store" style="width: 100%">KIRIM KE KONTER POLI</button>
							</div> --}}
						{{-- @endif
					</div> --}}
				</div>

				<div class='clearfix' style="margin-bottom: 5px"></div>
			</form>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="width:70%;">
		<div class="modal-content">
			<div class="modal-header modalHeaderGreen">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title modalHeaderTitle" id="product-detail-dialog"><i class="fa fa-info m-r-15 m-l-5"></i></h4>
			</div>
			<div class="modal-body">
				<div>
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

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

{{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.2/js/bootstrap.min.js" integrity="sha512-5BqtYqlWfJemW5+v+TZUs22uigI8tXeVah5S/1Z6qBLVO7gakAOtkOzUtgq6dsIo5c0NJdmGPs0H9I+2OHUHVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
	$(document).ready(()=>{
		$('.select2').select2()
	})

	$('.btnCaPas').click(function (e) {
		e.preventDefault();
		var nama = $('#nama').val()
		$('#cari').val(nama)
	});

	$('#btnCariPasien').on('click', function () {
		$("#detail-dialog").modal('show')
	})

	function reloadDataDiagnosa(key,kat,alamat,page) {
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
						dat += '<tr data-id="'+v.KodeCust+'"><td onclick="getdata('+klik+')"><center><button class="btn btn-sm btn-success" type="button"><i class="fa fa-plus-circle"></i></button></center></td>'+
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
				$('.btn-cari-pas').html('<i class="fa fa-search" aria-hidden="true"></i>').attr('disabled',false)
				$('#result').html(dat);
			}else{
				$('.btn-cari-pas').html('<i class="fa fa-search" aria-hidden="true"></i>').attr('disabled',false)
			}
		})
	}

	$("#cari").keyup(function(){
		var key = $('#cari').val();
		var alamat = $('#alamatCari').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,alamat,0);
	})

	$("#alamatCari").keyup(function(){
		var key = $('#cari').val();
		var alamat = $('#alamatCari').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,alamat,0);
	})

	function pagin(gopage){
		var key = $('#cari').val();
		var alamat = $('#alamatCari').val();
		var kat = $('#kat').val();
		reloadDataDiagnosa(key,kat,alamat,gopage);
	}

	function getdata(KodeCust,nobpjs){
		swal({
			title: 'KONFIRMASI !',
			type: 'info',
			text: 'Yakin data sudah benar?',
			confirmButtonClass: "btn-primary",
			confirmButtonText: "Yakin",
			cancelButtonText: "Tidak",
			showCancelButton: true,
		},(isConfirm)=>{
			if(isConfirm){
				$.post("{!! route('getDatapasien') !!}",{key:KodeCust,nobpjs:nobpjs}).done((res)=>{
					if(res.status == 'success'){
						// $('.btn-cetak-rm').hide()
						var ktp = $('#nik').val()
						var tgl = formatDate(res.data.TglLahir)
						var JK = res.data.JenisKel
						var telp = res.data.Telp
						var agama = res.data.Agama
						var tempatLahir = res.data.Tempat
						var bpjs = res.data.FieldCust1
						var golDarah = res.data.goldarah
						var pekerjaan = res.data.Pekerjaan
						var sPerkawinan = res.data.status
						var rt = res.data.rt
						var rw = res.data.rw
						var prov = ''
						var kab = ''
						var kec = ''
						var kel = ''
						var pJawab = ''
						var pendTerakhir = ''
						var namaPJawab = ''
						if(typeof(res.data.antrian_pasien_baru) != "undefined" && res.data.antrian_pasien_baru !== null) {
							prov         = res.data.antrian_pasien_baru.provinsi_id
							kab          = res.data.antrian_pasien_baru.kabupaten_id
							kec          = res.data.antrian_pasien_baru.kecamatan_id
							kel          = res.data.antrian_pasien_baru.desa_id
							pJawab       = res.data.antrian_pasien_baru.pen_jawab
							pendTerakhir = res.data.antrian_pasien_baru.pend_terakhir
							namaPJawab   = res.data.antrian_pasien_baru.nama_pen_jawab
						}

						if(prov){
							$('#provinsi').val(prov).change()
							loadDaerah(kab,kec,kel)
						}else{
							loadDaerah('-','-','-')
						}
						// $('#no_rm').val(res.data.KodeCust)
						$('#nomor_rm').val(res.data.KodeCust)
						$('#nama').val(res.data.NamaCust)
						if(res.data.NoKtp){
							$('#nik').val(res.data.NoKtp)
						}else{
							if(!ktp){
								$('#nik').val(res.data.NoKtp)
							}
						}
						$('#alamat').val(res.data.Alamat)
						$('#tgl_lahir').val(tgl)
						if(JK=='L'){
							$('#jenis_kelamin_L').prop('checked',true)
						}else{
							$('#jenis_kelamin_P').prop('checked',true)
						}
						if(telp){
							$('#telp').val(telp)
						}
						if(agama){
							if(agama.toLowerCase().indexOf("islam") >= 0){
								$('#agama option[value=Islam]').attr('selected','selected')
							}else if(agama.toLowerCase().indexOf("protestan") >= 0){
								$('#agama option[value=Protestan]').attr('selected','selected')
							}else if(agama.toLowerCase().indexOf("katolik") >= 0){
								$('#agama option[value=Katolik]').attr('selected','selected')
							}else if(agama.toLowerCase().indexOf("hindu") >= 0){
								$('#agama option[value=Hindu]').attr('selected','selected')
							}else if(agama.toLowerCase().indexOf("buddha") >= 0){
								$('#agama option[value=Buddha]').attr('selected','selected')
							}else if(agama.toLowerCase().indexOf("khonghucu") >= 0){
								$('#agama option[value=Khonghucu]').attr('selected','selected')
							}
						}
						if(golDarah){
							if(golDarah.toLowerCase().indexOf("a") >= 0){
								$('#gol_darah').val('A').change()
							}else if(golDarah.toLowerCase().indexOf("b") >= 0){
								$('#gol_darah').val('B').change()
							}else if(golDarah.toLowerCase().indexOf("ab") >= 0){
								$('#gol_darah').val('AB').change()
							}else if(golDarah.toLowerCase().indexOf("o") >= 0){
								$('#gol_darah').val('O').change()
							}
						}
						$('#pekerjaan').val(pekerjaan)
						if(pJawab){
							if(pJawab.toLowerCase().indexOf("suami / istri") >= 0){
								$('#pen_jawab').val('Suami / Istri').change()
							}else if(pJawab.toLowerCase().indexOf("orang tua") >= 0){
								$('#pen_jawab').val('Orang Tua').change()
							}else if(pJawab.toLowerCase().indexOf("saudara") >= 0){
								$('#pen_jawab').val('Saudara').change()
							}else if(pJawab.toLowerCase().indexOf("teman") >= 0){
								$('#pen_jawab').val('Teman').change()
							}
						}
						if(pendTerakhir){
							if(pendTerakhir.toLowerCase().indexOf("sd") >= 0){
								$('#pend_terakhir').val('SD').change()
							}else if(pendTerakhir.toLowerCase().indexOf("smp") >= 0){
								$('#pend_terakhir').val('SMP').change()
							}else if(pendTerakhir.toLowerCase().indexOf("sma") >= 0){
								$('#pend_terakhir').val('SMA / SMK').change()
							}else if(pendTerakhir.toLowerCase().indexOf("diploma") >= 0){
								$('#pend_terakhir').val('Diploma').change()
							}else if(pendTerakhir.toLowerCase().indexOf("s1") >= 0){
								$('#pend_terakhir').val('S1').change()
							}else if(pendTerakhir.toLowerCase().indexOf("s2") >= 0){
								$('#pend_terakhir').val('S2').change()
							}else if(pendTerakhir.toLowerCase().indexOf("s3") >= 0){
								$('#pend_terakhir').val('S3').change()
							}
						}
						if(sPerkawinan){
							if(sPerkawinan.toLowerCase().indexOf("belum") >= 0){
								$('#s_perkawinan').val('Belum Menikah').change()
							}else if(sPerkawinan.toLowerCase().indexOf("menikah") >= 0){
								$('#s_perkawinan').val('Menikah').change()
							}else if(sPerkawinan.toLowerCase().indexOf("cerai") >= 0){
								$('#s_perkawinan').val('Cerai').change()
							}
						}
						if(namaPJawab){
							$('#nama_pen_jawab').val(namaPJawab)
						}
						if(pJawab){
							if (pJawab == 'Suami / Istri') {
								$('#pjawab_suamistri').prop('checked',true)
							} else if(pJawab == 'Orang Tua') {
								$('#pjawab_orangtua').prop('checked',true)
							} else if(pJawab == 'Saudara') {
								$('#pjawab_saudara').prop('checked',true)
							} else {
								$('#pjawab_teman').prop('checked',true)
							}
						}
						if(tempatLahir){
							$('#tmpt_lahir').val(tempatLahir)
						}
						if(bpjs){
							$('#nobpjs').val(bpjs)
						}
						if(rt){
							$('#rt').val(rt)
						}
						if(rw){
							$('#rw').val(rw)
						}
						setTimeout(()=>{
							swal({
								title: 'Berhasil',
								type: 'success',
								text: 'Data Berhasil Di Pilih.',
								showConfirmButton: false,
								showCancelButton: false,
								timer: 1200
							})
						},400)
					}
				})
			}
		})
	}

	$(".btn-cari-pas").click(function(){
		var key = $('#cari').val();
		var alamat = $('#alamatCari').val();
		var kat = $('#kat').val();
		$('.btn-cari-pas').html('<div class="loader"></div>').attr('disabled',true)
		reloadDataDiagnosa(key,kat,alamat,0);
	})

	function formatDate(tgl){
		$tanggal = tgl.substr(8,2)
		$bulan   = tgl.substr(5,2)
		$tahun   = tgl.substr(0,4)
		return $tahun+'-'+$bulan+'-'+$tanggal
	}

	function tgl_indo(tgl) {
		$tanggal = tgl.substr(8,2);
		$bulan   = tgl.substr(5,2);
		$tahun   = tgl.substr(0,4);
		return $tanggal+'/'+$bulan+'/'+$tahun;
	}

	function btnCariCust(){
		var nama = $("#nama").val()
		var nik = $("#nik").val()
		var bpjs = $("#nobpjs").val()
		$('#detail-dialog').modal('show')
	}

	function cetakrm(obj) {
		obj = JSON.parse(obj)
		swal({
			title: 'KONFIRMASI !',
			type: 'info',
			text: 'Anda Ingin Cetak No RM?',
			confirmButtonClass: "btn-primary",
			confirmButtonText: "Cetak",
			cancelButtonText: "Tidak",
			showCancelButton: true,
		},(isConfirm)=>{
			if(isConfirm){
				var url = "{{route('cetakRMAntrian')}}";
				$.post(url,{id:obj.kode_booking,nik:obj.nik}).done(function(data){
					if(data.status == 'success'){
						swal({
							title: 'Berhasil',
							type: data.status,
							text: data.message,
							showConfirmButton: true,
						})
						// $('#no_rm').val(data.nomor);
						$('.btn-cetak-rm').hide()
					}else{
						swal({
							title: 'Whoops',
							type: data.status,
							text: data.message,
						})
					}
				})
			}
		})
	}

	// BTN TRACER
	// $('.btn-tracer').click(function (e) { 
	// 	e.preventDefault();
	// 	var id = $('#id_antrian').val();
	// 	var norm            = $('#no_rm').val();
	// 	var nama            = $('#nama').val();
	// 	var alamat          = $('#alamat').val();
	// 	var pem_pasien      = $('#pembayaran_pasien').val();

	// 	if (!norm) {
	// 		swal('Peringatan!!', 'No RM Wajib Diisi.', 'warning')
	// 	} else if (!nama) {
	// 		swal('Peringatan!!', 'Nama Pasien Wajib Diisi.', 'warning')
	// 	} else if (!alamat) {
	// 		swal('Peringatan!!', 'Alamat Wajib Diisi.', 'warning')
	// 	} else if (!pem_pasien) {
	// 		swal('Peringatan!!', 'Pembayaran Pasien Wajib Diisi.', 'warning')
	// 	} else {
	// 		swal({
	// 			title: 'KONFIRMASI !',
	// 			type: 'info',
	// 			text: 'Apakah Pasien Ingin Mencetak Tracer ?',
	// 			confirmButtonClass: "btn-primary",
	// 			confirmButtonText: "Ya",
	// 			cancelButtonText: "Tidak",
	// 			showCancelButton: true,
	// 		},(isConfirm)=>{
	// 			if(isConfirm){
	// 				var data = new FormData($('.formAdd')[0]);
	// 				$.ajax({
	// 					url: "{{route('saveListAntrian')}}",
	// 					type: 'POST',
	// 					data: data,
	// 					async: true,
	// 					cache: false,
	// 					contentType: false,
	// 					processData: false
	// 				}).done(function(data) {
	// 					if (data.code == 200) {
	// 						let urlD = '{{route("cetakTracerPasien", ["id" => ":id" ] )}}'
	// 						const url = urlD.replace(":id", id)
	// 						window.open(url)
	// 						swal('Berhasil', 'Antrian Berhasil Mencetak Tracer.', 'success')
	// 					} else {
	// 						swal("Warning!", "Antrian Gagal Mencetak Tracer", "error");
	// 					}
	// 				}).fail(function() {
	// 					swal("MAAF!", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
	// 				});
	// 			}
	// 		})	
	// 	}
	// });

	function sendToCounterPoli(data){
		return new Promise((resolve,reject)=>{
			$.ajax({
				url: "{{route('loket.sendToCounterPoli')}}",
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false
			}).then((data, status, xhr)=>{
				resolve(data)
			},(err)=>{
				reject(err)
			})
		})
	}

	// BTN SIMPAN
	$('.btn-store').click(async function(e) {
		e.preventDefault();
		var id = $('#id').val();
			id_antrian = $('#id_antrian').val();
			no_antrian = $('#no_antrian').val();
			metode = $('#metode').val();
			nama = $('#nama').val();
			kewarganegaraan = $("input[type='radio'][name='kewarganegaraan']:checked").val();
			nik = $('#nik').val();
			alamat = $('#alamat').val();
			tmpt_lahir = $('#tmpt_lahir').val();
			tgl_lahir = $('#tgl_lahir').val();
			prov = $('#provinsi').val();
			kab = $('#kabupaten').val();
			kelamin = $("input[type='radio'][name='jenis_kelamin']:checked").val();
			telp = $('#telp').val();
			kec = $('#kecamatan').val();
			desa = $('#desa').val();
			gol_darah = $('#gol_darah').find(":selected").val();
			agama = $('#agama').find(":selected").val();
			pekerjaan = $('#pekerjaan').val();
			pen_jawab = $('#pen_jawab').find(":selected").val();
			pend_terakhir = $('#pend_terakhir').find(":selected").val();
			s_perkawinan = $('#s_perkawinan').val();
			nama_pen_jawab = $('#nama_pen_jawab').val();
			telp_pen_jawab = $('#telp_pen_jawab').val();
			poli = $('#poli').val();
			// norm = $('#no_rm').val();
			jenis_pasien = $('#jenis_pasien').val();
			pem_pasien = $('#pembayaran_pasien').val();
			kodebooking = $('#kodebooking').val();

		// if (!norm && jenis_pasien=='BPJS') {
		// 	swal('Whooops','No RM Tidak Boleh Kosong!','warning');
		// } else
		if(!nama){
			swal('Whooops','Nama Pasien Tidak Boleh Kosong!','warning');
		} else if (!kewarganegaraan) {
			swal('Whooops','Kewarganegaraan Tidak Boleh Kosong!','warning');
		} else if (!nik) {
			swal('Whooops','NIK Tidak Boleh Kosong!','warning');
		} else if(!alamat) {
			swal('Whooops','Alamat Tidak Boleh Kosong!','warning');
		} else if(!tmpt_lahir) {
			swal('Whooops','Tempat Lahir Tidak Boleh Kosong!','warning');
		} else if(!tgl_lahir) {
			swal('Whooops','Tanggal Lahir Tidak Boleh Kosong!','warning');
		} else if(!prov) {
			swal('Whooops','Provinsi Tidak Boleh Kosong!','warning');
		} else if(!kab) {
			swal('Whooops','Kabupaten / Kota Tidak Boleh Kosong!','warning');
		} else if(!kelamin) {
			swal('Whooops','Jenis Kelamin Tidak Boleh Kosong!','warning');
		} else if(!telp) {
			swal('Whooops','No.telepon Tidak Boleh Kosong!','warning');
		} else if(!kec) {
			swal('Whooops','Kecamatan Tidak Boleh Kosong!','warning');
		} else if(!desa) {
			swal('Whooops','Desa / Kelurahan Tidak Boleh Kosong!','warning');
		} else if(!agama) {
			swal('Whooops','Agama Tidak Boleh Kosong!','warning');
		} else if(!s_perkawinan) {
			swal('Whooops','Status Perkawinan Tidak Boleh Kosong!','warning');
		} else if(!jenis_pasien || !pem_pasien) {
			swal('Whooops','Jenis / Pembayaran Pasien Tidak Boleh Kosong!','warning');
		} else{
			var data = new FormData($('.formAdd')[0]);
			sendToCounterPoli(data).then(async(data)=>{
				if(data.metadata.code==200){
					await swal({
						title: 'Berhasil',
						type: data.metadata.status,
						text: data.metadata.message,
						showConfirmButton: false,
						timer: 1000
					})
					setTimeout(()=>{
						$('.other-page').fadeOut(function() {
							$('.other-page').empty()
							$('.main-layer').fadeIn()
							$('#dataTable').DataTable().ajax.reload()
						})
					},900)
					// let idAntrian = $('#id_antrian').val()
					// let urlD = '{{route("cetakTracerPasien", ["id" => ":id" ] )}}'
					// const url = urlD.replace(":id", idAntrian)
					// var win = await window.open(url)
					// var timer = setInterval(() => {
					// 	if(win.closed){
					// 		clearInterval(timer)
					// 		swal({
					// 			title: 'Berhasil',
					// 			type: data.metadata.status,
					// 			text: data.metadata.message,
					// 			showConfirmButton: true,
					// 		},function(isConfirm){
					// 			// main-layer
					// 			$('.other-page').fadeOut(function() {
					// 				$('.other-page').empty()
					// 				$('.main-layer').fadeIn()
					// 				$('#dataTable').DataTable().ajax.reload()
					// 			})
					// 		})
					// 	}
					// }, 500)
				}else{
					swal({
						title: 'Whoops..',
						type: data.metadata.status,
						text: data.metadata.message,
						showConfirmButton: true,
					})
				}
			}).catch((e)=>{
				console.log(e)
				swal({
					title: 'Whoops..',
					type: e.responseJSON.metadata.status,
					text: e.responseJSON.metadata.message,
					showConfirmButton: true,
				})
			})
		}
	})

	// BTN SEP
	$('.btn-sep').click(function(e) {
		e.preventDefault()
		var id = $('#id').val()
			id_antrian = $('#id_antrian').val()
			no_antrian = $('#no_antrian').val()
			metode = $('#metode').val()
			nama = $('#nama').val()
			kewarganegaraan = $("input[type='radio'][name='kewarganegaraan']:checked").val()
			nik = $('#nik').val()
			alamat = $('#alamat').val()
			tmpt_lahir = $('#tmpt_lahir').val()
			tgl_lahir = $('#tgl_lahir').val()
			prov = $('#provinsi').val()
			kab = $('#kabupaten').val()
			kelamin = $("input[type='radio'][name='jenis_kelamin']:checked").val()
			telp = $('#telp').val()
			kec = $('#kecamatan').val()
			desa = $('#desa').val()
			gol_darah = $('#gol_darah').find(":selected").val()
			agama = $('#agama').find(":selected").val()
			pekerjaan = $('#pekerjaan').val()
			pen_jawab = $('#pen_jawab').find(":selected").val()
			pend_terakhir = $('#pend_terakhir').find(":selected").val()
			s_perkawinan = $('#s_perkawinan').val()
			nama_pen_jawab = $('#nama_pen_jawab').val()
			telp_pen_jawab = $('#telp_pen_jawab').val()
			poli = $('#poli').val()
			// norm = $('#no_rm').val()
			jenis_pasien = $('#jenis_pasien').val()
			pem_pasien = $('#pembayaran_pasien').val()

		// if (!norm) {
		// 	swal('Whooops','No RM Tidak Boleh Kosong!','warning')
		// } else
		if(!nama){
			swal('Whooops','Nama Pasien Tidak Boleh Kosong!','warning')
		} else if (!kewarganegaraan) {
			swal('Whooops','Kewarganegaraan Tidak Boleh Kosong!','warning')
		} else if (!nik) {
			swal('Whooops','NIK Tidak Boleh Kosong!','warning')
		} else if(!alamat) {
			swal('Whooops','Alamat Tidak Boleh Kosong!','warning')
		} else if(!tmpt_lahir) {
			swal('Whooops','Tempat Lahir Tidak Boleh Kosong!','warning')
		} else if(!tgl_lahir) {
			swal('Whooops','Tanggal Lahir Tidak Boleh Kosong!','warning')
		} else if(!prov) {
			swal('Whooops','Provinsi Tidak Boleh Kosong!','warning')
		} else if(!kab) {
			swal('Whooops','Kabupaten / Kota Tidak Boleh Kosong!','warning')
		} else if(!telp) {
			swal('Whooops','No.telepon Tidak Boleh Kosong!','warning')
		} else if(!kec) {
			swal('Whooops','Kecamatan Tidak Boleh Kosong!','warning')
		} else if(!desa) {
			swal('Whooops','Desa / Kelurahan Tidak Boleh Kosong!','warning')
		} else if(!agama) {
			swal('Whooops','Agama Tidak Boleh Kosong!','warning')
		} else if(!s_perkawinan) {
			swal('Whooops','Status Perkawinan Tidak Boleh Kosong!','warning')
		} else if(!jenis_pasien || !pem_pasien) {
			swal('Whooops','Jenis / Pembayaran Pasien Tidak Boleh Kosong!','warning')
		} else{
			var data = new FormData($('.formAdd')[0])
			sendToCounterPoli(data).then(async(data)=>{
				if(data.metadata.code==200){
					let idAntrian = $('#id_antrian').val()
					window.location.href = '{{route("bridging")}}?id='+idAntrian
				}else{
					swal({
						title: 'Whoops..',
						type: data.metadata.status,
						text: data.metadata.message,
						showConfirmButton: true,
					})
				}
			}).catch((e)=>{
				console.log(e)
				swal({
					title: 'Whoops..',
					type: e.responseJSON.metadata.status,
					text: e.responseJSON.metadata.message,
					showConfirmButton: true,
				})
			})

			// $.ajax({
			// 	url: "{{route('saveListAntrian')}}",
			// 	type: 'POST',
			// 	data: data,
			// 	async: true,
			// 	cache: false,
			// 	contentType: false,
			// 	processData: false
			// }).done(function(data) {
			// 	// $('.formAdd').validate(data, 'has-error');
			// 	if (data.code == 200) {
			// 		if (data.antrian.jenis_pasien == 'BPJS') {
			// 			swal("Berhasil!", "Data Berhasil Disimpan", "success");
			// 			// window.location.href = '{{ route("apm") }}?id='+id_antrian;
			// 			window.location.href = '{{ route("bridging") }}?id='+id_antrian;
			// 		} else {
			// 			var kd =  data.antrian.kode_booking;
			// 			$.post('{{route("loketToCounter")}}',{kode:kd}).done((res)=>{
			// 				if(res.status == 'success'){
			// 					swal({
			// 						title: 'Berhasil',
			// 						type: res.status,
			// 						text: res.message,
			// 						showConfirmButton: true,
			// 					})
			// 					// main-layer
			// 					$('.other-page').fadeOut(function() {
			// 						$('.other-page').empty();
			// 						$('.main-layer').fadeIn();
			// 						$('#dataTable').DataTable().ajax.reload();
			// 					});
			// 				}else{
			// 					swal({
			// 						title: 'Whoops',
			// 						type: res.status,
			// 						text: res.message,
			// 					})
			// 					location.reload();
			// 				}
			// 			})
			// 		}
			// 	} else {
			// 		swal("Warning!", "Data Gagal Disimpan", "error");
			// 	}
			// }).fail(function() {
			// 	Swal.fire("MAAF!", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
			// 	$('.btn-sep').val('Simpan').removeAttr('disabled');
			// });
		}
	})

	// Batalkan antrian
	function batalkan(kode) {
		swal({
			title: "Konfirmasi Batal!",
			text: "KODE BOOKING :" + kode,
			type: "input",
			showCancelButton: true,
			closeOnConfirm: false,
			inputPlaceholder: "Alasan Batal..."
		}, function (inputValue) {
			if (inputValue === false) return false;
			if (inputValue === "") {
				swal.showInputError("Inputan Tidak Boleh Kosong!");
				return false
			} else {
				var input = inputValue;

				$.ajax({
					type: "post",
					url: "{{route('batalAntrian')}}",
					data: {
						keterangan:input,
						kodebooking:kode
					},
					success: function (response) {
						if(response.metaData.code == 200){
							swal("Berhasil!", "Antrian Berhasil Dibatalkan", "success");
							location.reload();
						}else{
							swal("Warning!", response.metaData.message, "error");
						}
					}
				});
			}
		});
	}

	// BTN KEMBALI
	$('.btn-cancel').click(function(e) {
		e.preventDefault();
		$('.add-form').fadeOut();
		$('.other-page').fadeOut(function() {
			$('.other-page').empty();
			$('.main-layer').fadeIn();
			@if($view == 1) window.location.reload(); @endif
		});
	});

	$(document).ready(function () {
		setTimeout(()=>{
			@if($view==0)
			$('.select2').select2()
			@endif
			loadDaerah();
		},200)

		$('#provinsi').change(function(){
			var id = $('#provinsi').val();
			$.post("{{route('get_kabupaten')}}",{id:id},function(data){
				var kabupaten = '<option value="">..:: Pilih Kabupaten ::..</option>';
				if(data.status=='success'){
					if(data.data.length>0){
						$.each(data.data,function(v,k){
							kabupaten+='<option value="'+k.id+'">'+k.name+'</option>';
						});
					}
				}
				$('#kabupaten').html(kabupaten);
			});
		});

		$('#kabupaten').change(function(){
			var id = $('#kabupaten').val();
			$.post("{{route('get_kecamatan')}}",{id:id},function(data){
				var kecamatan = '<option value="">..:: Pilih Kecamatan ::..</option>';
				if(data.status=='success'){
					if(data.data.length>0){
						$.each(data.data,function(v,k){
							kecamatan+='<option value="'+k.id+'">'+k.name+'</option>';
						});
					}
				}
				$('#kecamatan').html(kecamatan);
			});
		});

		$('#kecamatan').change(function(){
			var id = $('#kecamatan').val();
			$.post("{{route('get_desa')}}",{id:id},function(data){
				var desa = '<option value="">..:: Pilih Desa ::..</option>';
				if(data.status=='success'){
					if(data.data.length>0){
						$.each(data.data,function(v,k){
							desa+='<option value="'+k.id+'">'+k.name+'</option>';
						});
					}
				}
				$('#desa').html(desa);
			});
		});
	});

	function loadDaerah(kab='',kec='',kel='') {
		var id = $('#provinsi').val()
		// SELECTED KABUPATEN
		var selectedkab = "{{ !empty($kab) ? $kab:'' }}"
		setTimeout(()=>{
			if(kab=='-'){
				selectedkab = ''
			}else if(kab){
				selectedkab = kab
			}
			// if (selectedkab != "" && selectedkab != null) {
				$.post("{{route('get_kabupaten')}}",{id:id},(data)=>{
					var kabupaten = '<option value="first">..:: Pilih Kabupaten ::..</option>'
					if(data.status=='success'){
						if(data.data.length>0){
							$.each(data.data,function(v,k){
								kabupaten+='<option value="'+k.id+'">'+k.name+'</option>'
							})
						}
						$('#kabupaten').html(kabupaten)
						$('#kabupaten').val((selectedkab?selectedkab:'first')).trigger('change')
					}
				});
			// }
		},200)

		var selectedkec = "{{ !empty($kec) ? $kec:'' }}";
		setTimeout(() => {
			// SELECTED KECAMATAN
			if(kec=='-'){
				selectedkec = ''
			}else if(kec){
				selectedkec = kec
			}
			// if (selectedkec != "" && selectedkec != null) {
				$.post("{{route('get_kecamatan')}}",{id:selectedkab},(data)=>{
					var kecamatan = '<option value="first">..:: Pilih Kecamatan ::..</option>';
					if(data.status=='success'){
						if(data.data.length>0){
							$.each(data.data,function(v,k){
								kecamatan+='<option value="'+k.id+'">'+k.name+'</option>';
							});
						}
					}

					$('#kecamatan').html(kecamatan);
					$('#kecamatan').val((selectedkec?selectedkec:'first')).trigger('change');
				});
			// }
		}, 600);

		var selectedkel = "{{ !empty($kel) ? $kel:'' }}";
		setTimeout(() => {
			// SELECTED DESA/KELURAHAN
			if(kel=='-'){
				selectedkel = ''
			}else if(kel){
				selectedkel = kel
			}
			// if (selectedkel != "" && selectedkel != null) {
				$.post("{{route('get_desa')}}",{id:selectedkec},function(data){
					var desa = '<option value="first">..:: Pilih Desa ::..</option>';
					if(data.status=='success'){
						if(data.data.length>0){
							$.each(data.data,function(v,k){
								desa+='<option value="'+k.id+'">'+k.name+'</option>';
							});
						}
					}

					$('#desa').html(desa);
					$('#desa').val((selectedkel?selectedkel:'first')).trigger('change');
				});
			// }
		}, 1200);
	}
</script>