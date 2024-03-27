@extends('registration.layout')

@section('content-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration">
		<div class="col-xs-12 p-0" id='mainRegistrasi'>
			<div class="pull-right">
				<a href="{{ route('helpRegistration') }}" class="btn btnHelp">
					<svg aria-hidden="true" data-prefix="far" data-icon="question-circle" role="img" viewBox="0 0 512 512" class="svg-inline--fa fa-question-circle fa-w-16">
						<path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" class=""></path>
					</svg>
					<span>BANTUAN</span>
				</a>
			</div>

			<div class="clearfix m-b-25"></div>
			<div class="col-lg-6 col-md-8 col-sm-10 col-xs-12 panelPilihJenis">
				<label class="titlePilihJenis" style="color: #fff;text-shadow:1px 1px #000;">Pilih Jenis Pendaftaran Anda</label>
				<div class="col-xs-12 p-0">
					<div class="col-xs-6 panelPilihan">
						<a href="javascript:void(0);" onclick="daftarAPM('BPJS')" class="col-xs-12 btnPilihan">
							<label>BPJS</label>
							<div class='pinPilihan'></div>
						</a>
					</div>
					<div class="col-xs-6 panelPilihan">
						<a href="javascript:void(0);" onclick="daftarAPM('UMUM')" class="col-xs-12 btnPilihan">
							<label>UMUM</label>
							<div class='pinPilihan'></div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="other-page"></div>
		<div class="modal-dialog"></div>
		<div class="modal-second"></div>
		<div class="printSEP"></div>
	</div>    
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
		function daftarAPM(jenis) {
			if (jenis == 'BPJS') {
				var title = 'Masukkan No Kartu BPJS atau No RM<br>atau Tempelkan Kartu Pasien RFID Anda';
			}else{
				var title = 'Masukkan No RM atau Tempelkan Kartu Pasien RFID Anda';
			}
			$.post("{!! route('formPendaftaran') !!}", {jenis:jenis,title:title}).done(function(data){
				if(data.status == 'success'){
					$('.modal-dialog').html(data.content);
				}
			});
		}
	</script>
@stop