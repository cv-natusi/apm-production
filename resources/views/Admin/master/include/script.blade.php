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

{{-- datepicker vanillajs --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script> --}}
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css"> --}}
<script type="text/javascript">
	/*
	|--------------------------------------------------------------------------
	| DOKUMENTASI DATEPICKER VANILLAJS
	|--------------------------------------------------------------------------
	| Official : https://mymth.github.io/vanillajs-datepicker
	|
	| Options:
	|		~> autohide
	|			- Type: Boolean
	|			- Default: false
	|		~> clearButton
	|			- Type: Boolean
	|			- Default: false
	|		~> todayButton
	|			- Type: Boolean
	|			- Default: false
	|		~> todayButtonMode
	|			- Type: Number
	|			- Default: 0
	|			- Mode:
	|				a. focus(0) Move the focused date to the current date without changing the selection
	|				b. select(1) Select (or toggle the selection of) the current date
	|		~> todayHighlight
	|			- Type: Boolean
	|			- Default: false
	|		~> maxDate
	|			- Type: String|Date|Number
	|			- Default: null
	|
	| How to use: $('input').initDatePicker()
	*/
	function dateCurrent(){
		let date = new Date()
		let days = date.getDate().toString().padStart(2, 0)
		let months = (date.getMonth() + 1).toString().padStart(2, 0)
		let years = date.getFullYear().toString()
		return `${days}-${months}-${years}`
	}
	$.fn.initDatePicker = function(params,type=dateCurrent()){
		let str = type.split('-')
			isThree = str.length===3;
			str = isThree ? `${str[2]}-${str[1]}-${str[0]}` : '' // str => [0=>dd, 1=>mm, 2=>yyyy]
			date = isThree ? new Date(str) : new Date();
		let obj = {
			format: 'dd-mm-yyyy',
			autohide: true,
			todayButton: true,
			clearButton: true,
			todayButtonMode: 1,
			todayHighlight: true,
			// maxDate: new Date(),
		}
		if(jQuery.inArray(params,['filter'])!==-1){
			let days = date.getDate().toString().padStart(2, 0)
			let months = (date.getMonth() + 1).toString().padStart(2, 0)
			let years = date.getFullYear().toString()
			this.val(`${days}-${months}-${years}`)

			obj['clearButton'] = false
		}

		const datepicker = new Datepicker(this[0], obj)
		return this
		// for (const el of Object.keys(this)) {
		// 	// console.log(el)
		// 	const datepicker = new Datepicker(el, obj)
		// }
	}
	// Datepicker vanillajs end


	$.fn.reinitInput = function(id='default'){
		const name = id.replace(/-/g,"_")
		this.empty().append(`<input type="text" class="form-control cs-default ${id}" name="${name}" id="${id}" readonly disabled>`)
      return this
	}

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