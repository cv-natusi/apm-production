@extends('registration.layoutNew')

@section('content-antrian-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration text-center">
        <div class="col-lg-12 col-md-12 text-center panel-antrian">
			<div class="border-manual">
				<h4 style="color: #000; margin-top: 30px;">Konfirmasi Antrian</h4>
                
                <div class="col-md-12">
                    <hr class="line3">
                </div>

                <div class="col-md-12">
                    <label style="color: #000; font-size: 12px;" class="pull-left">Masukkan Kode Unik Anda</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-xs-6 panelPilihan" style="margin-left: 0px;">
                    <a href="javascript:void(0);" onclick="konfirmasi()" class="col-xs-12 text-center konf-manual">
                        <label style="color: #FFFFFF; font-family: Arial;  font-size: 12px; font-style: normal; margin-top: 3px; text-align: center;">KONFIRMASI</label><br>
                    </a>
                    <a href="javascript:void(0);" onclick="kembali()" class="col-xs-12 text-center kem-manual">
                        <label style="color: #FFFFFF; font-family: Arial;  font-size: 12px; font-style: normal; margin-top: 3px; text-align: center;">KEMBALI</label><br>
                    </a>
                </div>
			</div>
		</div>
    </div>
    
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
		function konfirmasi() {
            // alert('tombol ambil diklik')
            window.location.href = '{{ route("show-antrian") }}';
        }
	</script>
@stop