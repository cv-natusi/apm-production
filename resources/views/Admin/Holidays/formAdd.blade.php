<div class="box box-warning" id='panel-add'>
	<button type="button" class="btn btn-warning btn-cancel"><span class="fa fa-chevron-left"></span> Kembali</button>
	<hr>
	<form method='post' action="{{ route('AddHoliday') }}" enctype='multipart/form-data'>
		{{ csrf_field() }}
		<div class="box-body">
			<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2' style='padding:0px'>
				<div class="form-group">
					<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" id='label-input'>Tanggal Libur</label>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group" style="padding: 0 15px">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" name='tanggal_libur' autocomplete='off' placeholder='dd-mm-yyyy' class="form-control pull-right"  id='form_datetime_today' data-date-format="dd-mm-yyyy" required='required'>
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" id='label-input'>Keterangan Libur</label>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<textarea rows='2' name='keterangan' maxlength='100' required="required" class='form-control input-sm customInput col-md-7 col-xs-12'></textarea>
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

    $('#form_datetime_today').datetimepicker({
        weekStart: 2,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
    });
</script>