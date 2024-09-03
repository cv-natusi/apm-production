{{-- Bootstrap 3.3.5 --}}
<link rel="stylesheet" href="{!! url('AssetsAdmin/bootstrap/css/bootstrap.min.css') !!}">

{{-- Font Awesome --}}
<link rel="stylesheet" href="{!! url('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}">

{{-- Ionicons --}}
<link rel="stylesheet" href="{!! url('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}">

{{-- Theme style --}}
<link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/AdminLTE.min.css') !!}">

{{-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load.--}}
<link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/skins/_all-skins.min.css') !!}">

{{-- iCheck --}}
<link rel="stylesheet" href="{!! url('AssetsAdmin/plugins/iCheck/flat/blue.css') !!}">

{{-- bootstrap wysihtml5 - text editor --}}
<link rel="stylesheet" href="{!! url('AssetsAdmin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') !!}">

{{-- Animate --}}
<link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/animate.css') !!}">

<link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/custom.css') !!}">

{{-- Select --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

{{-- Datatables --}}
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">

{{-- Chosen --}}
<link href="{!! url('AssetsAdmin/dist/css/chosen/chosen.css') !!}" rel="stylesheet" type="text/css" />

{{-- sweet alert --}}
{{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<link rel="stylesheet" href="{!! url('AssetsAdmin/dist/css/sweetalert.css') !!}">
<script src="{!! url('AssetsAdmin/dist/js/sweetalert-dev.js') !!}"></script>

{{-- select2 --}}
{{-- <link href="{!! url('AssetsAdmin/plugins/select2/select2.min.css') !!}" rel="stylesheet" />
<script src="{!! url('AssetsAdmin/plugins/select2/select2.min.js') !!}"></script> --}}

{{-- date picker vanilajs --}}
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css"> --}}

{{-- extra css --}}
<style>
	.cs-pointer{
		cursor: pointer !important;
	}
	.cs-default{
		cursor: default !important;
	}
</style>
@yield('extended_css')