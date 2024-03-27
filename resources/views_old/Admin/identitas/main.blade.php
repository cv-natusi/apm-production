@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
<section class="content-header">
	<h1>
		{{ $data['title'] }}
		<!-- <small>Control panel</small> -->
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('dashboardAdmin') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Indentitas</li>
	</ol>
</section>
<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
	<div class="loading" align="center" style="display: none">
		<i class="fa fa-refresh fa-spin" style="font-size: 100px"></i>
	</div>
</div>
<section class="content" id="main_content">
	<div class="row">
		<form method='post' action="{{ route('changeIdentity') }}" enctype='multipart/form-data'>
			{{ csrf_field() }}
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-success main-layer">
						<div class='panel-label_header col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<label class='label-header-green'>Data Umum</label>
						</div>
						<div class="form-group">
							<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Nama Web :</label>
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								<input type="text" name="nama_web"  value='{!! $data["identitas"]->nama_web !!}' required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
							</div>
						</div>
						<div class='clearfix' style='padding-bottom:2px;'></div><hr>
						<div class="form-group">
							<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Alamat :</label>
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								<input type="text" name="alamat"  value='{!! $data["identitas"]->alamat !!}' required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
							</div>
						</div>
						<div class='clearfix' style='padding-bottom:2px;'></div><hr>
						<div class="form-group">
							<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>No. Hp :</label>
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								<input type="text" name="phone"  value='{!! $data["identitas"]->phone !!}' required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
							</div>
						</div>
						<div class='clearfix' style='padding-bottom:2px;'></div><hr>
						<div class="form-group">
							<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Email :</label>
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								<input type="text" name="email"  value='{!! $data["identitas"]->email !!}' required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
							</div>
						</div>
						<div class='clearfix' style='padding-bottom:2px;'></div><hr>
						<div class="form-group">
							<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Jam Operasional :</label>
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								<input type="text" name="jam_operasional"  value='{!! $data["identitas"]->jam_operasional !!}' required="required" class="form-control input-sm customInput col-md-7 col-xs-12">
							</div>
						</div>
						<div class='clearfix' style='padding-bottom:2px;'></div><hr>
						<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12' style='padding:0px'>
							<div class="form-group">
								<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" style='font-weight:bold;color:black'>Icon :</label>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="crop-edit">
										<center>
											@if(!empty($data["identitas"]->favicon))
												<img id="preview-photo" src="{!! url('uploads/identitas/'.$data['identitas']->favicon) !!}" class="img-polaroid" width="100" height="101">
											@else
												<img id="preview-photo" src="{!! url('uploads/default.jpg') !!}" class="img-polaroid" width="100" height="101">
											@endif
										</center>
									</div>
									<div class='clearfix' style='padding-bottom:5px'></div>
									<input type="file" class="upload" onchange="loadFilePhoto(event)" name="icon" accept="image/*" class="form-control customInput input-sm col-md-7 col-xs-12">
								</div>
							</div>
						</div>
						<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12' style='padding:0px'>
							<div class="form-group">
								<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" style='font-weight:bold;color:black'>Logo :</label>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="crop-edit">
										<center>
											@if(!empty($data["identitas"]->logo_kiri))
												<img id="preview-logo" src="{!! url('uploads/identitas/'.$data['identitas']->logo_kiri) !!}" class="img-polaroid" width="250" height="101">
											@else
												<img id="preview-logo" src="{!! url('uploads/default.jpg') !!}" class="img-polaroid" width="150" height="101">
											@endif
										</center>
									</div>
									<div class='clearfix' style='padding-bottom:5px'></div>
									<input type="file" class="upload" onchange="loadFileLogo(event)" name="logo_kiri" accept="image/*" class="form-control customInput input-sm col-md-7 col-xs-12">
								</div>
							</div>
						</div>
						<div class='clearfix' style='padding-bottom:25px'></div>
					</div> <!-- form-panel -->
				</div> <!-- col-lg-6 -->
				<div class='clearfix' style='padding-bottom:10px'></div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-lg-6"><input type="submit" name="edit" id="btn_simpan" class="btn btn-primary btn-block" value="Simpan"></div>
					<div class="col-lg-6"><input type="reset" class="btn btn-warning btn-block" value="Reset"></div>
				</div>
			</form>
		</div>
	</section>
@stop

@section('script')
<script type="text/javascript">
    function loadFilePhoto(event) {
        var image = URL.createObjectURL(event.target.files[0]);
            $('#preview-photo').fadeOut(function(){
                $(this).attr('src', image).fadeIn().css({
                    '-webkit-animation' : 'showSlowlyElement 700ms',
                    'animation'         : 'showSlowlyElement 700ms'
                });
            });
    };

    function loadFileLogo(event) {
        var image = URL.createObjectURL(event.target.files[0]);
            $('#preview-logo').fadeOut(function(){
                $(this).attr('src', image).fadeIn().css({
                    '-webkit-animation' : 'showSlowlyElement 700ms',
                    'animation'         : 'showSlowlyElement 700ms'
                });
            });
    };

    $('#btn_simpan').click(function(){
    	$('#main_content').hide();
    	$('.loading').show();
    });
</script>
@stop