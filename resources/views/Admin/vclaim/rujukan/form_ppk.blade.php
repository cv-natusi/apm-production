<style media="screen">
  #select2-my_select-results .select2-results__option {
    white-space: pre;
  }
</style>
<div class="box box-primary form-ppkrujukan">
  <div class="box-header with-border">
    <i class="fa fa-battery-half"></i>
    <small class="pull-right">
      <label style="font-size:medium" id="lblnorujukan">PPK RUJUKAN</label>
    </small>
  </div>
  <div class="box-body">
    <form class="form-horizontal" id="formRujuk">
      <div class="form-group">
        <label class="col-md-3 col-sm-3 col-xs-12 control-label">PPK Rujuk</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <select class="form-control ppkrujuk_id" id="ppkrujuk_id" name="ppkrujuk_id">
            <option>::. Pilih PPK Rujuk .::</option>
          </select>
          {{-- <input type="text" class="form-control" min="3" placeholder="ketik kode atau nama ppk minimal 3 karakter" name="ppkrujuk_id" id="ppkrujuk_id" > --}}
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl.Rencana Rujukan</label>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <input type="date" class="form-control" name="tgl_rencana_rujukan" id="tgl_rencana_rujukan" placeholder="yyyy-MM-dd" value="{!! date('Y-m-d') !!}" maxlength="10">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-6">
          <button type="button" class="btn btn-primary" id="btnCariJadwal" style="margin-left: 103px;margin-bottom: 20px;" name="button"><i class="fa fa-search"> Cari</i></button>
        </div>
      </div>
    </form>
    <!-- obat -->
  </div>

  <div class="box-footer">
    <div class="form-group">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <button id="btnBatal" type="button" class="btn btn-default pull-right btn-cancel"><i class="fa fa-undo"></i> Batal</button>
      </div>
    </div>
  </div>
</div>
<div class="row" id="divrowPraktek">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <div class="form-group">
          <div class="col-md-5 col-sm-5 col-xs-12">
            <button class="btn bg-maroon" id="btnPoli" type="button" style="visibility: visible;"> <i class="fa fa-user-md"></i> Spesialis/SubSpesialis</button>
            <button class="btn bg-purple" id="btnSarana" type="button" style="visibility: visible;"> <i class="fa fa-medkit"></i> Sarana</button>
          </div>
        </div>
      </div>
      <div class="box-body">
        <div id="divSpesialis">
          <div class="alert alert-info alert-dismissible">
            <p>
              1. Untuk Melihat Jadwal Praktek Dokter klik Nama Spesialis/SubSpesialis<br>
              2. Jumlah Rujukan Merupakan Penjumlahan dari Rujukan Dari FKTP dan Rujukan Antar RS<br>
              3. Klik sarana untuk memastikan kelengkapan sarana prasarana pada FKRTL tujuan rujukan
            </p>
          </div>
            <div class="row">
              <div class="col-sm-12">
                <table class="table table-bordered table-striped" id="tblSpesialis">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Spesialis/Sub</th>
                      <th>Kapasitas</th>
                      <th>Jml.Rujukan</th>
                      <th>Prosentase</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>-</td>
                      <td>-</td>
                      <td>Mohon Tunggu Sebentar !</td>
                      <td>-</td>
                      <td>-</td>
                      <td>
                        <div class="btn-group">
                          <button type="button" class="btn btn-success btnpilihpoli btn-xs"><i class="fa fa-check"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
        <div id="divSarana" style="display: none;">
            <div class="row">
              <div class="col-sm-12">
                <table class="table table-bordered table-striped" width="100%" id="tblSarana">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Kode Sarana</th>
                      <th>Nama Sarana</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>Sarana</td>
                      <td>Sarana</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('#divrowPraktek').hide();
    $('#tblSpesialis').DataTable();
    $('#tblSarana').DataTable();
  });

  $('.btn-cancel').click(function(e) {
    e.preventDefault();
    $('.form-ppkrujukan').animateCss('bounceOutDown');
    $('.ppkRujukan').fadeOut(function() {
      $('.ppkRujukan').empty();
      $('.form-rujukan').fadeIn();
    });
  });

  $('#btnPoli').click(function() {
    $('#divSpesialis').show();
    $('#divSarana').hide();
  });
  $('#btnSarana').click(function() {
    $('#divSarana').show();
    $('#divSpesialis').hide();
  });

  // TOMBOL CARI JADWAL DOKTER
  $('#btnCariJadwal').click(function(e){
    e.preventDefault();
    var ppk_id = $('#ppkrujuk_id').val();
    var tgl_rencana_rujukan = $('#tgl_rencana_rujukan').val();
    var data = new FormData();
    data.append('ppkrujuk_id',ppk_id);
    data.append('tgl_rencana_rujukan',tgl_rencana_rujukan);

    $.ajax({
        url : "{!! route('cek_list_spesialistik_rujukan') !!}",
        type: 'POST',
        dataType: 'json',
        data: data,
        async: true,
        cache: false,
        contentType: false,
        processData: false
    }).done(function(result) {
      // console.log(result);
        if (result != null) {
            var tr = ``;
            var no = 0;
            $('#tblSpesialis').DataTable().destroy();
            $('#tblSpesialis tbody').html('');

            result.list.forEach(element => {
                tr += `<tr>`;

                no++;
                tr += `<td>`;
                tr += `${no}`;
                tr += `</td>`;

                tr += `<td>`;
                tr += `<a href="javascript:void(0)" class="btn btn-default btn-xs rounded-0">${element.kodeSpesialis} - ${element.namaSpesialis}</a>`;
                tr += `</td>`;

                tr += `<td>`;
                tr += `${element.kapasitas}`;
                tr += `</td>`;

                tr += `<td>`;
                tr += `${element.jumlahRujukan}`;
                tr += `</td>`;

                tr += `<td>`;
                tr += `${element.persentase}`;
                tr += `</td>`;

                tr += `<td>`;
                tr += `<div class="btn-group"> <button type="button" class="btn btn-success btnpilihpoli btn-xs" onclick="btnpilihpoli('${element.kodeSpesialis}')"><i class="fa fa-check"></i></button></div>`;
                tr += `</td>`;

                tr += `</tr>`;
            });
            // console.log(tr);
            $("#tblSpesialis tbody").html(tr);
            $('#tblSpesialis').DataTable();

        }
    });
    $.ajax({
        url : "{!! route('cek_list_sarana_rujukan') !!}",
        type: 'POST',
        dataType: 'json',
        data: data,
        async: true,
        cache: false,
        contentType: false,
        processData: false
    }).done(function(result) {
      // console.log(result);
        if (result != null) {
            var tr = ``;
            var no = 0;
            $('#tblSarana').DataTable().destroy();
            $('#tblSarana tbody').html('');

            result.list.forEach(element => {
                tr += `<tr>`;

                no++;
                tr += `<td>`;
                tr += `${no}`;
                tr += `</td>`;

                tr += `<td>`;
                tr += `<a href="javascript:void(0)" class="btn btn-info btn-xs rounded-0">${element.kodeSarana}</a>`;
                tr += `</td>`;

                tr += `<td>`;
                tr += `${element.namaSarana}`;
                tr += `</td>`;
                tr += `</tr>`;
            });
            // console.log(tr);
            $("#tblSarana tbody").html(tr);
            $('#tblSarana').DataTable();

        }
    });
    $('#divrowPraktek').show();
  });

  $("#ppkrujuk_id").select2({
    minimumInputLength: 2,
    width: '100%',
    tags: [],
    placeholder: 'Cari...',
    ajax: {
      url: "{!! route('cek_ppk_rujukan') !!}",
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: $.map(data.faskes, function(item) {
            return {
              text: item.kode + " - " + item.nama,
              id: item.kode
            }
          })
        };
      },
      cache: true
    }
  });

  function btnpilihpoli(subspesialis) {
    console.log(subspesialis);
    var ppk_rujuk = $('#ppkrujuk_id').val();
    var tgl_rencana_rujukan = $('#tgl_rencana_rujukan').val();
    var nama_ppk = $('#ppkrujuk_id option:selected').text();
    $('#txtnmpoli').val(subspesialis);
    $('#txtkdpoli').val(subspesialis);
    $('#txtkdppkdirujuk').val(ppk_rujuk);
    $('#txtrencanatglrujukan').val(tgl_rencana_rujukan);
    $('#txtnmppkdirujuk').val(nama_ppk);
    $('.form-ppkrujukan').animateCss('bounceOutDown');
    $('.ppkRujukan').fadeOut(function() {
      $('.ppkRujukan').empty();
      $('.form-rujukan').fadeIn();
    });
  }
</script>
