<div class="box box-warning" id='panel-add'>
	<button type="button" class="btn btn-warning btn-cancel"><span class="fa fa-chevron-left"></span> Kembali</button>
	<hr>
	<form method='post' action="{{ route('Updatebantuan') }}" enctype='multipart/form-data'>
		{{ csrf_field() }}
		<div class="box-body">
			<div class='col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1' style='padding:0px'>
				<div class="form-group">
					<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Nama Bantuan</label>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<input type="hidden" name="id_bantu" value="{{ $bantu->id_bantuan }}" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
						<input type="text" name="nama_bantuan" value="{{ $bantu->nama_bantuan }}" required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
					</div>
				</div>
				<div class='clearfix' style='padding-bottom:5px;'></div>
				<div class="form-group">
					<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" id='label-input'>Keterangan</label>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<textarea id="editor1" name='keterangan' required="required" class='form-control input-sm customInput col-md-7 col-xs-12'>{{ $bantu->keterangan }}</textarea>
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

    $( 'textarea#editor1' ).ckeditor({width:'100%', height: '150px', toolbar: [
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