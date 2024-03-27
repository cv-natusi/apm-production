@extends('Admin.master.layout')

@section('extended_css')
@stop
@section('content')
    <section class="content-header">
        <h1>
            GRAFIK PELAYANAN PASIEN
        </h1>
    </section>
    <div class="content col-md-12">
        <div class="box box-default main-layer">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="font-weight: bold; margin-left: 10px;">GRAFIK</h5>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3" style="margin-left: 10px;">
                    <form id="dataGrafik">
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label>Pilih Task</label>
                                <select name="task_waktu" id="task_waktu" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option value="waktu tunggu"> Waktu Tunggu </option>
                                    <option value="waktu layanan"> Waktu Layanan </option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label>Tampilkan Untuk</label>
                                <select name="tampilkan_untuk" id="tampilkan_untuk" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option value="admisi"> Admisi </option>
                                    <option value="poli"> Poli </option>
                                </select>
                            </div>
                        </div>
                        <div class="row row-poli" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label>Pilih Poli</label>
                                <select name="poli" id="poli" class="form-control">
                                    <option value="">- Pilih -</option>
                                        @if($data->count()!=0)
                                        @foreach($data as $dt)
                                        <option value="{{$dt->kdpoli}}">{{$dt->NamaPoli}}</option>
                                        @endforeach
                                        @endif
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label for="min">Tanggal Awal</label>
                                <input type="date" id="min" name="min" class="form-control">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label for="max">Tanggal Akhir</label>
                                <input type="date" id="max" name="max" class="form-control">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label>Jenis Pasien</label>
                                <select name="jenis_pasien" id="jenis_pasien" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option value="semua">Semua (UMUM, BPJS, Lainnya)</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <button type="button" id="tampilkan" class="btn btn-primary" style="width: 100%">TAMPILKAN</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="loading" style="display: none;">
                        <center style="margin-top: 100px;"><img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}"></center>
                    </div>
                    <div style="margin-top: 100px;" id="grafik"></div>
                </div>
            </div>
        </div>
    </div>
    <div class='clearfix'></div>
@stop
@section('script')
<!-- Highchart js -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.row-poli').hide();
    });
    // CHART

    function grafikPelayanan(dtDate,rest_data) {
        Highcharts.chart('grafik', {
            title: {
                text: 'Grafik Pelayanan Pasien'
            },
            xAxis: {
                categories: dtDate
            },
            yAxis: {
                title: {
                    text: 'Jumlah Pasien'
                }
            },
            tooltip: {
                pointFormat: `<b>{point.custom.title}</b><br>
                <b>Jumlah Pasien : {point.custom.jml_pasien} / {point.custom.diff}</b></br>
                <b>Tanggal : {point.custom.day}</b><br>
                <b>Estimasi Pelayanan : {point.custom.estimasi}`
            },
            series: [{
                name: 'Tanggal Periksa',
                data: rest_data
            }]
            
        });
    }
    grafikPelayanan();
    // BUTTON TAMPILKAN
    $('#tampilkan').click(function (e) { 
        e.preventDefault();
        var task = $('#task_waktu').val();
        var taskUntuk = $('#tampilkan_untuk').val();
        var poli = $('#poli').val();
        var min = $('#min').val();
        var max = $('#max').val();
        var jenis = $('#jenis_pasien').val();
        var data = new FormData($("#dataGrafik")[0]);

        if (!task) {
            swal('Peringatan!!','Pilih Task Wajib Diisi','warning');
        } else if(!taskUntuk) {
            swal('Peringatan!!','Tampilkan Untuk Wajib Diisi','warning');
        } else if(!min) {
            swal('Peringatan!!','Tanggal Awal Wajib Diisi','warning');
        } else if(!max) {
            swal('Peringatan!!','Tanggal Akhir Wajib Diisi','warning');
        } else if(!jenis) {
            swal('Peringatan!!','Jenis Pasien Wajib Diisi','warning');
        } else if(taskUntuk == 'poli' && poli== '') {
            swal('Peringatan!!','Pilih Poli Wajib Diisi','warning');
        } else {
            $('#loading').show(); // LOADING RUN
            
            $.ajax({
                url : "{{route('tampilkanGrafik')}}",
                type: 'POST',
                data: data,
                async: true,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(data) {
                if (data.status == 'success') {
                    $('#loading').hide(); // LOADING STOP

                    var i = 1;
                    var a = 0;
                    var dtDate = [];
                    var rest_data = [];
                    var jumlah = data.jumlahPasien;
                    $.each(jumlah, function (key, jum) {
                        var d = new Date(jum.tgl_periksa);
                        var day = d.getDate();
                        dtDate[a] = day;
                        if (jum.difference) {
                            var perbandingan = jum.difference;
                        } else {
                            var perbandingan = '-';
                        }
                        rest_data.push({
                            y: jum.jumlah,
                            custom : {
                                jml_pasien : jum.jumlah,
                                diff : perbandingan,
                                day : jum.tgl_periksa,
                                estimasi : jum.estimasi,
                                title: jum.title
                            }
                        })

                        i++;
                        a++;
                    });

                    grafikPelayanan(dtDate,rest_data);
                    swal({
                        title: 'Berhasil',
                        type: data.status,
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1000
                    })
                } else {
                    swal('Error!!', data.message, 'info');
                }
            }).fail(function() {
                swal("MAAF!", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
            });
        }
    });
    // POLI HIDE & SHOW 
    $('#tampilkan_untuk').change(function(){
        if($(this).val() == 'poli'){
            $('.row-poli').show();
        } else {
            $('.row-poli').hide();
        }
    });
</script>
@stop