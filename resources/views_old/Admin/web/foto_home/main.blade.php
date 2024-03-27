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
		<li class="active">Foto Beranda</li>
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
				<table class="table table-bordered table-striped b-t b-light">
					<thead>
						<tr>
							<th width='10px'><center>No</center></th>
							<th><center>Foto</center></th>
							<th><center>Edit</center></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td align='center'>1</td>
							<td align='center'>
								<?php if ($identity->foto_sejarah != null) { ?>
									<img src="{!! url('uploads/identitas/'.$identity->foto_sejarah) !!}" width="600">
								<?php }else{ ?>
									<img src="{!! url('AssetsSite/img/icon/default_logo.jpg') !!}" width="200">
								<?php } ?>
							</td>
							<td align='center'>
								<a onclick="updated()" href="javascript:void(0);" class='btn btn-primary btn-sm'><i class="fa fa-pencil"></i> Edit</a>
							</td>
						</tr>
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
	function updated(){
		$('.loading').show();
		$('.main-layer').hide();
		$.post("{!! route('formUpdatefotohome') !!}").done(function(data){
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