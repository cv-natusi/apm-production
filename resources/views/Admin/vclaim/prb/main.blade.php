@extends('Admin.master.layout')

@section('extended_css')
	<style type="text/css">
		.small-box h3{
			font-size: 72px;
			margin:none;
		}
		.small-box .inner i{
			font-size: 72px;
			text-align: center;
		}
		.bagi{
			width: 49%;
			float: left;
		}

		.garisbawah{
			border-bottom: 1px solid #333;
		}

		.box-main .form-group{
			padding-top: 5px !important;
		}

		.history > tbody > tr:hover, .history > tbody > tr:active{
			background: #eee;
		}
		.pad{
			padding: 0 5px !important;
		}
		/* .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
			padding: 2px 8px !important;
		} */
		.chosen-single {
			height: 26px !important;
		}
		.chosen-single > span, .chosen-single > div {
			margin-top: -4px !important;
		}
	</style>
{{-- <link rel="stylesheet" type="text/css" href="{{asset('AssetsAdmin/plugins/datatables/jquery.dataTables.min.css')}}"/> --}}
<link rel="stylesheet" type="text/css" href="{{asset('AssetsAdmin/plugins/datatables/dataTables.bootstrap.css')}}"/>
{{-- <link rel="stylesheet" type="text/css" href="{{asset('AssetsAdmin/plugins/select2/select2.css')}}"/> --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('content')
	<section class="content-header">
		<h1>
			Rujuk Balik (PRB)
			<!-- <small>Control panel</small> -->
		</h1>
	</section>
		<div class="content">
			<div style="width: 100%;">
				<div class="box box-success main-layer">
				<form id="insert-sep">
					<div class="box-header">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#panel_nosep" data-toggle="tab" aria-expanded="true"><i class="fa fa-file-text-o"> Nomor SEP</i></a></li>
              <li class=""><a href="#panel_listprb" data-toggle="tab" aria-expanded="false"><i class="fa fa-list"> List Rujuk Balik</i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="panel_nosep">
                <!-- Form -->
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="no_sep" class="col-sm-2 control-label">No. SEP</label>
                      <div class="col-sm-6">
                        <input type="text" name="no_sep" class="form-control" id="no_sep" placeholder="Ketik No SEP">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-6">
                        <button type="button" id="btnCari" class="btn btn-primary" style="margin-top: 5px;margin-left: 132px;margin-bottom: 20px;" name="button"><i class="fa fa-search"> Cari</i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.form -->
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="panel_listprb">
                <div class="row">
                  <div class="col-sm-2"></div>
                  <div class="col-sm-8">
                    <form class="form-horizontal" id="form-sep">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">Tanggal</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <div class="input-group date">
                            <input type="date" class="form-control datepicker" id="tgl_awal" placeholder="yyyy-MM-dd" value="{!! date('Y-m-d') !!}" maxlength="10">
                            <span class="input-group-addon">
                              <span class="fa fa-calendar">
                              </span>
                            </span>
                            <span class="input-group-addon">
                              s.d
                            </span>
                            <input type="date" class="form-control datepicker" id="tgl_akhir" placeholder="yyyy-MM-dd" value="{!! date('Y-m-d') !!}" maxlength="10">
                            <span class="input-group-addon">
                              <span class="fa fa-calendar">
                              </span>
                            </span>
                          </div>

                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-6">
                          <button class="btn btn-success" style="margin-top: 5px;margin-left: 132px;margin-bottom: 20px;" id="btn_carilistPrb" type="button"> <i class="fa fa-search"></i> Cari</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table id="prb_table" class="table table-striped table-bordered">
                      <thead>
                        <tr>
													<th aria-label="No.: activate to sort column descending" >No.</th>
													<th aria-label="No.SRB: activate to sort column ascending" >No.SRB</th>
													<th aria-label="No.Kartu: activate to sort column ascending" >No.Kartu</th>
													<th aria-label="Nama Peserta: activate to sort column ascending" >Nama Peserta</th>
													<th aria-label="Program PRB: activate to sort column ascending" >Program PRB</th>
													<th aria-label="No.SEP: activate to sort column ascending" >No.SEP</th>
													{{-- <th aria-label="Status: activate to sort column ascending" >Status</th> --}}
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                    </div>
                  </div>
                </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
					{{-- <div class="box-footer"></div> --}}
				</form>
				</div>
			</div>
      <div class="other-page"></div>
    </div>
	</div>

	<div class="clearfix"></div>
	<div class="modal-dialog"></div>
  <div class="printSEP"></div>
	<div id="updatetglpulang"></div>
@stop

@section('script')
  <script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
  <script src="{!! url('AssetsAdmin/plugins/datatables/jquery.dataTables.js') !!}"></script>
	<script src="{!! url('AssetsAdmin/plugins/datatables/dataTables.bootstrap.js') !!}"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  {{-- <script src="{!! url('AssetsAdmin/plugins/select2/select2.full.js') !!}"></script> --}}
<script type="text/javascript">
	$(document).ready(function(){
		$('#lokasi_laka').hide();
		$('#tblRujukan').DataTable();
	});

	$('#pilihDokterDpjp').chosen();

	$('#pilihDokterDpjp').change(function () {
		var id =  $('#pilihDokterDpjp').val();
		$.post("{!! route('getDokterDpjp') !!}",{idDokterBridg:id}).done(function(result){
			if(result.status == 'success'){
				$('#namaDpjp').val(result.data.namaDPJP);
				$('#kdDpjp').val(result.data.kodeDPJP);
			}
		});
	});

	$('#btn_carilistPrb').click(function (e) {
	    e.preventDefault();

	    var tgl_awal = $('#tgl_awal').val();
	    var tgl_akhir = $('#tgl_akhir').val();

	    var data = new FormData();
	    data.append('tgl_awal',tgl_awal);
	    data.append('tgl_akhir',tgl_akhir);

	    $.ajax({
	        url : "{!! route('listPRB') !!}",
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        async: true,
	        cache: false,
	        contentType: false,
	        processData: false
	    }).done(function(result) {
	        if (result.metaData.code == 200) {
	            var tr = ``;
	            var no = 0;
	            $('#prb_table').DataTable().destroy();
	            $('#prb_table tbody').html('');

	            result.response.prb.list.forEach(element => {
	                tr += `<tr>`;

	                no++;
	                tr += `<td>`;
	                tr += `${no}`;
	                tr += `</td>`;

	                tr += `<td>`;
									tr += `<a href="javascript:void(0)" onclick="edit_prb('${element.noSRB}')" class="btn btn-info btn-xs rounded-0">${element.noSRB}</a>`;
	                tr += `<input type="hidden" id="nosep" value="${element.noSEP}">`;
	                tr += `</td>`;

	                tr += `<td>`;
	                tr += `${element.peserta.noKartu}`;
	                tr += `</td>`;

	                tr += `<td>`;
	                tr += `${element.peserta.nama}`;
	                tr += `</td>`;

	                tr += `<td>`;
									tr += `${element.programPRB.nama}`;
	                tr += `</td>`;

	                tr += `<td>`;
									tr += `${element.noSEP}`;
	                tr += `</td>`;

	                // tr += `<td>`;
									// tr += `${element.peserta.nama}`;
	                // tr += `</td>`;

	                tr += `</tr>`;
	            });

	            $("#prb_table tbody").html(tr);
	            $('#prb_table').DataTable();
	        }else if (result.data.metaData.code == 201) {
	            swal('Whoops!',result.data.metaData.message,'warning');
	        }else {
	            swal('Whoops!',result.data.metaData.message,'error');
	        }
	    });
	});

	$('#btnCari').click(function(){
		$('.main-layer').hide();
		var no_sep = $('#no_sep').val();
		var edit = 'false';

		var data = new FormData();
		data.append('edit',edit);
		data.append('no_sep',no_sep);

		$.ajax({
			url : "{!! route('createPrb') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data) {
			if(data.status == 'success'){
				$('.other-page').html(data.content).fadeIn();
			} else {
				swal('Whoopss!!',data.message,'warning');
				$('.main-layer').show();
			}
		});
	});

	function edit_prb(id) {
		var noSRB = id;
		var no_sep = $('#nosep').val();
		var edit = 'true';
		var data = new FormData();
		data.append('noSRB',noSRB);
		data.append('no_sep',no_sep);
		data.append('edit',edit);
		$.ajax({
			url : "{!! route('createPrb') !!}",
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data) {
			if(data.status == 'success'){
				$('.main-layer').hide();
				$('.other-page').html(data.content).fadeIn();
			} else {
				swal('Whoopss!!',data.message,'warning');
				$('.main-layer').show();
			}
		});
	}
</script>
@stop
