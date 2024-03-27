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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{asset('AssetsAdmin/plugins/datepicker/datepicker3.css')}}"/>
@stop

@section('content')
	<section class="content-header">
		<h1>
			Rujukan Khusus
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
              <li class=""><a href="#panel_listrujukan" data-toggle="tab" aria-expanded="false"><i class="fa fa-list"> List Rujukan</i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="panel_nosep">
                <!-- Form -->
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
										<div class="form-group">
											<label class="col-sm-2 control-label">Pelayanan</label>
											<div class="col-sm-4">
												<select class="form-control" id="cbpelayananrujkhusus">
													<option value="0">Pilih Pelayanan</option>
													<option value="1">Hemodialisa (HDL)</option>
													<option value="2">Thalasemia - Hemofilia</option>
												</select>
												<input type="text" hidden="" id="txtjenisPelayanan" value="0">
											</div>
										</div>
										<br>
                    <div class="form-group">
                      <label for="txtnorujukan" class="col-sm-2 control-label">No.Rujukan(FKTP)</label>
                      <div class="col-sm-4">
                        <input type="text" name="txtnorujukan_0" class="form-control" id="txtnorujukan_0" placeholder="ketik nomor rujukan fktp" maxlength="19">
                      </div>
                    </div>
										<br>
                    <div class="form-group">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-6">
                        <button type="button" id="btnCari" class="btn btn-primary" style="margin-top: 5px;margin-bottom: 20px;" name="button"><i class="fa fa-search"> Cari</i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.form -->
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="panel_listrujukan">
                <div class="row">
                  <div class="col-sm-2"></div>
                  <div class="col-sm-8">
                    <form class="form-horizontal" id="form-sep">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">Bulan</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="input-group date">
														<select class="form-control" id="cbbulan">
															<option value="1">Januari</option>
															<option value="2">Februari</option>
															<option value="3">Maret</opation>
															<option value="4">April</option>
															<option value="5">Mei</option>
															<option value="6">Juni</option>
															<option value="7">Juli</option>
															<option value="8">Agustus</option>
															<option value="9">September</option>
															<option value="10">Oktober</option>
															<option value="11">Nopember</option>
															<option value="12">Desember</option>
														</select>
                          </div>
                        </div>
                      </div>
											<br>
											<div class="form-group">
                        <label class="col-sm-2 control-label">Tahun</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="input-group date">
                            <input type="text" class="form-control datepicker" id="cbtahun" value="{!! date('Y') !!}" maxlength="10">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-6">
                          <button class="btn btn-success" style="margin-top: 5px;margin-left: 132px;margin-bottom: 20px;" id="btnCariTanggal" type="button"> <i class="fa fa-search"></i> Cari</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table id="tblRujukan" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>No Rujukan</th>
                          <th>No Kartu</th>
													<th>Nama</th>
													<th>Diagnosa</th>
                          <th>Tgl.Rujukan Berlaku</th>
                          <th>Tgl.Rujukan Berakhir</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>-</td>
                          <td>-</td>
                          <td>-</td>
                          <td>Mohon Tunggu Sebentar</td>
                          <td>-</td>
													<td>-</td>
                          <td>-</td>
                        </tr>
                      </tbody>
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
  <script src="{!! url('AssetsAdmin/plugins/datepicker/bootstrap-datepicker.js') !!}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#lokasi_laka').hide();
		$('#tblRujukan').DataTable();
	});
	$('#cbtahun').datepicker({
		format: "yyyy",
    viewMode: "years",
    minViewMode: "years"
	});
	$('#pilihDokterDpjp').chosen();

	$('#btnCariTanggal').click(function(e){
		e.preventDefault();
		var cbbulan = $('#cbbulan').val();
		var cbtahun = $('#cbtahun').val();
		var data = new FormData();
		data.append('bulan',cbbulan);
		data.append('tahun',cbtahun);

		$.ajax({
			url : "{!! route('cek_rujukan_khusus') !!}",
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
				$('#tblRujukan').DataTable().destroy();
				$('#tblRujukan tbody').html('');

				result.list.forEach(element => {
					tr += `<tr>`;

					no++;
					tr += `<td>`;
					tr += `${no}`;
					tr += `</td>`;

					tr += `<td>`;
					tr += `<a href="javascript:void(0)" onclick="rujukan_bpjs('${element.norujukan}')" class="btn btn-info btn-xs rounded-0">${element.norujukan}</a>`;
					tr += `</td>`;

					tr += `<td>`;
					tr += `${element.nokapst}`;
					tr += `</td>`;

					tr += `<td>`;
					tr += `${element.nmpst}`;
					tr += `</td>`;

					tr += `<td>`;
					tr += `${element.diagppk}`;
					tr += `</td>`;

					tr += `<td>`;
					tr += `${element.tglrujukan_awal}`;
					tr += `</td>`;

					tr += `<td>`;
					tr += `${element.tglrujukan_berakhir}`;
					tr += `</td>`;

					tr += `</tr>`;
				});
				// console.log(tr);
				$("#tblRujukan tbody").html(tr);
				$('#tblRujukan').DataTable();

			}else {
				swal('Whoopss',result.metaData.message,'warning')
				var tr = ``;
				var no = 0;
				$('#tblRujukan').DataTable().destroy();
				$('#tblRujukan tbody').html('');

				// result.list.forEach(element => {
				tr += `<tr>`;
				tr += `<td>-</td>`;
				tr += `<td>-</td>`;
				tr += `<td>-</td>`;
				tr += `<td>Data Tidak Ditemukan</td>`;
				tr += `<td>-</td>`;
				tr += `<td>-</td>`;
				tr += `<td>-</td>`;
				tr += `</tr>`;
				// });
				// console.log(tr);
				$("#tblRujukan tbody").html(tr);
				$('#tblRujukan').DataTable();
			}
		});
	});
	$('#btnCari').click(function(){
		$('.main-layer').hide();
		var txtnorujukan_0 = $('#txtnorujukan_0').val();
		var data = new FormData();
		data.append('noRujukan',txtnorujukan_0);

		$.ajax({
			url : "{!! route('createRujukanKhusus') !!}",
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
</script>
@stop
