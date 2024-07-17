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
	<div class="other-page">
		<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
			<div class="box box-primary add-form">
				<div class="panel panel-default">
					<div class="panel-body">
						<form class="formAdd">
							<div class="row">
								<div class="col-md-12">
									<label><strong>Melayani Poli</strong></label><br>
									<select class="form-control" name="poli_id[]" id="poli_id">
										<option disabled value="">- PILIH POLI -</option>
										@foreach ($poli as $key => $polis)
											<option value="">{{$polis->tm_poli->NamaPoli}}</option>
										@endforeach
									</select>
								</div>
		
								<!-- JIKA FORM TAMBAH -->
								{{-- @if ($menu == 'Tambah')
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<label><strong>Pilih User</strong></label><br>
											<select class="form-control" name="user_id" id="user_id" style="height: 500px;">
												<option selected value="">- PILIH COUNTER -</option>
												@if (!empty($getUser))
													@foreach ($getUser as $usr)
														<option  value="{{$usr->id}}">{{$usr->email}}</option>
													@endforeach
												@endif
											</select>
										</div>
									</div>
								</div>
								@endif --}}
		
								<!-- JIKA FORM EDIT -->
								{{-- @if ($menu == 'Edit')
								<div class="col-md-12" style="margin-top: 1rem;">
									<table id="table_poli" class="table table-bordered mt-2">
										<thead>
											<tr>
												<th>Kode Poli</th>
												<th>Nama</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody id="tempatData">
											@foreach (!empty($data->trans_konter_poli)?$data->trans_konter_poli:[] as $dt)
											<tr id="kd_{{$dt->tm_poli->KodePoli}}">
												<td>{{$dt->tm_poli->KodePoli}}</td>
												<td>{{$dt->tm_poli->NamaPoli}}</td>
												<td>
													<button type="button" class="btn btn-danger" onclick="del_poli(`{{$dt->tm_poli->KodePoli}}`)"><i class="fa fa-times"></i></button>
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								@endif --}}
								<div class="col-md-12">
									<label>URL Video</label>
									<input type="text" name="url" id="url" class="form-control" value="{{ (!empty($data)) ? $data->url : '' }}">
								</div>
							</div>
						</form>
					</div>
					<div class="panel-footer">
						<button type="button" class="btn btn-sm btn-warning btn-cancel">Kembali</button>
						<input type="submit" value="Simpan" class="btn btn-sm btn-success btn-store">
					</div>
				</div>
			</div>
		</div>
	</div>
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