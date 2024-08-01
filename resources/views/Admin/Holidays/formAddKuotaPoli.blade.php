<div class="box box-warning" id='panel-add'>
	<h4 style="margin-left: 15px">KUOTA POLI</h4>
	<hr>
	<form method='post' action="{{ route('AddKuotaPoliHoliday') }}" enctype='multipart/form-data'>
		{{ csrf_field() }}
		<div class="box-body">
			<div class="row" style="padding: 5px">
				<div class="col-md-12">
					<div class="row" style="margin-bottom: 10px">
						<div class="col-md-4">
							<label for="">Tanggal Libur</label>
							<input type="date" name="tanggal_libur" class="form-control" placeholder="dd-mm-yyyy" autocomplete='off'>
						</div>
						<div class="col-md-8">
							<label for="">Pilih Poli</label>
							<select name="pilih_poli" id="form_pilih_poli" class="form-control">
								<option value="">Pilih Poli</option>
								@foreach ($poli as $item)
									<option value="{{ $item->KodePoli}}">{{ $item->NamaPoli}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row" style="margin-bottom: 10px">
						<div class="col-md-4">
							<label for="">Kuota WA</label>
							<input type="text" class="form-control" name="kuota_wa" id="form_kuota_wa" placeholder="Tulis kuota WA">
						</div>
						<div class="col-md-4">
							<label for="">Kuota Kios - K</label>
							<input type="text" class="form-control" name="kuota_kiosk" id="form_kuota_kiosk" placeholder="Tulis kuota Kios - K">
						</div>
					</div>
					<div class="row" style="margin-bottom: 10px">
						<div class="col-md-12">
							<label for="">Keterangan</label>
							<textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="10"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<button type="button" class="btn btn-warning btn-cancel"><span class="fa fa-chevron-left"></span> Kembali</button>
			<button type="submit" class="btn btn-primary">Simpan <span class="fa fa-save"></span></button>
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

	$( 'textarea#keterangan' ).ckeditor({width:'100%', height: '150px', toolbar: [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: ['NewPage', 'Preview', 'Print', '-', 'Templates' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-'] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
        { name: 'tools', items: [ 'Maximize' ] },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'styles', items: [ 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
	]});
</script>
	