@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
<section class="content-header">
	<h1>
		{{ $title }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('dashboardAdmin') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><i class="fa fa-user"></i> Modul Berita</li>
		<li class="active">Detai Profile</li>
	</ol>
</section>
<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
	<div class="loading" align="center" style="display: none;">
		<img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}" width="45%">
	</div>
</div>
<?php
$user = DB::table('users')->where('id',Sentinel::getUser()->id)->first();
?>
<section class="content">
	<div class="row">
		<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 main-layer'>
			<div class="col-lg-12 col-md-12" style="padding: 0px">
				<div class="col-lg-6 col-md-6">
					<div class="box box-primary" style="min-height: 300px">
						<div class="col-lg-12 col-md-12" style="margin-top: 10px">
							<small class="pull-right bg-green">Data Umum</small>
						</div>
						<div class="clearfix"></div>
						<div class="col-lg-12 col-md-12" style="padding: 0px;margin-top: 10px">
							<label class="col-lg-4 col-md-4">Nama</label>
							<div class="col-lg-8 col-md-8">
								<input type="text" class="form-control" readonly value="{{$user->name_user}}">
							</div>
						</div>
						<div class="col-lg-12 col-md-12" style="padding: 0px;margin-top: 10px">
							<label class="col-lg-4 col-md-4">Alias</label>
							<div class="col-lg-8 col-md-8">
								<input type="text" class="form-control" readonly value="{{$user->alias}}">
							</div>
						</div>
						<div class="col-lg-12 col-md-12" style="padding: 0px;margin-top: 10px">
							<label class="col-lg-4 col-md-4">Telephone</label>
							<div class="col-lg-8 col-md-8">
								<input type="text" class="form-control" readonly value="{{$user->phone}}">
							</div>
						</div>
						<div class="col-lg-12 col-md-12" style="padding: 0px;margin: 10px 0px">
							<label class="col-lg-4 col-md-4">Alamat</label>
							<div class="col-lg-8 col-md-8">
								<textarea class="form-control" readonly>{{$user->address_user}}</textarea>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="box box-primary" style="min-height: 300px">
						<div class="col-lg-12 col-md-12" style="margin-top: 10px">
							<small class="pull-right bg-blue">Keamanan</small>
						</div>
						<div class="clearfix"></div>
						<div class="col-lg-12 col-md-12" style="padding: 0px;margin-top: 10px">
							<label class="col-lg-4 col-md-4">Username</label>
							<div class="col-lg-8 col-md-8">
								<input type="text" class="form-control" readonly value="{{$user->email}}">
							</div>
						</div>
						<div class="col-lg-12 col-md-12" style="padding: 0px;margin: 10px 0px">
							<label class="col-lg-4 col-md-4">Password</label>
							<div class="col-lg-8 col-md-8">
								<input type="password" class="form-control" readonly value="password">
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="col-lg-12 col-md-12">
				<button id="ubah" class="form-control btn btn-warning">Ubah Password anda</button>
			</div>
		</div>
		<div class="other-page"></div>
		<div class="modal-dialog"></div>
	</section>
@stop
@section('script')
<link rel="stylesheet" href="{{url('tag_input/bootstrap-tagsinput.css')}}">
<script src="{{url('tag_input/bootstrap-tagsinput.js')}}"></script>
<script src="{{url('tag_input/bootstrap3-typeahead.js')}}"></script>

<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/ckeditor.js') !!}"></script>
<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/adapters/jquery.js') !!}"></script>
<script type="text/javascript">

	$('#ubah').click(function(){
		$('.loading').show();
		$('.main-layer').hide();
		// swal("success","iso","success");
		$.post("{!! route('formUbahPasswordAdmin') !!}").done(function(data){
			if(data.status == 'success'){
				$('.loading').hide();
				$('.other-page').html(data.content).fadeIn();
			} else {
				$('.main-layer').show();
			}
		});
	});

	$('#category').tagsinput({
	  typeahead: {
	    source: ['Mojokerto','Jombang','Kediri','Pare']
	  },
	  freeInput: true
	});
	
	$('#category').on('itemAdded', function(event) {
	    setTimeout(function(){
	        $(">input[type=text]",".bootstrap-tagsinput").val("");
	    },1);
	});
</script>
@stop