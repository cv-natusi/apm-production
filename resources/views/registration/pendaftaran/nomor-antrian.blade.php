@extends('registration.layoutNew')

@section('content-antrian-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration text-center">
        <div class="col-lg-12 col-md-12 text-center panel-antrian">
			<div class="border-antrian">
				<h4 style="color: #000; margin-top: 20px;">Anda Telah Memilih Poli</h4>
                
                <div class="col-md-12">
                    <hr class="line2">
                </div>

                <div class="col-md-12">
                    <input type="hidden" id="id" name="id" class="form-control" value="{{$antrian->id}}" autocomplete="off">
                    <input type="hidden" id="poli" name="kode_poli" class="form-control" value="{{$antrian->kode_poli}}" autocomplete="off">

                    <h3 style="color: #000; margin-top: -10px;">{{$tujuanpoli}}</h3>
                    <h1 style="color: #000;">{{$antrian->no_antrian}}</h1>
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
            var id = $('#id').val();
            var kode_poli = $('#poli').val();

            // console.log(id);
            // console.log(kode_poli);

            // window.location.href = '{{ url("cetak-antrian/") }}/'+id+'/'+kode_poli;
            window.open('{{ url("cetak-antrian/") }}/'+id+'/'+kode_poli);
        }
	</script>
@stop