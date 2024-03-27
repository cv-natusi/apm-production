@extends('registration.layoutNew')

@section('content-antrian-registration')
    <div class="col-lg-12 col-md-12 panelContentRegistration">
            @foreach ($poli as $p)
            <div class="col-lg-2 col-md-2 text-center div-poli" style="margin-left: 20px;">
                <input type="hidden" id="id" name="id" class="form-control" value="{{$antrian->id}}" autocomplete="off">
                <a href="javascript:void(0);" onclick="btnpoli({{$p->kode_poli}})" class="col-xs-12 btn-poli" style="background: #2CBA44; border-radius: 5px; width: 180px; position: relative; margin-left: -15px;">
                    <label style="color: #FFFFFF; font-family: Arial;  font-size: 8pt;">
                        <?php
                        $myvalue = $p->NamaPoli;
                        $arr = explode(' ',trim($myvalue));
                        echo $arr[0];
                        ?>
                    </label>
                </a>
    
                <small class="polikatalog">{{$p->NamaPoli}}</small>
            </div>
            @endforeach
    </div>
@stop

@section('script-registration')
	<script type="text/javascript">
		function btnpoli(poli) {
            var id = $('#id').val();
            var kode_poli = poli;

            $.post("{{route('poli-save')}}",{id:id, kode_poli:kode_poli},function(data){
				if(data.code=='200'){
					var id = data.data.id;

					window.location.href = '{{ route("poli-antrian") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }
	</script>
@stop