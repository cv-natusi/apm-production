@extends('registration.layoutNew')

@section('content-antrian-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration">
		<form class="pasien">
            <div class="col-lg-12 col-md-12 panel-antrian">
                <div class="col-xs-6 panelPilihan" style="margin-left: 390px;">
                    <div style="width: 400px; height: 70px; background: #FFFFFF; margin-top: 70px; border-radius: 15px; position: absolute;">
                        <a href="javascript:void(0);" onclick="pasienbaru()" class="col-xs-12 text-center btn-baru">
                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 10px; text-align: center;">PASIEN BARU</label><br>
                            <strong style="color: #000; font-size: 10px; text-align: center;">Baru Pertama Periksa / Dirawat di RSU Dr. Wahidin Sudiro Husodo</strong>
                            <input type="hidden" id="baru" name="pasien" value="Y">
                        </a>
                    </div>
                </div>
                <div class="col-xs-6 panelPilihan" style="margin-left: 390px;">
                    <div style="width: 400px; height: 70px; background: #FFFFFF; margin-top: 140px; border-radius: 15px; position: absolute;">
                        <a href="javascript:void(0);" onclick="pasienlama()" class="col-xs-12 text-center btn-lama">
                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 10px; text-align: center;">PASIEN LAMA</label><br>
                            <strong style="color: #000; font-size: 10px; text-align: center;">Pasien Sudah Pernah Periksa / Dirawat di RSU Dr. Wahidin Sudiro Husodo</strong>
                            <input type="hidden" id="lama"  name="pasien" value="N">
                        </a>
                    </div>
                </div>
            </div>
        </form>
	</div>    
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
		function pasienbaru() {
            var pasien = $('#baru').val();
            // console.log(data);
            // alert('Pasien Baru Di klik');
            // var baru = $('input[name=pasienbaru]').val();
            // console.log(baru);

            $.post("{{route('pasien-registration-save')}}",{pasien:pasien},function(data){
				if(data.code=='200'){
                    var id = data.data.id;
                    
					window.location.href = '{{ route("konsisi-pasien") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }

		function pasienlama() {
            var pasien = $('#lama').val();
            // console.log(data);
            // alert('Pasien Lama Di Klik');
            
            $.post("{{route('pasien-registration-save')}}",{pasien:pasien},function(data){
				if(data.code=='200'){
                    var id = data.data.id;


                    // console.log(data.data.id);
                    // window.location.href = '{{ route("konsisi-pasien") }}?id='+id;
					window.location.href = '{{ route("cari-pasien") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }
	</script>
@stop