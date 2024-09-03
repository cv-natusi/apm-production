@extends('Admin.master.layout')

@section('extended_css')
	<!-- DateTimePicker -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />

	{{-- datepicker vanillajs --}}
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">

	<style>
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: unset !important;
		}
		.select2-container .select2-selection--single .select2-selection__rendered {
			text-align: center;
		}
		.select2-container .select2-selection--single {
			box-sizing: border-box;
			cursor: pointer;
			display: grid;
			height: 33px;
			user-select: none;
			-webkit-user-select: none;
			align-content: space-around;
			justify-content: center;
		}

		/* position text selected select2 */
		.select2-container .select2-selection--single .select2-selection__rendered {
			padding-left: 0;
			padding-right: 0;
			height: auto;
			margin-top: -1px;
		}

		/* arrow icon select2 */
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 26px;
			position: absolute;
			top: 3px;
			right: 1px;
			width: 20px;
		}

		/* Datatable */
		.dataTables_scrollHeadInner div.dataTables_scrollHeadInner table.dataTable{
			width: 100% !important;
		}

		/* Badge */
		.badge{
			padding: .30em 1em;
		}
		.badge-success {
			color: #fff;
			background-color: #28a745;
		}
		.badge-secondary, .btn-secondary {
			color: #fff;
			/* background-color: #6c757d; */
			background-color: #a4a4a4;
		}
	</style>
@stop

@section('content')
{{-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> --}}
{{-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script> --}}
<!------ Include the above in your HEAD tag ---------->

<section class="content-header">
	<h1>
		Tanggal Libur
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel with-nav-tabs panel-default">
				<div class="panel-heading">
					<ul class="nav nav-tabs">
						<li class="tab-libur-nasional active"><a href="#main-libur-nasional" data-toggle="tab"><b>Libur Nasional</b></a></li>
						{{-- <li class="tab-libur-nasional"><a href="#main-libur-nasional" data-toggle="tab"><b>Libur Nasional</b></a></li> --}}
						{{-- <li class="tab-kuota-poli active"><a href="#main-kuota-poli" data-toggle="tab"><b>Kuota Poli</b></a></li> --}}
						<li class="tab-kuota-poli"><a href="#main-kuota-poli" data-toggle="tab"><b>Kuota Poli</b></a></li>
						{{-- <li class="tab-libur-poli active"><a href="#main-libur-poli" data-toggle="tab"><b>Libur Poli</b></a></li> --}}
						<li class="tab-libur-poli"><a href="#main-libur-poli" data-toggle="tab"><b>Libur Poli</b></a></li>
						{{-- <li class="dropdown">
							<a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="#tab4default" data-toggle="tab">Default 4</a></li>
								<li><a href="#tab5default" data-toggle="tab">Default 5</a></li>
							</ul>
						</li> --}}
					</ul>
				</div>
				<div class="panel-body">
					<div class="tab-content">
						@include('Admin.Holidays.libur-nasional.main')
						@include('Admin.Holidays.kuota-poli.main')
						@include('Admin.Holidays.libur-poli.main')
						{{-- <div class="tab-pane fade" id="tab4default">Default 4</div>
						<div class="tab-pane fade" id="tab5default">Default 5</div> --}}
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@stop

@section('script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>

	{{-- ckeditor --}}
	<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/ckeditor.js') !!}"></script>
	<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/adapters/jquery.js') !!}"></script>

	{{-- datepicker vanillajs --}}
	<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>

	<script>
		function dateCurrent(){
			let date = new Date()
			let days = date.getDate().toString().padStart(2, 0)
			let months = (date.getMonth() + 1).toString().padStart(2, 0)
			let years = date.getFullYear().toString()
			return `${days}-${months}-${years}`
		}
	</script>
	<script>
		/*
		|--------------------------------------------------------------------------
		| DOKUMENTASI DATEPICKER VANILLAJS
		|--------------------------------------------------------------------------
		| Official : https://mymth.github.io/vanillajs-datepicker
		|
		| Options:
		|		~> autohide
		|			- Type: Boolean
		|			- Default: false
		|		~> clearButton
		|			- Type: Boolean
		|			- Default: false
		|		~> todayButton
		|			- Type: Boolean
		|			- Default: false
		|		~> todayButtonMode
		|			- Type: Number
		|			- Default: 0
		|			- Mode:
		|				a. focus(0) Move the focused date to the current date without changing the selection
		|				b. select(1) Select (or toggle the selection of) the current date
		|		~> todayHighlight
		|			- Type: Boolean
		|			- Default: false
		|		~> maxDate
		|			- Type: String|Date|Number
		|			- Default: null
		|
		| How to use: $('input').initDatePicker()
		*/
		// $.fn.initDatePicker = function(params,type=dateCurrent()){
		$.fn.initDatePicker = async function(data={date:dateCurrent()}){
			if(!data.date){
				data['date'] = dateCurrent()
			}
			// if(data.date){
			// 	let str = data.date.split('-')
			// 		isThree = str.length===3;
			// 		str = isThree ? `${str[2]}-${str[1]}-${str[0]}` : '' // str => [0=>dd, 1=>mm, 2=>yyyy]
			// 		date = isThree ? new Date(str) : new Date();
			// }

			// You could extract the date parts and call the Date(year, month, day) constructor
			// date = new Date(data.date)
			let obj = {
				format: 'dd-mm-yyyy',
				autohide: true,
				todayButton: true,
				clearButton: true,
				todayButtonMode: 1,
				todayHighlight: true,
				// maxDate: new Date(),
				// clearButton: false
			}

			// let days = date.getDate().toString().padStart(2, 0)
			// let months = (date.getMonth() + 1).toString().padStart(2, 0)
			// let years = date.getFullYear().toString()
			// let tgl = `${days}-${months}-${years}`
			// console.log(days)
			// let tgl = `${months}-${days}-${years}`
			// console.log(tgl)
			// console.log(data.date)
			this.val(data.date) // Set value

			const datepicker = new Datepicker(this[0], obj)
			return this
			// for (const el of Object.keys(this)) {
			// 	const datepicker = new Datepicker(el, obj)
			// }
		}
	</script>

	<script type="text/javascript">
		// Add method to jQuery
		$.fn.initDatatable = function(){
			const tableID = this[0].id
			const columns = [
				{data: 'DT_Row_Index', name: 'DT_Row_Index'},
				{
					data: 'tanggal',
					name: 'tanggal',
				},
			]
			columnDefs = []
			if(tableID==='table-libur-nasional'){
				kategori = 'libur-nasional'
				columnDefs.push({className: "text-center", targets: [1,3,4]})
				columns.push({data: 'keterangan', name: 'keterangan'})
			}else if(tableID==='table-kuota-poli'){
				kategori = 'kuota-poli'
				columnDefs.push({className: "text-center", targets: [1,3,4,5,6]})
				columns.push(
					{
						data: 'nama_poli',
						name: 'nama_poli',
					},
					{
						data: 'kuota_wa',
						name: 'kuota_wa',
					},
					{
						data: 'kuota_kiosk',
						name: 'kuota_kiosk',
					},
				)
			}else if(tableID==='table-libur-poli'){
				kategori = 'libur-poli'
				columnDefs.push({className: "text-center", targets: [1,3,4]})
				columns.push({data: 'nama_poli', name: 'nama_poli'})
			}
			columns.push(
				{data: 'status', name: 'status'},
				{data: 'aksi', name: 'aksi'}
			)
			this.DataTable({
				destroy: true,
				scrollX: true,
				bPaginate: true,
				bFilter: true,
				processing: true,
				serverSide: true,
				// language: {
				// 	processing: loading,
				// },
				search: {
					caseInsensitive: true
				},
				columnDefs: columnDefs,
				// [
				// 	// {className: "text-center", targets: 1 }
				// 	// {
				// 	// 	orderable: false,
				// 	// 	targets: -1
				// 	// }
				// ],
				ajax: {
					url:"{{route('holiday.dataTable')}}",
					type: 'post',
					data: {
						kategori: kategori
					}
				},
				columns: columns,
			})

			return this
		}

		$(document).ready(()=>{
			$('#table-libur-nasional').initDatatable()
			// $('#table-kuota-poli').initDatatable()
			// $('#table-libur-poli').initDatatable()
		})
		function addForm(str){
			$.post('{{route("holiday.form")}}',{jenis:str}).done((data, status, xhr)=>{
				const code = xhr.status
				if(code==200){
					content = data.response
					if(str=='libur-nasional'){
						$('#content-libur-nasional').fadeOut(500,()=>{
							$('#other-libur-nasional').html(content).fadeIn(400)
						})
					}else if(str=='kuota-poli'){
						$('#content-kuota-poli').fadeOut(500,()=>{
							$('#other-kuota-poli').html(content).fadeIn(400)
						})
					}else if(str=='libur-poli'){
						$('#content-libur-poli').fadeOut(500,()=>{
							$('#other-libur-poli').html(content).fadeIn(400)
						})
					}
				}
			}).fail((e)=>{
				console.log(e)
			})
		}
		function editForm(obj){
			data = JSON.parse(obj)
			jenis = data.kategori
			$.post('{{route("holiday.form")}}',{
				jenis:jenis,
				id: data.id_holiday,
			}).done((data, status, xhr)=>{
				const code = xhr.status
				if(code==200){
					content = data.response
					if(jenis=='libur-nasional'){
						$('#content-libur-nasional').fadeOut(500,()=>{
							$('#other-libur-nasional').html(content).fadeIn(400)
						})
					}else if(jenis=='kuota-poli'){
						$('#content-kuota-poli').fadeOut(500,()=>{
							$('#other-kuota-poli').html(content).fadeIn(400)
						})
					}else if(jenis=='libur-poli'){
						$('#content-libur-poli').fadeOut(500,()=>{
							$('#other-libur-poli').html(content).fadeIn(400)
						})
					}
				}
			}).fail((e)=>{
				console.log(e)
			})
		}
		function updateStatus(obj){
			data = JSON.parse(obj)
			jenis = data.kategori
			swal({
				title: 'Konfirmasi',
				type: 'warning',
				text: 'Anda yakin ingin merubah status?',
				showCancelButton: true,
			},
			function(isConfirm){
				if(isConfirm){
					$.post('{{route("holiday.updateStatus")}}',{holiday_id: data.id_holiday}).done((res, status, xhr)=>{
						if(status=='nocontent'){
							setTimeout(()=>{
								swal({
									title : 'Whoops..',
									type : 'warning',
									text : 'Data tidak ditemukan',
									showConfirmButton: true
								})
							},300)
							return
						}
						setTimeout(()=>{
							swal({
								title : 'Success',
								type : 'success',
								text : 'Status berhasil dirubah',
								timer: 1100,
								showConfirmButton: false
							})
							initDatatable(data.kategori)
						},300)
					}).fail((e)=>{
						console.log(e)
						setTimeout(()=>{
							swal({
								title : 'Whoops',
								type : 'error',
								text : 'Data gagal dirubah, Internal server error!',
								showConfirmButton: true
							})
						},300)
					})
				}
			})
		}
		function destroy(obj){
			data = JSON.parse(obj)
			jenis = data.kategori
			swal({
				title: 'Anda yakin ingin menghapus data?',
				type: 'warning',
				text: 'Data yang sudah dihapus tidak bisa dikembalikan!',
				showCancelButton: true,
			},
			function(isConfirm){
				if(isConfirm){
					$.post('{{route("holiday.destroy")}}',{holiday_id: data.id_holiday}).done((res, status, xhr)=>{
						if(status=='nocontent'){
							setTimeout(()=>{
								swal({
									title : 'Whoops..',
									type : 'warning',
									text : 'Gagal menghapus, data tidak ditemukan',
									showConfirmButton: true
								})
							},300)
							return
						}
						setTimeout(()=>{
							swal({
								title : 'Success',
								type : 'success',
								text : 'Data berhasil dihapus',
								timer: 1100,
								showConfirmButton: false
							})
							setTimeout(()=>{initDatatable(data.kategori)},300)
						},300)
					}).fail((e)=>{
						console.log(e)
						setTimeout(()=>{
							swal({
								title : 'Whoops',
								type : 'error',
								text : 'Data gagal dihapus, Internal server error!',
								showConfirmButton: true
							})
						},300)
					})
				}
			})
		}
		function kembali(str){
			if(str=='libur-nasional'){
				$('#other-libur-nasional').fadeOut(()=>{
					$('#content-libur-nasional').fadeIn()
				})
			}else if(str=='kuota-poli'){
				$('#other-kuota-poli').fadeOut(()=>{
					$('#content-kuota-poli').fadeIn()
				})
			}else if(str=='libur-poli'){
				$('#other-libur-poli').fadeOut(()=>{
					$('#content-libur-poli').fadeIn()
				})
			}
		}

		function initDatatable(string=''){
			if(string=='libur-nasional'){
				$('#table-libur-nasional').initDatatable()
			}else if(string=='kuota-poli'){
				$('#table-kuota-poli').initDatatable()
			}else if(string=='libur-poli'){
				$('#table-libur-poli').initDatatable()
			}
		}


		// Include libur-nasional start
		$('.tab-libur-nasional').click((e)=>{
			// console.log('Tab libur nasional')
			if(!$.fn.DataTable.isDataTable('#table-libur-nasional')){
				setTimeout(() => {
					$('#table-libur-nasional').initDatatable()
				}, 300);
			}
		})
		// Include libur-nasional end


		// Include kuota-poli start
		$('.tab-kuota-poli').click((e)=>{
			// console.log('Tab kuota poli')
			// $('#table-kuota-poli').dataTable()
			if(!$.fn.DataTable.isDataTable('#table-kuota-poli')){
				setTimeout(() => {
					$('#table-kuota-poli').initDatatable()
				}, 300);
			}

			// var loading = '<div class="loader" id="loader-4"><span></span><span></span><span></span></div>'
		})
		// $( '.other-kuota-poli > .keterangan' ).ckeditor({width:'100%', height: '150px', toolbar: [
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
		// ]});
		// Include kuota-poli end


		// Include libur-poli start
		$('.tab-libur-poli').click((e)=>{
			if(!$.fn.DataTable.isDataTable('#table-libur-poli')){
				setTimeout(() => {
					$('#table-libur-poli').initDatatable()
				}, 300);
			}
		})
		// Include libur-poli end
	</script>
@stop