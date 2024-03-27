<html>
    <head>
        <!-- <title>Laravel</title> -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
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
        <link rel="stylesheet" href="{!! url('assetsite/css/bootstrap.min.css') !!}" />
        <link rel="stylesheet" type="text/css" href="{!! url('assetsite/css/isotope.css') !!}" media="screen" />
        <link rel="stylesheet" href="{!! url('assetsite/js/fancybox/jquery.fancybox.css') !!}" type="text/css" media="screen" />
        <link href="{!! url('assetsite/css/animate.css') !!}" rel="stylesheet" media="screen">
        <!-- Owl Carousel Assets -->
        <link href="{!! url('assetsite/js/owl-carousel/owl.carousel.css') !!}" rel="stylesheet">
        <link rel="stylesheet" href="{!! url('assetsite/css/custom.css') !!}" />
        <link rel="stylesheet" href="{!! url('assetsite/css/stylescustom.css') !!}" />
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
        </style>
    </head>
    <body>
        <div class="backgroundRegis"></div>
        <div class='col-lg-12 p-0' id='header'>
            <div  class="col-lg-12 panelHeaderRegistration">
                <div class="col-lg-6 col-md-5 col-sm-5">
                    <img src="{{ url('uploads/identitas') }}/{!! $data['identitas']->logo_kiri !!}" class="logoLeftRegistration" alt="Retro HTML5 template">
                </div>
                <div class="col-lg-6 col-md-7 col-sm-7">
                    <ul class="pull-right headerContact">
                        <li>
                            <i class=" fa fa-phone"></i>
                            <strong>Hubungi Kami</strong>
                            <br>
                            <small>{!! $data['identitas']->phone !!}</small>
                        </li>
                        <li>
                            <i class=" fa fa-envelope"></i>
                            <strong>Email Kami</strong>
                            <br>
                            <small>{!! $data['identitas']->email !!}</small>
                        </li>
                        <li>
                            <i class=" fa fa-clock-o"></i>
                            <strong>Jam Operasional</strong>
                            <br>
                            <small>{!! $data['identitas']->jam_operasional !!}</small>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- <div class="col-lg-12 col-md-12 titleHeaderRegistration"><h2>PENDAFTARAN RUMAH SAKIT</h2></div> --}}
            <div class="col-lg-12 col-md-12 titleHeaderRegistration"><h2>CETAK SEP PASIEN BPJS</h2></div>
        </div>
        @yield('content-registration')
        
        <div class="clearfix"></div>
        <footer class="footerRegistration">
            <div class="pull-right">
                <b>Version</b> 1.2.0
            </div>
            <strong>Copyright &copy; 2017 Rumah Sakit Dr. Wahidin Sudiro Husodo. Development by <a href="http://natusi.co.id" target='_blank'> CV. Natusi</a></strong>. All rights reserved.
        </footer>

        <script src="{!! url('assetsite/js/jquery-1.8.2.min.js" type="text/javascript') !!}"></script> 
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
            });
        </script>

        <!--  ..::: EXTRA JAVASCRIPT :::.. -->
        @yield('script-registration')
        <!--  ..::: END EXTRA JAVASCRIPT :::.. -->
    </body>
</html>
