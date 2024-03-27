<html>
    <head>
        <!-- <title>Laravel</title> -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <!--[if lt IE 9]> 
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <![endif]-->
        <title>{!! $data['identitas']->nama_web !!}</title>
        <link rel="shortcut icon" href="{{url('uploads/identitas/'.$data['identitas']->favicon)}}">
        <meta name="description" content="">
        <meta name="author" content="WebThemez">
        <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <![endif]-->
        <!--[if lte IE 8]>
                <script type="text/javascript" src="http://explorercanvas.googlecode.com/svn/trunk/excanvas.js"></script>
            <![endif]-->
        <!-- <link rel="stylesheet" href="{!! url('assetsite/css/bootstrap.min.css') !!}" /> -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="{!! url('assetsite/css/isotope.css') !!}" media="screen" />
        <link rel="stylesheet" href="{!! url('assetsite/js/fancybox/jquery.fancybox.css') !!}" type="text/css" media="screen" />
        <link href="{!! url('assetsite/css/animate.css') !!}" rel="stylesheet" media="screen">
        <!-- Owl Carousel Assets -->
        <link href="{!! url('assetsite/js/owl-carousel/owl.carousel.css') !!}" rel="stylesheet">
        <link rel="stylesheet" href="{!! url('assetsite/css/custom.css') !!}" />
        <!-- <link rel="stylesheet" href="{!! url('assetsite/css/stylescustom.css') !!}" /> -->
        <link rel="stylesheet" href="{!! url('aset/customcss.css') !!}" />
        <!-- Font Awesome -->
        <link href="{!! url('assetsite/font/css/font-awesome.min.css') !!}" rel="stylesheet">

        <!-- SWeatAlert -->
        <link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/sweetalert.css') !!}">
        <script src="{!! url('AssetsAdmin/dist/js/sweetalert-dev.js') !!}"></script>
        <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- DateTimePicker -->
        <link href="{!! url('AssetsAdmin/datetimepicker/bootstrap-datetimepicker.min.css') !!}" rel="stylesheet" type="text/css" />

        <link href="{!! url('AssetsAdmin/dist/css/chosen/chosen.css') !!}" rel="stylesheet" type="text/css" />

        <!-- Custome CSS  -->
        <link rel="stylesheet" href="{!! url('assetsite/css/custom_registration.css') !!}">
        <style>
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
                /*background-image: url("{!! url('aset/images/bg_quest.jpg') !!}");*/
                /*background-repeat: no-repeat;
                background-size: cover;*/
                width: 100%;
                height: 100%;
            }
            .backgroundRegis {
                position: fixed;
                /*background-image: url("{!! url('aset/images/bg_quest.jpg') !!}");*/
                background-image: url("{!! url('aset/images/DSC02323.jpg') !!}");
                background-repeat: no-repeat;
                background-size: cover;
                /*background-position: bottom -100px center;*/
                background-position: bottom -250px center;
                width: 100%;
            }
            .table-condensed {
                color: #000;
            }
            .clock {
                width: 50px;
            }
            .btn-ambil {
                width: 400px;
                height: 70px;
                background: #E3FEDA;
                position: absolute;
                border-radius: 15px;
                top:70px;
            }
            .btn-ambil:hover {
                background-color: #d0fac3;
            }
            .btn-konfirmasi {
                width: 400px;
                height: 70px;
                background: #BBC4E3;
                position: absolute;
                border-radius: 15px;
                top: 140px;
            }
            .btn-konfirmasi:hover {
                background-color: #a7b6e8;
            }
            .btn-baru {
                width: 400px;
                height: 70px;
                border-radius: 15px;
                top:0px;
                background: linear-gradient(180deg, #9069FE 15.09%, rgba(109, 131, 189, 0) 160.6%);
            }
            .btn-lama {
                width: 400px;
                height: 70px;
                background: linear-gradient(180deg, #2CBA8F 15.09%, rgba(186, 146, 44, 0) 160.6%);
                border-radius: 15px;
                top: 0px;
            }
            .border-cari {
                position: absolute;
                border-radius: 5px;
                margin-left: 255px;
                background: #FFFFFF;
                width: 700px;
                height: 310px;
                top: 0px;
            }
            #btn-cari {
                margin-left: 15px;
                /* top: 150px; */
                width: 225px;
                height: 40px;
                background: #7276ea;
                border-radius: 8px;
            }
            .border-konsisi {
                position: absolute;
                border-radius: 10px;
                margin-left: 350px;
                background: #FFFFFF;
                width: 500px;
                height: 240px;
                top: 30px;
            }
            .btn-ya {
                margin-left: 50px;
                top: 20px;
                width: 400px;
                height: 50px;
                background: #7276ea;
                border-radius: 15px;
            }
            .btn-ya:hover {
                background-color: #393b80;
            }
            .btn-tidak {
                margin-left: 50px;
                top: 30px;
                width: 400px;
                height: 50px;
                background: #26b98d;
                border-radius: 15px;
            }
            .btn-tidak:hover {
                background-color: #357e68;
            }
            hr.line {
                margin-left: 210px;
                border-top: 2px solid white;
            }
            .btn-kembali {
                margin-left: 215px;
                top: 50px;
                width: 350px;
                height: 50px;
                background: #DDDDD2;
                border-radius: 15px;
            }
            .btn-kembali:hover {
                background-color: #8a8a84;
            }
            .btn-selanjutnya {
                margin-left: 50px;
                top: 50px;
                width: 350px;
                height: 50px;
                background: #F5F93A;
                border-radius: 15px;
            }
            .btn-selanjutnya:hover {
                background-color: #b7b92f;
            }
            .btn-umum {
                width: 400px;
                height: 60px;
                position: absolute;
                border-radius: 15px;
                top:0px;
                background: linear-gradient(180deg, #2CBA44 15.09%, rgba(44, 186, 68, 0) 150.6%);
            }
            .btn-bpjs {
                width: 400px;
                height: 60px;
                position: absolute;
                border-radius: 15px;
                top:0px;
                background: linear-gradient(180deg, #F5F93A 15.09%, rgba(44, 186, 68, 0) 150.6%);
            }
            .btn-lainnya {
                width: 400px;
                height: 60px;
                position: absolute;
                border-radius: 15px;
                top:0px;
                background: linear-gradient(180deg, #3AD6F9 15.09%, rgba(69, 175, 87, 0.51) 150.6%);
            }
            .btn-cancel {
                width: 400px;
                height: 60px;
                position: absolute;
                border-radius: 15px;
                top:0px;
                background: linear-gradient(180deg, #DDDDD2 15.09%, rgba(115, 140, 119, 0) 150.6%);
            }
            .poli {
                width: 1260px;
                height: 400px;
                position: absolute;
            }
            .border-kunjungan {
                position: absolute;
                border-radius: 5px;
                margin-left: 270px;
                background: #FFFFFF;
                width: 700px;
                height: 400px;
                top: 150px;
            }
            .btn-kunjungan {
                margin-left: 60px;
                top: 50px;
                width: 430px;
                height: 40px;
                background: #7276ea;
                border-radius: 8px;
            }
            .kembali-kunjungan {
                margin-left: 60px;
                top: 60px;
                width: 430px;
                height: 40px;
                /* background: #7276ea; */
                background: linear-gradient(180deg, #c2c2bd 15.09%, rgba(115, 140, 119, 0) 150.6%);
                border-radius: 8px;
            }
            .btn-referensi {
                margin-left: 38px;
                top: 50px;
                width: 420px;
                height: 40px;
                background: #7276ea;
                border-radius: 7px;
            }
            .no_antri_pasien {
                position: absolute;
                border-radius: 10px;
                margin-left: 300px;
                background: #FFFFFF;
                width: 600px;
                height: 280px;
                top: 150px;
            }
            .border-antrian {
                position: absolute;
                border-radius: 10px;
                margin-left: 350px;
                background: #FFFFFF;
                width: 500px;
                height: 240px;
                top: 200px;
            }
            hr.line2 {
                /* margin-left: 0px; */
                margin-top: -2px;
                border-top: 2px solid #000;
            }
            .btn-cetak {
                width: 250px;
                height: 30px;
                position: absolute;
                border-radius: 5px;
                top:60px;
                background: #0000FF;
                margin-left: 20px;
                margin-top: -50px;
            }
            .btn-cetak-konfirmasi {
                width: 250px;
                height: 30px;
                position: absolute;
                border-radius: 5px;
                top: 0px;
                background: #0000FF;
                margin-left: -5px;
            }
            .btn-cetak:hover {
                background-color: #19198c;
            }
            .k-antrian {
                /* position: absolute; */
                border-radius: 5px;
                margin-top: 15rem;
                margin-left: 100px;
                background: #FFFFFF;
                width: 1000px;
                height: 380px;
                /* top: 200px; */
            }
            .konfirmasi-online {
                /* position: absolute; */
                border-radius: 5px;
                margin-top: 5rem;
                margin-left: 100px;
                background: #FFFFFF;
                width: 1000px;
                height: 450px;
                /* top: 200px; */
            }
            .btn-kmanual {
                width: 500px;
                height: 40px;
                position: absolute;
                border-radius: 5px;
                top:60px;
                background: #1F8B3D;
            }
            .btn-kmanual:hover {
                background-color: #0f5121;
            }
            .border-manual {
                position: absolute;
                border-radius: 10px;
                margin-left: 285px;
                background: #FFFFFF;
                width: 600px;
                height: 300px;
                top: 180px;
            }
            hr.line3 {
                /* margin-left: 0px; */
                margin-top: -2px;
                border-top: 1px solid #000;
            }
            .konf-manual {
                width: 460px;
                height: 30px;
                position: absolute;
                border-radius: 5px;
                top: 0px;
                background: #287DE0;
                margin-left: -108px;
                /* margin-top: -70px; */
            }
            .konf-manual:hover {
                background-color: #17467b;
            }
            .kem-manual {
                width: 460px;
                height: 30px;
                position: absolute;
                border-radius: 5px;
                top:60px;
                background: #D9D9D9;
                margin-left: -108px;
                margin-top: -20px;
            }
            .kem-manual:hover {
                background-color: #9a9999;
            }
            /* .btn-poli {
                background: #2CBA44;
                border-radius: 5px;
            } */
            .div-poli {
                margin-bottom: 10px;
                /* margin-top: 10px; */
                width: 150px;
                height: 100px;
                background: #FFFFFF;
                border-radius: 5px;
            }
            .polikatalog {
                color: #000;
            }
            .footerRegistration{
                position: fixed;
            }
        </style>
    </head>
    <body>
        <div class="backgroundRegis"></div>
        <div class='col-lg-12 p-0' id='header'>
            <div  class="col-lg-12 panelHeaderRegistration">
                <div class="col-lg-6 col-md-5 col-sm-5">
                    <img style="max-width: 70%; height: auto;" src="{{ url('uploads/identitas') }}/{!! $data['identitas']->logo_kiri !!}" class="logoLeftRegistration" alt="Retro HTML5 template">
                </div>
                <div class="col-lg-6 col-md-7 col-sm-7">
                    <ul class="pull-right headerContact">
                        <li>
                            <img class="clock" src="{{ url('uploads/clock.png')}}"><br>
                            <div>
                                <span id="time" style="font-size: 10pt"></span> <br>
                                {{-- <input type="time" name="jam" class="" value="{!! date('H:i:s') !!}" id="clockDisplay" style="width: 100%;" readonly > --}}
                                <span><b id="date" style="font-size: 9pt;"></b></span>
                                {{-- <small><b style="font-size: 8pt;" id="clockDisplay">{!! date('H:i:s') !!}</b></small> --}}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 text-center titleHeaderRegistration"><h2>PENDAFTARAN RUMAH SAKIT</h2></div>
        </div>
        @yield('content-antrian-registration')
        
        <div class="clearfix"></div>
        <footer class="footerRegistration">
            <div class="pull-right">
                <b>Version</b> 1.2.0
            </div>
            <strong>Copyright &copy; 2017 Rumah Sakit Dr. Wahidin Sudiro Husodo. Development by <a href="http://natusi.co.id" target='_blank'> CV. Natusi</a></strong>. All rights reserved.
        </footer>

        <!-- <script src="{!! url('assetsite/js/jquery-1.8.2.min.js" type="text/javascript') !!}"></script>  -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/jquery-1.8.2.min.js"></script>

        <script src="{!! url('assetsite/js/bootstrap.min.js" type="text/javascript') !!}"></script> 
        

        <!-- DateTimePicker -->
        <script src="{!! url('AssetsAdmin/datetimepicker/bootstrap-datetimepicker.min.js') !!}"></script>
        <script src="{!! url('AssetsAdmin/dist/js/chosen.jquery.min.js') !!}"></script>

        <script type="text/javascript">
            @if(!empty(Session::get('message')))
                swal({
                    title : "{{ Session::get('title') }}",
                    text : "{{ Session::get('message') }}",
                    type : "{{ Session::get('type') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
                $('#rm').empty().focus();
            @endif

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function Kosong() {
                swal({
                    title: "MAAF !",
                    text: "Fitur Belum Bisa Digunakan !!",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            function renderTime(){
                var currentTime = new Date();
                var h = currentTime.getHours();
                var m = currentTime.getMinutes();
                var s = currentTime.getSeconds();
                if (h == 0){
                    h = 24;
                }
                if (h < 10){
                    h = "0" + h;
                }
                if (m < 10){
                    m = "0" + m;
                }
                if (s < 10){
                    s = "0" + s;
                }
                // var myClock = document.getElementById('time');
                $('#time').html("<b>"+h+" : " + m + " : " + s + " WIB</b>");
                setTimeout ('renderTime()',1000);
            }
            renderTime();

            $(document).ready(function() {
                var heightWindow = $(window).height();
                var header = $('#header').height();
                var footer = $('.footerRegistration').innerHeight();
                var content = heightWindow - (header + footer) - 2;
                var ukr = 'min-height:'+content;
                $('.panelContentRegistration').attr('style',ukr);
                var bg = heightWindow - footer;
                var ukrBg = 'height:'+ bg;
                $('.backgroundRegis').attr('style',ukrBg);

                arrbulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
                date = new Date();
                hari = date.getDay();
                tanggal = date.getDate();
                bulan = date.getMonth();
                tahun = date.getFullYear();
                // document.write(tanggal+"-"+arrbulan[bulan]+"-"+tahun+"<br/>"+jam+" : "+menit+" : "+detik+"."+millisecond);

                $('#date').html(tanggal+" "+arrbulan[bulan]+" "+tahun)
            });
        </script>

        <!--  ..::: EXTRA JAVASCRIPT :::.. -->
        @yield('script-registration')
        <!--  ..::: END EXTRA JAVASCRIPT :::.. -->
    </body>
</html>