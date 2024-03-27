<div class="row form-prb" id="panel-add">
  <input type="hidden" name="noSRB" id="noSRB" value="<?php if ($data['edit'] == 'true') { print_r($data['dataprb']['response']->prb->noSRB); } ?>">
  <input type="hidden" name="noKartu" id="noKartu" value="<?php print_r($data['sep']['response']->peserta->noKartu) ?>">
  <input type="hidden" name="noSep" id="noSep" value="<?php print_r($data['sep']['response']->noSep) ?>">
  <input type="hidden" name="id" id="id" value="<?php print_r($data['id'])?>">
  <input type="hidden" name="edit" id="edit" value="<?php print_r($data['edit'])?>">
  <input type="hidden" name="jnsPelayanan" id="jnsPelayanan" value="<?php print_r($data['sep']['response']->jnsPelayanan) ?>">
  <input type="hidden" name="kodePoliSep" id="kodePoliSep" value="<?php print_r($data['sep']['response']->poli) ?>">
  <input type="hidden" name="poliRujukan" id="poliRujukan" value="<?php print_r($data['rujukan']['response']->rujukan->poliRujukan->kode) ?>">
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
          <label style="font-size:medium" id="lblnorujukan"><?php if ($data['edit'] == 'false') { print_r($data['sep']['response']->noSep); }else{print_r($data['dataprb']['response']->prb->noSRB);} ?></label>
        </small>
      </div>
      <div class="box-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label"><label style="color:gray;font-size:x-small">(yyyy-mm-dd)</label> Tgl.Rujuk Balik</label>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <div class="input-group date">
                <input type="text" class="form-control datepicker" id="txttglsrb" value="<?php if ($data['edit'] == 'false') { print_r($data['sep']['response']->tglSep); }else{print_r($data['dataprb']['response']->prb->tglSRB);} ?>" placeholder="yyyy-MM-dd" maxlength="10" >
                <span class="input-group-addon">
                  <span class="fa fa-calendar">
                  </span>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Peserta <label style="color:red;font-size:small">*</label></label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" id="alamat" value="<?php if ($data['edit'] == 'false') {  }else{print_r($data['dataprb']['response']->prb->peserta->alamat);} ?>" placeholder="ketik alamat rumah tinggal peserta" maxlength="200" >
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Kontak/Email Peserta/Keluarga <label style="color:red;font-size:small">*</label></label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" id="email" value="<?php if ($data['edit'] == 'false') {  }else{print_r($data['dataprb']['response']->prb->peserta->email);} ?>" placeholder="ketik kontak/alamat email peserta/keluarga peserta" maxlength="200" >
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Program PRB<label style="color:red;font-size:small">*</label></label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <select class="form-control" id="txtnmdiagnosa" >
                <option value="" selected disabled>-- Silahkan Pilih --</option>
                <?php if ($data['edit'] == 'true') { ?>
                  <option selected value="<?php if ($data['edit'] == 'true') {print_r($data['dataprb']['response']->prb->programPRB->kode);} ?>" ><?php if ($data['edit'] == 'true') {print_r($data['dataprb']['response']->prb->programPRB->nama); } ?>
                  </option>
              <?php  } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">DPJP Pemberi Pelayanan <label style="color:red;font-size:small">*</label></label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <select class="form-control" id="txtnmdpjpPelayanan" >
                <option value="" selected disabled>-- Silahkan Pilih --</option>
                <?php if ($data['edit'] == 'true') { ?>
                  <option selected value="<?php if ($data['edit'] == 'true') {print_r($data['dataprb']['response']->prb->DPJP->kode);} ?>" ><?php if ($data['edit'] == 'true') {print_r($data['dataprb']['response']->prb->DPJP->nama); } ?>
                  </option>
              <?php  } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Keterangan</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" value="<?php if ($data['edit'] == 'false') {  }else{print_r($data['dataprb']['response']->prb->keterangan);} ?>" id="keterangan" placeholder="ketik keterangan maksimal 150 karakter" maxlength="150" >
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Saran</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" id="saran" value="<?php if ($data['edit'] == 'false') {  }else{print_r($data['dataprb']['response']->prb->saran);} ?>" placeholder="ketik saran maksimal 150 karakter" maxlength="150" >
            </div>
          </div>
        </form>
        <!-- obat -->
        <div class="row">
          <div class="col-xs-12">
            <h4 class="page-header">
              <i class="fa fa-cart-plus"></i> Obat
            </h4>
          </div>
          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Obat <label style="color:red;font-size:small">*</label></label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <select name="kodeobatPrb" id="txtnamaobatPrb"  class="form-control" style="width: 100%" >
                </select>
                <input type="hidden" disabled="" class="form-control form-control-sm" id="txtkodeobatPrb">
                <input type="hidden" name="kode_obat" id="kode_obat">
                <input type="hidden" name="nama_obat" id="nama_obat">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12 control-label">Signa <label style="color:red;font-size:small">*</label></label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="input-group">
                  <input type="number" class="form-control" id="signa1" value="0" max="9" min="0" placeholder="Signa 1" onkeyup="if(this.value>99){this.value='9';}else if(this.value<0){this.value='0';}" >
                  <span class="input-group-addon">
                    x
                  </span>
                  <input type="number" class="form-control" id="signa2" value="0" max="9" min="0" placeholder="Signa 2" onkeyup="if(this.value>99){this.value='9';}else if(this.value<0){this.value='0';}" >
                </div>

              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah <label style="color:red;font-size:small">*</label></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <div class="input-group">
                  <input type="number" class="form-control" id="jmlObat" placeholder="jml obat" value="0" min="0" >
                  <span class="input-group-btn">
                    <button type="button" onclick="AddObat(this)" id="btnAddObat" title="Tambah Obat" class="btn btn-secondary btn-tambah-obat-resep">
                      &nbsp;
                      <i class="fa fa-plus"></i>
                    </button>
                  </span>
                </div>

              </div>
            </div>
            <div class="form-group">
              <div class="col-md-2 col-sm-2 col-xs-12"></div>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <div id="tbobat_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-bordered table-striped dataTable no-footer" id="hasil_resep">
                        <thead>
                          <tr role="row">
                            <th>#</th>
                            <th>Kode Obat</th>
                            <th>Nama Obat</th>
                            <th>S1</th>
                            <th>S2</th>
                            <th>Jumlah</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if ($data['edit'] == 'true') {
                            // $dataObat = $data['dataprb']['response']->prb->obat->obat;
                            $dataObat = $data['obat_prb'];
                            ?>
                            <?php
                              $no = 1;
                            ?>
                              <?php foreach ($dataObat as $value) : ?>
                                <tr id="row{{$value->kdObat}}">
                                  <td>
                                    {{$no++}}
                                    <input type="hidden" name="kode_obat[]" value="{{$value->kdObat}}">
                                    <input type="hidden" name="nama_obat[]" value="{{$value->nmObat}}">
                                    <input type="hidden" name="signa1[]" value="{{$value->signa1}}">
                                    <input type="hidden" name="signa2[]" value="{{$value->signa2}}">
                                    <input type="hidden" name="jmlObat[]" value="{{$value->jmlObat}}">
                                  </td>
                                  <td>{{$value->kdObat}}</td>
                                  <td>{{$value->nmObat}}</td>
                                  <td>{{$value->signa1}}</td>
                                  <td>{{$value->signa2}}</td>
                                  <td>{{$value->jmlObat}}</td>
                                  <td><button type="button" name="remove" id="{{$value->kdObat}}" class="btn btn-danger btn_remove_obat_new btn-xs"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            <?php endforeach; ?>
                          <?php }?>
                        </tbody>
                      </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-5"></div>
                  <div class="col-sm-7"></div>
                </div></div>
                </div>
              </div>

            </form>
          </div>
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
<div class="printPrb"></div>
<script type="text/javascript">
var edit = $('#edit').val();
if (edit == 'true') {
  $("#txttglsrb").prop("readonly",true);
  $("#txtnamaobatPrb").prop("disabled",true);
  $("#signa1").prop("readonly",true);
  $("#signa2").prop("readonly",true);
  $("#jmlObat").prop("readonly",true);
  $("#alamat").prop("readonly",true);
  $("#email").prop("readonly",true);
  $("#keterangan").prop("readonly",true);
  $("#saran").prop("readonly",true);
  $("#txtnmdpjpPelayanan").prop("disabled",true);
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
  $("#txttglsrb").prop("readonly",false);
  $("#txtnamaobatPrb").prop("disabled",false);
  $("#signa1").prop("readonly",false);
  $("#signa2").prop("readonly",false);
  $("#jmlObat").prop("readonly",false);
  $("#alamat").prop("readonly",false);
  $("#email").prop("readonly",false);
  $("#keterangan").prop("readonly",false);
  $("#saran").prop("readonly",false);
  $("#txtnmdpjpPelayanan").prop("disabled",false);
  $("#txtnmdiagnosa").prop("disabled",false);
  $('#btnEdit').hide();
  $('#btnHapus').hide();
  $('#btnCetak').hide();
  $('#btnSimpan').show();
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

$("#txtnmdpjpPelayanan").select2({
   minimumInputLength: 3,
   width: '100%',
   tags: [],
   ajax: {
       url: "{{ route('cek_dokter_dpjp') }}",
       dataType: 'json',
       type: "POST",
           data: function (params) {
           var jp = $('#jnsPelayanansa').val();

           if (jp == 'Rawat Jalan') {
             var rawat = '2';
           }else{
             var rawat = '1';
           }
           var tanggal =  $('#txttglsrb').val();
           var poli = $("#poliRujukan").val();

           var queryParameters = {
               poli: poli,
               rawat:rawat,
               tanggal:tanggal,

           }
           dokter = params.term;
           return queryParameters;
       },
       processResults: function (data) {
           return {
               results: $.map(data.list, function (item) {
                   console.log(item);
                   dokter = dokter.toUpperCase();
                   var nama = item.nama.toUpperCase();
                   var check = nama.search(dokter);
                   if (check != '-1') {
                       return {
                           text: item.nama,
                           id: item.kode
                       }

                   }
               })
           };
       }

   }
});
$("#txtnmdiagnosa").select2({
   // minimumInputLength: 1,
     width: '100%',
   tags: [],
   ajax: {
     url: "{{ route('cek_diagnosaprb') }}",
     dataType: 'json',
     type: "GET",
     //     data: function (params) {
     //     var queryParameters = {
     //         diagnosa: params.term
     //     }
     //     return queryParameters;
     // },
     processResults: function (data) {
         return {
             results: $.map(data.list, function (item) {
                 return {
                     text: item.nama,
                     id: item.kode
                 }
             })
         };
     }
   }
});

$("#txtnamaobatPrb").select2({
   minimumInputLength: 1,
   tags: [],
   ajax: {
     url: "{{ route('cek_obat_prb') }}",
     dataType: 'json',
     type: "POST",
         data: function (params) {
         var kd_obat =  $('#txtnamaobatPrb').val();

         var queryParameters = {
             kd_obat: params.term
         }
         return queryParameters;
     },
     processResults: function (data) {
         return {
             results: $.map(data.list, function (item) {
                 return {
                     text: item.nama,
                     id: item.kode
                 }
             })
         };
     }
   }
});

$('#txtnamaobatPrb').change(function () {
  console.log($('#txtnamaobatPrb').val());
  console.log($("#select2-txtnamaobatPrb-container"));
});

var array_obat = [];
function AddObat(e){
    var checks = '';
    var kode_obat = $('#txtnamaobatPrb').val();
    var nama_obat = $("#select2-txtnamaobatPrb-container");
    var signa1 = $("input[id='signa1'"+"]").val();
    var signa2 = $("input[id='signa2'"+"]").val();
    var jmlObat = $("input[id='jmlObat'"+"]").val();

    array_obat.forEach(element => {
        if (element[0] == kode_obat) {
            checks = 'ada';
        }
    });

    if((signa1 == '')){
      swal('peringatan',"Signa1 harus diisi",'warning');
      return false;
    }
    if((signa2 == '')){
      swal('peringatan',"Signa2 harus diisi",'warning');
      return false;
    }
    if((jmlObat == '')){
      swal('peringatan',"Jumlah harus diisi",'warning');
      return false;
    }

    $('#hasil_resep tbody').append('<tr id="row'+kode_obat+'"> <td><input type="hidden" name="kode_obat[]" value="'+kode_obat+'" /> <input type="hidden" name="nama_obat[]" value="'+nama_obat[0].outerText+'" /> <input type="hidden" readonly name="signa1[]" value="'+signa1+'" /><input type="hidden" readonly name="signa2[]" value="'+signa2+'" /> <input type="hidden" readonly name="jmlObat[]" value="'+jmlObat+'" /> <td> '+kode_obat+' </td> <td> '+nama_obat[0].outerText+' </td> <td> '+signa1+' </td> <td> '+signa2+' </td> <td> '+jmlObat+' </td> <td><button type="button" name="remove" id="'+kode_obat+'" class="btn btn-danger btn_remove_obat btn-sm"><i class="fa fa-trash"></button></td></tr>');

    $('#nama_obat').val('');
    $('#kode_obat').val('');
    $('#signa1').val('');
    $('#signa2').val('');
    $('#jmlObat').val('');

    var obat_sementara = [kode_obat,nama_obat,signa1,signa2,jmlObat];
    array_obat.push(obat_sementara);
}

$(document).on('click', '.btn_remove_obat', function(){
    var button_id = $(this).attr("id");
    $('#row'+button_id+'').remove();
    array_obat = array_obat.filter(x=>x[0]!=button_id);
    console.log(array_obat);
});

$(document).on('click', '.btn_remove_obat_new', function(){
    var button_id = $(this).attr("id");
    $('#row'+button_id+'').remove();
    array_obat = array_obat.filter(x=>x[0]!=button_id);
    console.log(array_obat);
});

$('#btnSimpan').click(function(){
  console.log(array_obat);
  if (edit == 'true') {
    var id = $('#id').val();
    var noSRB = $('#noSRB').val();
  }
    var noKartu = $('#noKartu').val();
    var noSep = $('#noSep').val();

    var alamat              = $('#alamat').val();
    var email               = $('#email').val();
    var txtnmdiagnosa       = $('#txtnmdiagnosa').val();
    var txtnmdpjpPelayanan  = $('#txtnmdpjpPelayanan').val();
    var keterangan          = $('#keterangan').val();
    var saran               = $('#saran').val();

    var kode_obat_arr       = $('input[name="kode_obat[]"]');
    var nama_obat_arr       = $('input[name="nama_obat[]"]');
    var signa1_arr          = $('input[name="signa1[]"]');
    var signa2_arr          = $('input[name="signa2[]"]');
    var jmlObat_arr         = $('input[name="jmlObat[]"]');

    var kode_obat = [];
    var nama_obat = [];
    var signa1 = [];
    var signa2 = [];
    var jmlObat = [];

    kode_obat_arr.map((i,v) => {
      kode_obat.push(v.value)

    });

    nama_obat_arr.map((i,v) => {
      nama_obat.push(v.value)

    });

    signa1_arr.map((i,v) => {
      signa1.push(v.value)

    });

    signa2_arr.map((i,v) => {
      signa2.push(v.value)

    });

    jmlObat_arr.map((i,v) => {
      jmlObat.push(v.value)
      // console.log(v)
    });


    var data = new FormData();
    if (edit == 'true') {
      data.append('id',id);
      data.append('noSRB',noSRB);
      data.append('noSep',noSep);
      data.append('noKartu',noKartu);
      data.append('alamat',alamat);
      data.append('email',email);
      data.append('txtnmdpjpPelayanan',txtnmdpjpPelayanan);
      data.append('keterangan',keterangan);
      data.append('saran',saran);

      data.append('kode_obat',JSON.stringify(kode_obat));
      data.append('nama_obat',JSON.stringify(nama_obat));
      data.append('signa1',JSON.stringify(signa1));
      data.append('signa2',JSON.stringify(signa2));
      data.append('jmlObat',JSON.stringify(jmlObat));
    }else {
      data.append('noSep',noSep);
      data.append('noKartu',noKartu);
      data.append('alamat',alamat);
      data.append('email',email);
      data.append('txtnmdiagnosa',txtnmdiagnosa);
      data.append('txtnmdpjpPelayanan',txtnmdpjpPelayanan);
      data.append('keterangan',keterangan);
      data.append('saran',saran);

      data.append('kode_obat',JSON.stringify(kode_obat));
      data.append('nama_obat',JSON.stringify(nama_obat));
      data.append('signa1',JSON.stringify(signa1));
      data.append('signa2',JSON.stringify(signa2));
      data.append('jmlObat',JSON.stringify(jmlObat));
    }
    if (edit == 'false') {
      $.ajax({
          url : "{!! route('storePrb') !!}",
          type: 'POST',
          dataType: 'json',
          data: data,
          async: true,
          cache: false,
          contentType: false,
          processData: false
      }).done(function(result) {
        if (result.metaData.code == 200) {
          arrbulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
          date = new Date();
          millisecond = date.getMilliseconds();
          detik = date.getSeconds();
          menit = date.getMinutes();
          jam = date.getHours();
          hari = date.getDay();
          tanggal = date.getDate();
          bulan = date.getMonth();
          tahun = date.getFullYear();

          var this_date = tanggal+"-"+arrbulan[bulan]+"-"+tahun+" "+jam+" : "+menit+" : "+detik;

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
              cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">SURAT RUJUK BALIK (PRB)</p></td>';
              cetak += '<td '+stTitle+' ><p style="margin:0px;font-size:17px;">No. SRB : '+result.response["noSRB"]+'</p></td>';
              cetak += '</tr>';
              cetak += '<tr>';
              cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">RS Dr. Wahidin Sudiro Husodo</p></td>';
              cetak += '<td '+stTitle+' ><p style="margin:0px;font-size:17px;">Tanggal: '+result.response["tglSRB"]+'</p></td>';
              cetak += '</tr>';
              cetak += '</table>';

              cetak += '<div style="margin-bottom: 5px;"></div>';
              cetak += '<table border="0" style="margin-left:10px;">';
              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Kepada Yth</p></td>';
              cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: </p></td>';
              cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
              cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
              cetak += '</tr>';


              cetak += '<tr>';
              cetak += '<td colspan="2" width="760px;"><p style="margin:0px;font-size:17px;">Mohon Pemeriksaan dan Penangan Lebih Lanjut:</p></td>';
              cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
              cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
              cetak += '</tr>';


              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  >&nbsp</td>';
              cetak += '<td '+widthIsi1+' '+stIsi+' >&nbsp</td>';

              cetak += '<td '+widthJd2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">=R/=</p></td>';
              cetak += '<td '+widthIsi2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;"></p></td>';
              cetak += '</tr>';


              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  >&nbsp</td>';
              cetak += '<td '+widthIsi1+' '+stIsi+' >&nbsp</td>';

              cetak += '<td colspan="2" width="760px;">';
              cetak += '<table border="0">';
              var no    = 0;
              if (result.response.obat.list != '') {
                $.each(result.response.obat.list, function(k,h){
                    var nomor = 1 + no++;
                  cetak += '<tr>';
                  cetak += '<td><p style="margin-left:5px;font-size:17px;">'+nomor+'</p></td>';
                  cetak += '<td><p style="margin-left:5px;font-size:17px;">'+h.signa+'</p></td>';
                  cetak += '<td><p style="margin-left:5px;font-size:17px;">'+h.nmObat+'</p></td>';
                  cetak += '<td><p style="margin-left:5px;font-size:17px;">'+h.jmlObat+'</p></td>';
                  cetak += '</tr>';
                });
              }
              cetak += '</table>';
              cetak += '</td>';
              cetak += '</tr>';



              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. Kartu</p></td>';
              cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.response.peserta["noKartu"]+'</p></td>';
              cetak += '</tr>';
              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Nama Peserta</p></td>';
              cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.response.peserta["nama"]+' &nbsp ('+result.response.peserta["kelamin"]+')</p></td>';
              cetak += '</tr>';
              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl.Lahir</p></td>';
              cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.response.peserta["tglLahir"]+'</p></td>';
              cetak += '</tr>';
              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Diagnosa</p></td>';
              cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: </p></td>';
              cetak += '</tr>';
              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">ProgramPRB</p></td>';
              cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.response.programPRB["nama"]+'</p></td>';
              cetak += '</tr>';
              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Keterangan</p></td>';
              cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.response["keterangan"]+'</p></td>'
              cetak += '</tr>';

              cetak += '<tr>';
              cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Saran</p></td>';
              cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.response["saran"]+'</p></td>'
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
              cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Tgl Cetak : '+this_date+' </p></td>';
              cetak += '<td align="right">.</td>';
              cetak += '</tr>';

              cetak += '</table>';
              cetak += '</div>';
              cetak += '<div style="page-break-after: always;"></div>';
          // }

          $('.printPrb').html(cetak);
          var printHtml = window.open('', 'PRINT', '');
          printHtml.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">');
          printHtml.document.write($('.printPrb').html());
          printHtml.document.write('</body></html>');
          printHtml.document.close();
          printHtml.focus();
          setTimeout(() => {
              printHtml.print();
              printHtml.close();
              $('.printPrb').html('');
              $('#form-prb').modal('hide');
          }, 500);
        }else if (result.metaData.code == 201) {
          swal('Whoops!',result.metaData.message,'warning');
        }else {
          swal('Whoops!',result.metaData.message,'error');
        }
      });
    }else {
      $.ajax({
          url : "{!! route('updatePrb') !!}",
          type: 'POST',
          dataType: 'json',
          data: data,
          async: true,
          cache: false,
          contentType: false,
          processData: false
      }).done(function(result) {
        if (result.metaData.code == 200) {
          swal('Berhasil!','Data Berhasil di Update','success');
          $('#panel-add').animateCss('bounceOutDown');
          $('.other-page').fadeOut(function(){
            $('.other-page').empty();
            $('.main-layer').fadeIn();
          });
          // arrbulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
          // date = new Date();
          // millisecond = date.getMilliseconds();
          // detik = date.getSeconds();
          // menit = date.getMinutes();
          // jam = date.getHours();
          // hari = date.getDay();
          // tanggal = date.getDate();
          // bulan = date.getMonth();
          // tahun = date.getFullYear();
          //
          // var this_date = tanggal+"-"+arrbulan[bulan]+"-"+tahun+" "+jam+" : "+menit+" : "+detik;
          //
          // var cetak = '';
          // var stTitle = 'height="10px" style="font-size:11px;"';
          // var stIsi = 'height="10px" style="font-size:11px;"';
          // var widthJd1 = 'width="190px;"';
          // var widthIsi1 = 'width="380px;"';
          // var widthJd2 = 'width="140px;"';
          // var widthIsi2 = 'width="280px;"';
          // var stBerkas = 'style="font-size:11px;padding-left: 50px;"';
          // var stPasKel = 'style="font-size: 10px;padding-left: 35px;height:10px;"';
          // var stKet = 'style="font-size: 9px;"';
          // var stCat2 = 'height="10px" style="font-size:10px;"';
          // // for (var p = 0; p < 3; p++) {
          //     // if (p == 2) {
          //         cetak += '<div style="border-right:dashed 1px #fff;padding:0px;margin:0px;width:100%;height:250px;">';
          //     // }else{
          //     //  cetak += '<div style="border-right:dashed 1px #777;padding:0px;margin:0px;width:650px;height:250px;">';
          //     // }
          //
          //     cetak += '<table border="0" style="margin-top:13px;margin-left:10px;">';
          //     cetak += '<tr>';
          //     cetak += '<td rowspan="2" width="400px">';
          //     var logoBpjs = "{!! url('AssetsAdmin/dist/img/logo-bpjs.png') !!}";
          //     cetak += '<img src="'+logoBpjs+'" width="300px" style="margin-left: 5px;">';
          //     cetak += '</td>';
          //     cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">SURAT RUJUK BALIK (PRB)</p></td>';
          //     cetak += '<td '+stTitle+' ><p style="margin:0px;font-size:17px;">No. SRB : '+result.response["noSRB"]+'</p></td>';
          //     cetak += '</tr>';
          //     cetak += '<tr>';
          //     cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">RS Dr. Wahidin Sudiro Husodo</p></td>';
          //     cetak += '<td '+stTitle+' ><p style="margin:0px;font-size:17px;">Tanggal: '+result.response["tglSRB"]+'</p></td>';
          //     cetak += '</tr>';
          //     cetak += '</table>';
          //
          //     cetak += '<div style="margin-bottom: 5px;"></div>';
          //     cetak += '<table border="0" style="margin-left:10px;">';
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Kepada Yth</p></td>';
          //     cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: </p></td>';
          //     cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
          //     cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
          //     cetak += '</tr>';
          //
          //
          //     cetak += '<tr>';
          //     cetak += '<td colspan="2" width="760px;"><p style="margin:0px;font-size:17px;">Mohon Pemeriksaan dan Penangan Lebih Lanjut:</p></td>';
          //     cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
          //     cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
          //     cetak += '</tr>';
          //
          //
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  >&nbsp</td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+' >&nbsp</td>';
          //
          //     cetak += '<td '+widthJd2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">=R/=</p></td>';
          //     cetak += '<td '+widthIsi2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;"></p></td>';
          //     cetak += '</tr>';
          //
          //
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  >&nbsp</td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+' >&nbsp</td>';
          //
          //     cetak += '<td colspan="2" width="760px;">';
          //     cetak += '<table border="0">';
          //     var no    = 0;
          //     if (result.response.obat.list != '') {
          //       $.each(result.response.obat.list, function(k,h){
          //           var nomor = 1 + no++;
          //         cetak += '<tr>';
          //         cetak += '<td><p style="margin-left:5px;font-size:17px;">'+nomor+'</p></td>';
          //         cetak += '<td><p style="margin-left:5px;font-size:17px;">'+h.signa+'</p></td>';
          //         cetak += '<td><p style="margin-left:5px;font-size:17px;">'+h.nmObat+'</p></td>';
          //         cetak += '<td><p style="margin-left:5px;font-size:17px;">'+h.jmlObat+'</p></td>';
          //         cetak += '</tr>';
          //       });
          //     }
          //     cetak += '</table>';
          //     cetak += '</td>';
          //     cetak += '</tr>';
          //
          //
          //
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. Kartu</p></td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.response.peserta["noKartu"]+'</p></td>';
          //     cetak += '</tr>';
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Nama Peserta</p></td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.response.peserta["nama"]+' &nbsp ('+result.response.peserta["kelamin"]+')</p></td>';
          //     cetak += '</tr>';
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl.Lahir</p></td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.response.peserta["tglLahir"]+'</p></td>';
          //     cetak += '</tr>';
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Diagnosa</p></td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: </p></td>';
          //     cetak += '</tr>';
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">ProgramPRB</p></td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.response.programPRB["nama"]+'</p></td>';
          //     cetak += '</tr>';
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Keterangan</p></td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.response["keterangan"]+'</p></td>'
          //     cetak += '</tr>';
          //
          //     cetak += '<tr>';
          //     cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Saran</p></td>';
          //     cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.response["saran"]+'</p></td>'
          //     cetak += '</tr>';
          //
          //     cetak += '<tr>';
          //     cetak += '<td colspan="3">&nbsp</td>';
          //     cetak += '<td '+stPasKel+'><p style="margin:0px;font-size:17px;">Mengetahui</p></td>';
          //     cetak += '</tr>';
          //     cetak += '<tr>';
          //     cetak += '<td colspan="3" '+stKet+' valign="bottom"><p style="margin:0px;font-size:17px;">Demikian atas bantuannya, diucapkan banyak terima kasih.</p></td>';
          //     cetak += '<td '+stPasKel+' valign="bottom" height="25px">_____________________</td>';
          //     cetak += '</tr>';
          //
          //     cetak += '<tr>';
          //     cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Tgl Cetak : '+this_date+' </p></td>';
          //     cetak += '<td align="right">.</td>';
          //     cetak += '</tr>';
          //
          //     cetak += '</table>';
          //     cetak += '</div>';
          //     cetak += '<div style="page-break-after: always;"></div>';
          // // }
          //
          // $('.printPrb').html(cetak);
          // var printHtml = window.open('', 'PRINT', '');
          // printHtml.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">');
          // printHtml.document.write($('.printPrb').html());
          // printHtml.document.write('</body></html>');
          // printHtml.document.close();
          // printHtml.focus();
          // setTimeout(() => {
          //     printHtml.print();
          //     printHtml.close();
          //     $('.printPrb').html('');
          //     $('#form-prb').modal('hide');
          // }, 500);
        }else if (result.metaData.code == 201) {
          swal('Whoops!',result.metaData.message,'warning');
        }else {
          swal('Whoops!',result.metaData.message,'error');
        }
      });
    }
});

$('#btnHapus').click(function(){
  var id = $('#id').val();
  var noSRB = $('#noSRB').val();
  var noSep = $('#noSep').val();
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
      $.post("{!! route('hapus_rujukan') !!}", {noSRB:noSRB, noSep:noSep, id:id}).done(function(data){
        if(result.metaData.code == 200){
          swal("Success!", "Berhasil di Hapus", "success");
        }else if (result.metaData.code == 201) {
          swal('Whoops!',result.metaData.message,'warning');
        }else{
          swal('Whoops!',result.metaData.message,'error');
        }
      });
    }
  )
});

$('#btnCetak').click(function(){
  var id = $('#id').val();
  var noSRB = $('#noSRB').val();
  var noSep = $('#noSep').val();

  var data = new FormData();
  data.append('id',id);
  data.append('noSRB',noSRB);
  data.append('noSep',noSep);

  $.ajax({
      url : "{!! route('cetakPrb') !!}",
      type: 'POST',
      dataType: 'json',
      data: data,
      async: true,
      cache: false,
      contentType: false,
      processData: false
  }).done(function(result) {
    if (result.dataprb.metaData.code == 200) {
      arrbulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
      date = new Date();
      millisecond = date.getMilliseconds();
      detik = date.getSeconds();
      menit = date.getMinutes();
      jam = date.getHours();
      hari = date.getDay();
      tanggal = date.getDate();
      bulan = date.getMonth();
      tahun = date.getFullYear();

      var this_date = tanggal+"-"+arrbulan[bulan]+"-"+tahun+" "+jam+" : "+menit+" : "+detik;

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
          cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">SURAT RUJUK BALIK (PRB)</p></td>';
          cetak += '<td '+stTitle+' ><p style="margin:0px;font-size:17px;">No. SRB : '+result.dataprb.response.prb["noSRB"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+stTitle+' colspan="2"><p style="margin:0px;font-size:20px;">RSUD Dr. Wahidin Sudiro Husodo</p></td>';
          cetak += '<td '+stTitle+' ><p style="margin:0px;font-size:17px;">Tanggal: '+result.dataprb.response.prb["tglSRB"]+'</p></td>';
          cetak += '</tr>';
          cetak += '</table>';

          cetak += '<div style="margin-bottom: 5px;"></div>';
          cetak += '<table border="0" style="margin-left:10px;">';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Kepada Yth</p></td>';
          cetak += '<td '+widthIsi1+' ><p style="margin:0px;font-size:17px;">: </p></td>';
          cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
          cetak += '</tr>';


          cetak += '<tr>';
          cetak += '<td colspan="2" width="760px;"><p style="margin:0px;font-size:17px;">Mohon Pemeriksaan dan Penangan Lebih Lanjut:</p></td>';
          cetak += '<td '+widthJd2+' '+stIsi+'>&nbsp</td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'>&nbsp</td>';
          cetak += '</tr>';


          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  >&nbsp</td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' >&nbsp</td>';

          cetak += '<td '+widthJd2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">R/.</p></td>';
          cetak += '<td '+widthIsi2+' '+stIsi+'  ><p style="margin:0px;font-size:17px;"></p></td>';
          cetak += '</tr>';


          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  >&nbsp</td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' >&nbsp</td>';

          cetak += '<td colspan="2" width="760px;">';
          cetak += '<table border="0">';
          var no    = 0;
          if (result.obat_prb != '') {
            $.each(result.obat_prb, function(k,h){
                var nomor = 1 + no++;
              cetak += '<tr>';
              cetak += '<td><p style="margin-left:5px;font-size:17px;">'+nomor+'</p></td>';
              cetak += '<td><p style="margin-left:5px;font-size:17px;">'+h.signa1+'x'+h.signa2+'</p></td>';
              cetak += '<td><p style="margin-left:5px;font-size:17px;">'+h.nmObat+'</p></td>';
              cetak += '<td><p style="margin-left:15px;font-size:17px;">'+h.jmlObat+'</p></td>';
              cetak += '</tr>';
            });
          }
          cetak += '</table>';
          cetak += '</td>';
          cetak += '</tr>';



          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">No. Kartu</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.dataprb.response.prb.peserta["noKartu"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Nama Peserta</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.dataprb.response.prb.peserta["nama"]+' &nbsp ('+result.dataprb.response.prb.peserta["kelamin"]+')</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Tgl.Lahir</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+' ><p style="margin:0px;font-size:17px;">: '+result.dataprb.response.prb.peserta["tglLahir"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Diagnosa</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: </p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">ProgramPRB</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.dataprb.response.prb.programPRB["nama"]+'</p></td>';
          cetak += '</tr>';
          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Keterangan</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.dataprb.response.prb["keterangan"]+'</p></td>'
          cetak += '</tr>';

          cetak += '<tr>';
          cetak += '<td '+widthJd1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">Saran</p></td>';
          cetak += '<td '+widthIsi1+' '+stIsi+'  ><p style="margin:0px;font-size:17px;">: '+result.dataprb.response.prb["saran"]+'</p></td>'
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
          cetak += '<td colspan="3" '+stKet+' valign="top"><p style="margin:0px;font-size:17px;">Tgl Cetak : '+this_date+' </p></td>';
          cetak += '<td align="right">.</td>';
          cetak += '</tr>';

          cetak += '</table>';
          cetak += '</div>';
          cetak += '<div style="page-break-after: always;"></div>';
      // }

      $('.printPrb').html(cetak);
      var printHtml = window.open('', 'PRINT', '');
      printHtml.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">');
      printHtml.document.write($('.printPrb').html());
      printHtml.document.write('</body></html>');
      printHtml.document.close();
      printHtml.focus();
      setTimeout(() => {
          printHtml.print();
          printHtml.close();
          $('.printPrb').html('');
          $('#form-prb').modal('hide');
      }, 500);
    }else {
      swal('Whoops!',result.metaData.message,'error');
    }
  });
});

</script>
