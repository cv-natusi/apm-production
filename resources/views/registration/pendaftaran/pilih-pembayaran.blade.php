@extends('registration.layoutNew')

<!-- Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap" rel="stylesheet">

@section('content-antrian-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration">
        <h5 class="text-center" style="color: yellow; font-size: 22px; font-family: 'Shrikhand', cursive; position: absolute; margin-left: 470px; margin-top: -10px;">SILAHKAN PILIH ANTRIAN</h5>
		<div class="col-lg-12 col-md-12 panel-antrian">
            <input type="hidden" id="id" name="id" class="form-control" value="{{$antrian->id}}" autocomplete="off">
			<div class="col-xs-6 panelPilihan" style="margin-left: 390px;">
				<div style="width: 400px; height: 60px; background: #FFFFFF; margin-top: 40px; border-radius: 15px; position: absolute;">
                    <a href="javascript:void(0);" onclick="umum()" class="col-xs-12 text-center btn-umum">
                        <label style="color: #000; font-family: Arial;  font-size: 22px; margin-top: 10px; text-align: center;">PASIEN UMUM</label>
                        <input type="hidden" id="umum" name="umum" class="form-control" value="UMUM" autocomplete="off">
                    </a>
                </div>
			</div>
			<div class="col-xs-6 panelPilihan" style="margin-left: 390px;">
                <div style="width: 400px; height: 60px; background: #FFFFFF; margin-top: 90px; border-radius: 15px; position: absolute;">
                    <a href="javascript:void(0);" onclick="bpjs()" class="col-xs-12 text-center btn-bpjs">
                        <label style="color: #000; font-family: Arial;  font-size: 22px; margin-top: 10px; text-align: center;">PASIEN BPJS</label>
                        <input type="hidden" id="bpjs" name="bpjs" class="form-control" value="BPJS" autocomplete="off">
                    </a>
                </div>
			</div>
            <div class="col-xs-6 panelPilihan" style="margin-left: 390px;">
                <div style="width: 400px; height: 60px; background: #FFFFFF; margin-top: 140px; border-radius: 15px; position: absolute;">
                    <a href="javascript:void(0);" onclick="asuransi()" class="col-xs-12 text-center btn-lainnya">
                        <label style="color: #000; font-family: Arial;  font-size: 22px; margin-top: 10px; text-align: center;">ASURANSI LAINNYA</label>
                        <input type="hidden" id="asuransi" name="asuransi" class="form-control" value="ASURANSI LAINNYA" autocomplete="off">
                    </a>
                </div>
			</div>
            <div class="col-xs-6 panelPilihan" style="margin-left: 390px;">
                <div style="width: 400px; height: 60px; background: #FFFFFF; margin-top: 190px; border-radius: 15px; position: absolute;">
                    <a href="javascript:void(0);" class="col-xs-12 text-center btn-cancel">
                        <label style="color: #000; font-family: Arial;  font-size: 22px; margin-top: 10px; text-align: center;">KEMBALI</label>
                    </a>
                </div>
			</div>
		</div>
	</div>    
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
		function umum() {
            var id = $('#id').val();
			var pembayaran = $('#umum').val();

            // console.log(id);
            // console.log(umum);

			$.post("{{route('pembayaran-pasien-save')}}",{id:id, pembayaran:pembayaran},function(data){
				if(data.code=='200'){
					var id = data.data.id;

					window.location.href = '{{ route("pilih-poli") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }

		function bpjs() {
            var id = $('#id').val();
			var pembayaran = $('#bpjs').val();

			$.post("{{route('pembayaran-pasien-save')}}",{id:id, pembayaran:pembayaran},function(data){
				if(data.code=='200'){
					var id = data.data.id;

					window.location.href = '{{ route("pilih-poli") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }

        function asuransi() {
            var id = $('#id').val();
			var pembayaran = $('#asuransi').val();

			$.post("{{route('pembayaran-pasien-save')}}",{id:id, pembayaran:pembayaran},function(data){
				if(data.code=='200'){
					var id = data.data.id;

					window.location.href = '{{ route("pilih-poli") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }

        function kembali() {
            var id = $('#id').val();
                    
		    window.location.href = '{{ route("data-pasien") }}?id='+id;
        }
	</script>
@stop