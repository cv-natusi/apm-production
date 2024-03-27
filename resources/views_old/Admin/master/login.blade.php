<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php
    $identitas = DB::table('identitas')->where('id_identitas','1')->first();
    ?>
    <title>Login | {{$identitas->nama_web}}</title>
    <link rel="shortcut icon" href="#">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/bootstrap/css/bootstrap.min.css') !!}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{!! url('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{!! url('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/AdminLTE.min.css') !!}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/plugins/iCheck/square/blue.css') !!}">
    <!-- Animate -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/animate.css') !!}">

    <!--  ..::: SWEET ALERT :::.. -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/sweetalert.css') !!}">
    <script src="{!! url('AssetsAdmin/dist/js/sweetalert-dev.js') !!}"></script>
    <!--  ..::: END SWEET ALERT :::.. -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
      .login-box-body {
        border: 1px solid #B0B6BE;
        background: rgba(213,215,222,0.9) !important;
      }

      .input-group-addon {
        padding: 10px 12px;
        font-size: 16px;
        color: #8b9199;
        font-weight: normal;
        line-height: 1;
        text-align: center;
        background-color: #ffffff;
        border: none;
        border-radius: 0;
      }
      .form-control {
        border: none;
        font-size: 16px;
        padding: 10px 12px;
        height: auto;
      }
      .input-group {
        padding-bottom: 15px;
      }
      .btnSubmit {
        width:100%;font-size:16px;
      }
    </style>
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <form class='login-form'>
        <div class="login-box-body" style='text-align:center'>
          <p class="login-img" style='font-size:50px;color:#34aadc;'><i class="fa fa-lock"></i></p>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" name="email" class="form-control" placeholder="Username" autofocus>
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-key"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Password">
          </div>
          <button class="btn btn-info btnSubmit" id="btn-login" type="submit"><i class="fa fa-lock"></i> SIGN IN </button>
        </div><!-- /.login-box-body -->
      </form>
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="{!! url('AssetsAdmin/plugins/jQuery/jQuery-2.1.4.min.js') !!}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{!! url('AssetsAdmin/bootstrap/js/bootstrap.min.js') !!}"></script>
    <!-- iCheck -->
    <script src="{!! url('AssetsAdmin/plugins/iCheck/icheck.min.js') !!}"></script>
    <!-- Animate -->
    <script src="{!! url('AssetsAdmin/dist/js/animate.js') !!}"></script>
    <!-- Validate -->
    <script src="{!! url('AssetsAdmin/dist/js/validate.js') !!}"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
    <script type="text/javascript">
      $.ajaxSetup({
        headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>
    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="{!! url('AssetsAdmin/dist/js/jquery.backstretch.min.js') !!}"></script>
    <script>
        // $.backstretch("{!! url('AssetsAdmin/dist/img/photo2.png') !!}", {speed: 500});
        $.backstretch("{!! url('aset/images/DSC02323.jpg') !!}", {speed: 500});
    </script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('.login-form').animateCss('flipInY');
      });
      $('.login-form').submit(function(e){
        e.preventDefault();
        var button = $('#btn-login');
        var data = $('.login-form').serialize();
        button.html('Please wait...').attr('disabled', true);
        $.post("{!! route('DoLogin') !!}", data).done(function(data){
          button.html('<i class="fa fa-lock"></i> Sign In').removeAttr('disabled');
          $('.login-form').validate(data.result, 'has-error');
          if(data.status == 'success'){
            window.location.replace("{!! url($data['next_url']) !!}");
          // } else if($.isPlainObject(data.result)){
            // button.animateCss('shake');
          } else if(data.status == 'error' || data.status == 'warning'){
            swal('Maaf', data.message, data.status);
          } else {
            // swal({
            //   type: data.status,
            //   title: 'Warning!',
            //   text: data.result
            // });
            // if(data.code==403){
            //   window.location.reload();
            // }
            button.animateCss('shake');
          }
        });
      });
      @if(!empty(session('message')))
        swal({
          type: 'warning',
          title: 'Warning!',
          text: '{{ session('message') }}',
          animation: false,
          customClass: 'animated shake'
        });
      @endif
    </script>
  </body>
</html>
