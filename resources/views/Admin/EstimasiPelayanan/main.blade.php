@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
	<section class="content-header">
		<h1>
			Estimasi Pelayanan
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
							<span class="fa fa-plus"></span> &nbsp New Pelayanan
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
	<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/ckeditor.js') !!}"></script>
	<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/adapters/jquery.js') !!}"></script>
	<script type="text/javascript">
		var datagrid = $("#datagrid").datagrid({
			url                     : "{!! route('EstimasiPelayananDatagrid') !!}",
			primaryField            : 'kodepoli', 
			rowNumber               : true, 
			rowCheck                : false, 
			searchInputElement      : '#search', 
			searchFieldElement      : '#search-option', 
			pagingElement           : '#paging', 
			optionPagingElement     : '#option', 
			pageInfoElement         : '#info',
			columns                 : [
				{field: 'kodepoli', title: 'Kode Poli', editable: false, sortable: true, width: 450, align: 'left', search: true},
				{field: 'poli', title: 'Poli', editable: false, sortable: true, width: 450, align: 'left', search: true},
				{field: 'jamlayanan', title: 'Jam Layanan', editable: false, sortable: true, width: 450, align: 'left', search: true},
				{field: 'estimasi', title: 'Estimasi', editable: false, sortable: true, width: 450, align: 'left', search: true},
				{field: 'tampil1', title: 'Tampil', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return tampil(rowData, rowIndex);
					}
				},
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
			$.post("{!! route('formAddEstimasiPelayanan') !!}").done(function(data){
				if(data.status == 'success'){
					$('.loading').hide();
					$('.other-page').html(data.content).fadeIn();
				} else {
					$('.main-layer').show();
				}
			});
		});

		function view(rowIndex) {
			var id = datagrid.getRowData(rowIndex).kodepoli;
			swal(
				{
					title: "Warning",
					text: "Apakah anda yakin akan mengubah status tampil?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('tampilEstimasiPelayanan') !!}", {id:id}).done(function(data){
						if(data.status == 'success'){
							datagrid.reload();
							// $('.modal-dialog').html(data.content);
							var tampil = data.tampil;
							if(tampil == 1){
								$('.tampil_'+rowIndex).html('<a href="javascript:void(0)" class="btn btn-xs btn-info m-0" onclick="view('+rowIndex+')"><span class="fa fa-eye"></span> &nbsp Sudah Tampil</a>');
							} else {
								$('.tampil_'+rowIndex).html('<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="view('+rowIndex+')"><span class="fa fa-eye"></span> &nbsp Belum Tampil</a>')
							};
							swal("Success!", "Mengubah Status Berhasil", "success");
						}
					});
			});
		}

		function updated(rowIndex){
			var rowData = datagrid.getRowData(rowIndex);
			$('.loading').show();
			$('.main-layer').hide();
			$.post("{!! route('formUpdateEstimasiPelayanan') !!}", {id:rowData.kodepoli}).done(function(data){
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
					title: "Apa anda yakin Menghapus Pelayanan Ini?",
					text: "Pelayanan akan dihapus dari sistem dan tidak dapat dikembalikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('deleteEstimasiPelayanan') !!}", {id:rowData.kodepoli}).done(function(data){
						if(data.status == 'success'){
							datagrid.reload();
							swal("Success!", "Pelayanan Berhasil Dihapus", "success");
						}
					});
				}
			);
		}

		function tampil(rowData, rowIndex) {
			var tampil = datagrid.getRowData(rowIndex).tampil;
			if(tampil == 1){
				var tag = '<div class="tampil_'+rowIndex+'"><a href="javascript:void(0)" class="btn btn-xs btn-info m-0" onclick="view('+rowIndex+')"><span class="fa fa-eye"></span> &nbsp Sudah Tampil</a></div>';
			} else {
				var tag = '<div class="tampil_'+rowIndex+'"><a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="view('+rowIndex+')"><span class="fa fa-eye"></span> &nbsp Belum Tampil</a></div>';
			}
			return tag;
		}

		function edit(rowData, rowIndex) {
			var tag = '<a href="javascript:void(0)" class="btn btn-xs btn-warning m-0" onclick="updated('+rowIndex+')"><span class="fa fa-pencil"></span> &nbsp Edit</a>';
			return tag;
		}

		function hapus(rowData, rowIndex) {
			var tag = '<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="deleted('+rowIndex+')"><span class="fa fa-trash-o"></span> &nbsp Hapus</a>';
			return tag;
		}
	</script>
@stop