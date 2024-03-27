@extends('Admin.master.layout')

@section('extended_css')
@stop

@section('content')
	<section class="content-header">
		<h1>
			Operasi
		</h1>
	</section>
	<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<div class="loading" align="center" style="display: none;">
			<img src="{!! url('AssetsAdmin/dist/img/loading.gif') !!}" width="45%">
		</div>
	</div>
	<section class="content"> 
		<div class="row">
			<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
				<div class="box box-primary main-layer">
					<div class="col-md-8 col-sm-8 col-xs-12 form-inline main-layer" style='padding:5px'>
					<div class="input-group">
							<button class="btn btn-success" id="btn-add" type="button"> <i class="fa fa-plus"></i> Tambah Operasi</button>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Tanggal</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class="input-group date">
									<input type="date" class="form-control datepicker" id="tanggalawal" placeholder="yyyy-MM-dd"
										value="{!! date('Y-m-d') !!}" maxlength="10">
									<span class="input-group-addon">
										<span class="fa fa-calendar">
										</span>
									</span>
									<span class="input-group-addon">
										s.d
									</span>
									<input type="date" class="form-control datepicker" id="tanggalakhir" placeholder="yyyy-MM-dd"
										value="{!! date('Y-m-d') !!}" maxlength="10">
									<span class="input-group-addon">
										<span class="fa fa-calendar">
										</span>
									</span>
								</div>
							</div>
						</div>
						<div class="input-group">
							<button class="btn btn-info" id="btn_cari_jadwalOperasiRS" type="button"> <i class="fa fa-search"></i> Cari</button>
						</div>
					</div>
					<!-- Search -->
					<div class="col-md-4 col-sm-4 col-xs-12 form-inline main-layer" style="text-align: right;padding:5px;">
						<div class="form-group">
							<select class="input-sm form-control input-s-sm inline v-middle option-search" id="search-option"></select>
						</div>
						<div class="form-group">
							<input type="text" class="input-sm form-control" placeholder="Search" id="search">
						</div>
					</div>
					<div class='clearfix'></div>
					<div class="col-md-12" style='padding:0px'>
						<!-- Datagrid -->
						<div class="table-responsive">
							<table class="table table-striped b-t b-light" id="datagrid">
								<thead>
									<th class="text-center">No</th>
									<th>Kode Booking</th>
									<th>No Peserta</th>
									<th>Nama Poli</th>
									<th>Tanggal</th>
									<th>Jenis Tindakan</th>
									<th>Action</th>
								</thead>
								<tbody id="resultJadwalOperasi">
									<!-- @foreach($data as $dt => $key)
									<tr>
										<td class="text-center">{{ $dt +1 }}</td>
										<td>{{$key->id_operasi}}</td>
										<td>{{$key->norm}}</td>
										<td>{{$key->nama}}</td>
										<td>{{$key->Ruangan}}</td>
										<td>{{$key->tanggal}}</td>
										<td>{{$key->namaDPJP}}</td>
									</tr>
									@endforeach -->
								</tbody>
							</table>
						</div>
						<footer class="panel-footer">
							<div class="row">
								<!-- Page Option -->
								<div class="col-sm-1 hidden-xs">
									<select class="input-sm form-control input-s-sm inline v-middle option-page" id="option"></select>
								</div>
								<!-- Page Info -->
								<div class="col-sm-6 text-center">
									<small class="text-muted inline m-t-sm m-b-sm" id="info"></small>
								</div>
								<!-- Paging -->
								<div class="col-sm-5 text-right text-center-xs">
									<ul class="pagination pagination-sm m-t-none m-b-none" id="paging"></ul>
								</div>
							</div>
						</footer>
					</div>
					<div class='clearfix'></div>
				</div>
				<div class="other-page"></div>
				<div class="modal-dialog"></div>
			</div>
		</div>
	</section>
@stop

@section('script')
<script type="text/javascript">
		$( document ).ready(function() {
			cariJadwalOperasi();
		});
		$('#btn_cari_jadwalOperasiRS').click(function(){
			cariJadwalOperasi();
		});
    function cariJadwalOperasi() {
      var tanggalawal = $('#tanggalawal').val();
      var tanggalakhir = $('#tanggalakhir').val();
      $.post("{{url('api/jadwalOperasiRS')}}", {tanggalawal:tanggalawal,tanggalakhir:tanggalakhir}).done(function(result){
        if (result.metadata.code == '200') {
          var dat ='';
          var i = 1;
          $.each(result.response.list, function(c,v){
			if(v.terlaksana == 0){
				var button = '<button class="btn btn-sm btn-warning" onclick=changeStatus(`'+v.kodebooking+'`)><i class="fa fa-pencil"></i></button>';
			}else{
				var button = 'Terlaksana';
			}
            dat += '<tr>'+
            '<td>'+i+'</td>'+
            '<td>'+v.kodebooking+'</td>'+
            '<td>'+v.nopeserta+'</td>'+
            '<td>'+v.namapoli+'</td>'+
            '<td>'+v.tanggaloperasi+'</td>'+
            '<td>'+v.jenistindakan+'</td>'+
            '<td>'+button+'</td>'+
            `</tr>`;
            i++;
          });

          $('#resultJadwalOperasi').html(dat);
        } else if (result.metadata.code == '201') {
          dat += `<tr><td colspan="6" style="text-align:center">`+result.metadata.message+`</td></tr>`;
          $('#resultJadwalOperasi').html(dat);
        }else{
          swal('Maaf '+result.metadata.code, result.metadata.message ,'warning')
        }
      });
    }

		$('#btn-add').click(function(){
			$('.loading').show();
			$('.main-layer').hide();
			$.post("{!! route('formOperasi') !!}").done(function(data){
				if(data.status == 'success'){
					$('.loading').hide();
					$('.other-page').html(data.content).fadeIn();
				} else {
					$('.main-layer').show();
				}
			});
		});

	function changeStatus(id){
		swal(
				{
					title: "Apa anda yakin Merubah Status Data Ini?",
					text: "Status Akan di Ubah!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Saya yakin!",
					cancelButtonText: "Batal!",
					closeOnConfirm: false
				},
				function(){
					$.post("{!! route('ubahStatusOP') !!}", {id_operasi:id}).done(function(data){
						if(data.status == 'success'){
							cariJadwalOperasi();
							swal("Success!", "Berhasil Ubah Status", "success");
						}else{
							swal("Warning!", "Gagal Ubah Status", "error");
						}
					});
				}
			);
	}
	</script>
@stop