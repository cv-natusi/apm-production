<form method='post' enctype='multipart/form-data' id="form-libur-poli">
	<input type="hidden" name="kategori" value="libur-poli">
	<input type="hidden" name="holiday_id" value="{{$data?$data->id_holiday:''}}">

	<div class="box-body">
		<div class="row" style="padding: 5px">
			<div class="col-md-12">
				<div class="row" style="margin-bottom: 10px">
					<div class="col-md-6">
						<label for="pilih-kuota-poli">Pilih Poli</label>
						<select name="kode_poli" id="pilih-kuota-poli" class="form-control select2" style="width:100%;">
							<option value="">-- PILIH OPSI --</option>
							@foreach ($poli as $polis)
								{{-- <option value="{{$polis->kdpoli_rs}}">{{json_encode($polis)}}</option> --}}
								<option
									value="{{$polis->kdpoli_rs}}"
									{{$data && $data->poli_id == $polis->kdpoli_rs ? 'selected' : ''}}
								>
									{{$polis->NamaPoli}}
								</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-6">
						<label for="tanggal_libur">Tanggal Libur</label>
						<input
							type="text"
							name="tanggal"
							id="tanggal-libur-poli"
							class="form-control cs-pointer"
							readonly
							style="
								border:solid 1px #ccc;
								border-radius: 5px;
							"
						>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label for="keterangan">Keterangan</label>
						<textarea name="keterangan" id="keterangan" class="form-control keterangan" cols="30" rows="10">{{$data?$data->keterangan:''}}</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button type="button" class="btn btn-warning btn-cancel" onclick="kembali('libur-poli')"><span class="fa fa-chevron-left"></span> Kembali</button>
		<button type="button" class="btn btn-primary simpan-libur-poli">Simpan <span class="fa fa-save"></span></button>
	</div>
</form>

<script>
	$(document).ready(()=>{
		$('.select2').select2()

		$('#tanggal-libur-poli').initDatePicker({date: "{{$data && $data->tanggal ?date('d-m-Y',strtotime($data->tanggal)):''}}"})

		$('.simpan-libur-poli').click((e)=>{
			e.preventDefault()
			const data = new FormData($("#form-libur-poli")[0])
			data.set('keterangan',CKEDITOR.instances.keterangan.getData())
			$.ajax({
				url: '{{route("holiday.store")}}',
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				success: (data, status, xhr)=>{
					swal({
						title : 'Success',
						type : 'success',
						text : data.metadata.message,
						timer: 1000,
						showConfirmButton: false
					})
					setTimeout(()=>{
						kembali('libur-poli')
						setTimeout(()=>{$('#table-libur-poli').initDatatable()},400)
					},1100)
				}
			}).fail((e)=>{
				console.log(e)
				swal({
					title : 'Whoops..',
					type : 'error',
					text : e.responseJSON.metadata.message,
					showConfirmButton: true
				})
			})
		})

		// $( '.other-libur-poli .keterangan' ).ckeditor({width:'100%', height: '150px', toolbar: [
		// 	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: ['NewPage', 'Preview', 'Print', '-', 'Templates' ] },
		// 	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		// 	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-'] },
		// 	{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		// 	{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
		// 	{ name: 'tools', items: [ 'Maximize' ] },
		// 	'/',
		// 	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
		// 	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
		// 	{ name: 'styles', items: [ 'Font', 'FontSize' ] },
		// 	{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		// 	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
		// ]})
		$( '.other-libur-poli .keterangan' ).ckeditor({width:'100%', height: '150px', toolbar: [
			{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: ['NewPage', 'Preview', 'Print', '-', 'Templates' ] },
			{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
			{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-'] },
			{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
			{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
			{ name: 'tools', items: [ 'Maximize' ] },
			'/',
			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
			{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
			'/',
			{ name: 'styles', items: [ 'Font', 'FontSize' ] },
			{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		]})
	})
</script>
{{-- <script type="text/javascript">
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
</script> --}}
