@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
		.content-header > h1 {
			text-align: center !important;
		}
		.small-box {
			height: 150px;
		}
		.small-box>.small-box-footer {
			position: absolute !important;
			width: 100%;
			bottom: 0px;
		}
		.icon {
			top: 10px !important;
		}
		.bg-purple {
			background: #932ab6 !important;
		}
		.bg-maroon {
			background: #85144b !important;
		}
		.bg-red {
			background: #f56954 !important;
		}
	</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			.: Selamat Datang di Halaman Administrator :.
		</h1>
	</section>
	<section class="content" style="min-height: 0px;">
		<a href="javascript:void(0)" onclick="rawatjalan()" class="btn-primary btn ">Rawat Jalan</a>
		<!-- <a href="javascript:void(0)" onclick="rawatjalan()" class="btn-primary btn ">Rawat Jalan</a> -->
	</section>
	<section class="content" style="min-height: 0px; padding: 0px 15px !important;">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="box box-default main-layer">
					<h3 class='col-lg-12 col-md-12 col-sm-12 col-xs-12 judul' style='margin-top: 5px;'>Data Kunjungan Pasien</h3>
					<div class='clearfix'></div>
					<hr style='margin-top:10px;margin-bottom:10px'>
					
					<div class='clearfix'></div>
				</div>
			</div>
		</div>
	</section>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div  class="box box-default main-layer">
			<h4 class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='margin-top: 5px;'>Pilih Rentang Tanggal</h4>
			<div style="padding: 15px;">
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5">
					<input type="date" name="start" class="form-control" value="{!! date('Y-m-d') !!}" id="start">
				</div>
				<div class=" col-lg-1  col-md-1 col-sm-1 col-xs-1">
					<label class="label-control">s / d</label>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 ">
					<input type="date" name="start" class="form-control" value="{!! date('Y-m-d') !!}" id="end">
				</div>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 ">
					<select name="poli" class="form-control" id="jenis">
						<option value="all">SEMUA POLI</option>
						@foreach($data['poli'] as $poli)
							<option value="{!! $poli->NamaPoli !!}">{!! $poli->NamaPoli !!}</option>
						@endforeach
					</select>
				</div>
				<input type="hidden" name="kategori" class="kategori" value="rj"> &nbsp;
				<a href="javascript:void(0)" class="btn-result btn btn-primary">check</a>
			</div>
			<div class='clearfix' style="margin-bottom: 5px"></div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div  class="box box-default main-layer">
			<center>
				<h4 class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='margin-top: 5px;'>Data Kunjungan Pasien</h4>
				<span id="tgltabel">dd/MM/YYYY - dd/MM/YYYY</span>
			</center>
			<div style="padding: 15px; min-height: 450px;" class="paneltabel">
				<table class="table">
					<thead>
						<th>No.</th>
						<th>Nama Poli</th>
						<th>Jumlah Pasien</th>
					</thead>
					<tbody id="resulttabel">
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
				<div id="loadingjuga" style="display: none;">
	        		<center style="margin-top: 100px;"><img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}"></center>
	        	</div>
			</div>
			<div class='clearfix'></div><br>
		</div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
		<div  class="box box-default main-layer">
			<div id="loading" style="width:100%; height:400px; display: none;">
				<center>
					<span style="font-size: 19px; font-family: sans-serif;">Data Kunjungan Pasien</span><br>
					<span>dd-mm-YYYY - dd-mm-YYYY</span>
				</center>
        		<center style="margin-top: 100px;"><img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}"></center>

			</div>
			<div id="container" style="width:100%; height:400px;">
			</div>
			<div class='clearfix'></div><br>
		</div>
	</div>
	<div class='clearfix'></div>
@stop
@section('script')
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script type="text/javascript">
		
		$(document).ready(function(){

		});

		kategori = [{name:'poli', data:[0]}];
		
		function grafik(type='bar',judul = 'Data Kunjungan Pasien', rentang = 'dd mm YYYY - dd mm YYYY', name = 'Jumlah Kunjungan', data = kategori){

		    Highcharts.chart('container', {
			    // chart: {
			    //     type: 'area'
			    // },
			    title: {
			        text: judul
			    },
			    subtitle: {
			        text: rentang
			    },
			    xAxis: {
			        type: 'category',
			        labels: {
			            rotation: -45,
			            style: {
			                fontSize: '13px',
			                fontFamily: 'Verdana, sans-serif'
			            }
			        }
			    },
			    yAxis: {
			        min: 0,
			        title: {
			            text: name
			        }
			    },
			    legend: {
			        layout: 'vertical',
			        align: 'right',
			        verticalAlign: 'middle'
			    },
			    // tooltip: {
			    //     pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
			    // },
			    series: [{
			        name: 'Pasien',
			        data: data,
			        dataLabels: {
			            enabled: true,
			            rotation: -90,
			            color: '#FFFFFF',
			            align: 'right',
			            format: '{point.y:.1f}', // one decimal
			            y: 10, // 10 pixels down from the top
			            style: {
			                fontSize: '13px',
			                fontFamily: 'Verdana, sans-serif'
			            }
			        }
			    }]
			});
		}


		function rawatjalan(){
			$('.kategori').val('rj');
			$('.judul').html('Data Kunjungan Rawat Jalan');
		};

		$('.btn-result').click(function(){
			$('#container').hide();
			$('#resulttabel').hide();
			$('#loading').show();
			$('#loadingjuga').show();
			$('#container').hide();
			var start = $('#start').val();
			var end = $('#end').val();
			var kat = $('.kategori').val(); 
			var poli = $('#jenis').val();
			if(start > end){
				$('#container').show();
				$('#resulttabel').show();
				$('#loading').hide();
				$('#loadingjuga').hide();
				$('#container').show();
				swal('Perhatian','Tanggal Mulai tidak boleh lebih besar dari tanggal Selesai','warning');
			}else{
				$.post("{!! route('getkunjungan') !!}",{start : start, end:end, kat:kat, jenis:poli}).done(function(result){
					var tab = '';
					var cat = [];
					var jum = [];
					var name = [];
					var dat = [];
					var i = 1;
					var a = 0;
					var sums= 0;
					var jmlTotal = 0;
					if(result.totkun.length > 0){ 
						$('#container').show();
						$('#resulttabel').show();
						$('#loading').hide();
						$('#loadingjuga').hide();
						$.each(result.totkun, function(c,v){

								if(result.jenis == 'all'){
									tab += '<tr>'+
										'<td>'+i+'</td>'+
										'<td>'+v.poli+'</td>'+
										'<td>'+v.sum+'</td>'+
										'</tr>';
								}else{
									sums += v.sum;
									tab = '<tr>'+
										'<td>1</td>'+
										'<td>'+result.jenis+'</td>'+
										'<td>'+sums+'</td>'+
										'</tr>';
								}
								dat[a] = [v.poli, v.sum] ;
								i++;
								a++;
								jmlTotal = jmlTotal + v.sum;
						});
						tab += '<tr>'+
								'<td>&nbsp</td>'+
								'<td>JUMLAH</td>'+
								'<td>'+jmlTotal+'</td>'+
								'</tr>';

						// if(result.jenis == 'all'){
							grafik('column', result.title, result.subtitle, 'Jumlah Kunjungan', dat);
						// }else{
						// 	jum[0] = {name: name, data: dat };
						// 	grafik('column', result.title, result.subtitle, name, jum);
						// }
					}else{
						$('#resulttabel').show();
						$('#container').show();
						$('#loading').hide();
						$('#loadingjuga').hide();
						swal('Perhatian','Data Kunjungan Tidak ditemukan','warning');
					}
					$('#tgltabel').html(result.subtitle)
					$('#resulttabel').html(tab);

					var tinggitabel = $('.paneltabel').height();
						if(tinggitabel > 450){
							$('.paneltabel').attr('style','overflow-y:scroll; height:550px;');
						}
				});
			}
		});

		grafik();
	</script>
@stop