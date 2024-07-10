@extends('Admin.master.layout')

@section('extended_css')
<style type="text/css">
</style>
@stop

@section('content')
<section class="content-header">
	<h1>
		MASTER POLI
	</h1>
</section>

<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
	<div class="box box-primary main-layer">
		<div class="panel panel-default">
			<div class="panel-body">
				<button class="btn btn-primary" id="btn-add"><i class="fa fa-plus"></i> Tambah </button>
				<div class='clearfix' style="margin-bottom: 20px;"></div>
				<table id="dataTable" class="table table-striped dataTable display nowrap" style="width: 100%;">
					<thead class="text-center">
						<tr>
							<th>NO</th>
							<th>NAMA POLI / UNIT</th>
							<th>KODE AWALAN</th>
							<th>AKSI</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<div class="other-page"></div>
</div>
<div class='clearfix'></div>
@stop
@section('script')
<script type="text/javascript">
	function loadTable(){
		var table = $('#dataTable').dataTable({
			scrollX: true,
			processing: true,
			serverSide: true,
			columnDefs: [{
				orderable: false,
				targets: -1
			}],
			ajax: {
				url: "{{route('mstpoli-list')}}",
				type: 'post',
			},
			columns: [
				{data: 'DT_Row_Index', name: 'DT_Row_Index'},
				{data: 'tm_poli.NamaPoli', name: 'tm_poli.NamaPoli'},
				{data: 'kode', name: 'kode'},
				{
					data: null,
					render: function(data, type, row) {
						return '<a href="javascript:void(0)" onclick="form(`'+data.kdpoli_rs+'`)" class="btn btn-success rounded-0 btn-sm" title="Edit"><span> Edit</span></a> '
					}
				},
			],
		})
	}

	$('#btn-add').click(function(){
		form();
	});

	function form(id=''){
		$('.main-layer').hide();
		$.post("{!! route('mstpoli-form') !!}", {id:id}).done(function(data){
			if(data.status == 'success'){
				$('.other-page').html(data.content).fadeIn();
			} else {
				$('.main-layer').show();
			}
		});
	}

	$(document).ready(function () {
		loadTable()
	});
</script>
@stop