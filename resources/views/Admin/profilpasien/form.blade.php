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
    <div class="box box-default add-form">
        <div class="panel-body">
            <form class="formAdd" style="padding-top: 10px; padding-left: 20px;">
                <h5>DATA PROFIL PASIEN</h5>
                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <label>Nomor RM</label>
                        <input type="text" class="form-control" name="no_rm" id="no_rm" value="" readonly>
                    </div>
                    <div class="col-md-8">
                        <label>Nama Pasien <span style="color:red">*)</span> </label>
                        <input type="text" class="form-control" name="nama_pasien" id="nama_pasien" value="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>No KTP  <span style="color:red">*)</span> </label>
                        <input type="number" class="form-control" name="no_ktp" id="no_ktp" value="">
                    </div>
                    <div class="col-md-4">
                        <label>Tempat Lahir <span style="color:red">*)</span> </label>
                        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="">
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal Lahir <span style="color:red">*)</span> </label>
                        <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" value="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Golongan Darah <span style="color:red">*)</span> </label>
                        <select name="gol_darah" id="gol_darah" class="form-control" >
                            <option value="" readonly="">..:: Pilih Gol Darah ::..</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Jenis Kelamin <span style="color:red">*)</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <label><input type="radio" name="jenis_kelamin" id="jenis_kelamin_P" value="P" checked>&nbsp; Perempuan </label>
                            </div>
                            <div class="col-md-6">
                                <label><input type="radio" name="jenis_kelamin" id="jenis_kelamin_L" value="L" >&nbsp; Laki - Laki </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Kewarganegaraan <span style="color:red">*)</span> </label>
                        <div class="row">
                            <div class="col-md-6">
                                <label><input type="radio" name="kewarganegaraan" id="wni"  value="WNI" checked>&nbsp; WNI </label>
                            </div>
                            <div class="col-md-6">
                                <label><input type="radio" name="kewarganegaraan" id="wna"  value="WNA">&nbsp; WNA </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Status Perkawinan <span style="color:red">*)</span> </label>
                        <select name="s_perkawinan" id="s_perkawinan" class="form-control" >
                            <option value="" readonly="">..:: Pilih Status Perkawinan ::..</option>
                            <option value="Belum Menikah">Belum Menikah</option>
                            <option value="Cerai"> Cerai </option>
                            <option value="Menikah">Sudah Menikah</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Pekerjaan <span style="color:red">*)</span></label>
                        <input type="text" name="pekerjaan" id="pekerjaan" class="form-control" value="">
                    </div>
                    <div class="col-md-4">
                        <label>Suku / Ras <span style="color:red">*)</span></label>
                        <input type="text" name="sukuras" id="sukuras" class="form-control" value="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Asuransi <span style="color:red">*)</span></label>
                        <select name="asuransi" id="jenis_pasien" class="form-control">
                            <option value="">.:: Asuransi ::.</option>
                            <option value="UMUM">UMUM</option>
                            <option value="BPJS">BPJS</option>
                            <option value="ASURANSILAIN">ASURANSI LAINNYA</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>No. Asuransi <span style="color:red">*)</span></label>
                        <input type="text" name="noasuransi" id="noasuransi" class="form-control" value="">
                    </div>
                    <div class="col-md-4">
                        <label>Agama <span style="color:red">*)</span></label>
                        <select name="agama"  id="agama" class="form-control" >
                            <option value="" readonly="">..:: Pilih Agama ::..</option>
                            <option value="Islam">Islam</option>
                            <option value="Protestan">Protestan</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Khonghucu">Khonghucu</option>
                        </select>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-4">
                        <label>Pendidikan Terakhir <span style="color:red">*)</span></label>
                        <select name="pend_terakhir" id="pend_terakhir" class="form-control" >
                            <option value="" readonly="">..:: Pendidikan Terakhir ::..</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA / SMK">SMA / SMK</option>
                            <option value="Diploma">Diploma</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="text" name="email" id="email" class="form-control" value="">
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12">
                        <label>Alamat <span style="color:red">*)</span></label>
                        <input type="text" class="form-control" name="alamat" id="alamat" value="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label>RT <span style="color:red">*)</span></label>
                        <input type="number" class="form-control" name="rt" id="rt" value="">
                    </div>
                    <div class="col-md-3">
                        <label>RW <span style="color:red">*)</span></label>
                        <input type="number" class="form-control" name="rw" id="rw" value="">
                    </div>
                    <div class="col-md-3">
                        <label>Provinsi <span style="color:red">*)</span></label>
                        <select name="provinsi_id" class="form-control select2" id="provinsi" >
                            <option value="" readonly="">..::Pilih Provinsi ::..</option>
                            @foreach ($provinsi as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>	
                    </div>
                    <div class="col-md-3">
                        <label>Kab/Kota <span style="color:red">*)</span></label>
                        <select name="kabupaten_id" class="form-control select2" id="kabupaten">
                            <option value="" readonly="">..:: Pilih Kab/Kota ::..</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Kecamatan <span style="color:red">*)</span></label>
                        <select name="kecamatan_id" class="form-control select2" id="kecamatan">
                            <option value="" readonly="">..:: Pilih Kecamatan ::..</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Desa / Kelurahan <span style="color:red">*)</span></label>
                        <select name="desa_id" class="form-control select2" id="desa">
                            <option value="" readonly="">..:: Pilih Desa / Kelurahan ::..</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Kode POS <span style="color:red">*)</span></label>
                        <input type="text" class="form-control" name="kodepos" id="kodepos" value="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Telepon <span style="color:red">*)</span></label>
                        <input type="number" class="form-control" name="telepon" id="telepon" value="">
                    </div>
                    <div class="col-md-4">
                        <label>Fax</label>
                        <input type="text" class="form-control" name="fax" id="fax" value="">
                    </div>
                    <div class="col-md-4">
                        <label>Foto Pasien</label>
                        <input type="file" name="foto_pasien" class="form-control" id="foto_pasien" value="">
                    </div>
                </div>
                
                <div class="row" style="margin-top: 1rem;">
                    <div class="col-md-2 text-center">
                        <button type="button" class="btn btn-success btn-store" style="width: 100%;">SIMPAN</button>
                    </div>
                    <div class="col-md-2 text-center">
                        <button type="button" class="btn btn-secondary btn-cancel" style="width: 100%;">KEMBALI</button>
                    </div>
                    <div class="col-md-2 text-center">
                        <button type="button" class="btn btn-danger btn-cetak-rm" onclick="cetakrm()" style="width: 100%">CETAK NO RM</button>
                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.2/js/bootstrap.min.js" integrity="sha512-5BqtYqlWfJemW5+v+TZUs22uigI8tXeVah5S/1Z6qBLVO7gakAOtkOzUtgq6dsIo5c0NJdmGPs0H9I+2OHUHVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
<script type="text/javascript">
    function validasiForm(){
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