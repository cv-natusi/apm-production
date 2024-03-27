@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
<section class="content-header">
	<h1>
		{{ $title }}
		<small>( Editor )</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('dashboardAdmin') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><i class="fa fa-user"></i> Modul Pengguna</li>
		<li class="active">Editor</li>
	</ol>
</section>
<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
	<div class="loading" align="center" style="display: none;">
		<img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}" width="45%">
	</div>
</div>
<section class="content">
	<div class="row">
		<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='padding:0px'>
			<div class="box box-primary main-layer">
				<div class="col-md-4 col-sm-4 col-xs-12 form-inline main-layer" style='padding:5px'>
					<button type="button" class="btn btn-sm btn-primary" id="btn-add">
						<span class="fa fa-plus"></span> &nbsp New Editor
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
	</section>
@stop

@section('script')
<script type="text/javascript">
	var datagrid = $("#datagrid").datagrid({
		url                     : "{!! route('editorDatagrid') !!}",
		primaryField            : 'id', 
		rowNumber               : true, 
		rowCheck                : false, 
		searchInputElement      : '#search', 
		searchFieldElement      : '#search-option', 
		pagingElement           : '#paging', 
		optionPagingElement     : '#option', 
		pageInfoElement         : '#info',
		columns                 : [
			{field: 'image', title: 'Foto', editable: false, sortable: true, width: 200, align: 'center', search: false,
				rowStyler: function(rowData, rowIndex) {
					return image(rowData, rowIndex);
				}
			},
			{field: 'email', title: 'Email/Username', editable: false, sortable: true, width: 200, align: 'left', search: true},
			{field: 'name_user', title: 'Nama Pengguna', editable: false, sortable: true, width: 150, align: 'left', search: true},
			{field: 'address_user', title: 'Alamat', editable: false, sortable: true, width: 300, align: 'left', search: true},
			{field: 'phone', title: 'Telp', editable: false, sortable: true, width: 150, align: 'left', search: true},
			{field: 'active', title: 'Status', editable: false, sortable: true, width: 150, align: 'left', search: true},
			{field: 'edit', title: 'Edit', editable: false, sortable: true, width: 150, align: 'left', search: true,
				rowStyler: function(rowData, rowIndex) {
					return edit(rowData, rowIndex);
				}
			},
			{field: 'resets', title: 'Reset Sandi', editable: false, sortable: true, width: 150, align: 'left', search: true,
				rowStyler: function(rowData, rowIndex) {
					return resets(rowData, rowIndex);
				}
			}
			// {field: 'menu', title: 'Menu', sortable: false, width: 100, align: 'center', search: false, 
			// 	rowStyler: function(rowData, rowIndex) {
			// 		return menu(rowData, rowIndex);
			// 	}
			// }
		]
	});

	$(document).ready(function() {
		datagrid.run();
	});

	$('#btn-add').click(function(){
		$('.loading').show();
		$('.main-layer').hide();
		$.post("{!! route('formAddEditor') !!}").done(function(data){
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
		$.post("{!! route('formUpdateEditor') !!}", {id:rowData.id}).done(function(data){
			if(data.status == 'success'){
				$('.loading').hide();
				$('.other-page').html(data.content).fadeIn();
			} else {
				$('.main-layer').show();
			}
		});
	}

	function resetPassword(rowIndex){
		var rowData = datagrid.getRowData(rowIndex);
		swal(
			{
				title: "Apa anda yakin Mereset Sandi Editor Ini?",
				text: "Sandi akan direset sesuai dengan Email/Username Editor!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Saya yakin!",
				cancelButtonText: "Batal!",
				closeOnConfirm: false
			},
			function(){
				$.post("{!! route('resetSandiEditor') !!}", {id:rowData.id}).done(function(data){
					if(data.status == 'success'){
						datagrid.reload();
						swal("Success!", "Reset Sandi Successfully Deleted", "success");
					}
				});
			}
		);
	}

	function image(rowData, rowIndex){
		if (rowData.photo_user != "") {
			var tag = '<img src="{!! url("AssetsAdmin/dist/img/Editor/'+rowData.photo_user+'") !!}" style="height:100px;width:auto">';
		}else{
			var tag = '<img src="{!! url("AssetsSite/img/icon/default_logo.jpg") !!}" style="height:100px;width:auto">';
		};
		return tag;
	}
	function edit(rowData, rowIndex) {
		var tag = '<a href="javascript:void(0)" class="btn btn-sm btn-info m-0" onclick="updated('+rowIndex+')"><span class="fa fa-pencil"></span> &nbsp Edit</a>';
		return tag;
	}
	function resets(rowData, rowIndex){
		var tag = '<a href="javascript:void(0)" class="btn btn-sm btn-danger m-0" onclick="resetPassword('+rowIndex+')"><span class="fa fa-refresh"></span> &nbsp Reset</a>';
		return tag;
	}
</script>
@stop