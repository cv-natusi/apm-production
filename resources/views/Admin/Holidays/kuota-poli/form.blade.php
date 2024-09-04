<style>
	/* #tanggal-kuota-poli{
		cursor: default;
	} */
</style>
<form enctype='multipart/form-data' id="form-kuota-poli">
	{{-- {{ csrf_field() }} --}}

	<input type="hidden" name="kategori" value="kuota-poli">
	<input type="hidden" name="holiday_id" value="{{$data?$data->id_holiday:''}}">

	<div class="box-body">
		<div class="row" style="padding: 5px">
			<div class="col-md-12">
				{{-- <div class="row">
					<div class="col-md-12"><pre>{{json_encode($data,JSON_PRETTY_PRINT)}}</</div>
				</div> --}}
				<div class="row" style="margin-bottom: 10px">
					<div class="col-md-4">
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
					<div class="col-md-4">
						<label for="format-kuota-poli">Format Kuota Poli berdasarkan</label>
						<select name="format" id="format-kuota-poli" class="form-control select2" style="width:100%;">
							<option value="">-- PILIH OPSI --</option>
							<option value="tanggal">
								Tanggal
							</option>
							<option value="hari">
								Hari
							</option>
						</select>
						{{-- <input type="date" name="tanggal_libur" id="tanggal-libur" class="form-control" placeholder="dd-mm-yyyy" autocomplete='off'> --}}
					</div>
					<div class="col-md-4">
						<div id="container-hari-kuota-poli" style="display: none;">
							<label for="hari-kuota-poli">Tanggal / Hari</label>
							<select name="hari" id="hari-kuota-poli" class="form-control select2" style="width:100%;">
								<option value="">-- PILIH OPSI --</option>
								<option value="1">Senin</option>
								<option value="2">Selasa</option>
								<option value="3">Rabu</option>
								<option value="4">Kamis</option>
								<option value="5">Jum'at</option>
								<option value="5">Sabtu</option>
							</select>
						</div>
						<div id="container-tanggal-kuota-poli">
							<label for="tanggal-kuota-poli">Tanggal / Hari</label>
							<div id="input-tanggal-kuota-poli">
								<input
									class="form-control cs-default"
									id="tanggal-kuota-poli"
									name="tanggal"
									type="text"
									placeholder="Silahkan pilih format kuota terlebih dahulu"
									readonly
									disabled
								/>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="margin-bottom: 10px">
					<div class="col-md-6">
						<label for="kuota-wa">Kuota WA</label>
						<input
							type="text"
							class="form-control"
							inputmode="numeric"
							name="kuota_wa"
							id="kuota-wa"
							placeholder="Tulis kuota WA"
							value="{{$data?$data->kuota_wa:''}}"
						>
					</div>
					<div class="col-md-6">
						<label for="kuota-kiosk">Kuota Kios - K</label>
						<input
							type="text"
							class="form-control"
							name="kuota_kiosk"
							id="kuota-kiosk"
							placeholder="Tulis kuota Kios - K"
							value="{{$data?$data->kuota_kiosk:''}}"
						>
					</div>
				</div>
				<div class="row" style="margin-bottom: 10px">
					<div class="col-md-12">
						{{-- <div id="editor">
						</div> --}}
						{{-- <label for="">Keterangan</label>
						<textarea name="keterangan" id="keterangan" class="form-control keterangan" cols="30" rows="10"></textarea> --}}
					</div>
				</div>
				<div class="row" style="margin-bottom: 10px">
					<div class="col-md-12">
						<label for="">Keterangan</label>
						<textarea name="keterangan" id="keterangan" class="form-control keterangan" cols="30" rows="10">{{$data?$data->keterangan:''}}</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		@if($data)
		<button
			type="button"
			class="btn btn-default"
			id="btn-trigger"
			onclick="setHoliday(this)"
			data-is-hari="{{$data->is_hari}}"
			data-tanggal="{{date('d-m-Y',strtotime($data->tanggal))}}"
			{{-- data-tanggal="{{$data->tanggal}}" --}}
			data-hari="{{$data->hari}}"
			style="display: none;"
		>
			Trigger
		</button>
		@endif
		<button type="button" class="btn btn-warning btn-cancel" onclick="kembali('kuota-poli')"><span class="fa fa-chevron-left"></span> Kembali</button>
		<button type="submit" class="btn btn-primary simpan-kuota-poli">Simpan <span class="fa fa-save"></span></button>
	</div>
</form>

<script>
	function setHoliday($this){
		let isHari = $($this).data('is-hari')
			tanggal = $($this).data('tanggal')
			hari = $($this).data('hari')
		if(isHari==0){
			$('#tanggal-kuota-poli').initDatePicker({
				date: tanggal
			})
		}else{
			$('#hari-kuota-poli').val(hari).trigger('change')
		}
		$('#format-kuota-poli').val(isHari==0?'tanggal':'hari').trigger('change')
	}
	$(document).ready(()=>{
		$('.select2').select2()
		// CKEDITOR.replace('editor')
		$( '.other-kuota-poli .keterangan' ).ckeditor({width:'100%', height: '150px', toolbar: [
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

		$('.simpan-kuota-poli').click((e)=>{
			e.preventDefault()
			const data = new FormData($("#form-kuota-poli")[0])
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
					// console.log(data)
					swal({
						title : 'Success',
						type : 'success',
						text : data.metadata.message,
						timer: 1000,
						showConfirmButton: false
					})
					setTimeout(()=>{
						kembali('kuota-poli')
						setTimeout(()=>{$('#table-kuota-poli').initDatatable()},400)
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

		@if ($data)
			setTimeout(() => {
				$('#btn-trigger').click()
			}, 300)
			// CKEDITOR.instances['keterangan'].setData(holiday.keterangan)
		@endif
	})

	$.fn.reinitInput = function(id='default'){
		const name = id.replace(/-/g,"_")
		this.empty().append(`<input type="text" class="form-control cs-default ${id}" name="tanggal" id="${id}" readonly disabled>`)
		return this
	}
	$.fn.checkContainer = function(e){
		const format = this.val()
		if(format === 'hari'){
			$('#container-tanggal-kuota-poli').hide()
			$('#container-hari-kuota-poli').show()
		}else{
			$('#container-hari-kuota-poli').hide()
			$('#container-tanggal-kuota-poli').show()
		}
	}

	$('#kuota-wa').setRules('0-9')
	$('#kuota-kiosk').setRules('0-9')

	$('#format-kuota-poli').change((e)=>{
		const $this = $(e.currentTarget)

		$this.checkContainer()
		const $tanggalKuotaPoli = $('#tanggal-kuota-poli')

		if($this.val() === 'tanggal'){
			$tanggalKuotaPoli.attr({placeholder: 'dd-mm-yyyy'})
				.addClass('cs-pointer')
				.removeClass('cs-default')
				.removeAttr('disabled')
			if(!$tanggalKuotaPoli.hasClass('datepicker-input')){
				$tanggalKuotaPoli.initDatePicker()
			}
		}
		if(!$this.val()){
			$('#input-tanggal-kuota-poli').reinitInput('tanggal-kuota-poli')
			$('#tanggal-kuota-poli').attr({placeholder: 'Silahkan pilih format kuota terlebih dahulu'})
		}
		// if($this.val()=='tanggal'){
		// 	$('#tanggal-kuota-poli').attr('type','date')
		// 	$('#tanggal-kuota-poli').removeAttr('disabled readonly')
		// }else if($this.val()=='hari'){
		// 	$('#tanggal-kuota-poli').hide()
		// 	$('#hari-kuota-poli').show()
		// }else{
		// 	$('#tanggal-kuota-poli').attr('type','text')
		// 	$('#tanggal-kuota-poli').attr({
		// 		disabled: true,
		// 		readonly: true
		// 	})
		// 	// $('#tanggal-kuota-poli').attr('disabled',true)
		// }
		// console.log($this.val())
		// $('#tanggal-kuota-poli').removeAttr('disabled readonly')
		// $('#tanggal-kuota-poli').attr('type','date')
		// console.log($this[0].id)
	})

	// $('.simpan-kuota-poli').click((e)=>{
	// 	const data = new FormData($('#form-kuota-poli')[0])
	// 	console.log(data)
	// })

	// function checkContainer(){
	// 	if($('#container-hari-kuota-poli').is(':visible')){
	// 		$('#container-hari-kuota-poli').hide()
	// 		$('#tanggal-kuota-poli').show()
	// 	}else{
	// 		$('#tanggal-kuota-poli').hide()
	// 		$('#container-hari-kuota-poli').show()
	// 	}
	// }
</script>