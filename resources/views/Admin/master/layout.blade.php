<!DOCTYPE html>
<html>
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<?php $identitas = DB::table('identitas')->where('id_identitas','1')->first(); ?>
	<title>Admin | {{$identitas->nama_web}}</title>
	@if(file_exists(public_path()."/uploads/identitas/$identitas->favicon"))
		<link rel="shortcut icon" href="{{asset("/uploads/identitas/$identitas->favicon")}}">
	@endif
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	@include('Admin.master.include.style')
	
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
					@if(file_exists(public_path()."/uploads/identitas/$identitas->favicon"))
						<span class="logo-mini"><img src="{{asset("/uploads/identitas/$identitas->favicon")}}" width='28px'></span>
					@endif
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

		@include('Admin.master.include.script')
	</body>
</html>
