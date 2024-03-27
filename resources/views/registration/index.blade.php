@extends('registration.layoutNew')

@section('content-antrian-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration">
		<div class="col-lg-12 col-md-12 panel-antrian">

			<input type="hidden" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}">
			<div class="col-xs-6 panelPilihan" style="margin-left: 390px;">
				<a href="javascript:void(0);" onclick="ambil()" class="col-xs-12 text-center btn-ambil">
					<label style="color: #000; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 16px;">AMBIL ANTRIAN</label>
				</a>
			</div>
			<div class="col-xs-6 panelPilihan" style="margin-left: 390px;">
				<a href="javascript:void(0);" onclick="konfirmasi()" class="col-xs-12 text-center btn-konfirmasi">
					<label style="color: #000; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 16px;">KONFIRMASI ANTRIAN ONLINE</label>
				</a>
			</div>

		</div>
	</div>
	
	@include('registration.pendaftaran.jenis_pasien')
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
		// function ambil() {
        //     // alert('tombol ambil diklik')
        //     window.location.href = '{{ route("pasien-registration") }}';
        // }

        // function konfirmasi() { 
        //     // alert('tombol konfirmasi diklik');
		// 	window.location.href = '{{ route("konfirmasi-antrian") }}';
        // }

		function ambil() {
            $('#JenisPasienModal').modal('show');
        }
	</script>
@stop