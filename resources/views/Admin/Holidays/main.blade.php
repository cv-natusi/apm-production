@extends('Admin.master.layout')

@section('extended_css')
	<!-- DateTimePicker -->
	<link href="{!! url('AssetsAdmin/datetimepicker/bootstrap-datetimepicker.min.css') !!}" rel="stylesheet" type="text/css" />
@stop

@section('content')
	<section class="content-header">
		<h1>
			Tanggal Libur
		</h1>
	</section>
	<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<div class="loading" align="center" style="display: none;">
			<img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}" width="45%">
		</div>
	</div>
	@if(session('kuota_poli'))
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				$('.btn-kuota-poli').click()
			});
		</script>
	@elseif(session('libur_poli'))
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				$('.btn-libur-poli').click()
			});
		</script>
	@endif
	<div class="button" style="padding-top: 15px; padding-left: 18px">
		<button class="btn btn-success btn-libur-nasional">Libur Nasional</button>
		<button class="btn btn-kuota-poli" style="background-color: #A9A9A9; border-color: #A9A9A9;">Kuota Poli</button>
		<button class="btn btn-libur-poli" style="background-color: #A9A9A9; border-color: #A9A9A9;">Libur Poli</button>
	</div>
	<section class="content">
		<div class="row">
			{{-- Libur Nasional --}}
			<div class="libur-nasional" style="display: ">
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="box box-primary main-layer">
						<div class="col-md-4 col-sm-4 col-xs-12 form-inline main-layer" style='padding:5px'>
							<button type="button" class="btn btn-sm btn-primary" id="btn-add">
								<span class="fa fa-plus"></span> &nbsp Libur Nasional
							</button>
						</div>
						<!-- Search -->
						<div class="col-md-8 col-sm-8 col-xs-12 form-inline main-layer" style="text-align: right;padding:5px;">
							<div class="form-group">
								<select class="input-sm form-control input-s-sm inline v-middle option-search" id="search-option"></select>
							</div>
							<div class="form-group">
								<input type="text" class="input-sm form-control" placeholder="Search" id="search">
							</div>
						</div>
						<div class='clearfix'></div>
						<div class="col-md-12" style='padding:0px'>
							<!-- Datagrid -->
							<div class="table-responsive">
								<table class="table table-striped b-t b-light" id="datagrid"></table>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<!-- Page Option -->
									<div class="col-sm-1 hidden-xs">
										<select class="input-sm form-control input-s-sm inline v-middle option-page" id="option"></select>
									</div>
									<!-- Page Info -->
									<div class="col-sm-6 text-center">
										<small class="text-muted inline m-t-sm m-b-sm" id="info"></small>
									</div>
									<!-- Paging -->
									<div class="col-sm-5 text-right text-center-xs">
										<ul class="pagination pagination-sm m-t-none m-b-none" id="paging"></ul>
									</div>
								</div>
							</footer>
						</div>
						<div class='clearfix'></div>
					</div>
					<div class="other-page"></div>
					<div class="modal-dialog"></div>
				</div>
			</div>

			{{-- Kuota Poli --}}
			<div class="kuota-poli" style="display: none">
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="box box-primary main-layer">
						<div class="col-md-4 col-sm-4 col-xs-12 form-inline main-layer" style='padding:5px'>
							<button type="button" class="btn btn-sm btn-primary" id="btn-add-kuota-poli">
								<span class="fa fa-plus"></span> &nbsp Kuota Poli
							</button>
						</div>
						<!-- Search -->
						<div class="col-md-8 col-sm-8 col-xs-12 form-inline main-layer" style="text-align: right;padding:5px;">
							<div class="form-group">
								<select class="input-sm form-control input-s-sm inline v-middle option-search" id="search-option-kuota-poli"></select>
							</div>
							<div class="form-group">
								<input type="text" class="input-sm form-control" placeholder="Search" id="search-kuota-poli">
							</div>
						</div>
						<div class='clearfix'></div>
						<div class="col-md-12" style='padding:0px'>
							<!-- Datagrid -->
							<div class="table-responsive">
								<table class="table table-striped b-t b-light" id="datagridKuotaPoli"></table>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<!-- Page Option -->
									<div class="col-sm-1 hidden-xs">
										<select class="input-sm form-control input-s-sm inline v-middle option-page" id="option-kuota-poli"></select>
									</div>
									<!-- Page Info -->
									<div class="col-sm-6 text-center">
										<small class="text-muted inline m-t-sm m-b-sm" id="info-kuota-poli"></small>
									</div>
									<!-- Paging -->
									<div class="col-sm-5 text-right text-center-xs">
										<ul class="pagination pagination-sm m-t-none m-b-none" id="paging-kuota-poli"></ul>
									</div>
								</div>
							</footer>
						</div>
						<div class='clearfix'></div>
					</div>
					<div class="other-page"></div>
					<div class="modal-dialog"></div>
				</div>
			</div>

			{{-- Libur Poli --}}
			<div class="libur-poli" style="display: none">
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<div class="box box-primary main-layer">
						<div class="col-md-4 col-sm-4 col-xs-12 form-inline main-layer" style='padding:5px'>
							<button type="button" class="btn btn-sm btn-primary" id="btn-add-libur-poli">
								<span class="fa fa-plus"></span> &nbsp Libur Poli
							</button>
						</div>
						<!-- Search -->
						<div class="col-md-8 col-sm-8 col-xs-12 form-inline main-layer" style="text-align: right;padding:5px;">
							<div class="form-group">
								<select class="input-sm form-control input-s-sm inline v-middle option-search" id="search-option-libur-poli"></select>
							</div>
							<div class="form-group">
								<input type="text" class="input-sm form-control" placeholder="Search" id="search-libur-poli">
							</div>
						</div>
						<div class='clearfix'></div>
						<div class="col-md-12" style='padding:0px'>
							<!-- Datagrid -->
							<div class="table-responsive">
								<table class="table table-striped b-t b-light" id="datagridLiburPoli"></table>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<!-- Page Option -->
									<div class="col-sm-1 hidden-xs">
										<select class="input-sm form-control input-s-sm inline v-middle option-page" id="option-libur-poli"></select>
									</div>
									<!-- Page Info -->
									<div class="col-sm-6 text-center">
										<small class="text-muted inline m-t-sm m-b-sm" id="info-libur-poli"></small>
									</div>
									<!-- Paging -->
									<div class="col-sm-5 text-right text-center-xs">
										<ul class="pagination pagination-sm m-t-none m-b-none" id="paging-libur-poli"></ul>
									</div>
								</div>
							</footer>
						</div>
						<div class='clearfix'></div>
					</div>
					<div class="other-page"></div>
					<div class="modal-dialog"></div>
				</div>
			</div>
		</div>
	</section>
@stop

@section('script')
	<!-- DateTimePicker -->
	<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/ckeditor.js') !!}"></script>
	<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/adapters/jquery.js') !!}"></script>
	<script src="{!! url('AssetsAdmin/datetimepicker/bootstrap-datetimepicker.min.js') !!}"></script>
	<script type="text/javascript">
		var datagrid = $("#datagrid").datagrid({
			url                     : "{!! route('holidayDatagrid') !!}",
			primaryField            : 'id_holiday', 
			rowNumber               : true, 
			rowCheck                : false, 
			searchInputElement      : '#search', 
			searchFieldElement      : '#search-option', 
			pagingElement           : '#paging', 
			optionPagingElement     : '#option', 
			pageInfoElement         : '#info',
			columns                 : [
				{field: 'tanggal', title: 'Tanggal', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'keterangan', title: 'Keterangan', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'edit', title: 'Edit', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return edit(rowData, rowIndex)
					}
				},
				{field: 'hapus', title: 'Delete', editable: false, sortable: true, width: 100, align: 'center', search: true,
					rowStyler: function(rowData, rowIndex) {
						return hapus(rowData, rowIndex)
					}
				}
			]
		})

		var datagridKuotaPoli = $("#datagridKuotaPoli").datagrid({
			url                     : "{!! route('holidayDatagridKuotaPoli') !!}",
			primaryField            : 'id_holiday', 
			rowNumber               : true, 
			rowCheck                : false, 
			searchInputElement      : '#search-kuota-poli', 
			searchFieldElement      : '#search-option-kuota-poli', 
			pagingElement           : '#paging-kuota-poli', 
			optionPagingElement     : '#option-kuota-poli', 
			pageInfoElement         : '#info-kuota-poli',
			columns                 : [
				{field: 'tanggal', title: 'Tanggal', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'NamaPoli', title: 'Poli', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'kuota_wa', title: 'Kuota WA', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'kuota_kiosk', title: 'Kuota Kios K', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'keterangan', title: 'Keterangan', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'edit', title: 'Edit', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return editKuotaPoli(rowData, rowIndex)
					}
				},
				{field: 'hapus', title: 'Delete', editable: false, sortable: true, width: 100, align: 'center', search: true,
					rowStyler: function(rowData, rowIndex) {
						return hapusKuotaPoli(rowData, rowIndex)
					}
				}
			]
		})

		var datagridLiburPoli = $("#datagridLiburPoli").datagrid({
			url                     : "{!! route('holidayDatagridLiburPoli') !!}",
			primaryField            : 'id_holiday', 
			rowNumber               : true, 
			rowCheck                : false, 
			searchInputElement      : '#search-libur-poli', 
			searchFieldElement      : '#search-option-libur-poli', 
			pagingElement           : '#paging-libur-poli', 
			optionPagingElement     : '#option-libur-poli', 
			pageInfoElement         : '#info-libur-poli',
			columns                 : [
				{field: 'tanggal', title: 'Tanggal', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'NamaPoli', title: 'Poli', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'keterangan', title: 'Keterangan', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'edit', title: 'Edit', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return editLiburPoli(rowData, rowIndex)
					}
				},
				{field: 'hapus', title: 'Delete', editable: false, sortable: true, width: 100, align: 'center', search: true,
					rowStyler: function(rowData, rowIndex) {
						return hapusLiburPoli(rowData, rowIndex)
					}
				}
			]
		})

		$(document).ready(function() {
			datagrid.run()
			datagridKuotaPoli.run()
			datagridLiburPoli.run()
		});

		$('#btn-add').click(function(){
			$('.loading').show()
			$('.main-layer').hide()
			$.post("{!! route('formAddHoliday') !!}").done(function(data){
				if(data.status == 'success'){
					$('.loading').hide()
					$('.other-page').html(data.content).fadeIn()
				} else {
					$('.main-layer').show()
				}
			})
		})

		$('#btn-add-kuota-poli').click(function(){
			$('.loading').show();
			$('.main-layer').hide();
			$.post("{!! route('formAddKuotaPoliHoliday') !!}").done(function(data){
				if(data.status == 'success'){
					$('.loading').hide()
					$('.other-page').html(data.content).fadeIn()
				} else {
					$('.main-layer').show()
				}
			})
		})

		$('#btn-add-libur-poli').click(function(){
			$('.loading').show()
			$('.main-layer').hide()
			$.post("{!! route('formAddLiburPoliHoliday') !!}").done(function(data){
				if(data.status == 'success'){
					$('.loading').hide()
					$('.other-page').html(data.content).fadeIn()
				} else {
					$('.main-layer').show()
				}
			})
		})

		function updated(rowIndex){
			var rowData = datagrid.getRowData(rowIndex)
			$('.loading').show()
			$('.main-layer').hide()
			$.post("{!! route('formUpdateHoliday') !!}", {id:rowData.id_holiday}).done(function(data){
				if(data.status == 'success'){
					$('.loading').hide()
					$('.other-page').html(data.content).fadeIn()
				} else {
					$('.main-layer').show()
				}
			})
		}

		function updatedKuotaPoli(rowIndex){
			var rowData = datagridKuotaPoli.getRowData(rowIndex)
			$('.loading').show()
			$('.main-layer').hide()
			$.post("{!! route('formUpdateKuotaPoliHoliday') !!}", {id:rowData.id_holiday}).done(function(data){
				if(data.status == 'success'){
					$('.loading').hide()
					$('.other-page').html(data.content).fadeIn()
				} else {
					$('.main-layer').show()
				}
			})
		}

		function updatedLiburPoli(rowIndex){
			var rowData = datagridLiburPoli.getRowData(rowIndex)
			$('.loading').show()
			$('.main-layer').hide()
			$.post("{!! route('formUpdateLiburPoliHoliday') !!}", {id:rowData.id_holiday}).done(function(data){
				if(data.status == 'success'){
					$('.loading').hide()
					$('.other-page').html(data.content).fadeIn()
				} else {
					$('.main-layer').show()
				}
			})
		}

		function deleted(rowIndex) {
			var rowData = datagrid.getRowData(rowIndex)
			swal(
				{
					title: "Apa anda yakin Menghapus Tanggal Libur Ini?",
					text: "Tanggal Libur akan dihapus dari sistem dan tidak dapat dikembalikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('deleteHoliday') !!}", {id:rowData.id_holiday}).done(function(data){
						if(data.status == 'success'){
							datagrid.reload()
							swal("Success!", "Tanggal Berhasil Dihapus", "success")
						}
					})
				}
			)
		}

		function deletedKuotaPoli(rowIndex) {
			var rowData = datagridKuotaPoli.getRowData(rowIndex)
			swal(
				{
					title: "Apa anda yakin Menghapus Tanggal Libur Ini?",
					text: "Tanggal Libur akan dihapus dari sistem dan tidak dapat dikembalikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('deleteKuotaPoliHoliday') !!}", {id:rowData.id_holiday}).done(function(data){
						if(data.status == 'success'){
							datagridKuotaPoli.reload()
							swal("Success!", "Tanggal Berhasil Dihapus", "success")
						}
					})
				}
			)
		}

		function deletedLiburPoli(rowIndex) {
			var rowData = datagridLiburPoli.getRowData(rowIndex)
			swal(
				{
					title: "Apa anda yakin Menghapus Tanggal Libur Ini?",
					text: "Tanggal Libur akan dihapus dari sistem dan tidak dapat dikembalikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('deleteLiburPoliHoliday') !!}", {id:rowData.id_holiday}).done(function(data){
						if(data.status == 'success'){
							datagridLiburPoli.reload()
							swal("Success!", "Tanggal Berhasil Dihapus", "success")
						}
					})
				}
			)
		}

		function edit(rowData, rowIndex) {
			return '<a href="javascript:void(0)" class="btn btn-sm btn-info m-0" onclick="updated('+rowIndex+')"><span class="fa fa-pencil"></span> &nbsp Edit</a>'
		}

		function editKuotaPoli(rowData, rowIndex) {
			return '<a href="javascript:void(0)" class="btn btn-sm btn-info m-0" onclick="updatedKuotaPoli('+rowIndex+')"><span class="fa fa-pencil"></span> &nbsp Edit</a>'
		}

		function editLiburPoli(rowData, rowIndex) {
			return '<a href="javascript:void(0)" class="btn btn-sm btn-info m-0" onclick="updatedLiburPoli('+rowIndex+')"><span class="fa fa-pencil"></span> &nbsp Edit</a>'
		}

		function hapus(rowData, rowIndex) {
			return '<a href="javascript:void(0)" class="btn btn-sm btn-danger m-0" onclick="deleted('+rowIndex+')"><span class="fa fa-trash-o"></span> &nbsp Hapus</a>'
		}

		function hapusKuotaPoli(rowData, rowIndex) {
			return '<a href="javascript:void(0)" class="btn btn-sm btn-danger m-0" onclick="deletedKuotaPoli('+rowIndex+')"><span class="fa fa-trash-o"></span> &nbsp Hapus</a>'
		}

		function hapusLiburPoli(rowData, rowIndex) {
			return '<a href="javascript:void(0)" class="btn btn-sm btn-danger m-0" onclick="deletedLiburPoli('+rowIndex+')"><span class="fa fa-trash-o"></span> &nbsp Hapus</a>'
		}
	</script>
		
	<script>	
		$('.btn-libur-nasional').click(function(){
			$('.libur-nasional').show()
			$('.kuota-poli').hide()
			$('.libur-poli').hide()
		})
		$('.btn-kuota-poli').click(function(){
			$('.libur-nasional').hide()
			$('.kuota-poli').show()
			$('.libur-poli').hide()
			$('.btn-libur-nasional').removeClass('btn-success').css({
				'background-color': '#A9A9A9',
				'border-color': '#A9A9A9'
			})
			$('.btn-kuota-poli').addClass('btn-success').css({
				'background-color': '',
				'border-color': ''
			})
		})
		$('.btn-libur-poli').click(function(){
			$('.libur-nasional').hide()
			$('.kuota-poli').hide()
			$('.libur-poli').show()
			$('.btn-libur-nasional').removeClass('btn-success').css({
				'background-color': '#A9A9A9',
				'border-color': '#A9A9A9'
			})
			$('.btn-libur-poli').addClass('btn-success').css({
				'background-color': '',
				'border-color': ''
			})
		})

		$(document).ready(function (){
			$('.button button').click(function (){
				$('.button button').removeClass('btn-success').css({
					'background-color': '#A9A9A9',
					'border-color': '#A9A9A9'
				})
				$(this).css({
					'background-color': '',
					'border-color': ''
				}).addClass('btn-success')
			})
		})
	</script>
@stop