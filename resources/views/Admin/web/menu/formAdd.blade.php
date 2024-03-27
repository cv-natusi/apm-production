<div class="box box-warning" id='panel-add'>
	<button type="button" class="btn btn-warning btn-cancel"><span class="fa fa-chevron-left"></span> Kembali</button>
	<hr>
	<form method='post' action="{{ route('AddMenu') }}" enctype='multipart/form-data'>
		{{ csrf_field() }}
		<div class="box-body">
			<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2' style='padding:0px'>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Nama Menu</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="text" name="nama_menu" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input id="parent_cek" type="checkbox" name="parent_cek" value="y">Dengan Parent Menu
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group" id="parent" style="display: none">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Menu Parent</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<select name="parent" class="form-control">
							<option value="" selected disabled>..:: Parent Menu ::..</option>
						<?php
						$menus_parent = DB::table('menu')->where('parent_id','0')->get();
						foreach ($menus_parent as $mp) {
							?>
								<option value="{{$mp->id_menu}}">{{$mp->nama_menu}}</option>
							<?php
						}
						?>
						</select>
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Status</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="radio" name="aktif" value='1' required="required" id='ya'><label for='ya' style='margin-right:10px;font-weight:normal'>Aktif</label>
						<input type="radio" name="aktif" value='0' required="required" id='tidak'><label for='tidak' style='font-weight:normal'>Tidak Aktif</label>
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
    var a = 0;
    $('#parent_cek').change(function(){
    	if(a%2==0){
		$('#parent').show();
    	}else{
		$('#parent').hide();
    	}
    	a=a+1;
    });
</script>