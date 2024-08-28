@extends('Admin.master.layout')

@section('extended_css')
	<!-- DateTimePicker -->
	<link href="{!! url('AssetsAdmin/datetimepicker/bootstrap-datetimepicker.min.css') !!}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
	{{-- <link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css"> --}}
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
						{{-- <li class="tab-libur-nasional active"><a href="#main-libur-nasional" data-toggle="tab"><b>Libur Nasional</b></a></li> --}}
						<li class="tab-libur-nasional"><a href="#main-libur-nasional" data-toggle="tab"><b>Libur Nasional</b></a></li>
						<li class="tab-kuota-poli active"><a href="#main-kuota-poli" data-toggle="tab"><b>Kuota Poli</b></a></li>
						{{-- <li class="tab-kuota-poli"><a href="#main-kuota-poli" data-toggle="tab"><b>Kuota Poli</b></a></li> --}}
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
	<!-- DateTimePicker -->
	<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/ckeditor.js') !!}"></script>
	<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/adapters/jquery.js') !!}"></script>
	<script src="{!! url('AssetsAdmin/datetimepicker/bootstrap-datetimepicker.min.js') !!}"></script>
	<script type="text/javascript">
		$.fn.initDatatable = function(){
			const tableID = this[0].id
			// console.log(this)
			// console.log(tableID)
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
				columnDefs: [{
					orderable: false,
					targets: -1
				}],
				ajax: {
					url:"{{route('holiday.dataTable')}}",
					type: 'post',
					// data: {
					// 	namaCounter : namaCounter,
					// 	tglAwal : tglAwal,
					// 	tglAkhir : tglAkhir
					// }
				},
				columns: [
					{data: 'DT_Row_Index', name: 'DT_Row_Index'},
					{
						data: 'tanggal', 
						name: 'tanggal',
					},
					{
						data: 'tanggal', 
						name: 'tanggal',
					},
					{
						data: 'tanggal', 
						name: 'tanggal',
					},
					{
						data: 'tanggal', 
						name: 'tanggal',
					},
					{
						data: 'tanggal', 
						name: 'tanggal',
					},
					{
						data: 'tanggal', 
						name: 'tanggal',
					},
				],
			})

		}

		$(document).ready(()=>{
			$('#table-kuota-poli').initDatatable()
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

		function initDatatable(){
			// $('#table-kuota-poli').DataTable({
			// 	destroy: true,
			// 	scrollX: true,
			// 	bPaginate: true,
			// 	bFilter: true,
			// 	processing: true,
			// 	serverSide: true,
			// 	// language: {
			// 	// 	processing: loading,
			// 	// },
			// 	search: {
			// 		caseInsensitive: true
			// 	},
			// 	columnDefs: [{
			// 		orderable: false,
			// 		targets: -1
			// 	}],
			// 	ajax: {
			// 		url:"{{route('holiday.dataTable')}}",
			// 		type: 'post',
			// 		// data: {
			// 		// 	namaCounter : namaCounter,
			// 		// 	tglAwal : tglAwal,
			// 		// 	tglAkhir : tglAkhir
			// 		// }
			// 	},
			// 	columns: [
			// 		{data: 'DT_Row_Index', name: 'DT_Row_Index'},
			// 		{
			// 			data: 'tanggal', 
			// 			name: 'tanggal',
			// 		},
			// 		{
			// 			data: 'nomor_antrian_poli', 
			// 			name: 'nomor_antrian_poli',
			// 		},
			// 		{
			// 			data: 'namaCust', 
			// 			name: 'tm_customer.NamaCust',
			// 			orderable: false,
			// 			searchable: true,
			// 		},
			// 		{
			// 			data: 'tgl_periksa', 
			// 			name: 'tgl_periksa',
			// 			render: function(data, type, row) {
			// 				return '<p style="color:black" class="text-center">' + ((data=='' || data ==null) ? '-' : data) + '</p>';
			// 			}
			// 		},
			// 		{
			// 			data: 'mapping_poli_bridging.tm_poli.NamaPoli', 
			// 			name: 'NamaPoli',
			// 			render: function(data, type, row) {
			// 				return '<p style="color:black">' + ((data=='' || data ==null) ? '-' : data) + '</p>';
			// 			}
			// 		},
			// 		{
			// 			data: 'metode_ambil', 
			// 			name: 'metode_ambil',
			// 			render: function(data, type, row) {
			// 				return '<p style="color:black" class="text-center">' + ((data=='' || data ==null) ? '-' : data) + '</p>';
			// 			}
			// 		},
			// 	],
			// })
		}


		// Include libur-nasional start
		$('.tab-libur-nasional').click((e)=>{
			// console.log('Tab libur nasional')
		})
		// Include libur-nasional end


		// Include kuota-poli start
		$('.tab-kuota-poli').click((e)=>{
			// console.log('Tab kuota poli')
			// $('#table-kuota-poli').dataTable()

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
			// console.log('Tab libur poli')
		})
		// Include libur-poli end
	</script>
@stop