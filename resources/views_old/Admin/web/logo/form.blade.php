<div class="box box-warning" id='panel-add'>
	<button type="button" class="btn btn-warning btn-cancel"><span class="fa fa-chevron-left"></span> Kembali</button>
	<hr>
	<form method='post' action="{{ route('UpdateLogo') }}" enctype='multipart/form-data'>
		{{ csrf_field() }}
		<div class="box-body">
			<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2' style='padding:0px'>
				<div class="form-group">
					<input type="hidden" name='position' value='{!! $position !!}' class="form-control" required='required'>
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>
					Logo : 
					</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="file" class="upload" onchange="loadFilePhoto(event)" name="logo" accept="image/*" class="form-control customInput input-sm col-md-7 col-xs-12">
							<i>* Rekomendasi Ukuran Logo 200x195 pixel | format file .png</i>
					</div>
					<div class='clearfix' style='padding-bottom:5px'></div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="crop-edit">
							<center>
								@if(!empty($identity->logo_kiri))
									<img id="preview-photo" src="{!! url('uploads/identitas/'.$identity->logo_kiri) !!}" class="img-polaroid" width="200">
								@else
									<img id="preview-photo" src="{!! url('AssetsSite/img/icon/default_logo.jpg') !!}" class="img-polaroid" width="200">
								@endif
							</center>
						</div>
					</div>
				</div>
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