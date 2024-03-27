<style type="text/css">
	.loader {
		border: 7px solid #f3f3f3;
		border-radius: 50%;
		border-top: 7px solid #3498db;
		width: 20px;
		height: 20px;
		-webkit-animation: spin 2s linear infinite; /* Safari */
		animation: spin 1s linear infinite;
	}

	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
</style>

<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
    <div class="box box-default edit-form">
        <div class="panel-body">
            <form class="formEdit" style="padding-top: 10px; padding-left: 20px;">
                <h5>@if($data->view == 0) EDIT @endif DATA PROFIL PASIEN</h5>
                <hr>
                <input hidden name="cust_id" type="text" value="{{$data->cust_id}}">
                <div class="row">
                    <div class="col-md-4">
                        <label>Nomor RM</label>
                        <input type="text" class="form-control" name="no_rm" id="no_rm" value="{{$data->KodeCust}}" readonly>
                    </div>
                    <div class="col-md-8">
                        <label>Nama Pasien <span style="color:red">*)</span> </label>
                        <input type="text" class="form-control" name="nama_pasien" id="nama_pasien" value="{{$data->NamaCust}}" @if($data->view == 1) readonly @endif>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>No KTP  <span style="color:red">*)</span> </label>
                        <input type="number" class="form-control" name="no_ktp" id="no_ktp" value="{{$data->NoKtp}}" @if($data->view == 1) readonly @endif>
                    </div>
                    <div class="col-md-4">
                        <label>Tempat Lahir <span style="color:red">*)</span> </label>
                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="{{$data->Tempat}}" @if($data->view == 1) readonly @endif>
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal Lahir <span style="color:red">*)</span> </label>
                        <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" value="{{substr($data->TglLahir,0,10)}}" @if($data->view == 1) readonly @endif>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Golongan Darah <span style="color:red">*)</span> </label>
                        <select name="gol_darah" id="gol_darah" class="form-control" @if($data->view == 1) disabled @endif>
                            <option @if($data->goldarah == "")selected @endif value="" readonly="">..:: Pilih Gol Darah ::..</option>
                            <option @if($data->goldarah == "A")selected @endif value="A">A</option>
                            <option @if($data->goldarah == "B")selected @endif value="B">B</option>
                            <option @if($data->goldarah == "AB")selected @endif value="AB">AB</option>
                            <option @if($data->goldarah == "O")selected @endif value="O">O</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Jenis Kelamin <span style="color:red">*)</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <label><input type="radio" name="jenis_kelamin" id="jenis_kelamin_P" value="P" @if($data->JenisKel == "P")checked @endif @if($data->view == 1) disabled @endif >&nbsp; Perempuan </label>
                            </div>
                            <div class="col-md-6">
                                <label><input type="radio" name="jenis_kelamin" id="jenis_kelamin_L" value="L" @if($data->JenisKel == "L")checked @endif @if($data->view == 1) disabled @endif >&nbsp; Laki - Laki </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Kewarganegaraan <span style="color:red">*)</span> </label>
                        <div class="row">
                            <div class="col-md-6">
                                <label><input type="radio" name="kewarganegaraan" id="wni"  value="WNI" @if($data->warganegara == "WNI")checked @endif @if($data->view == 1) disabled @endif >&nbsp; WNI </label>
                            </div>
                            <div class="col-md-6">
                                <label><input type="radio" name="kewarganegaraan" id="wna"  value="WNA" @if($data->warganegara == "WNA")checked @endif @if($data->view == 1) disabled @endif >&nbsp; WNA </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Status Perkawinan <span style="color:red">*)</span> </label>
                        <select name="s_perkawinan" id="s_perkawinan" class="form-control" @if($data->view == 1) disabled @endif>
                            <option @if($data->status == "")selected @endif value="" readonly="">..:: Pilih Status Perkawinan ::..</option>
                            <option @if($data->status == "Belum Menikah")selected @endif value="Belum Menikah">Belum Menikah</option>
                            <option @if($data->status == "Cerai")selected @endif value="Cerai"> Cerai </option>
                            <option @if($data->status == "Menikah")selected @endif value="Menikah">Sudah Menikah</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Pekerjaan <span style="color:red">*)</span></label>
                        <input type="text" name="pekerjaan" id="pekerjaan" class="form-control" value="{{$data->Pekerjaan}}" @if($data->view == 1) readonly @endif>
                    </div>
                    <div class="col-md-4">
                        <label>Suku / Ras <span style="color:red">*)</span></label>
                        <input type="text" name="sukuras" id="sukuras" class="form-control" value="{{$data->suku}}" @if($data->view == 1) readonly @endif>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Asuransi <span style="color:red">*)</span></label>
                        <select name="asuransi" id="jenis_pasien" class="form-control" @if($data->view == 1) disabled @endif>
                            <option @if($data->asuransi == "")selected @endif value="">.:: Asuransi ::.</option>
                            <option @if($data->asuransi == "UMUM")selected @endif value="UMUM">UMUM</option>
                            <option @if($data->asuransi == "BPJS")selected @endif value="BPJS">BPJS</option>
                            <option @if($data->asuransi == "ASURANSILAIN")selected @endif value="ASURANSILAIN">ASURANSI LAINNYA</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>No. Asuransi <span style="color:red">*)</span></label>
                        <input type="text" name="noasuransi" id="noasuransi" class="form-control" value="{{$data->FieldCust1}}" @if($data->view == 1) readonly @endif>
                    </div>
                    <div class="col-md-4">
                        <label>Agama <span style="color:red">*)</span></label>
                        <select name="agama"  id="agama" class="form-control" @if($data->view == 1) disabled @endif>
                            <option @if($data->Agama == "") selected @endif value="" readonly="">..:: Pilih Agama ::..</option>
                            <option @if($data->Agama == "Islam") selected @endif value="Islam">Islam</option>
                            <option @if($data->Agama == "Protestan") selected @endif value="Protestan">Protestan</option>
                            <option @if($data->Agama == "Katolik") selected @endif value="Katolik">Katolik</option>
                            <option @if($data->Agama == "Hindu") selected @endif value="Hindu">Hindu</option>
                            <option @if($data->Agama == "Buddha") selected @endif value="Buddha">Buddha</option>
                            <option @if($data->Agama == "Khonghucu") selected @endif value="Khonghucu">Khonghucu</option>
                        </select>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-4">
                        <label>Pendidikan Terakhir <span style="color:red">*)</span></label>
                        <select name="pend_terakhir" id="pend_terakhir" class="form-control" @if($data->view == 1) disabled @endif>
                            <option @if($data->pend_terakhir == "") selected @endif value="" readonly="">..:: Pendidikan Terakhir ::..</option>
                            <option @if($data->pend_terakhir == "SD") selected @endif value="SD">SD</option>
                            <option @if($data->pend_terakhir == "SMP") selected @endif value="SMP">SMP</option>
                            <option @if($data->pend_terakhir == "SMA / SMK") selected @endif value="SMA / SMK">SMA / SMK</option>
                            <option @if($data->pend_terakhir == "Diploma") selected @endif value="Diploma">Diploma</option>
                            <option @if($data->pend_terakhir == "S1") selected @endif value="S1">S1</option>
                            <option @if($data->pend_terakhir == "S2") selected @endif value="S2">S2</option>
                            <option @if($data->pend_terakhir == "S3") selected @endif value="S3">S3</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="text" name="email" id="email" class="form-control" value="{{$data->email}}" @if($data->view == 1) readonly @endif>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12">
                        <label>Alamat <span style="color:red">*)</span></label>
                        <input type="text" class="form-control" name="alamat" id="alamat" value="{{$data->Alamat}}" @if($data->view == 1) readonly @endif>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label>RT <span style="color:red">*)</span></label>
                        <input type="number" class="form-control" name="rt" id="rt" value="{{$data->rt}}" @if($data->view == 1) readonly @endif>
                    </div>
                    <div class="col-md-3">
                        <label>RW <span style="color:red">*)</span></label>
                        <input type="number" class="form-control" name="rw" id="rw" value="{{$data->rw}}" @if($data->view == 1) readonly @endif>
                    </div>
                    <div class="col-md-3">
                        <label>Provinsi <span style="color:red">*)</span></label>
                        <select name="provinsi_id" class="form-control select2" id="provinsi" @if($data->view == 1) disabled @endif>
                            <option value="" readonly="">..::Pilih Provinsi ::..</option>
                            @foreach ($provinsi as $row)
                                <option @if($data->namaProv == $row->name) selected @endif value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>	
                    </div>
                    <div class="col-md-3">
                        <label>Kab/Kota <span style="color:red">*)</span></label>
                        <select name="kabupaten_id" class="form-control select2" id="kabupaten" @if($data->view == 1) disabled @endif>
                            <option @if($data->namaKab == $row->name) selected @endif value="" readonly="">..:: Pilih Kab/Kota ::..</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Kecamatan <span style="color:red">*)</span></label>
                        <select name="kecamatan_id" class="form-control select2" id="kecamatan" @if($data->view == 1) disabled @endif>
                            <option @if($data->namaKec == $row->name) selected @endif value="" readonly="">..:: Pilih Kecamatan ::..</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Desa / Kelurahan <span style="color:red">*)</span></label>
                        <select name="desa_id" class="form-control select2" id="desa" @if($data->view == 1) disabled @endif>
                            <option @if($data->namaKel == $row->name) selected @endif value="" readonly="">..:: Pilih Desa / Kelurahan ::..</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Kode POS <span style="color:red">*)</span></label>
                        <input type="text" class="form-control" name="kodepos" id="kodepos" value="{{$data->kodepos}}" @if($data->view == 1) readonly @endif>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Telepon <span style="color:red">*)</span></label>
                        <input type="number" class="form-control" name="telepon" id="telepon" value="{{$data->Telp}}" @if($data->view == 1) readonly @endif>
                    </div>
                    <div class="col-md-4">
                        <label>Fax</label>
                        <input type="text" class="form-control" name="fax" id="fax" value="{{$data->Fax}}" @if($data->view == 1) readonly @endif>
                    </div>
                    <div class="col-md-4">
                        <label>Foto Pasien</label>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    @if(!empty($data->fotoCust))
                                    <img src="{{ asset('aset/images/fotopasien/' . $data->fotoCust) }}" width="50px" height="50px" alt="{{$data->fotoCust}}">
                                    @else
                                    <img src="{{ asset('aset/images/fotopasien/default.jpg') }}" width="50px" height="50px" alt="{{$data->fotoCust}}">
                                    @endif
                                </div>
                                @if($data->view == 0)
                                <div class="col-md-10">
                                    <input type="file" name="foto_pasien" class="form-control" id="foto_pasien" value="">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top: 1rem;">
                    @if($data->view == 0)
                    <div class="col-md-2 text-center">
                        <button type="button" class="btn btn-success btn-update" style="width: 100%;">SIMPAN PERUBAHAN</button>
                    </div>
                    @endif
                    <div class="col-md-2 text-center">
                        <button type="button" class="btn btn-secondary btn-cancel" style="width: 100%;">KEMBALI</button>
                    </div>
                    {{-- @if($data->view == 0)
                    <div class="col-md-2 text-center">
                        <button type="button" class="btn btn-danger btn-cetak-rm" onclick="cetakrm()" style="width: 100%">CETAK NO RM</button>
                    </div>
                    @endif --}}
                </div>

                <div class='clearfix' style="margin-bottom: 5px"></div>
            </form>
        </div>
    </div>
</div>
	
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

{{-- <script src="https://code.highcharts.com/highcharts.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.2/js/bootstrap.min.js" integrity="sha512-5BqtYqlWfJemW5+v+TZUs22uigI8tXeVah5S/1Z6qBLVO7gakAOtkOzUtgq6dsIo5c0NJdmGPs0H9I+2OHUHVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}} --}}
<script type="text/javascript">
    $(document).ready(function () {
        daerah();
        $(".btn-cancel").click(function (e) { 
            e.preventDefault();
            $(".main-layer").show();
            $(".edit-form").hide();
        });

        $('#provinsi').change(function(){
            var id = $('#provinsi').val();
            $.post("{{route('get_kabupaten')}}",{id:id},function(data){
                var kabupaten = '<option value="">..:: Pilih Kabupaten ::..</option>';
                if(data.status=='success'){
                    if(data.data.length>0){
                        $.each(data.data,function(v,k){
                            kabupaten+='<option value="'+k.id+'">'+k.name+'</option>';
                        });
                    }
                }
                $('#kabupaten').html(kabupaten);
            });
        });

        $('#kabupaten').change(function(){
            var id = $('#kabupaten').val();
            $.post("{{route('get_kecamatan')}}",{id:id},function(data){
                var kecamatan = '<option value="">..:: Pilih Kecamatan ::..</option>';
                if(data.status=='success'){
                    if(data.data.length>0){
                        $.each(data.data,function(v,k){
                            kecamatan+='<option value="'+k.id+'">'+k.name+'</option>';
                        });
                    }
                }
                $('#kecamatan').html(kecamatan);
            });
        });

		$('#kecamatan').change(function(){
            var id = $('#kecamatan').val();
            $.post("{{route('get_desa')}}",{id:id},function(data){
                var desa = '<option value="">..:: Pilih Desa ::..</option>';
                if(data.status=='success'){
                    if(data.data.length>0){
                        $.each(data.data,function(v,k){
                            desa+='<option value="'+k.id+'">'+k.name+'</option>';
                        });
                    }
                }
                $('#desa').html(desa);
            });
        });
        
        $(".btn-update").click(function (e) { 
			e.preventDefault();
			console.log("clicked");
			//validasi
			let valid = validasiFormEdit();
			//create data
			if(!valid){
                let data = new FormData();
                let fotoValid = "{{ $data->fotoCust }}";
				//Add Foto To FormData
                var fotoPasien = $('input[name=foto_pasien]')[0].files;
                if(fotoPasien.length > 0){
                    for (var i = 0; i < fotoPasien.length; i++) {
                        data.append("foto_pasien", fotoPasien[i]);
                    }
                }else{
                    console.log("masuk sini");
                    if(fotoValid != null && fotoValid != ""){
                        console.log("sini lagi");
                        data.append("foto_pasien", fotoValid);
                    }
                }
                console.log(fotoValid);
				//Add DataSerialize to FormData
				let dataForm = $(".formEdit").serializeArray();
                $.each(dataForm, function( key, value ) {
					data.append(value.name, value.value);
				});
				$.ajax({
                    type: "POST",
                    url: "{{ route('simpanProfilPasien') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(data, textStatus, jqXHR) {
                        if(data.status == "success"){
                            swal('Berhasil',data.message,data.status);
                            // $(".main-layer").show();
                            // $(".edit-form").hide();
                            window.location.reload();
                        }else{
                            swal('Whooops',data.message,data.status);
                        }
                    },
                    error: function(data, textStatus, jqXHR) {
						console.log("fail");
						swal('Whooops',data.message,data.status);
					},
                });

				// let data = $(".formEdit").serialize();
				// console.log(data)
				// $.post("{{ route('simpanProfilPasien') }}",data)
				// 	.done(function(data){
                //         if(data.status == "success"){
                //             swal('Whooops',data.message,data.status);
                //             $(".main-layer").show();
                //             $(".edit-form").hide();
                //         }else{
                //             swal('Whooops',data.message,data.status);
                //         }
				// 	})
				// 	.fail(function(){
				// 		console.log("fail");
                //         swal('Whooops',data.message,data.status);
				// 	});
			}
		});
    });

    function daerah(){
        let selectedProv = $("#provinsi").val();; 
        let selectedKab = "{{ $data->Kota }}";
        if(selectedKab != "" && selectedKab != null){
            $.post("{{route('get_kabupaten')}}",{id:selectedProv},function(data){
                var kabupaten = '<option value="">..:: Pilih Kabupaten ::..</option>';
                if(data.status=='success'){
                    if(data.data.length>0){
                        $.each(data.data,function(v,k){
                            kabupaten+='<option value="'+k.id+'">'+k.name+'</option>';
                        });
                    }
                }
                $('#kabupaten').html(kabupaten);
                $("#kabupaten").val(selectedKab);
            });
        }

        let selectedKec = "{{ $data->kecamatan }}";
        if(selectedKec != "" && selectedKec != null){
            $.post("{{route('get_kecamatan')}}",{id:selectedKab},function(data){
                var kecamatan = '<option value="">..:: Pilih Kecamatan ::..</option>';
                if(data.status=='success'){
                    if(data.data.length>0){
                        $.each(data.data,function(v,k){
                            kecamatan+='<option value="'+k.id+'">'+k.name+'</option>';
                        });
                    }
                }
                $('#kecamatan').html(kecamatan);
                $("#kecamatan").val(selectedKec);
            });
        }

        let selectedKel = "{{ $data->kelurahan }}";
        if(selectedKel != "" && selectedKel != null){
            $.post("{{route('get_desa')}}",{id:selectedKec},function(data){
                var desa = '<option value="">..:: Pilih Desa ::..</option>';
                if(data.status=='success'){
                    if(data.data.length>0){
                        $.each(data.data,function(v,k){
                            desa+='<option value="'+k.id+'">'+k.name+'</option>';
                        });
                    }
                }
                $('#desa').html(desa);
                $('#desa').val(selectedKel);
            });
        }
    }

    function validasiFormEdit(){
        if( $("input[name=no_rm]").val() == "" || $("input[name=no_rm]").val() == null ){
            swal('Whooops','Silahkan Cetak Nomor RM terlebih dahulu','warning');
            return true;
        }
        if( $("input[name=nama_pasien]").val() == "" || $("input[name=nama_pasien]").val() == null ){
            swal('Whooops','Nama Pasien Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=no_ktp]").val() == "" || $("input[name=no_ktp]").val() == null ){
            swal('Whooops','Nomor KTP Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=no_ktp]").val().length < 16 || $("input[name=no_ktp]").val().length > 16 ){
            swal('Whooops','Nomor KTP Harus 16 Angka!','warning');
            return true;
        }
        if( $("input[name=tempat_lahir]").val() == "" || $("input[name=tempat_lahir]").val() == null ){
            swal('Whooops','Tempat Lahir Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=tgl_lahir]").val() == "" || $("input[name=tgl_lahir]").val() == null ){
            swal('Whooops','Tanggal Lahir Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("select[name=gol_darah]").val() == "" || $("select[name=gol_darah]").val() == null ){
            swal('Whooops','Golongan Darah Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("select[name=s_perkawinan]").val() == "" || $("select[name=s_perkawinan]").val() == null ){
            swal('Whooops','Status Perkawinan Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=pekerjaan]").val() == "" || $("input[name=pekerjaan]").val() == null ){
            swal('Whooops','Pekerjaan Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=sukuras]").val() == "" || $("input[name=sukuras]").val() == null ){
            swal('Whooops','Suku / Ras Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("select[name=asuransi]").val() == "" || $("select[name=asuransi]").val() == null ){
            swal('Whooops','Asuransi Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("select[name=agama]").val() == "" || $("select[name=agama]").val() == null ){
            swal('Whooops','Agama Tidak Boleh Kosong!','warning');
            return true;
        }
        if($("select[name=asuransi]").val() == "BPJS"){
            if( $("input[name=noasuransi]").val() == "" || $("input[name=noasuransi]").val() == null ){
                swal('Whooops','Nomor Asuransi Tidak Boleh Kosong!','warning');
                return true;
            }
        }
        if( $("select[name=pend_terakhir]").val() == "" || $("select[name=pend_terakhir]").val() == null ){
            swal('Whooops','Pendidikan Terkahir Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=alamat]").val() == "" || $("input[name=alamat]").val() == null ){
            swal('Whooops','Alamat Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=rt]").val() == "" || $("input[name=rt]").val() == null ){
            swal('Whooops','RT Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=rw]").val() == "" || $("input[name=rw]").val() == null ){
            swal('Whooops','RW Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("select[name=provinsi_id]").val() == "" || $("select[name=provinsi_id]").val() == null ){
            swal('Whooops','Provinsi Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("select[name=kabupaten_id]").val() == "" || $("select[name=kabupaten_id]").val() == null ){
            swal('Whooops','Kabupaten Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("select[name=kecamatan_id]").val() == "" || $("select[name=kecamatan_id]").val() == null ){
            swal('Whooops','Kecamatan Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("select[name=desa_id]").val() == "" || $("select[name=desa_id]").val() == null ){
            swal('Whooops','Kelurahan Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=kodepos]").val() == "" || $("input[name=kodepos]").val() == null ){
            swal('Whooops','Kode Pos Tidak Boleh Kosong!','warning');
            return true;
        }
        if( $("input[name=telepon]").val() == "" || $("input[name=telepon]").val() == null ){
            swal('Whooops','Telepon Tidak Boleh Kosong!','warning');
            return true;
        }
    }
</script>