@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
		.small-box h3{
			font-size: 72px;
			margin:none;
		}
		.small-box .inner i{
			font-size: 72px;
			text-align: center;
		}
		.bagi{
			width: 49%;
			float: left;
		}

		.garisbawah{
			border-bottom: 1px solid #333;
		}
	</style>
@stop

@section('content')
	<div style="width: 35%; float: left;">
		<div class="content">
			<div style="width: 100%;">
				<div class="box box-default main-layer">
					<div class="box-header">
						<div class="small-box bg-aqua">
							<div class="inner">
								<div class="bagi">
									<h2>Nomor Antrian Sekarang</h2>
								</div>
								<div class="bagi">
									<h3 class="pull-right">@if($data['noantriannow']) {!! $data['noantriannow']->no_antrian !!} @else 0 @endif</h3>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>	
						<div class="small-box bg-aqua">
							<div class="inner">
								<div class="bagi">
									<h2>Sisa Antrian</h2>
								</div>
								<div class="bagi">
									<h3 class="pull-right">{!! $data['sisaantrian'] !!}</h3>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>	
						<div class="clearfix"></div>
						<div class="col-lg-4">
							<button class="small-box btn" id="panggil">
								<div class="inner">
									<i class="fa fa-volume-up"></i>
								</div>
							</button>
						</div>
						<div class="col-lg-4">
							<button class="small-box btn" id="refres">
								<div class="inner">
									<i class="fa fa-retweet"></i>
								</div>
							</button>
						</div>
						<div class="col-lg-4">
							<button class="small-box btn">
								<div class="inner">
									<i class="fa fa-sign-in"></i>
								</div>
							</button>
						</div>
					</div>
					<div class="box-main">
						
					</div>
					<div class="box-footer"></div>
				</div>
			</div>
		</div>
	</div>
	<div style="width: 65%; float: left; ">
		<div class="content">
			<div  style="width: 100%;">
				<div class="box box-default main-layer">
					<div class='panel-label_header col-lg-12 col-md-12 col-sm-12 col-xs-12' style="margin-top:0px; margin-bottom: 15px; ">
						<h3>Data Umum</h3>
					</div>
					<hr><form>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">	
						<div class="form-group">
							<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>No. BPJS:</label>
							<div class="col-lg-9 col-md-9  col-sm-12 col-xs-12">
								<input type="text" name="nama_web"  value='' required="required" class="form-control input-sm customInput col-md-7 col-xs-12 nobpjs">
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Status :</label>
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								<input type="text" name="nama_web"  value='' required="required" class="form-control input-sm customInput col-md-7 col-xs-12 status" readonly>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id='label-input'>Poli :</label>
							<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								<select class="form-control" style="border-radius: 5px;">
										<option value="">-- Pilih Poli --</option>
									@foreach($data['poli'] as $pol)
										<option value="{{$pol->kode_poli}}">{!! $pol->nama_poli!!}</option>
									@endforeach
								</select>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 10px;">	
						<a href="" class="btn btn-success col-lg-12" style="margin-bottom: 10px;" disabled><h2>Verification</h2></a>
						<a href="" class="btn btn-primary col-lg-6" ><h4>Cancel</h4></a>
						<button type="reset" class="btn btn-warning col-lg-6" ><h4>Reset</h4></button>
					</div></form>
					<hr>
					<div class="form-group">
						<table class="table" style="display: none;">
							<tr>
								<td><strong>1. Kode Customer</strong></td>
								<td>: <span id="kdcs"></span></td>
								<td>Nama Pasien</td>
								<td>: cdfsdfg sdfsd </td>
							</tr>
							<tr>
								<td><strong>2. Nama Pasien</strong></td>
								<td>: cdfsdfg</td>
								<td>Nama Pasien</td>
								<td>: cdfsdfg</td>
							</tr>
							<tr>
								<td><strong>3. NO. KTP</strong></td>
								<td>: cdfsdfg</td>
								<td>NO. KTP</td>
								<td>: cdfsdfg</td>
							</tr>
							<tr>
								<td><strong>4. Jenis Kelamain</strong></td>
								<td>: cdfsdfg</td>
								<td>Jenis Kelamain</td>
								<td>: cdfsdfg</td>
							</tr>
							<tr>
								<td><strong>5. Agama</strong></td>
								<td>: cdfsdfg</td>
								<td>Agama</td>
								<td>: cdfsdfg</td>
							</tr>
								<td><strong>6. Pekerjaan</strong></td>
								<td>: cdfsdfg</td>
								<td>Gol. Darah</td>
								<td>: cdfsdfg</td>
							</tr>
							<tr>
								<td><strong>7. Golongan Darah</strong></td>
								<td>: cdfsdfg</td>
								<td>Alamat</td>
								<td>: cdfsdfg</td>
							</tr>
							<tr>
								<td><strong>8. Umur</strong></td>
								<td>: cdfsdfg</td>
								<td>assf</td>
								<td>: cdfsdfg</td>
							</tr>
							<tr>
								<td><strong>9. Status</strong></td>
								<td>: cdfsdfg</td>
								<td>assf</td>
								<td>: cdfsdfg</td>
							</tr>
							<tr>
								<td><strong>10. Warganegaraan</strong></td>
								<td>: WNI</td>
								<td>assf</td>
								<td>: cdfsdfg</td>
							</tr>
						</table>
					</div>
					<div class='clearfix'></div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
@stop

@section('script')
<script type="text/javascript">
    function loadFilePhoto(event) {
        var image = URL.createObjectURL(event.target.files[0]);
            $('#preview-photo').fadeOut(function(){
                $(this).attr('src', image).fadeIn().css({
                    '-webkit-animation' : 'showSlowlyElement 700ms',
                    'animation'         : 'showSlowlyElement 700ms'
                });
            });
    };

    $('#btn_simpan').click(function(){
    	$('#main_content').hide();
    	$('.loading').show();
    });

    //Panggil No. Antrian & Cek data Peserta
    $('#panggil').click(function(e){
    	e.preventDefault();
    	$.post("{!! route('getpasien') !!}",{noantrian : "@if($data['noantriannow']) {!! $data['noantriannow']->no_antrian !!} @else 0 @endif"}).done(function(result){
    		$('.table').show();
    		$('#kdcs').html(result.data.KodeCust);
    	});
    });

    //refresh 
    $('#refres').click(function(){
    	location.reload();
    })
    //Cek No. BPJS
    $('.nobpjs').keypress(function (e) {
    	var no =  $('.nobpjs').val();
	    if (e.which == 13 || e.keyCode == 13) {
	        $.post("{!! route('cekpeserta') !!}",{nobpjs:no}).done(function(result){
	        	var status;
	        	if(result.response.peserta.statusPeserta.kode == 0){
	        		// status = "Aktif";
	        	}else{
	        		// status
	        	}
	        	$('.status').val(result.response.peserta.statusPeserta.keterangan);
	        });
	    }
	});
</script>
@stop