<div class="box box-warning" id='panel-add'>
	<button type="button" class="btn btn-warning btn-cancel"><span class="fa fa-chevron-left"></span> Kembali</button>
	<hr>
	<form method='post' action="{{ route('storeOperasi') }}" enctype='multipart/form-data'>
		{{ csrf_field() }}
		<div class="box-body">
			<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2' style='padding:0px'>
			<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Tanggal</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="date" name="tanggal" id="tanggal" required="required" value="{{date('Y-m-d')}}" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>No RM</label>
					<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
						<input type="text" name="norm" id="norm" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
					<button class="btn-cari-peserta" type="button">:</button>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Nama Pasien</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="text" name="nama_pasien" id="nama_pasien" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>No BPJS</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="text" name="no_bpjs" id="no_bpjs" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Pembiayaan</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<select name="jenisPeserta" class="" id="penjamin" style="width: 84%;">
						<option value="">.: Pilih Jenis Pasien :. </option>
						@foreach($data['jenispasien'] as $j)
							<option value="{!! $j->subgroups !!}">{!! $j->nilaichar !!}</option>
						@endforeach
					</select>
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Poli</label>
					<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
						<input type="hidden" name="kdpoli" id="kdpoli" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
						<input type="text" name="namapoli" id="namapoli" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<button class="btn-cari-poli" type="button">:</button>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Jenis Tindakan</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<textarea rows='2' name='jenis_tindakan' maxlength='100' required="required" class='form-control input-sm customInput col-md-7 col-xs-12'></textarea>
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Nama DPJP</label>
					<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
						<input type="hidden" name="dpjpLayan" id="dpjpLayan" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
						<input type="text" name="dpjp_layan" id="dpjp_layan" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
					<button class="btn-cari-dpjp" type="button">:</button>
				</div>
				<div class='clearfix' style='padding-bottom:2px;'></div>
			</div>
			<div class='clearfix' style='padding-bottom:5px'></div>
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right">Simpan <span class="fa fa-save"></span></button>
		</div>
	</form>
</div>
<script type="text/javascript">
	var onLoad = (function() {
		$('#panel-add').animateCss('bounceInUp');
	})();

	$('.btn-cancel').click(function(e){
    	e.preventDefault();
    	$('#panel-add').animateCss('bounceOutDown');
    	$('.other-page').fadeOut(function(){
    		$('.other-page').empty();
            $('.main-layer').fadeIn();
        });
    });

	$('.btn-cari-peserta').click(function(){
		$.post("{!! route('formPasienOperasi') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});
	$('.btn-cari-poli').click(function(){
		$.post("{!! route('formPoliOperasi') !!}").done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});

	$('.btn-cari-dpjp').click(function(){
		var poli = $('#kdpoli').val();
		$.post("{!! route('formDPJPOperasi') !!}",{dpjp:'layan', poli:poli}).done(function(data){
			if(data.status == 'success'){
				$('.modal-dialog').html(data.content);
			}
		});
	});
</script>