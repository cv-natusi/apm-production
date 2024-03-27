<div class="row" id="divEntry">
        <div class="col-md-3">
            <!-- /. box -->
            <input type="hidden" name="noRujukan" id="noRujukan" value="<?php print_r($data['rujukan']['response']->rujukan->noKunjungan) ?>">

            <div class="box box-solid box-success">
                <div class="box-header with-border">
                    <span><i class="fa fa-user"> Peserta</i> </span>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a title="No.Kartu"><i class="fa fa-sort-numeric-asc text-blue"></i> <label id="lblnokartu"><?php print_r($data['rujukan']['response']->rujukan->peserta->noKartu)?></label></a></li>
                        <li><a title="Nama Peserta"><i class="fa fa-user text-light-blue"></i> <label id="lblnmpeserta"><?php print_r($data['rujukan']['response']->rujukan->peserta->nama)?></label></a></li>
                        <li><a title="Tgl.Lahir"><i class="fa fa-calendar text-blue"></i> <label id="lbltgllhrpst"><?php print_r($data['rujukan']['response']->rujukan->peserta->tglLahir)?></label></a></li>
                        <li><a title="Kelamin"><i class="fa fa-intersex  text-blue"></i> <label id="lbljkpst"><?php print_r($data['rujukan']['response']->rujukan->peserta->sex)?></label></a></li>
                        <li><a title="PPK Asal Peserta"><i class="fa fa-user-md  text-blue"></i> <label id="lblppkpst"><?php print_r($data['rujukan']['response']->rujukan->peserta->provUmum->nmProvider)?></label></a></li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- end kanan -->

        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-battery-half"></i>
                    <small class="pull-right">
                        <label style="font-size:medium" id="lblnorujukan"><?php print_r($data['rujukan']['response']->rujukan->noKunjungan)?></label>
                    </small>
                </div>
                <div class="box-body">
                  <form class="form-horizontal form-save">
                    <div class="form-group">
                      <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl.Rujukan</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group date">
                          <input type="text" class="form-control datepicker" id="txttglrujukan_berlaku_lama" placeholder="yyyy-MM-dd" maxlength="10" disabled="">
                          <span class="input-group-addon">
                            <span class="fa fa-calendar">
                            </span>
                          </span>
                        </div>
                      </div>
                      <label class="col-md-1 col-sm-1 col-xs-12 control-label">s/d</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group date">
                          <input type="text" class="form-control datepicker" id="txttglrujukan_berakhir_lama" placeholder="yyyy-MM-dd" maxlength="10" disabled="">
                          <span class="input-group-addon">
                            <span class="fa fa-calendar">
                            </span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl.Rujukan <label style="color:red;font-size:small">(new)</label></label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group date">
                          <input type="text" class="form-control datepicker" id="txttglrujukan_berlaku_baru" placeholder="yyyy-MM-dd" maxlength="10" disabled="">
                          <span class="input-group-addon">
                            <span class="fa fa-calendar">
                            </span>
                          </span>
                        </div>
                      </div>
                      <label class="col-md-1 col-sm-1 col-xs-12 control-label">s/d</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group date">
                          <input type="text" class="form-control datepicker" id="txttglrujukan_berakhir_baru" placeholder="yyyy-MM-dd" maxlength="10" disabled="">
                          <span class="input-group-addon">
                            <span class="fa fa-calendar">
                            </span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa <label style="color:red;font-size:small">*</label></label>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <label><input type="checkbox" id="chkutama"> Utama</label>
                          </span>
                          <select class="form-control ui-autocomplete-input" id="txtnmdiagnosa"name="" placeholder="ketik kode atau nama diagnosa min 3 karakter" autocomplete="off"></select>
                          {{-- <input type="text" class="form-control ui-autocomplete-input" id="txtnmdiagnosa" maxlength="10" placeholder="ketik kode atau nama diagnosa min 3 karakter" autocomplete="off"> --}}
                          <span class="input-group-btn">
                            <button type="button" id="btntambahdiag" class="btn btn-flat btn-default">
                              <i class="fa fa-plus"></i>
                            </button>
                          </span>
                          <input type="hidden" class="form-control" id="txtkddiagnosa">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-3 col-sm-3 col-xs-12"></div>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        <div id="tbdiag_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                          <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6"></div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                              <table class="table table-bordered table-striped dataTable no-footer" style="width: 100%; font-size: small;" cellspacing="0" id="tbdiag" role="grid">
                                <thead>
                                  <tr role="row">
                                    <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;" aria-label="Diagnosa">Diagnosa</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;" aria-label="Kode">Kode</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;" aria-label="Aksi">Aksi</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-7"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                      <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Procedure/Tindakan <label style="color:red;font-size:small">*</label></label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <div class="input-group">
                            {{-- <input type="text" class="form-control ui-autocomplete-input" id="txtnmproc" maxlength="10" placeholder="ketik kode atau nama diagnosa min 3 karakter" autocomplete="off"> --}}
                            <select class="form-control ui-autocomplete-input" id="txtnmproc"name="" placeholder="ketik kode atau nama diagnosa min 3 karakter" autocomplete="off"></select>
                            <span class="input-group-btn">
                              <button type="button" id="btntambahproc" class="btn btn-flat btn-default">
                                <i class="fa fa-plus"></i>
                              </button>
                            </span>
                            <input type="hidden" class="form-control" id="txtkdproc">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <div id="tbproc_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table class="table table-bordered table-striped dataTable no-footer" style="width: 100%; font-size: small;" cellspacing="0" id="tbproc" role="grid">
                            <thead>
                              <tr role="row">
                                <th class="sorting_asc" rowspan="1" colspan="1" style="width: 0px;" aria-label="Procedure/tindakan">Procedure/tindakan</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;" aria-label="Kode">Kode</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;" aria-label="Aksi">Aksi</th></tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                            <div id="tbproc_processing" class="dataTables_processing" style="display: none;">Processing...</div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-5"></div>
                          <div class="col-sm-7"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                </div>
                <div class="box-footer">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button id="btnSimpan" type="button" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <button id="btnBatal" type="button" class="btn btn-default pull-right btn-cancel"><i class="fa fa-undo"></i> Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
var no_diagnosa = 1;
$('#btntambahdiag').click(function (e) {
    e.preventDefault();
    var kode = $('#txtnmdiagnosa :selected').val();
    var nama = $('#txtnmdiagnosa :selected').text();
    nama = nama.split("-");

    if ($('#chkutama').is(":checked"))
    {
        var utama = 1;
    }else{
        var utama = 0;
    }

    if (kode != null) {
        var html = `<tr id="diagnosa_${no_diagnosa}">`;
        html += `<td>`;
        html += `${nama[1]}`;
        html += `</td>`;
        html += `<td>`;
        html += `${kode}`;
        html += `<input type="hidden" name="utama[]" value="${utama}" />`;
        html += `<input type="hidden" name="kode[]" value="${kode}" />`;
        html += `<input type="hidden" name="nama[]" value="${nama[1]}" />`;
        html += `</td>`;

        html += `<td>`;
        html += `<a href="javascript:void(0)" onclick="hapus_diagnosa('${no_diagnosa}')" class="btn btn-danger rounded-0"><i class="fa fa-trash"></i></a>`;
        html += `</td>`;
        html += `</tr>`;
        $('#tbdiag tbody').append(html);
        no_diagnosa++;
    }
});
function hapus_diagnosa(no_diagnosa) {
    $('#diagnosa_'+no_diagnosa).remove();
}
var no_procedure = 1;
$('#btntambahproc').click(function (e) {
    e.preventDefault();
    e.preventDefault();
    var kode = $('#txtnmproc :selected').val();
    var nama = $('#txtnmproc :selected').text();
    nama = nama.split("-");
    if (kode != null) {
        var html = `<tr id="procedure_${no_procedure}">`;
        html += `<td>`;
        html += `${nama[1]}`;
        html += `</td>`;
        html += `<td>`;
        html += `${kode}`;
        html += `<input type="hidden" name="kode_proc[]" value="${kode}" />`;
        html += `<input type="hidden" name="nama_proc[]" value="${nama[1]}" />`;
        html += `</td>`;

        html += `<td>`;
        html += `<a href="javascript:void(0)" onclick="hapus_procedure('${no_procedure}')" class="btn btn-danger rounded-0"><i class="fa fa-trash"></i></a>`;
        html += `</td>`;
        html += `</tr>`;
        $('#tbproc tbody').append(html);
        no_procedure++;
    }
});
function hapus_procedure(no_procedure) {
    $('#procedure_'+no_procedure).remove();
}
$("#txtnmdiagnosa").select2({
  minimumInputLength: 2,
  width: '100%',
  tags: [],
  placeholder: 'Cari...',
  ajax: {
    url: "{!! route('cek_diagnosa') !!}",
    dataType: 'json',
    delay: 250,
    processResults: function (data) {
      return {
        results: $.map(data.diagnosa, function (item) {
          return {
            text: item.nama,
            id: item.kode
          }
        })
      };
    }
  }
});

$("#txtnmproc").select2({
  minimumInputLength: 2,
  width: '100%',
  tags: [],
  placeholder: 'Cari...',
  ajax: {
    url: "{!! route('cek_procedure') !!}",
    dataType: 'json',
    delay: 250,
    processResults: function (data) {
      return {
        results: $.map(data.procedure, function (item) {
          return {
            text: item.nama,
            id: item.kode
          }
        })
      };
    }
  }
});

$('#btnSimpan').click(function (e) {
    e.preventDefault();
    var data  = new FormData($('.form-save')[0]);
    var no_rujukan = $('#noRujukan').val();
    data.append('no_rujukan',no_rujukan);
    $.ajax({
        url: "{{ route('storeRujukanKhusus') }}",
        type: 'POST',
        data: data,
        async: true,
        cache: false,
        contentType: false,
        processData: false
    }).done(function(data){
        if (data.status == '200') {
            swal('Berhasil','Rujukan Khusus berhasil Di Perpanjang','success');
            location.reload();
        }else{
            swal('Peringatan',data.message,'warning');
        }
    });
});

$('.btn-cancel').click(function(e){
  e.preventDefault();
  $('.other-page').fadeOut(function(){
    $('.other-page').empty();
    $('.main-layer').fadeIn();
  });
});
</script>
