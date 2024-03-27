@extends('registration.layoutNew')

@section('content-antrian-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration text-center">
        <div class="col-lg-12 col-md-12 text-center panel-antrian">
			<div class="border-antrian">
				<h4 style="color: #000; margin-top: 20px;">Antrian Anda Telah Terkonfirmasi</h4>
                
                <div class="col-md-12">
                    <hr class="line2">
                </div>

                <div class="col-md-12">
                    <h3 style="color: #000; margin-top: -10px;">POLI SPESIALIS GIGI</h3>
                    <h1 style="color: #000;">L081</h1>
                    <p style="color: #000; font-size: 10pt;">Silahkan Tekan Tombol <span style="color: red;">Cetak Antrian </span>Dan Menuju Ke <span style="color: red;">Loket</span></p>
                    <div class="col-xs-6 panelPilihan" style="">
                        <a href="javascript:void(0);" onclick="cetak()" class="col-xs-12 text-center btn-cetak">
                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 10pt; font-style: normal; margin-top: 5px; text-align: center;"><i class="fa fa-print"></i> Cetak Antrian</label>
                        </a>
                    </div>
                </div>
			</div>
		</div>
    </div>
    
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
    function cetak() {
            // alert('tombol ambil diklik');
            window.location.href = '{{ route("poli-antrian") }}';
        }
	</script>
@stop