@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
	<section class="content-header">
		<h1>
			Device
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
						<!-- <button type="button" class="btn btn-sm btn-primary" id="btn-add">
							<span class="fa fa-plus"></span> &nbsp New
						</button> -->
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
	<script type="text/javascript">
		var datagrid = $("#datagrid").datagrid({
			url                     : "{!! route('DeviceDatagrid') !!}",
			primaryField            : 'id_device', 
			rowNumber               : true, 
			rowCheck                : false, 
			searchInputElement      : '#search', 
			searchFieldElement      : '#search-option', 
			pagingElement           : '#paging', 
			optionPagingElement     : '#option', 
			pageInfoElement         : '#info',
			columns                 : [
				{field: 'KodeCust', title: 'Kode Pasien', editable: false, sortable: true, width: 150, align: 'left', search: true},
				// {field: 'NamaCust', title: 'Nama Pasien', editable: false, sortable: true, width: 300, align: 'left', search: true},
				{field: 'imei', title: 'Imei', editable: false, sortable: true, width: 200, align: 'left', search: true},
				{field: 'accepteds', title: 'Status', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return accepteds(rowData, rowIndex);
					}
				},
				{field: 'reset', title: 'Reset', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return reset(rowData, rowIndex);
					}
				},
				{field: 'hapus', title: 'Delete', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return hapus(rowData, rowIndex);
					}
				}
			]
		});

		$(document).ready(function() {
			datagrid.run();
		});

		function accDevice(rowIndex){
			var rowData = datagrid.getRowData(rowIndex);
			swal(
				{
					title: "Apa anda yakin Mem-Verifikasi Device Ini?",
					text: "Device akan diverifikasi oleh sistem!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('AccDevice') !!}", {id:rowData.id_device}).done(function(data){
						if(data.status == 'success'){
							datagrid.reload();
							swal("Success!", "Device Berhasil di Verifikasi", "success");
						}
					});
				}
			);
		}

		function blockDevice(rowIndex){
			var rowData = datagrid.getRowData(rowIndex);
			swal(
				{
					title: "Apa anda yakin Mem-Block Device Ini?",
					text: "Device akan diblock oleh sistem!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('BlockDevice') !!}", {id:rowData.id_device}).done(function(data){
						if(data.status == 'success'){
							datagrid.reload();
							swal("Success!", "Device Berhasil di Block", "success");
						}
					});
				}
			);
		}

		function resets(rowIndex){
			var rowData = datagrid.getRowData(rowIndex);
			swal(
				{
					title: "Apa anda yakin Mereset Device Ini?",
					text: "Imei Device akan direset menjadi Kosong dan tidak dapat dikembalikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('ResetDevice') !!}", {id:rowData.id_device}).done(function(data){
						if(data.status == 'success'){
							datagrid.reload();
							swal("Success!", "Imei Device Berhasil Direset", "success");
						}
					});
				}
			);
		}

		function deleted(rowIndex) {
			var rowData = datagrid.getRowData(rowIndex);
			swal(
				{
					title: "Apa anda yakin Menghapus Device Ini?",
					text: "Device akan dihapus dari sistem dan tidak dapat dikembalikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('deleteDevice') !!}", {id:rowData.id_device}).done(function(data){
						if(data.status == 'success'){
							datagrid.reload();
							swal("Success!", "Device Berhasil Dihapus", "success");
						}
					});
				}
			);
		}

		function accepteds(rowData, rowIndex) {
			var accepteds = datagrid.getRowData(rowIndex).accepted;
			if (accepteds == '1') {
				var tag = '<a href="javascript:void(0)" class="btn btn-xs btn-success m-0" ondblclick="blockDevice('+rowIndex+')"><span class="fa fa-check"></span> &nbsp Accept</a>';
			}else{
				var tag = '<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" ondblclick="accDevice('+rowIndex+')"><span class="fa fa-close"></span> &nbsp Block</a>';
			}
			return tag;
		}

		function reset(rowData, rowIndex) {
			var tag = '<a href="javascript:void(0)" class="btn btn-xs btn-info m-0" onclick="resets('+rowIndex+')"><span class="fa fa-refresh"></span> &nbsp Reset</a>';
			return tag;
		}

		function hapus(rowData, rowIndex) {
			var tag = '<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="deleted('+rowIndex+')"><span class="fa fa-trash-o"></span> &nbsp Hapus</a>';
			return tag;
		}
	</script>
@stop