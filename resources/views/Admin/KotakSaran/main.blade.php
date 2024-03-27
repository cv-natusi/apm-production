@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
	<section class="content-header">
		<h1>
			Kotak Saran
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
			url                     : "{!! route('KotakSaranDatagrid') !!}",
			primaryField            : 'id_kotak_saran', 
			rowNumber               : true, 
			rowCheck                : false, 
			searchInputElement      : '#search', 
			searchFieldElement      : '#search-option', 
			pagingElement           : '#paging', 
			optionPagingElement     : '#option', 
			pageInfoElement         : '#info',
			columns                 : [
				{field: 'KodeCust', title: 'Kode Pasien', editable: false, sortable: true, width: 150, align: 'left', search: true},
				{field: 'NamaCust', title: 'Nama Pasien', editable: false, sortable: true, width: 250, align: 'left', search: true},
				{field: 'judul', title: 'Judul', editable: false, sortable: true, width: 300, align: 'left', search: true},
				{field: 'detail', title: 'Detail', editable: false, sortable: true, width: 100, align: 'center', search: false,
					rowStyler: function(rowData, rowIndex) {
						return detail(rowData, rowIndex);
					}
				},
				// {field: 'hapus', title: 'Delete', editable: false, sortable: true, width: 100, align: 'center', search: false,
				// 	rowStyler: function(rowData, rowIndex) {
				// 		return hapus(rowData, rowIndex);
				// 	}
				// }
			]
		});

		$(document).ready(function() {
			datagrid.run();
		});

		function view(rowIndex) {
			var id = datagrid.getRowData(rowIndex).id_kotak_saran;
			$.post("{!! route('detailKotakSaran') !!}", {id:id}).done(function(data){
				if(data.status == 'success'){
					$('.modal-dialog').html(data.content);
				}
			});
		}

		function deleted(rowIndex) {
			var rowData = datagrid.getRowData(rowIndex);
			swal(
				{
					title: "Apa anda yakin Menghapus Saran Ini?",
					text: "Saran akan dihapus dari sistem dan tidak dapat dikembalikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('deleteKotakSaran') !!}", {id:rowData.id_kotak_saran}).done(function(data){
						if(data.status == 'success'){
							datagrid.reload();
							swal("Success!", "Saran Berhasil Dihapus", "success");
						}
					});
				}
			);
		}

		function detail(rowData, rowIndex) {
			var tag = '<a href="javascript:void(0)" class="btn btn-xs btn-info m-0" onclick="view('+rowIndex+')"><span class="fa fa-eye"></span> &nbsp Detail</a>';
			return tag;
		}

		function hapus(rowData, rowIndex) {
			var tag = '<a href="javascript:void(0)" class="btn btn-xs btn-danger m-0" onclick="deleted('+rowIndex+')"><span class="fa fa-trash-o"></span> &nbsp Hapus</a>';
			return tag;
		}
	</script>
@stop