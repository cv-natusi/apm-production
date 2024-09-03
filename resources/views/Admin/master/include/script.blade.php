<script src="{!! url('AssetsAdmin/plugins/jQuery/jQuery-2.1.4.min.js') !!}"></script>
<script src="{!! url('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js') !!}"></script>


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button);
</script>



{{-- Bootstrap 3.3.5 --}}
	<script src="{!! url('AssetsAdmin/bootstrap/js/bootstrap.min.js') !!}"></script>
{{-- Bootstrap WYSIHTML5 --}}
	<script src="{!! url('AssetsAdmin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') !!}"></script>
{{-- Slimscroll --}}
	<script src="{!! url('AssetsAdmin/plugins/slimScroll/jquery.slimscroll.min.js') !!}"></script>
{{-- FastClick --}}
	<script src="{!! url('AssetsAdmin/plugins/fastclick/fastclick.min.js') !!}"></script>
{{-- AdminLTE App --}}
	<script src="{!! url('AssetsAdmin/dist/js/app.min.js') !!}"></script>
{{-- Datagrid --}}
	<script src="{!! url('AssetsAdmin/dist/js/datagrid.js') !!}" type="text/javascript"></script>
{{-- Animate --}}
	<script src="{!! url('AssetsAdmin/dist/js/animate.js') !!}"></script>
{{-- Validate --}}
	<script src="{!! url('AssetsAdmin/dist/js/validate.js') !!}"></script>
{{-- Chosen --}}
	<script src="{!! url('AssetsAdmin/dist/js/chosen.jquery.min.js') !!}"></script>
{{-- Select --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

{{-- Datatables --}}
	{{-- <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script> --}}
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	})

	function Kosong(){
		swal({
			title: "MAAF !",
			text: "Fitur Belum Bisa Digunakan !!",
			type: "warning",
			timer: 2000,
			showConfirmButton: false
		})
	}
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

<script type="text/javascript">
	$.fn.setRules = function(rules='a-zA-Z0-9'){
		this.on('keypress',(e)=>{
			let regex = new RegExp(`^[${rules}\b]+$`) // Rules only [ numeric ]
			let key = String.fromCharCode(!e.charCode ? e.which : e.charCode) // Get character on keypress
			if(!regex.test(key)){ // Bool, cek "key", rules regex terpenuhi(value===true)
				e.preventDefault()
				return false
			}
		})
		this.on('paste', function(){
			let el = this
			setTimeout(function(){
				const re = new RegExp(`[^${rules}]`,'g')
				let convert = $(el).val().replace(re, '')
				$(el).val(convert)
			}, 20)
		})
		return this
	}
</script>

@yield('script') {{-- Extra javascript --}}