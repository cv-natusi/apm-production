@extends('registration.layoutNew')

@section('content-antrian-registration')
    <div class="col-lg-12 col-md-12 panelContentRegistration">
        <div class="col-lg-12 col-md-12 panel-antrian">
            <div class="border-cari">
                <div class="col-md-6">
                    <h3 class="" style="color: #000; font-size: 12pt;"><b>CARI DATA PASIEN</b></h3><br>

                    <label><b style="color: #000; margin-left: 0px; font-size: 8pt;">Pilih Pencarian</b></label>
                    <div class="">
                        <input type="hidden" name="id_antrian" class="form-control" value="{{isset($antrian) ? $antrian->id : ''}}" autocomplete="off">
                        <label style="color: #000; font-size: 8pt;"><input type="radio" name="data" id="rm">&nbsp; No. RM</label><br>
                        <label style="color: #000; font-size: 8pt;"><input type="radio" name="data" id="bpjs">&nbsp; No. BPJS</label><br>
                        <label style="color: #000; font-size: 8pt;"><input type="radio" name="data" id="nik">&nbsp; NIK &nbsp;</label>
                    </div>
                    <div class="col-md-10" style="position: absolute;">
                        <input type="text" name="dtpasien" id="dtpasien" onkeyup="search()" class="form-control" value="{{isset($antrian) ? $antrian->no_rm : ''}}">
                    </div>
                    <a href="javascript:void(0);" onclick="selanjutnya()" class="col-xs-12 btn-cari">
                        <label style="color: #FFFFFF; font-family: Arial;  font-size: 10pt; font-style: normal; margin-top: 10px; margin-left: 70px;">Selanjutnya</label>
                    </a>
                </div>
                <div class="col-md-6">
                    <div id="tempat_pasien" style="margin-top: 15px;"></div>
                </div>
			</div>
        </div>
    </div>    
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
        $(document).ready(function () {
            $('.btn-cari').hide();    
        });

        // Start Cari
        function search() {

            if ($("#rm").is(":checked")) { 
                var cari = $('input[name=dtpasien]').val();
            } else if ($("#bpjs").is(":checked")) {
                var cari = $('input[name=dtpasien]').val();
            } else if ($("#nik").is(":checked")) {
                var cari = $('input[name=dtpasien]').val();
            } else {
                var cari = console.log('Nothing');
            }

            if(cari==''){
                $('#tempat_pasien').html('');
                return;
            }

            if (!cari) { 
              swal('Whoops','Silahkan Memilih Kategori Pencarian Terlebih Dahulu','warning')
            } else {
                
                if($("#rm").is(':checked',true)){
                    $.post("{{route('cari')}}",{no_rm:cari},function(data){
                        var html = '';
                        if(data.antrian.length!=0){
                            $.each(data.antrian,function(k,v){
                                $('.btn-cari').show();

                                if (v.no_rm) {
                                    html+='<a href="javascript:void(0)" style="font-size: 10pt; color: #000; background-color: #7276ea;" onclick="pilih(\''+v.id+'\',\''+v.NamaCust+'\')">Nama: '+v.NamaCust+'<br>No. RM: '+v.no_rm+'</a><br><br>';
                                } else {
                                    html+='<p>Data Tidak Ditemukan</p>';
                                }
                            });
                        }

                        $('#tempat_pasien').html(html);
                    });
                }else if($("#bpjs").is(':checked',true)){
                    $.post("{{route('cari')}}",{bpjs:cari},function(data){
                        var html = '';
                        if(data.antrian.length!=0){
                            $.each(data.antrian,function(k,v){
                                $('.btn-cari').show();

                                if (v.nomor_kartu) {
                                    html+='<a href="javascript:void(0)" style="font-size: 10pt; color: #000; background-color: #7276ea;" onclick="pilih(\''+v.id+'\',\''+v.NamaCust+'\')">Nama: '+v.NamaCust+'<br>No. BPJS: '+v.nomor_kartu+'</a><br><br>';
                                } else {
                                    html+='<p>Data Tidak Ditemukan</p>';
                                }
                            });
                        }

                        $('#tempat_pasien').html(html);
                    });
                } else if($("#nik").is(':checked',true)) {
                    $.post("{{route('cari')}}",{nik:cari},function(data){
                        var html = '';
                        if(data.antrian.length!=0){
                            $.each(data.antrian,function(k,v){
                                $('.btn-cari').show();

                                if (v.nik) {
                                    html+='<a href="javascript:void(0)" style="font-size: 10pt; color: #000; background-color: #7276ea;" onclick="pilih(\''+v.id+'\',\''+v.NamaCust+'\')">Nama: '+v.NamaCust+'<br>NIK: '+v.nik+'</a><br><br>';
                                } else {
                                    html+='<p>Data Tidak Ditemukan</p>';
                                }
                            });
                        }

                        $('#tempat_pasien').html(html);
                    });
                } else {
                    swal('Whoops','Maaf, Data Tidak Di Temukan','warning')
                }

            }

            
        }
        // End cari

        // Start Pilih
        function pilih(id,nama){
            var id_antrian = $('input[name=id_antrian]').val();
            $('#tempat_pasien').html('');
            $('input[name=dtpasien]').val(nama);

            $.post("{{route('pilih-pasien')}}",{id:id, id_antrian:id_antrian},function(data){
                // selanjutnya(id)
            });
        }
        // End Cari

        function selanjutnya(id, id_antrian) {
            var id_antrian = $('input[name=id_antrian]').val();
            // console.log(id);
            // console.log(id_antrian);

            $.post("{{route('pilih-pasien-save')}}",{id:id, id_antrian:id_antrian},function(data){
				if(data.code=='200'){
					// var id = data.data.id;

					// window.location.href = '{{ route("data-pasien") }}?id='+id;
				}else{
					swal('Whooops',data.message,'error');
				}
			});
        }
	</script>
@stop