@extends('Admin.master.layout')

@section('extended_css')
<style type="text/css">
</style>
@stop

@section('content')
<section class="content-header">
	<h1>
		PENGATURAN JADWAL POLI
	</h1>
</section>

<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
	<div class="box box-primary main-layer">
		<div class="panel panel-default">
			<div class="panel-body">
				<button class="btn btn-primary" id="btn-add"><i class="fa fa-plus"></i> Tambah Jadwal</button>
				<div class='clearfix' style="margin-bottom: 20px;"></div>
				<div class="row">
					<div class="col-md-12">
						<table id="dataTable" class="table table-striped table-bordered" style="
							width: 100%;
							overflow-x: auto;
							white-space: nowrap;
						">
							<thead>
								<tr>
									<th class="text-center">NO</th>
									<th class="text-center">NAMA POLI</th>
									<th class="text-center">TANGGAL TUTUP</th>
									<th class="text-center">AKSI</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="other-page"></div>
</div>
<div class="clearfix" style="margin-bottom: 50px;"></div>
@stop
@section('script')
<script type="text/javascript">
	// function loadTable(){
	// 	var table = $('#dataTable').dataTable({
	// 		scrollX: true,
	// 		processing: true,
	// 		serverSide: true,
	// 		columnDefs: [{
	// 			orderable: false,
	// 			targets: -1
	// 		}],
	// 		ajax: {
	// 			url: "{{route('mstkonterpoli-list')}}",
	// 			type: 'post',
	// 			// method: 'post',
	// 		},
	// 		columns: [
	// 			{data: 'DT_Row_Index', name: 'DT_Row_Index'},
	// 			// {data: 'trans_konter_poli.mst_konterpoli.nama_konterpoli', name: 'trans_konter_poli.mst_konterpoli.nama_konterpoli'},
	// 			// {data: 'NamaPoli', name: 'NamaPoli'},
	// 			// // {data: 'namaKonter', name: 'namaKonter'},
	// 			// // {data: 'mst_konterpoli.url', name: 'mst_konterpoli.url'},
	// 			// {data: 'urlVideo', name: 'urlVideo'},
	// 			// // {data: 'action', name: 'action'},
	// 			{data: 'nama_konterpoli', name: 'Nama Konter Poli'},
	// 			{data: 'listPoli', name: 'Poli Yang Dilayani'},
	// 			{data: 'url', name: 'URL Video'},
	// 			{
	// 				data: null,
	// 				render: function(data, type, row) {
	// 					return '<a href="javascript:void(0)" onclick="form('+data.id+')" class="btn btn-success rounded-0 btn-sm" title="Edit"><span> Edit</span></a> '
	// 				}
	// 			},
	// 		],
	// 		rowsGroup: [
	// 			'trans_konter_poli->mst_konterpoli->nama_konterpoli:name',
	// 			// 'urlVideo:name',
	// 		]
	// 	})
	// }

	// $(document).ready(function () {
	// 	loadTable()
	// });

	$('#btn-add').click(function(){
		form();
	});

	function form(id=''){
		$('.main-layer').hide();
		$.post("{!! route('pengaturan.jadwalPoli.form') !!}", {id:id}).done(function(data){
			if(data.status == 'success'){
				$('.other-page').html(data.content).fadeIn();
			} else {
				console.log('fail')
				$('.main-layer').show();
			}
		});
	}
</script>
@stop