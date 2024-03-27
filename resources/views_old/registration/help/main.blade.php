@extends('registration.layout')

@section('content-registration')
	<div class="col-lg-12 panelContentRegistration">
		<div class="pull-left">
			<a href="{{ route('registration') }}" class="btn btnBack">
				<i class="fa fa-chevron-left"></i>
				<span>Kembali</span>
			</a>
		</div>
		<div class="clearfix m-b-25"></div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			@foreach($data['helper'] as $help)
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 panelTitleHelp">
				<a href="javascript:void(0);" onclick="detailBantuan({{ $help->id_bantuan }})" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					{{ $help->nama_bantuan }}
				</a>
			</div>
			@endforeach
		</div>
		<div class="other-page"></div>
		<div class="modal-dialog"></div>
	</div>
@stop

@section('script-registration')
	<script type="text/javascript">
		function detailBantuan(id) {
			$.post("{!! route('detailHelper') !!}",{id:id}).done(function(data){
				if(data.status == 'success'){
					$('.modal-dialog').html(data.content);
				}
			});
		}
	</script>
@stop