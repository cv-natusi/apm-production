@extends('registration.layoutNew')

@section('content-antrian-registration')
    <div class="col-lg-12 col-md-12 panelContentRegistration">
        <div class="col-lg-12 col-md-12 panel-antrian">
            <div class="k-antrian">
                <div class="col-md-4" style="margin-top: 200px; margin-left: 50px; position: absolute;">
                    <div class="brcode">
                        <span style="color: #000;">[QrCode]</span>
                    </div>
                </div>
                <div class="col-md-4" style="margin-left: 400px; margin-top: 10px;">
                    <div class="t-antrian text-center">
                        <span style="color: #000;"><b>Konfirmasi Antrian</b></span><br><br>
                    </div>
                </div>
                <div class="col-md-6" style="margin-left: 360px;">
                    <div class="txt-antrian">
                        <span style="color: #000;">Silahkan Konfirmasi Antrian RSU Dr. Wahidin Sudiro Husodo</span><br><br>
                        <span style="color: #000;">1. Buka aplikasi<b> SIMAPAN </b>di Handphone Anda</span><br>
                        <span style="color: #000;">2. Pilih<b> Konfirmasi Antrian</b></span><br>
                        <span style="color: #000;">3. Lakukan<b> Scan QR Code</b></span><br>
                        <span style="color: #000;">4. Antrian Anda<b> Telah Terkonfirmasi</b></span>
                    </div>
                </div>
                <div class="col-xs-6 panelPilihan" style="margin-left: 350px;">
                    <a href="javascript:void(0);" onclick="manual()" class="col-xs-12 text-center btn-kmanual">
                        <label style="color: #FFFFFF; font-family: Arial;  font-size: 12px; font-style: normal; margin-top: 10px; text-align: center;">GUNAKAN CARA MANUAL UNTUK KONFIRMASI</label><br>
                    </a>
                </div>
            </div>
        </div>
    </div>    
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
    function manual() {
        window.location.href = '{{ route("antrian-manual") }}';
    }
	</script>
@stop