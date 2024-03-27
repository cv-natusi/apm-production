<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <?php
      $identitas = DB::table('identitas')->where('id_identitas','1')->first();
    ?>
    <title>Admin | {{$identitas->nama_web}}</title>
    <link rel="shortcut icon" href="{{url('uploads/identitas/'.$identitas->favicon)}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/skins/_all-skins.min.css') !!}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/plugins/iCheck/flat/blue.css') !!}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') !!}">
    <!-- Animate -->
    <link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/animate.css') !!}">

    <link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/custom.css') !!}">

    <!-- Select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

    <!-- Datatables -->
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">

    <!-- Chosen -->
    <link href="{!! url('AssetsAdmin/dist/css/chosen/chosen.css') !!}" rel="stylesheet" type="text/css" />

    <!--  ..::: SWEET ALERT :::.. -->
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/sweetalert.css') !!}">
    <script src="{!! url('AssetsAdmin/dist/js/sweetalert-dev.js') !!}"></script>
    <!--  ..::: END SWEET ALERT :::.. -->

    <!--  ..::: SELECT2 :::.. -->
    {{-- <link href="{!! url('AssetsAdmin/plugins/select2/select2.min.css') !!}" rel="stylesheet" />
    <script src="{!! url('AssetsAdmin/plugins/select2/select2.min.js') !!}"></script> --}}
    <!--  ..::: END SELECT2 :::.. -->

    <!--  ..::: EXTRA CSS :::.. -->
    @yield('extended_css')
    <!--  ..::: END EXTRA CSS :::.. -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  {{-- <body class="hold-transition skin-green-light sidebar-mini {!! $data['classtutup'] !!}"> --}}
  <body class="hold-transition skin-green-light sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="{{route('dashboardAdmin')}}" class="logo" style='font-style:oblique'>
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img src="{{ url('uploads/identitas/'.$identitas->favicon) }}" width='28px'></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">
            <?php
              $idUser = Auth::user()->level;
              $userName = Auth::User()->name_user;
              if($idUser==1){
            ?>
              <b>ADMISI</b>
            <?php
              }else{
            ?>
              {{$userName}}
            <?php
              }
            ?>
          </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          @include('Admin.master.navbar')
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        @include('Admin.master.sidebar')
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @yield('content')
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.3.0
        </div>
       Copyright &copy; 2018   <strong>APM Rsu Dr. Wahidin Sudiro Husodo</strong>
      </footer>
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="{!! url('AssetsAdmin/plugins/jQuery/jQuery-2.1.4.min.js') !!}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{!! url('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js') !!}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{!! url('AssetsAdmin/bootstrap/js/bootstrap.min.js') !!}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{!! url('AssetsAdmin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') !!}"></script>
    <!-- Slimscroll -->
    <script src="{!! url('AssetsAdmin/plugins/slimScroll/jquery.slimscroll.min.js') !!}"></script>
    <!-- FastClick -->
    <script src="{!! url('AssetsAdmin/plugins/fastclick/fastclick.min.js') !!}"></script>
    <!-- AdminLTE App -->
    <script src="{!! url('AssetsAdmin/dist/js/app.min.js') !!}"></script>
    <!-- Datagrid -->
    <script src="{!! url('AssetsAdmin/dist/js/datagrid.js') !!}" type="text/javascript"></script>
    <!-- Animate -->
    <script src="{!! url('AssetsAdmin/dist/js/animate.js') !!}"></script>
    <!-- Validate -->
    <script src="{!! url('AssetsAdmin/dist/js/validate.js') !!}"></script>
    <!-- Chosen -->
    <script src="{!! url('AssetsAdmin/dist/js/chosen.jquery.min.js') !!}"></script>
    <!-- Select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <!-- Datatables -->
    {{-- <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script> --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
      function Kosong(){
        swal({
          title: "MAAF !",
          text: "Fitur Belum Bisa Digunakan !!",
          type: "warning",
          timer: 2000,
          showConfirmButton: false
        });
      };
    </script>
    <script type="text/javascript">
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>
    <script type="text/javascript">
      @if(!empty(Session::get('message')))
        swal({
          title : "{{ Session::get('title') }}",
          text : "{{ Session::get('message') }}",
          type : "{{ Session::get('type') }}",
          timer: 2000,
          showConfirmButton: false
        });
      @endif
    </script>

    <!--  ..::: EXTRA JAVASCRIPT :::.. -->
    @yield('script')
    <!--  ..::: END EXTRA JAVASCRIPT :::.. -->
  </body>
</html>
