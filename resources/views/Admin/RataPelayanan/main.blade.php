@extends('Admin.master.layout')

@section('extended_css')
<style type="text/css">
    .body {
        font-family: 'Times New Roman'
    }
    .txt-val{
        font-size: 24px;
        /* color: #000000; */
        color: #FFA500;
    }
    .boxColor {
        width:20px;
        height:20px;
        float:left;
        background:#FF0000;
        margin-bottom: 20px;
    }
    .highcharts-point{
        fill: #FF0000 !important;
    }
</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			RATA RATA PELAYANAN
		</h1>
	</section>
	<div class="content col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="background-color: #9191E7; color: #fff;">Rata - Rata Waktu Tunggu di Admisi</div>
                    <div class="panel-body text-center">
                        <h5 class="txt-val" id="tungguAdmisi">0 Hari 00:00:00</h5>
                        <span style="margin-left: 60px; font-size: 8pt;">jam : menit : detik</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="background-color: #4A4AA7; color: #fff;">Rata - Rata Waktu Tunggu di Poli</div>
                    <div class="panel-body text-center">
                        <h5 class="txt-val" id="tungguPoli">0 Hari 00:00:00</h5>
                        <span style="margin-left: 60px; font-size: 8pt;">jam : menit : detik</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="background-color: #787878; color: #fff;">Rata - Rata Waktu Tunggu di Farmasi</div>
                    <div class="panel-body text-center">
                        <h5 class="txt-val" id="tungguFarmasi">0 Hari 00:00:00</h5>
                        <span style="margin-left: 60px; font-size: 8pt;">jam : menit : detik</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="background-color: #9191E799; color: #fff;">Rata - Rata Waktu Pelayanan di Admisi</div>
                    <div class="panel-body text-center">
                        <h5 class="txt-val" id="layanAdmisi">0 Hari 00:00:00</h5>
                        <span style="margin-left: 60px; font-size: 8pt;">jam : menit : detik</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="background-color: #4A4AA799; color: #fff;">Rata - Rata Waktu Pelayanan di Poli</div>
                    <div class="panel-body text-center">
                        <h5 class="txt-val" id="layanPoli">0 Hari 00:00:00</h5>
                        <span style="margin-left: 60px; font-size: 8pt;">jam : menit : detik</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="background-color: #78787899; color: #fff;">Rata - Rata Waktu Pelayanan di Farmasi</div>
                    <div class="panel-body text-center">
                        <h5 class="txt-val" id="layanFarmasi">0 Hari 00:00:00</h5>
                        <span style="margin-left: 60px; font-size: 8pt;">jam : menit : detik</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="background-color: #149411; color: #fff;">Rata - Rata Waktu Pelayanan <br> di Admisi sampai ke Farmasi</div>
                    <div class="panel-body text-center">
                        <h5 class="txt-val" id="layanAdmisiFarmasi">0 Hari 00:00:00</h5>
                        <span style="margin-left: 60px; font-size: 8pt;">jam : menit : detik</span>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading text-center" style="background-color: #105C0F; color: #fff;">Trend Rata Rata Waktu Layanan di Admisi sampai ke Farmasi</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                        <div class="boxColor mr-2"></div> Lebih dari 3,5 Jam layanan
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                        <label>Pilih tahun dan bulan</label>
                                        <input type="text" id="bulan" name="bulan" class="form-control form-control-sm bulan" autocomplete="off" placeholder="MM yyyy" data-date-format="MM yyyy">
                                        {{-- <input type="date" name="start" class="form-control" value="{!! date('Y-m-d') !!}" id="end"> --}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="margin-bottom: 10px;">
                                        <button type="button" class="btn btn-primary" id="tampilkan" style="width: 100%;">TAMPILKAN</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <figure class="highcharts-figure">
                                    <div id="container"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<div class='clearfix'></div>
@stop
@section('script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        rekapTungguAdmisi();
        rekapLayanAdmisi();
        rekapTungguPoli();
        rekapLayanPoli();
        rekapTunggufarmasi();
        rekapLayanFarmasi();
        rekapLayanAdmisiFarmasi();

        $('.bulan').datetimepicker({
            weekStart: 6,
            todayBtn:  4,
            autoclose: 1,
            todayHighlight: 0,
            startView: 3,
            minView: 5,
            forceParse: 0,
        });
    });

    //CHART
    function grafikChart(periode, estimasi, roundEst) {
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Rata Rata Waktu Pelayanan'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Lama Pelayanan'
                }

            },
            legend: {
                enabled: false
            },
            // plotOptions: {
            //     series: {
            //         borderWidth: 0,
            //         dataLabels: {
            //             enabled: true,
            //             format: '{point.y:.1f}%'
            //         }
            //     }
            // },
            tooltip: {
                pointFormat: '<b>ADMISI - FARMASI</b><br/>Rata-rata Waktu Pelayanan: '+estimasi+'<br/>'
            },
            // tooltip: {
            //     headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            //     pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
            // },
            series: [
                {
                    name: 'rata-rata pelayanan',
                    colorByPoint: true,
                    data: [
                        {
                            name: periode,
                            y: roundEst,
                            // drilldown: 'Admisi Sampai Farmasi'
                        },
                    ]
                }
            ],
            // drilldown: {
            //     breadcrumbs: {
            //         position: {
            //             align: 'right'
            //         }
            //     },
            //     series: [
            //         {
            //             name: 'Chrome',
            //             id: 'Chrome',
            //             data: [
            //                 [
            //                     'v65.0',
            //                     0.1
            //                 ],
            //             ]
            //         },
            //     ]
            // }
        });
    }
    
    // var collection =  [{
    //     name: 'Estimasi',
    //     value: 10.30
    // }];
            
    // var obj = {
    //     'Estimasi': 10.30
    // }
            
    // var categories = collection.map(point => point.name);

    // var seriesData = collection.map(point => point.value);

    // var categoriesObj = Object.keys(obj);

    // var seriesDataObj = Object.values(obj);


    // var chart = Highcharts.chart('container', {
    //         chart: {
    //         type: 'column'
    //     },
    //     title: {
    //         text: 'Estimasi Rata-Rata Waktu Pelayanan'
    //     },
        
    // /*     chart: {type: 'bar'}, */
    //     yAxis: {
    //         title: {
    //             text: 'Waktu Pelayanan'
    //         }

    //     },
    //     xAxis: {
    //         categories: categoriesObj
    //     }, 
    //     // tooltip: {
    //     //     pointFormat: '<b>ADMISI - FARMASI</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'+datatgl+'</b><br/>{series.name}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b>{point.y:,.0f}</b> (Pasien bertambah -- jiwa dari sebelumnya )<br/>Rata-rata Waktu Tunggu: '+estimasi+' /Pasien<br/>Status Tunggu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Lebih <b style="color:blue;">CEPAT</b> <b>00 hr 00 mn 00 sc</b> dari Hari Sebelumnya'
    //     // },
    //     series: [{
    //         name: 'Waktu Pelayanan',
    //         colorByPoint: true,
    //         data: seriesDataObj
    //     }],
    //     legend: false
    // });

    // grafikChart();
    // BUTTON TAMPILKAN
    $('#tampilkan').click(function (e) { 
        e.preventDefault();
        var monthYear = $('#bulan').val();
        var data      = monthYear;

        if (!monthYear) {
            swal('Peringatan!!','Silahkan Memilih Tahun Dan Bulan','warning');
        } else {
            $('#loading').show(); // LOADING RUN
            
            $.post("{{route('tampilkanRataPelayanan')}}", {monthYear:monthYear},function(data){
                console.log(data)
                // var periode  = data.periode;
                var bulan  = data.bulan;
                var tahun  = data.tahun;
                var estimasi = data.estimasi;
                var roundEst = data.roundEst;
                var bulanTahun = GetMonthName(bulan)+' '+tahun;
                console.log(bulan)
                console.log(tahun)
                console.log(estimasi)
                console.log(bulanTahun)
                if(data.code == 200){
                    swal({
                        title: 'Berhasil',
                        type: 'success',
                        text: 'Data Berhasil Ditemukan',
                        showConfirmButton: false,
                        timer: 1000
                    })
                    grafikChart(bulanTahun, estimasi, roundEst);
                }else{
                    swal('Error!!', 'Data Gagal Ditemukan', 'info');
                }
            }).fail(()=>{
                swal("MAAF!", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
            })
        }
    });
    // GET MONTH NAME
    function GetMonthName(monthNumber) {
        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return months[monthNumber - 1];
    }
    // TUNGGU ADMISI
    function rekapTungguAdmisi(){
        $.get("{{ route('tungguAdmisi') }}").done(function(result){
            $('#tungguAdmisi').text('0 Hari '+result['estimasi']);
        });
    }
    // LAYAN ADMISI
    function rekapLayanAdmisi(){
        $.get("{{ route('layanAdmisi') }}").done(function(result){
            $('#layanAdmisi').text('0 Hari '+result['estimasi']);
        });
    }
    // TUNGGU POLI
    function rekapTungguPoli(){
        $.get("{{ route('tungguPoli') }}").done(function(result){
            $('#tungguPoli').text('0 Hari '+result['estimasi']);
        });
    }
    // LAYAN POLI
    function rekapLayanPoli(){
        $.get("{{ route('layanPoli') }}").done(function(result){
            $('#layanPoli').text('0 Hari '+result['estimasi']);
        });
    }
    // TUNGGU FARMASI
    function rekapTunggufarmasi(){
        $.get("{{ route('tungguFarmasi') }}").done(function(result){
            $('#tungguFarmasi').text('0 Hari '+result['estimasi']);
        });
    }
    // LAYAN FARMASI
    function rekapLayanFarmasi(){
        $.get("{{ route('layanFarmasi') }}").done(function(result){
            $('#layanFarmasi').text('0 Hari '+result['estimasi']);
        });
    }
    // LAYAN FARMASI
    function rekapLayanAdmisiFarmasi(){
        $.get("{{ route('layanAdmisiFarmasi') }}").done(function(result){
            $('#layanAdmisiFarmasi').text('0 Hari '+result['estimasi']);
        });
    }
</script>
@stop