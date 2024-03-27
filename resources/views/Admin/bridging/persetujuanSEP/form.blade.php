<div class="modal fade in" id="mpenjaminan" tabindex="-1" role="dialog" data-target="#btnRefresh" aria-labelledby="myModalLabel" aria-hidden="true" style="display: block; padding-right: 15px;">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <input id="txturl" type="hidden">
            <input id="txtkdstatuspst" type="hidden" value="0">
            <button type="button" id="btnBatal" class="close" data-dismiss="modal">×</button>
            <h3 class="modal-title">Persetujuan SEP</h3>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger alert-dismissible" id="divInfo" style="display: none;">
                <input type="hidden" id="txtidhiden" value="0">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                <p id="pInfo"></p>
            </div>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12"><label style="color:gray;font-size:x-small">(yyyy-mm-dd)</label> Tgl.SEP</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group date">
                            <input type="date" value="{{date('Y-m-d')}}" class="form-control datepicker" id="txttglsep_penjaminan" placeholder="yyyy-MM-dd" maxlength="10">
                            <span class="input-group-addon">
                                <span class="fa fa-calendar">
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">No.Kartu</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="input-group">
                            <input type="text" class="form-control" id="txtkartu_penjaminan" placeholder="ketik nomor kartu bpjs" maxlength="13">
                            <span class="input-group-btn">
                                <button type="button" id="btnKartu_penjaminan" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Nama</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" id="txtnama_penjaminan" placeholder="nama" disabled="">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Pelayanan</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" id="cbpelayanan_penjaminan">
                            <option value="1">Rawat Inap</option>
                            <option value="2">Rawat Jalan</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Pilih</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <select class="form-control" id="cbflag">
                            <option value="1">Persetujuan Tanggal SEP Backdate</option>
                            <option value="2">Persetujuan Fingerprint</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Keterangan</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea type="text" class="form-control" id="txtketerangan_penjaminan" placeholder="keterangan"> </textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="btnSimpan_penjaminan" class="btn btn-danger pull-left"><span class="fa fa-save"></span> Simpan</button>
            <a href="javascript:void(0)" id="btnClose" class="btn btn-default pull-right" class="close"><span class="fa fa-close"></span> Batal</aa>
        </div>
    </div>
</div>

{{-- <script src="/VClaim/Content/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<link href="/VClaim/Content/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet"> --}}

<script type="text/javascript">

$('#btnBatal').click(function(e){
  e.preventDefault();
  $('#mpenjaminan').animateCss('bounceOutDown');
  // $('.modal-dialog').fadeOut(function(){
  //   $('.modal-dialog').empty();
  //   $('.main-layer').fadeIn();
  // });
  location.reload();
});
$('#btnClose').click(function(e){
  e.preventDefault();
  $('#mpenjaminan').animateCss('bounceOutDown');
  // $('.modal-dialog').fadeOut(function(){
  //   $('.modal-dialog').empty();
  //   $('.main-layer').fadeIn();
  // });
  location.reload();
});

    $('#txturl').val("");
    $('#divInfo').hide();

    var dt = new Date();
    dt.setMonth(dt.getMonth() - 6);

    $('.datepicker').datepicker({
        language: 'id',
        startDate: formattgl(dt),
        endDate: formattgl(new Date()),
        todayHighlight: 1,
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: "left bottom"
    });
    $('#txtidhiden').val("0");

    function parseDate(str) {
        var mdy = str.split('-');
        return new Date(mdy[0], mdy[1] - 1, mdy[2]);
    }
    function datediff(first, second) {
        return Math.round((second - first) / (1000 * 60 * 60 * 24));
    }


    function formattgl(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }

    $('#btnKartu_penjaminan').click(function () {
        if ($('#txttglsep_penjaminan').val() == '') {
            $('#divInfo').show();
            $('#pInfo').html('Tanggal SEP Harus Diisi')
            return false;
        }

        if ($('#txtkartu_penjaminan').val().length < 13) {
            $('#divInfo').show();
            $('#pInfo').html('No.Kartu Tidak Sesuai');
            return false;
        }

        var nobpjs = $('#txtkartu_penjaminan').val();
        var data = new FormData();
        data.append('nobpjs',nobpjs);
        data.append('jnsCari','bpjs');
        $.ajax({
          url : "{!! route('cekpeserta') !!}",
          type: 'POST',
          dataType: 'json',
          data: data,
          async: true,
          cache: false,
          contentType: false,
          processData: false
        }).done(function(data) {
          if (data.metaData.code == '200') {
              $('#txtnama_penjaminan').val(data.response.peserta.nama);
              $('#txtkdstatuspst').val(data.response.peserta.statusPeserta.kode);
              $('#divInfo').hide();
              $('#txtidhiden').val(data.metaData.code);
          }
          else {
              $('#divInfo').show();
              $('#txtnama_penjaminan').val("");
              $('#txtkdstatuspst').val("");
              $('#pInfo').html(data.metaData.message)
              $('#txtidhiden').val(data.metaData.code);
          }
        });
    })
    //
    //
    $('#btnSimpan_penjaminan').click(function () {
        var noka = $('#txtkartu_penjaminan').val();
        var tgl = $('#txttglsep_penjaminan').val();
        var jenpel = $('#cbpelayanan_penjaminan').val();
        var status = $('#txtkdstatuspst').val();
        var ket = $('#txtketerangan_penjaminan').val();
        var flag = $('#cbflag').val();

        if ($('#txtkartu_penjaminan').val().length < 13) {
            $('#divInfo').show();
            $('#pInfo').html('No.Kartu Tidak Sesuai');
            return false;
        }

        if (tgl == '')
        {
            $('#divInfo').show();
            $('#pInfo').html('Tanggal SEP Harus Diisi')
            return false;
        }

        var now = formattgl(new Date());
        var hit = datediff(parseDate(tgl), parseDate(now))
        if (hit > 180) {
            swal("Informasi..!!", 'Maaf..Maksimal Pengajuan 6 Bulan ', 'info');
            return false;
        }

        //cek dulu
        var nobpjs = $('#txtkartu_penjaminan').val();
        var data = new FormData();
        data.append('nobpjs',nobpjs);
        data.append('jnsCari','bpjs');
        $.ajax({
          url : "{!! route('cekpeserta') !!}",
          type: 'POST',
          dataType: 'json',
          data: data,
          async: true,
          cache: false,
          contentType: false,
          processData: false
        }).done(function(data) {
          if (data.metaData.code == '200') {
            simpanApp(noka, tgl, jenpel, ket, status, flag);
            $('#btnCari').click();
          }
          else {
              $('#divInfo').show();
              $('#txtnama_penjaminan').val("");
              $('#txtkdstatuspst').val("");
              $('#pInfo').html(data.metaData.message)
              $('#txtidhiden').val(data.metaData.code);
          }
        });
    })
    function onFocusOutNoKartuLeadingZero(nomor) {
        var ret = '';
        ret = nomor.padStart(13, '0');
        return ret;
    }
    $('#txtkartu_penjaminan').focusout(function () {

        $('#txtkartu_penjaminan').val(onFocusOutNoKartuLeadingZero(this.value));
    });

    function simpanApp(noka, tgl, jenpel, ket, status, flag)
    {
      var data = new FormData();
      data.append('noka',noka);
      data.append('tgl',tgl);
      data.append('jenpel',jenpel);
      data.append('keterangan',ket);
      data.append('aprove','0');
      data.append('status',status);
      data.append('flag',flag);

        // data = {
        //     noka: noka, tgl: tgl, jenpel: jenpel, keterangan: ket, aprove: '0', status: status, flag: flag
        // }
        // console.log(data);
        $.ajax({
          url : "{{route('simpanPengajuanSEP') }}",
          type: 'POST',
          dataType: 'json',
          data: data,
          async: true,
          cache: false,
          contentType: false,
          processData: false
        }).done(function(data) {
          if (data.metaData.code == '200') {
            swal({
              title:"OK..!",
              message: data.metaData.message,
              type: 'success',
              // timer:3000,
            });
            // $('#mpenjaminan').modal('hide');
            // location.reload();
          }else {
            swal("PERHATIAN..!!", data.metaData.message, 'warning');
            // $('#mpenjaminan').modal('hide');
            // location.reload();
          }
        });
    }

</script>
</div>
