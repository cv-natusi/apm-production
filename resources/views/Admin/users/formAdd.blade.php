<div class="box box-warning" id='panel-add'>
	<button type="button" class="btn btn-warning btn-cancel"><span class="fa fa-chevron-left"></span> Kembali</button>
	<hr>
	<form method='post' action="{{ route('AddUsers') }}" enctype='multipart/form-data'>
		{{ csrf_field() }}
		<div class="box-body">
			<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2' style='padding:0px'>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Email</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="text" name="email" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
						<i>* digunakan sebagai username & sandi sementara</i>
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Nama Lengkap</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="text" name="name_user" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Nama Panggilan</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="text" name="alias" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Alamat</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<textarea rows='2' name='address_user' maxlength='100' required="required" class='form-control input-sm customInput col-md-7 col-xs-12'></textarea>
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Telp</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="text" name="phone" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Foto</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="file" class="upload" onchange="loadFilePhoto(event)" name="photo_user" accept="image/*" class="form-control customInput input-sm col-md-7 col-xs-12">
					</div>
					<div class='clearfix' style='padding-bottom:5px'></div>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 col-lg-offset-3 col-md-offset-3">
						<div class="crop-edit">
							<center>
								<img id="preview-photo" src="{!! url('AssetsAdmin/dist/img/default_image.jpg') !!}" class="img-polaroid" width="100">
							</center>
						</div>
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Status</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="radio" name="active" value='active' required="required" id='ya'><label for='ya' style='margin-right:10px;font-weight:normal'>Aktif</label>
						<input type="radio" name="active" value='non-active' required="required" id='tidak'><label for='tidak' style='font-weight:normal'>Tidak Aktif</label>
					</div>
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

    function loadFilePhoto(event) {
        var image = URL.createObjectURL(event.target.files[0]);
        $('#preview-photo').fadeOut(function(){
        	$(this).attr('src', image).fadeIn().css({
        		'-webkit-animation' : 'showSlowlyElement 700ms',
        		'animation'         : 'showSlowlyElement 700ms'
        	});
        });
    };
</script>