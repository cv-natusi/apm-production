<div class="row form-rujukan">

  <input type="hidden" name="noRujukan" id="noRujukan" value="<?php if ($data['edit'] == 'true') { print_r($data['rujukan']['response']->rujukan->noRujukan); } ?>">
  <input type="hidden" name="edit" id="edit" value="<?php print_r($data['edit'])?>">
  <div class="col-md-3">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-solid box-success">
          <div class="box-header with-border">
            <span><i class="fa fa-envelope"> SEP</i> </span>
            <div class="box-tools">
              <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
              <li><a title="No.SEP"><i class="fa fa-sort-numeric-asc"></i> <label id="lblnosep"><?php print_r($data['sep']['response']->noSep)?></label></a></li>
              <li><a title="Tgl.SEP"><i class="fa fa-calendar"></i> <label id="lbltglsep"><?php print_r($data['sep']['response']->tglSep)?></label></a></li>
              <li><a title="Jns.Pelayanan"><i class="fa fa-medkit"></i> <label id="lbljenpel"><?php print_r($data['sep']['response']->jnsPelayanan)?></label></a></li>
              <li><a title="Diagnosa"><i class="fa fa-heartbeat"></i> <label id="lbldiagnosa"><?php print_r($data['sep']['response']->diagnosa)?></label></a></li>
            </ul>
          </div>
            <!-- /.box-body -->
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-solid">
          <div class="box-header with-border">
            <span><i class="fa fa-user"> Peserta</i> </span>
            <div class="box-tools">
              <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
              <li><a title="No.Kartu"><i class="fa fa-sort-numeric-asc text-blue"></i> <label id="lblnokartu"><?php print_r($data['sep']['response']->peserta->noKartu)?></label></a></li>
              <li><a title="Nama Peserta"><i class="fa fa-user text-light-blue"></i> <label id="lblnmpeserta"><?php print_r($data['sep']['response']->peserta->nama)?></label></a></li>
              <li><a title="Tgl.Lahir"><i class="fa fa-calendar text-blue"></i> <label id="lbltgllhrpst"><?php print_r($data['sep']['response']->peserta->tglLahir)?></label></a></li>
              <li><a title="Kelamin"><i class="fa fa-intersex  text-blue"></i> <label id="lbljkpst"><?php print_r($data['sep']['response']->peserta->kelamin)?></label></a></li>
              <li><a title="Kelas Peserta"><i class="fa fa-user  text-blue"></i> <label id="lblklpst"><?php print_r($data['sep']['response']->peserta->hakKelas)?></label></a></li>
            </ul>
          </div>
            <!-- /.box-body -->
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9">
    <div class="box box-primary">
      <div class="box-header with-border">
        <i class="fa fa-battery-half"></i>
        <small class="pull-right">
          <label style="font-size:medium" id="lblnorujukan"><?php if ($data['edit'] == 'true') { print_r($data['rujukan']['response']->rujukan->noRujukan); } ?></label>
        </small>
      </div>
      <div class="box-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl.Rujukan</label>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <div class="input-group date">
                <input type="date" class="form-control datepicker" id="txttglrujukan" value="<?php if ($data['edit'] == 'true') { print_r($data['rujukan']['response']->rujukan->tglRujukan); } ?>" placeholder="yyyy-MM-dd" maxlength="10">
                <input type="hidden" class="form-control datepicker" id="txtrencanatglrujukan" placeholder="yyyy-MM-dd" maxlength="10" value="<?php if ($data['edit'] == 'true') {print_r($data['rujukan']['response']->rujukan->tglRencanaKunjungan); } ?>">
                <span class="input-group-addon">
                  <span class="fa fa-calendar">
                  </span>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Pelayanan</label>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <select class="form-control" id="cbpelayanan">
                <option value="2" <?php if ($data['edit'] == 'true' && $data['rujukan']['response']->rujukan->jnsPelayanan == 2) {'selected';} ?>>Rawat Jalan</option>
                <option value="1" <?php if ($data['edit'] == 'true' && $data['rujukan']['response']->rujukan->jnsPelayanan == 1) {'selected';} ?>>Rawat Inap</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tipe</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
              <label><input type="radio" name="rbrujukan" value="0" <?php if ($data['edit'] == 'true' && $data['rujukan']['response']->rujukan->tipeRujukan == 0) {'selected';} ?> id="rbpenuh" checked=""> Penuh</label>
              <label><input type="radio" name="rbrujukan" value="1" <?php if ($data['edit'] == 'true' && $data['rujukan']['response']->rujukan->tipeRujukan == 1) {'selected';} ?> id="rbpartial"> Partial</label>
              <label><input type="radio" name="rbrujukan" value="2" <?php if ($data['edit'] == 'true' && $data['rujukan']['response']->rujukan->tipeRujukan == 2) {'selected';} ?> id="rbbalik"> Rujuk Balik (Non PRB)</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa Rujukan</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
              <select class="form-control txtnmdiagnosa" id="txtnmdiagnosa" name="txtkddiag">
                <option>::. Pilih Diagnosa .::</option>
                <?php if ($data['edit'] == 'true') {
                  ?>
                  <option selected value="<?php print_r($data['rujukan']['response']->rujukan->diagRujukan); ?>"><?php print_r($data['rujukan']['response']->rujukan->diagRujukan); ?> <?php print_r($data['rujukan']['response']->rujukan->namaDiagRujukan); ?></option>

              <?php  } ?>
              </select>
              {{-- <input type="text" class="form-control ui-autocomplete-input" id="txtnmdiagnosa" placeholder="ketik kode atau nama  diagnosa minimal 3 karakter" autocomplete="off">
              <input type="hidden" id="txtkddiag" value=""> --}}
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Di Rujuk Ke</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="input-group">
                <input type="text" class="form-control" id="txtnmppkdirujuk" placeholder="nama ppk rujuk" value="<?php if ($data['edit'] == 'true') {print_r($data['rujukan']['response']->rujukan->namaPpkDirujuk);} ?>">
                <input type="hidden" id="txtkdppkdirujuk" name="txtkdppkdirujuk" value="<?php if ($data['edit'] == 'true') {print_r($data['rujukan']['response']->rujukan->ppkDirujuk);} ?>">
                <span class="input-group-btn">
                  <button type="button" id="btnCariPPKRujukan" class="btn btn-success">
                    <span><i class="fa fa-hospital-o"></i></span> &nbsp;
                  </button>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div id="divPoli">
              <label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis</label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" class="form-control" id="txtnmpoli" placeholder="spesialis atau subspesialis" value="<?php if ($data['edit'] == 'true') {print_r($data['rujukan']['response']->rujukan->namaPoliRujukan);} ?>">
                <input type="hidden" id="txtkdpoli" value="<?php if ($data['edit'] == 'true') {print_r($data['rujukan']['response']->rujukan->poliRujukan);} ?>">
              </div>
            </div>


          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Catatan Rujukan</label>
            <div class="col-md-7 col-sm-7 col-xs-12">
              <textarea type="text" class="form-control" id="txtketerangan"><?php if ($data['edit'] == 'true') {print_r($data['rujukan']['response']->rujukan->catatan);} ?></textarea>
            </div>

          </div>
        </form>
        <!-- obat -->
      </div>

      <div class="box-footer">
        <div class="form-group">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <button id="btnSimpan" type="button" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button id="btnEdit" type="button" class="btn btn-warning" ><i class="fa fa-edit"></i> Edit</button>
            <button id="btnHapus" type="button" class="btn btn-danger" ><i class="fa fa-trash"></i> Hapus</button>
            <button id="btnCetak" type="button" class="btn btn-info" ><i class="fa fa-print"></i> Cetak</button>
            <button id="btnBatal" type="button" class="btn btn-default pull-right btn-cancel"><i class="fa fa-undo"></i> Batal</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="ppkRujukan"></div>
<div class="printRujukan"></div>
<script type="text/javascript">
var edit = "<?php print_r($data['edit'])?>";
if (edit == 'true') {
  $("#txttglrujukan").prop("readonly",true);
  $("#cbpelayanan").prop("disabled",true);
  $("[name='rbrujukan']").prop("readonly",true);
  $("#txtnmppkdirujuk").prop("readonly",true);
  $("#txtnmpoli").prop("readonly",true);
  $("#txtketerangan").prop("readonly",true);
  $("#txtnmdiagnosa").prop("disabled",true);
  $('#btnEdit').show();
  $('#btnHapus').show();
  $('#btnCetak').show();
  $('#btnSimpan').hide();
}else{
  $('#btnEdit').hide();
  $('#btnHapus').hide();
  $('#btnCetak').hide();
}

$('#btnEdit').click(function(){
  $("#txttglrujukan").prop("readonly",false);
  $("#cbpelayanan").prop("disabled",false);
  $("[name='rbrujukan']").prop("readonly",false);
  $("#txtnmppkdirujuk").prop("readonly",false);
  $("#txtnmpoli").prop("readonly",false);
  $("#txtketerangan").prop("readonly",false);
  $("#txtnmdiagnosa").prop("disabled",false);
  $('#btnEdit').hide();
  $('#btnHapus').hide();
  $('#btnCetak').hide();
  $('#btnSimpan').show();
});
$('#btnHapus').click(function(){
  if (edit == true) {
    var id = $('#id').val();
  }
  var rowData = $('#noRujukan').val();
  swal(
    {
      title: "Apa anda yakin Menghapus Data Ini?",
      text: "Data akan dihapus dari sistem dan tidak dapat dikembalikan!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Saya yakin!",
      cancelButtonText: "Batal!",
      closeOnConfirm: false
    },
    function(){
      $.post("{!! route('hapus_rujukan') !!}", {noRujukan:rowData, id:id}).done(function(data){
        if(data.metaData.code == 200){
          swal("Success!", "Berhasil di Hapus", "success");
          $('#panel-add').animateCss('bounceOutDown');
          $('.other-page').fadeOut(function(){
            $('.other-page').empty();
            $('.main-layer').fadeIn();
          });
          location.reload();
        }else if (data.metaData.code == 201) {
          swal('Whoops!',data.metaData.message,'warning');
        }else{
          swal('Whoops!',data.metaData.message,'error');
        }
      });
    }
  )
});

$('.btn-cancel').click(function(e){
  e.preventDefault();
  $('#panel-add').animateCss('bounceOutDown');
  $('.other-page').fadeOut(function(){
    $('.other-page').empty();
    $('.main-layer').fadeIn();
  });
});

$('#btnCariPPKRujukan').click(function(){
  $('.form-rujukan').hide();
  $.post("{!! route('formPPKRujukan') !!}").done(function(data){
    if(data.status == 'success'){
      $('.ppkRujukan').html(data.content).fadeIn();
    } else {
      $('.form-rujukan').show();
    }
  });
});

$('#rbpenuh').click(function(){
  $('#txtkdppkdirujuk').val('');
  $('#txtnmppkdirujuk').val('');
  $('#divPoli').show();
  $('#btnCariPPKRujukan').show();
});
$('#rbpartial').click(function(){
  $('#txtkdppkdirujuk').val('');
  $('#txtnmppkdirujuk').val('');
  $('#btnCariPPKRujukan').show();
  $('#divPoli').hide();
});
$('#rbbalik').click(function(){
  var kode_ppk = "13200202";
  var nama_ppk = "Blooto";
  $('#txtkdppkdirujuk').val(kode_ppk);
  $('#txtnmppkdirujuk').val(nama_ppk);
  $('#divPoli').hide();
  $('#btnCariPPKRujukan').hide();
});

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

$('#btnSimpan').click(function(){
  var noSep = "<?php print_r($data['sep']['response']->noSep)?>";
  var id =  $('#id').val();
  var edit =  $('#edit').val();
  var tglRujukan =  $('#txttglrujukan').val();
  var tglRencanaKunjungan = $('#txtrencanatglrujukan').val();
  var ppkDirujuk = $('#txtkdppkdirujuk').val();
  var jnsPelayanan = $('#cbpelayanan').val();
  var catatan = $('#txtketerangan').val();
  var diagRujukan = $('#txtnmdiagnosa').val();
  var tipeRujukan = $("[name='rbrujukan']").val();
  var poliRujukan = $('#txtkdpoli').val();
  var noRujukan = $('#noRujukan').val();

  var data = new FormData();
  data.append('noSep',noSep);
  data.append('id',id);
  data.append('edit',edit);
  data.append('tglRujukan',tglRujukan);
  data.append('tglRencanaKunjungan',tglRencanaKunjungan);
  data.append('ppkDirujuk',ppkDirujuk);
  data.append('jnsPelayanan',jnsPelayanan);
  data.append('catatan',catatan);
  data.append('diagRujukan',diagRujukan);
  data.append('tipeRujukan',tipeRujukan);
  data.append('poliRujukan',poliRujukan);
  data.append('noRujukan',noRujukan);

if (edit == 'true') {
  $.ajax({
    url : "{!! route('update_rujukan') !!}",
    type: 'POST',
    dataType: 'json',
    data: data,
    async: true,
    cache: false,
    contentType: false,
    processData: false
  }).done(function(result) {
    console.log(result);
    if (result.update.metaData.code == 200) {
      var cetak = '';
      var stTitle = 'height="10px" style="font-size:11px;"';
      var stIsi = 'height="10px" style="font-size:11px;"';
      var widthJd1 = 'width="190px;"';
      var widthIsi1 = 'width="380px;"';
      var widthJd2 = 'width="140px;"';
      var widthIsi2 = 'width="280px;"';
      var stBerkas = 'style="font-size:11px;padding-left: 50px;"';
      var stPasKel = 'style="font-size: 10px;padding-left: 35px;height:10px;"';
      var stKet = 'style="font-size: 9px;"';
      var stCat2 = 'height="10px" style="font-size:10px;"';
      // for (var p = 0; p < 3; p++) {
          // if (p == 2) {
              cetak += '<div style="border-right:dashed 1px #fff;padding:0px;margin:0px;width:100%;height:250px;">';
          // }else{
          //  cetak += '<div style="border-right:dashed 1px #777;padding:0px;margin:0px;width:650px;height:250px;">';
          // }

          cetak += '<table border="0" style="margin-top:13px;margin-left:10px;">';
          cetak += '<tr>';
          cetak += '<td rowspan="2" width="400px">';
          var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
          cetak += '<img src="'+logoBpjs+'" width="300px" style="margin-left: 5px;">';
          cetak += '</td>';
          cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">SURAT RUJUKAN</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+stTitle+'><p style="margin:0px;font-size:20px;">RSUD Dr. Wahidin Sudiro Husodo</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. </p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["noRujukan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl. </p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["tglRujukan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '</table>';

          cetak += '<div style="margin-bottom: 5px;"></div>';
          cetak += '<table border="0" style="margin-left:10px;">';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Kepada Yth</p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["namaPoliRujukan"]+'</p></td>';
          cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;"></p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["namaPpkDirujuk"]+'</p></td>';

          cetak += '<td '+widthJd2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">'+result.rujukan.response.rujukan["namaTipeRujukan"]+'</p></td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">'+result.rujukan.response.rujukan["jnsPelayanan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Mohon Pemeriksaan dan Penanganan Lebih Lanjut:</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: </p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. Kartu</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["noKartu"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Nama Peserta</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["nama"]+' &nbsp ('+result.rujukan.response.rujukan["kelamin"]+')</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl.Lahir</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["tglLahir"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Diagnosa</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["namaDiagRujukan"]+' ('+result.rujukan.response.rujukan["diagRujukan"]+')</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Keterangan</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["catatan"]+'</p></td>';
          cetak += '</tr>';

          cetak += '<tr>';
          cetak += '<td colspan="3">&nbsp</td>';
          cetak += '<td '+stPasKel+'><p style="margin:0px;font-size:17px;">Mengetahui</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td colspan="3" '+stKet+' valign="bottom"><p style="margin:0px;font-size:17px;">Demikian atas bantuannya, diucapkan banyak terima kasih.</p></td>';
          cetak += '<td '+stPasKel+' valign="bottom" height="25px">_____________________</td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Rujukan Berlaku Sampai Dengan '+result.rujukan.response.rujukan["tglRujukan"]+'.<br>Tanggal Rencana Berkunjung '+result.rujukan.response.rujukan["tglRencanaKunjungan"]+'</p></td>';
          cetak += '<td align="right">.</td>';
          cetak += '</tr>';

          cetak += '<tr>';
          cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Tgl Cetak '+result.rujukan.response.rujukan["tglRujukan"]+' </p></td>';
          cetak += '<td align="right">.</td>';
          cetak += '</tr>';

          cetak += '</table>';
          cetak += '</div>';
          cetak += '<div style="page-break-after: always;"></div>';
      // }

      $('.printRujukan').html(cetak);
      var printHtml = window.open('', 'PRINT', '');
      printHtml.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">');
      printHtml.document.write($('.printRujukan').html());
      printHtml.document.write('</body></html>');
      printHtml.document.close();
      printHtml.focus();
      setTimeout(() => {
          printHtml.print();
          printHtml.close();
          $('.printRujukan').html('');
          $('#panel-add').animateCss('bounceOutDown');
          $('.other-page').fadeOut(function(){
            $('.other-page').empty();
            $('.main-layer').fadeIn();
          });
          // $('#modalPasienBpjs').modal('hide');
      }, 500);
    }else if (result.update.metaData.code == 201) {
      swal('Whoops!',result.update.metaData.message,'warning');
    }else {
      swal('Whoops!',result.update.metaData.message,'error');
    }
  });
}else {
  $.ajax({
    url : "{!! route('insert_rujukan') !!}",
    type: 'POST',
    dataType: 'json',
    data: data,
    async: true,
    cache: false,
    contentType: false,
    processData: false
  }).done(function(result) {
    console.log(result);
    if (result.insert.metaData.code == 200) {
      var cetak = '';
      var stTitle = 'height="10px" style="font-size:11px;"';
      var stIsi = 'height="10px" style="font-size:11px;"';
      var widthJd1 = 'width="190px;"';
      var widthIsi1 = 'width="380px;"';
      var widthJd2 = 'width="140px;"';
      var widthIsi2 = 'width="280px;"';
      var stBerkas = 'style="font-size:11px;padding-left: 50px;"';
      var stPasKel = 'style="font-size: 10px;padding-left: 35px;height:10px;"';
      var stKet = 'style="font-size: 9px;"';
      var stCat2 = 'height="10px" style="font-size:10px;"';
      // for (var p = 0; p < 3; p++) {
          // if (p == 2) {
              cetak += '<div style="border-right:dashed 1px #fff;padding:0px;margin:0px;width:100%;height:250px;">';
          // }else{
          //  cetak += '<div style="border-right:dashed 1px #777;padding:0px;margin:0px;width:650px;height:250px;">';
          // }

          cetak += '<table border="0" style="margin-top:13px;margin-left:10px;">';
          cetak += '<tr>';
          cetak += '<td rowspan="2" width="400px">';
          var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
          cetak += '<img src="'+logoBpjs+'" width="300px" style="margin-left: 5px;">';
          cetak += '</td>';
          cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">SURAT RUJUKAN</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+stTitle+'><p style="margin:0px;font-size:20px;">RSUD Dr. Wahidin Sudiro Husodo</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. </p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.insert.response.rujukan["noRujukan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl. </p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.insert.response.rujukan["tglRujukan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '</table>';

          cetak += '<div style="margin-bottom: 5px;"></div>';
          cetak += '<table border="0" style="margin-left:10px;">';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Kepada Yth</p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.insert.response.rujukan.poliTujuan["nama"]+'</p></td>';
          cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;"></p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.insert.response.rujukan.tujuanRujukan["nama"]+'</p></td>';

          cetak += '<td '+widthJd2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">'+result.rujukan.response.rujukan["namaTipeRujukan"]+'</p></td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">'+result.rujukan.response.rujukan["jnsPelayanan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Mohon Pemeriksaan dan Penanganan Lebih Lanjut:</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: </p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. Kartu</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.insert.response.rujukan.peserta["noKartu"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Nama Peserta</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.insert.response.rujukan.peserta["nama"]+' &nbsp ('+result.insert.response.rujukan.peserta["kelamin"]+')</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl.Lahir</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.insert.response.rujukan.peserta["tglLahir"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Diagnosa</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.insert.response.rujukan.diagnosa["nama"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Keterangan</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["catatan"]+'</p></td>';
          cetak += '</tr>';

          cetak += '<tr>';
          cetak += '<td colspan="3">&nbsp</td>';
          cetak += '<td '+stPasKel+'><p style="margin:0px;font-size:17px;">Mengetahui</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td colspan="3" '+stKet+' valign="bottom"><p style="margin:0px;font-size:17px;">Demikian atas bantuannya, diucapkan banyak terima kasih.</p></td>';
          cetak += '<td '+stPasKel+' valign="bottom" height="25px">_____________________</td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Rujukan Berlaku Sampai Dengan '+result.insert.response.rujukan["tglRujukan"]+'.<br>Tanggal Rencana Berkunjung '+result.insert.response.rujukan["tglRencanaKunjungan"]+'</p></td>';
          cetak += '<td align="right">.</td>';
          cetak += '</tr>';

          cetak += '<tr>';
          cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Tgl Cetak '+result.insert.response.rujukan["tglRujukan"]+' </p></td>';
          cetak += '<td align="right">.</td>';
          cetak += '</tr>';

          cetak += '</table>';
          cetak += '</div>';
          cetak += '<div style="page-break-after: always;"></div>';
      // }

      $('.printRujukan').html(cetak);
      var printHtml = window.open('', 'PRINT', '');
      printHtml.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">');
      printHtml.document.write($('.printRujukan').html());
      printHtml.document.write('</body></html>');
      printHtml.document.close();
      printHtml.focus();
      setTimeout(() => {
          printHtml.print();
          printHtml.close();
          $('.printRujukan').html('');
          $('#panel-add').animateCss('bounceOutDown');
          $('.other-page').fadeOut(function(){
            $('.other-page').empty();
            $('.main-layer').fadeIn();
          });
          // $('#modalPasienBpjs').modal('hide');
      }, 500);
    }else if (result.insert.metaData.code == 201) {
      swal('Whoops!',result.insert.metaData.message,'warning');
    }else {
      swal('Whoops!',result.insert.metaData.message,'error');
    }
  });
}

});

$('#btnCetak').click(function(){
  var noRujukan = $('#noRujukan').val();
  var data = new FormData();
  data.append('noRujukan',noRujukan);
  $.ajax({
    url : "{!! route('cetak_rujukan') !!}",
    type: 'POST',
    dataType: 'json',
    data: data,
    async: true,
    cache: false,
    contentType: false,
    processData: false
  }).done(function(result) {
    console.log(result);
    if (result.rujukan.metaData.code == 200) {
      var cetak = '';
      var stTitle = 'height="10px" style="font-size:11px;"';
      var stIsi = 'height="10px" style="font-size:11px;"';
      var widthJd1 = 'width="190px;"';
      var widthIsi1 = 'width="380px;"';
      var widthJd2 = 'width="140px;"';
      var widthIsi2 = 'width="280px;"';
      var stBerkas = 'style="font-size:11px;padding-left: 50px;"';
      var stPasKel = 'style="font-size: 10px;padding-left: 35px;height:10px;"';
      var stKet = 'style="font-size: 9px;"';
      var stCat2 = 'height="10px" style="font-size:10px;"';
      // for (var p = 0; p < 3; p++) {
          // if (p == 2) {
              cetak += '<div style="border-right:dashed 1px #fff;padding:0px;margin:0px;width:100%;height:250px;">';
          // }else{
          //  cetak += '<div style="border-right:dashed 1px #777;padding:0px;margin:0px;width:650px;height:250px;">';
          // }

          cetak += '<table border="0" style="margin-top:13px;margin-left:10px;">';
          cetak += '<tr>';
          cetak += '<td rowspan="2" width="400px">';
          var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
          cetak += '<img src="'+logoBpjs+'" width="300px" style="margin-left: 5px;">';
          cetak += '</td>';
          cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">SURAT RUJUKAN</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+stTitle+'><p style="margin:0px;font-size:20px;">RSUD Dr. Wahidin Sudiro Husodo</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. </p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["noRujukan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl. </p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["tglRujukan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '</table>';

          cetak += '<div style="margin-bottom: 5px;"></div>';
          cetak += '<table border="0" style="margin-left:10px;">';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Kepada Yth</p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["namaPoliRujukan"]+'</p></td>';
          cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;"></p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["namaPpkDirujuk"]+'</p></td>';

          cetak += '<td '+widthJd2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">'+result.rujukan.response.rujukan["namaTipeRujukan"]+'</p></td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">'+result.rujukan.response.rujukan["jnsPelayanan"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Mohon Pemeriksaan dan Penanganan Lebih Lanjut:</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: </p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. Kartu</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["noKartu"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Nama Peserta</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["nama"]+' &nbsp ('+result.rujukan.response.rujukan["kelamin"]+')</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl.Lahir</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["tglLahir"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Diagnosa</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["namaDiagRujukan"]+' ('+result.rujukan.response.rujukan["diagRujukan"]+')</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Keterangan</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.rujukan.response.rujukan["catatan"]+'</p></td>';
          cetak += '</tr>';

          cetak += '<tr>';
          cetak += '<td colspan="3">&nbsp</td>';
          cetak += '<td '+stPasKel+'><p style="margin:0px;font-size:17px;">Mengetahui</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td colspan="3" '+stKet+' valign="bottom"><p style="margin:0px;font-size:17px;">Demikian atas bantuannya, diucapkan banyak terima kasih.</p></td>';
          cetak += '<td '+stPasKel+' valign="bottom" height="25px">_____________________</td>';
          cetak += '</tr>';
          cetak += '<tr>';
          var dateRujukan = new Date(result.rujukan.response.rujukan["tglRujukan"]);
          var dateAdd89Days = dateRujukan.setDate(dateRujukan.getDate() + 89);
          var options = {
              weekday: "long",
              year: "numeric",
              month: "2-digit",
              day: "numeric"
          };
          var strtime = new Date(dateAdd89Days).toLocaleString("id",options);
          console.log(strtime);
          cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Rujukan Berlaku Sampai Dengan '+strtime+'.<br>Tanggal Rencana Berkunjung '+result.rujukan.response.rujukan["tglRencanaKunjungan"]+'</p></td>';
          cetak += '<td align="right">.</td>';
          cetak += '</tr>';

          cetak += '<tr>';
          cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Tgl Cetak '+result.rujukan.response.rujukan["tglRujukan"]+' </p></td>';
          cetak += '<td align="right">.</td>';
          cetak += '</tr>';

          cetak += '</table>';
          cetak += '</div>';
          cetak += '<div style="page-break-after: always;"></div>';
      // }

      $('.printRujukan').html(cetak);
      var printHtml = window.open('', 'PRINT', '');
      printHtml.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">');
      printHtml.document.write($('.printRujukan').html());
      printHtml.document.write('</body></html>');
      printHtml.document.close();
      printHtml.focus();
      setTimeout(() => {
          printHtml.print();
          printHtml.close();
          $('.printRujukan').html('');
          $('#panel-add').animateCss('bounceOutDown');
          $('.other-page').fadeOut(function(){
            $('.other-page').empty();
            $('.main-layer').fadeIn();
          });
          // $('#modalPasienBpjs').modal('hide');
      }, 500);
    }else if (result.metaData.code == 201) {
      swal('Whoops!',result.metaData.message,'warning');
    }else {
      swal('Whoops!',result.metaData.message,'error');
    }
  });
});
</script>
