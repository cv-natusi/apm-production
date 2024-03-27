@extends('registration.layoutNew')

@section('content-antrian-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration">
		<div class="col-lg-12 col-md-12 text-center panel-antrian">
			<div class="border-konsisi">
				<div style="margin-top: 10px;">
					<input type="hidden" id="id" name="id" class="form-control" value="{{$antrian->id}}" autocomplete="off">
					<strong style="color: #000; font-size: 14px; margin-top: 40px;">APAKAH ANDA</strong><br>
					<strong style="color: #000;">PENYANDANG DISABILITAS</strong><br>
					<span style="color: #000;">(BERKEBUTUHAN KHUSUS)</span>
					<br>
				</div>
				<a href="javascript:void(0);" onclick="ya()" class="col-xs-12 btn-ya">
					<label style="color: #FFFFFF; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 8px; text-align: center;">YA, SAYA DISABILITAS</label>
					<input type="hidden" id="ya" name="geriatri" value="Y">
				</a>
				<a href="javascript:void(0);" onclick="tidak()" class="col-xs-12 btn-tidak">
					<label style="color: #FFFFFF; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 8px; text-align: center;">SAYA TIDAK</label>
					<input type="hidden" id="tidak" name="geriatri" value="N">
				</a>
			</div>
		</div>
	</div>  
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">

		function ya() {
			var id = $('#id').val();
			var geriatri = $('#ya').val();
			// console.log(geriatri);

			$.post("{{route('konsisi-pasien-save')}}",{id:id, geriatri:geriatri},function(data){
				if(data.code=='200'){
					var id = data.data.id;
                    // console.log(id);

                    // console.log(data.data);
					window.location.href = '{{ route("data-pasien") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }

		function tidak() {
			var id = $('#id').val();
			var geriatri = $('#tidak').val();
			// console.log(geriatri);

			$.post("{{route('konsisi-pasien-save')}}",{id:id, geriatri:geriatri},function(data){
				if(data.code=='200'){
					var id = data.data.id;
                    // console.log(id);

                    // console.log(data.data);
					window.location.href = '{{ route("data-pasien") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }
	</script>
@stop