<div class="modal fade" id="LahirPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="row">
                <div style="margin-top: 50px;">
                    <h3 class="text-center" style="color: #FAFF00; font-family: 'Shrikhand', cursive;">TANGGAL LAHIR PASIEN</h3>
                </div>
                <br>

                <div class="col-md-12" style="margin-top: 50px; margin-left: 200px;">
                    <div class="col-md-2" style="color: #000;">
                        <label>NAMA</label>
                        <input type="text" id="namabaru" name="namabaru" class="form-control" style="background: #FFFFFF; text-align: center; border-radius: 5px !important;" required>
                    </div>
                    <div class="col-md-3" style="color: #000;">
                        <label>NIK</label>
                        <input type="text" id="nikbaru" name="nikbaru" maxlength="16" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" class="form-control" style="background: #FFFFFF; text-align: center; border-radius: 5px !important;" required>
                        <small class="msg-nik text-danger"></small>
                    </div>
                    <div class="col-md-3" style="color: #000">
                        <label>NO. BPJS</label>
                        <input type="text" id="bpjsbaru" name="bpjsbaru" class="form-control" maxlength="13" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==13) return false;" placeholder="kosongi jika pasien umum .." style="background: #FFFFFF; text-align: center; border-radius: 5px !important;">
                        <small class="msg-bpjs text-danger"></small>
                    </div>
                </div>
    
                <div class="col-md-12" style="margin-top: 10px;">
                    <div class="col-md-2" style="color: #000; margin-left: 200px;">
                        <label>TANGGAL</label>
                        <input type="text" id="tanggal" name="tanggal_lahir" class="form-control form-control-sm tgl" autocomplete="off" data-date-format="dd" style="background: #FFFFFF; text-align: center; border-radius: 5px !important;">
                    </div>
                    <div class="col-md-4" style="color: #000">
                        <label>BULAN</label>
                        <input type="text" id="bulan" name="bulan" class="form-control form-control-sm bulan" autocomplete="off" data-date-format="mm" style="background: #FFFFFF; text-align: center; border-radius: 5px !important;">
                    </div>
                    <div class="col-md-2" style="color: #000">
                        <label>TAHUN</label>
                        <input type="text" id="tahun" name="tahun" class="form-control form-control-sm tahun" autocomplete="off" data-date-format="yyyy" style="background: #FFFFFF; text-align: center; border-radius: 5px !important;">
                    </div>
                </div>
                <div class="col-md-10" style="margin-top: 0px;">
                    <hr class="line">
                </div>
                <div class="col-md-12 text-center" style="margin-top: 0px;">
                    <a href="javascript:void(0);" onclick="kembali('lahir_pasien')" class="col-xs-12 btn-kembali">
                        <label style="color: #000; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 8px;">KEMBALI</label>
                    </a>
                    <a href="javascript:void(0);" onclick="lpselanjutnya()" class="col-xs-12 btn-selanjutnya">
                        <label style="color: #000; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 8px;">SELANJUTNYA</label>
                    </a>
                </div>
            </div>

        </div>
      </div>
    </div>
</div>