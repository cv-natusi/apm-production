@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
<section class="content-header">
	<h1>
		{{ $title }}
		<!-- <small>Control panel</small> -->
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('dashboardAdmin') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><i class="fa fa-list-alt"></i> Modul Web</li>
		<li class="active">All Menu</li>
	</ol>
</section>
<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
	<div class="loading" align="center" style="display: none;">
		<img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}" width="45%">
	</div>
</div>
<section class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-primary main-layer">
				<div class="col-md-12 col-sm-12 col-xs-12 form-inline main-layer" style='padding:5px'>
					<button type="button" class="btn btn-sm btn-primary" id="btn-add">
						<span class="fa fa-plus"></span> &nbsp Tambah
					</button>
				</div>
				<table class="table table-bordered table-striped b-t b-light">
					<thead>
						<tr>
							<th width='10px'><center>No</center></th>
							<th>Nama Menu</th>
							<th width='15%'><center>Status</center></th>
							<th width='10%'><center>Edit</center></th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; ?>
						@if(count($menus) != 0)
							@foreach ($menus as $menu)
								<tr>
									<td align='center'>{{ $no }}</td>
									<td>{{ $menu->nama_menu}}</td>
									<td align='center'>
										@if($menu->aktif == 1)
											Aktif
										@else
											Tidak Aktif
										@endif
									</td>
									<td align='center'>
										<a onclick="updated('{{ $menu->id_menu }}')" href="javascript:void(0);" class='btn btn-primary btn-sm'><i class="fa fa-pencil"></i> Edit</a>
									</td>
								</tr>
								@foreach ($childMenus as $childMenu)
									@if ($childMenu->parent_id == $menu->id_menu)
										<tr>
											<td align='center'>&nbsp</td>
											<td><i class='fa fa-angle-double-right'></i> {{ $childMenu->nama_menu}}</td>
											<td align='center'>
												@if($childMenu->aktif == 1)
													Aktif
												@else
													Tidak Aktif
												@endif
											</td>
											<td align='center'>
												<a onclick="updated('{{ $childMenu->id_menu }}')" href="javascript:void(0);" class='btn btn-primary btn-sm'><i class="fa fa-pencil"></i> Edit</a>
											</td>
										</tr>
									@endif
								@endforeach
								<?php $no++; ?>
							@endforeach
						@else
							<tr><td colspan='4' align='center'><i> --Data Tidak Ditemukan-- </i></td></tr>
						@endif
					</tbody>
				</table>
			</div>
			<div class="other-page"></div>
			<div class="modal-dialog"></div>
		</div>
	</section>
@stop

@section('script')
<script type="text/javascript">
	$('#btn-add').click(function(){
		$('.loading').show();
		$('.main-layer').hide();
		$.post("{!! route('formAddMenu') !!}").done(function(data){
			if(data.status == 'success'){
				$('.loading').hide();
				$('.other-page').html(data.content).fadeIn();
			} else {
				$('.main-layer').show();
			}
		});
	});

	function updated(idMenu){
		$('.loading').show();
		$('.main-layer').hide();
		$.post("{!! route('formUpdateMenu') !!}", {id:idMenu}).done(function(data){
			if(data.status == 'success'){
				$('.loading').hide();
				$('.other-page').html(data.content).fadeIn();
			} else {
				$('.main-layer').show();
			}
		});
	}
</script>
@stop