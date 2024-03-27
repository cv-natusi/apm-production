<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0px;">
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="padding-top: 5px;">No RM</label>
		<label class="col-lg-9 col-md-9 col-sm-6 col-xs-6" style="padding-top: 5px;">: {{$data->KodeCust}}</label>
		<input type="hidden" name="noRMPas" id="noRmPas" value="{{$data->KodeCust}}" class="form-control">
	</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0px;">
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="padding-top: 5px;">No BPJS</label>
			<?php
			if ($data->FieldCust1 != null) {
				$noBpjsPas = $data->FieldCust1;
			}else{
				$noBpjsPas = '-';
			}
			?>
		<label class="col-lg-9 col-md-9 col-sm-6 col-xs-6" style="padding-top: 5px;">: {{$noBpjsPas}}</label>
	</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0px;">
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="padding-top: 5px;">Nama</label>
		<label class="col-lg-9 col-md-9 col-sm-6 col-xs-6" style="padding-top: 5px;">: {{$data->NamaCust}}</label>
	</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0px;">
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="padding-top: 5px;">Jenis Kelamin</label>
		<?php
		if ($data->JenisKel == 'L') {
			$jnKel = 'Laki - Laki';
		}else{
			$jnKel = 'Perempuan';
		}
		?>
		<label class="col-lg-9 col-md-9 col-sm-6 col-xs-6" style="padding-top: 5px;">: {{$jnKel}}</label>
	</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0px;">
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="padding-top: 5px;">Tanggal Lahir</label>
		<?php
		$tglLahirAwal = explode("delimiter", $data->TglLahir);
		$formatAwalTgl = explode("-", $tglLahirAwal[0]);
		$formatAkhirTgl = $formatAwalTgl[2]."-".$formatAwalTgl[1]."-".$formatAwalTgl[0];
		?>
		<label class="col-lg-9 col-md-9 col-sm-6 col-xs-6" style="padding-top: 5px;">: {{$formatAkhirTgl}}</label>
	</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0px;">
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="padding-top: 5px;">Alamat</label>
		<label class="col-lg-9 col-md-9 col-sm-6 col-xs-6" style="padding-top: 5px;">: {{$data->Alamat}}</label>
	</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0px;">
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-6 col-xs-6" style="padding-top: 5px;">No RFID</label>
		<?php
		if ($statusRfid == 'Exist') {
			if ($dataRfid->noRfid != null) {
				$noRfidPas = $dataRfid->noRfid;
				$judul = 'Edit';
				$icon = 'fa-pencil';
			}else{
				$noRfidPas = '-';
				$judul = 'Tambah';
				$icon = 'fa-plus';
			}
		}else{
			$noRfidPas = '-';
				$judul = 'Tambah';
				$icon = 'fa-plus';
		}
		?>
		<label class="col-lg-9 col-md-9 col-sm-6 col-xs-6" style="padding-top: 5px;">: {{$noRfidPas}}</label>
	</div>
</div>
<div class="clearfix" style="margin-bottom: 10px"></div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0px;">
	<a href="javascript:void(0)" onclick="printCardRfid()" class="btn btn-sm btn-info" style="margin-right:5px"><i class="fa fa-print"></i> Cetak Kartu</a>
	<a href="javascript:void(0)" onclick="addCardRfid()" class="btn btn-sm btn-success" style="margin-right:5px"><i class="fa {{$icon}}"></i> {{$judul}} Kartu</a>
</div>
<div class="clearfix" style="margin-bottom: 10px"></div>