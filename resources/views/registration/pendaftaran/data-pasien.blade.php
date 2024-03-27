@extends('registration.layoutNew')

<!-- Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Shrikhand&display=swap" rel="stylesheet">

@section('content-antrian-registration')
	<div class="col-lg-12 col-md-12 panelContentRegistration">
		<div class="col-lg-12 col-md-12 panel-antrian">
			<div >
				<h3 class="text-center" style="color: #FAFF00; font-family: 'Shrikhand', cursive;">TANGGAL LAHIR PASIEN</h3>
			</div>
            <br>

			<div class="col-md-12">
				<input type="hidden" id="id" name="id" class="form-control" value="{{$antrian->id}}" autocomplete="off">
				<div class="col-md-2" style="color: #FFFFFF; margin-left: 200px;">
					<label>Tanggal</label>
					<input type="text" id="tanggal" name="bulan" class="form-control form-control-sm tgl" autocomplete="off" data-date-format="dd" style="background: #FFFFFF; text-align: center; border-radius: 5px !important;">
				</div>
				<div class="col-md-4" style="color: #FFFFFF">
					<label>Bulan</label>
					<input type="text" id="bulan" name="bulan" class="form-control form-control-sm bulan" autocomplete="off" data-date-format="mm" style="background: #FFFFFF; text-align: center; border-radius: 5px !important;">
				</div>
				<div class="col-md-2" style="color: #FFFFFF">
					<label>Tahun</label>
					<input type="text" id="tahun" name="tahun" class="form-control form-control-sm tahun" autocomplete="off" data-date-format="yyyy" style="background: #FFFFFF; text-align: center; border-radius: 5px !important;">
				</div>
			</div>
			<div class="col-md-10">
				<hr class="line">
			</div>
			<div class="col-md-12 text-center">
				<a href="javascript:void(0);" onclick="kembali()" class="col-xs-12 btn-kembali">
					<label style="color: #000; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 8px;">KEMBALI</label>
				</a>
				<a href="javascript:void(0);" onclick="next()" class="col-xs-12 btn-selanjutnya">
					<label style="color: #000; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 8px;">SELANJUTNYA</label>
				</a>
			</div>
		</div>
	</div>     
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">

	$('.tgl').datetimepicker({
		// weekStart: 6,
		// todayBtn:  4,
		autoclose: 1,
		todayHighlight: 0,
		startView: 2,
		minView: 2,
		forceParse: 0,
	});

	$('.bulan').datetimepicker({
		weekStart: 6,
		// todayBtn:  4,
		autoclose: 1,
		todayHighlight: 0,
		startView: 3,
		minView: 5,
		forceParse: 0, 
	});

	$('.tahun').datetimepicker({
		// weekStart: 6,
		// todayBtn:  4,
		autoclose: 1,
		todayHighlight: 0,
		startView: 4,
		minView: 4,
		forceParse: 0, 
	});

	function kembali() {
		var id = $('#id').val();
                    
		window.location.href = '{{ route("konsisi-pasien") }}?id='+id;
	}

	function next() {
		var geriatri = '';
		var id = $('#id').val();
		var tahun = $('#tahun').val();

		let now = new Date();
    	let today = now.getFullYear();

		var age = today - tahun;
		
		if (age > 60) {
			geriatri = 'Y'; 
		} else {
			geriatri = 'N'
		}

		// console.log(geriatri);

        $.post("{{route('data-pasien-save')}}",{id:id, geriatri:geriatri},function(data){
			if(data.code=='200'){
				var id = data.data.id;
                // console.log(id);

                // console.log(data.data);
				window.location.href = '{{ route("pembayaran-pasien") }}?id='+id;
			}else{
				swal('Whooops',data.message,'error');
			}
		});
    }
	</script>
@stop