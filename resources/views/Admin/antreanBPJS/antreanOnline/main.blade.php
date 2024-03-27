@extends('Admin.master.layout')

@section('extended_css')
<style type="text/css">
</style>
@stop

@section('content')
	<section class="content-header">
		<h1>
			DAFTAR CETAK SEP PAGI
		</h1>
	</section>

	<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
		
		<div class="box box-primary main-layer">
			<div class="panel panel-default">
				<div class="col">
					<div class="col-md-2">
						<label for="namaCounter">Nama Counter</label>
						<select id="namaCounter" class="form-control">
							@if ($user==1)
							<option value="">Pilih Counter</option>
							@endif
							@foreach ($dataDropdownCounter as $item)
								<option value="{{$item->nama_konterpoli}}">{{$item->nama_konterpoli}}</option>
							@endforeach
						</select>
					</div>
					<div class="pull-right">
						<div class="col-md-5">
							<label for="min">Tanggal Awal</label>
							<input type="date" id="min" class="form-control">
						</div>
						<div class="col-md-5">
							<label for="max">Tanggal Akhir</label>
							<input type="date" id="max" class="form-control">
						</div>
					</div>
				</div>
				<div class="clearfix" style="margin-bottom: 30px;"></div>
				<div class="panel-body">
					<table id="dataTable" class="table table-striped dataTable display nowrap" cellspacing="0" style="width: 100%;">
						<thead class="text-center">
							<tr>
								<th>No</th>
                                <th>Nama</th>
                                <th>Usia</th>
								<th>No RM</th>
								<th>Poli</th>
                                <th>Via</th>
								<th>Buat SEP</th>
								<th>Cetak SEP</th>
								<th>Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="other-page"></div>
	</div>
	
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@stop
@section('script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.2/js/bootstrap.min.js" integrity="sha512-5BqtYqlWfJemW5+v+TZUs22uigI8tXeVah5S/1Z6qBLVO7gakAOtkOzUtgq6dsIo5c0NJdmGPs0H9I+2OHUHVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
	<script type="text/javascript">
		$(document).ready(function () {
			var date = new Date();

			var day = date.getDate();
			var month = date.getMonth() + 1;
			var year = date.getFullYear();

			if (month < 10) month = "0" + month;
			if (day < 10) day = "0" + day;

			var today = year + "-" + month + "-" + day ;      
			$("#min").attr("value", today);
			$("#max").attr("value", today);

			//initial run
			loadTable($("#namaCounter").val(), today , today);
			filterTableByCounter();
		});

		//filter date
		var minDate, maxDate;

		function loadTable(namaCounter = null,tglAwal = null,tglAkhir = null){
			var url = "{{route('get-antrian-online')}}";

			var x = $('#dataTable').dataTable({
				scrollX: true,
				bPaginate: true,
				bFilter: true,
				bDestroy: true,
				processing: true,
				serverSide: true,
				columnDefs: [{
					orderable: false,
					targets: -1
				}],
				ajax: {
					url:url,
					type: 'post',
					data: {
						tglAwal : tglAwal,
						tglAkhir : tglAkhir,
						namaCounter : namaCounter,
					}
				},
				columns: [
					{data: 'DT_Row_Index', name: 'DT_Row_Index', orderable:false, searchable:false},
					{data: 'nama', name: 'nama'},
					{
						data: 'tanggal_lahir', 
						name: 'tanggal_lahir',
						render: function(data, type, row) {
							return '<p style="color:black">' + ((data=='' || data==null) ? '-' : data) + '</p>';
						}
					},
					{data: 'noRm', name: 'noRm'},
					{data: 'poli', name: 'poli'},
					{data: 'metode', name: 'metode'},
					{data: 'buatSep', name: 'buatSep'},
					{data: 'cetakSep', name: 'cetakSep'},
                    {data: 'action', name: 'action'}
				],
			})
		}

		function filterTableByCounter() {
			$("#dataTable_filter").addClass("hidden");

			$("#namaCounter").change(function (e) { 
				e.preventDefault();
				loadTable( $(this).val(), $("#min").val(), $("#max").val() );
				$("#dataTable_filter").addClass("hidden");
			});

			$("#min").change(function (e) { 
				e.preventDefault();
				loadTable( $("#namaCounter").val() , $(this).val() , $("#max").val() );
				$("#dataTable_filter").addClass("hidden");
			});

			$("#max").change(function (e) { 
				e.preventDefault();
				loadTable( $("#namaCounter").val(), $("#min").val() , $(this).val() );
				$("#dataTable_filter").addClass("hidden");
			});
		}

        function buatSep(params) {
            var param = JSON.parse(params);
            var id = param[0];
            var metode = param[1];
            window.location.href = '{{ route("bridging") }}?id='+id+'&metode='+metode;
        }
	</script>
@stop