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
	<section class="content">
		<div class="row">
			<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
				<div class="box box-primary main-layer">
					<div class="col-md-4 col-sm-4 col-xs-12 form-inline main-layer" style='padding:5px'>
						<button type="button" class="btn btn-sm btn-primary" id="btn-add">
							<span class="fa fa-plus"></span> &nbsp New Holiday
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
	</section>
@stop

@section('script')
	<!-- DateTimePicker -->
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
				{field: 'tanggal', title: 'Tanggal', editable: false, sortable: true, width: 150, align: 'center', search: true},
				{field: 'keterangan', title: 'Keterangan', editable: false, sortable: true, width: 450, align: 'left', search: true},
				{field: 'edit', title: 'Edit', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return edit(rowData, rowIndex);
					}
				},
				{field: 'hapus', title: 'Delete', editable: false, sortable: true, width: 100, align: 'center', search: true,
					rowStyler: function(rowData, rowIndex) {
						return hapus(rowData, rowIndex);
					}
				}
			]
		});

		$(document).ready(function() {
			datagrid.run();
		});

		$('#btn-add').click(function(){
			$('.loading').show();
			$('.main-layer').hide();
			$.post("{!! route('formAddHoliday') !!}").done(function(data){
				if(data.status == 'success'){
					$('.loading').hide();
					$('.other-page').html(data.content).fadeIn();
				} else {
					$('.main-layer').show();
				}
			});
		});

		function updated(rowIndex){
			var rowData = datagrid.getRowData(rowIndex);
			$('.loading').show();
			$('.main-layer').hide();
			$.post("{!! route('formUpdateHoliday') !!}", {id:rowData.id_holiday}).done(function(data){
				if(data.status == 'success'){
					$('.loading').hide();
					$('.other-page').html(data.content).fadeIn();
				} else {
					$('.main-layer').show();
				}
			});
		}

		function deleted(rowIndex) {
			var rowData = datagrid.getRowData(rowIndex);
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
							datagrid.reload();
							swal("Success!", "Tanggal Berhasil Dihapus", "success");
						}
					});
				}
			);
		}

		function edit(rowData, rowIndex) {
			var tag = '<a href="javascript:void(0)" class="btn btn-sm btn-info m-0" onclick="updated('+rowIndex+')"><span class="fa fa-pencil"></span> &nbsp Edit</a>';
			return tag;
		}

		function hapus(rowData, rowIndex) {
			var tag = '<a href="javascript:void(0)" class="btn btn-sm btn-danger m-0" onclick="deleted('+rowIndex+')"><span class="fa fa-trash-o"></span> &nbsp Hapus</a>';
			return tag;
		}
	</script>
@stop