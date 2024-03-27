<html>
    <head>
        <title>Laravel</title>
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
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
                margin-bottom: 40px;
            }

            .quote {
                font-size: 24px;
            }

            body{
                background-image: url("{!! url('aset/images/bg_quest.jpg') !!}");
                background-repeat: no-repeat;
                background-size: cover;
                width: 100%;
                height: 100%;
            }

            .panelInfoAntrian {
                border-radius: 10px;
                padding: 0px;
            }
            .infoLeft {
                padding-right: 10px;
            }
            .infoRight {
                padding-left: 10px;
            }
            .headerInfoAntrian {
                background: #2eb245;
                padding: 0px 20px;
                border-radius: 10px 10px 0px 0px;
                text-align: left;
            }
            .headerInfoAntrian > h3 {
                color: #fff;
                font-size: 24px;
                font-family: 'arial';
                margin-top: 10px;
                font-weight: 600;
            }
            .bodyInfoAntrian{
                background: #40db5c;
                border-radius: 0px 0px 10px 10px;
                text-align: center;
            }
            .bodyInfoAntrian > h2 {
                color: #fff;
                font-size: 132px;
                font-family: 'arial';
                margin-top: 10px;
                font-weight: 600;
            }
            .buttonAntrian {
                background: #00adef;
                color: #fff;
                padding: 55px 15px;
                margin-top: 10px;
                border-radius: 10px;
                font-size: 48px;
                font-weight: 600;
            }
            .buttonAntrian:hover{
                color: #fff;
            }

            @media (max-width: 1366px){
                .bodyInfoAntrian > h2 {
                    font-size: 98px;
                }
            }

        </style>
    </head>
    <body>
        <div  class="col-lg-12" style="padding: 10px 5px 0px; background-color: #fff;">
            <div class="col-lg-6">
                <img src="{{ url('uploads/identitas') }}/{!! $data['identitas']->logo_kiri !!}" style="height: 50px;" alt="Retro HTML5 template">
            </div>
            <div class="col-lg-6">
                <ul class="pull-right">
                    <li style="display: inline-block;text-align: center;color: #151515;padding:0px 5px;"><i class=" fa fa-phone" style="color: #39d557;"></i> <strong>Hubungi Kami</strong> <br> <small>{!! $data['identitas']->phone !!}</small></li>
                    <li style="display: inline-block;text-align: center;color: #151515;padding:0px 5px;"><i class=" fa fa-envelope" style="color: #39d557;"></i> <strong>Email Kami</strong> <br> <small>{!! $data['identitas']->email !!}</small></li>
                    <li style="display: inline-block;text-align: center;color: #151515;padding:0px 5px;"><i class=" fa fa-clock-o" style="color: #39d557;"></i> <strong>Jam Operasional</strong> <br> <small> 08:00-16:00 WIB</small></li>
                </ul>
            </div>
        </div>
        <div  class="col-lg-12" style="background-color: #2cba44; text-align: center; box-shadow: 1px 5px 1px #333;"><h2 style="padding: 0px 0px 10px; color: #fff;">ANTRIAN RUMAH SAKIT</h2></div>
        <div class="col-lg-6" style="margin-top:50px">
            <center>
                <form method="post" action="{!! route('nomorantrian') !!}">
                    {{ csrf_field() }}
                    <div class="col-sm-2"><label class="label-control" style="color: #333;">ID :</label></div>
                    <div class="col-sm-10"><input type="text" class="form-control" name="rm" id="rm"></div>
                    <div class="clearfix"></div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 panelInfoAntrian infoLeft pull-left">
                            <div class="col-xs-12 headerInfoAntrian">
                                <h3>Total Antrian</h3>
                            </div>
                            <div class="col-xs-12 bodyInfoAntrian">
                                <h2>60</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 panelInfoAntrian infoRight pull-right">
                            <div class="col-xs-12 headerInfoAntrian">
                                <h3>Sisa Antrian</h3>
                            </div>
                            <div class="col-xs-12 bodyInfoAntrian">
                                <h2>18</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <a href="#" class="col-xs-12 buttonAntrian">AMBIL ANTRIAN</a>
                    </div>
                </form>
            </center>
        </div>    
        <script src="{!! url('assetsite/js/jquery-1.8.2.min.js" type="text/javascript') !!}"></script> 
        <script src="{!! url('assetsite/js/bootstrap.min.js" type="text/javascript') !!}"></script> 
        <script type="text/javascript">
            $(document).ready(function(){
                $('#rm').focus();
            });

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
        </script>
    </body>
</html>
