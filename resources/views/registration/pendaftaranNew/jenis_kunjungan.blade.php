<div class="modal fade" id="JenisKunjunganModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="row">
                <div class="border-kunjungan">
                    <div class="col-md-12" style="margin-left: 50px; margin-top: 10px;">
                        <input type="hidden" name="nikkunjungan" id="nikkunjungan" value="">
                        <input type="hidden" name="namakunjungan" id="namakunjungan" value="">
                        <input type="hidden" name="nobpjskunjungan" id="nobpjskunjungan" value="">
                        <input type="hidden" name="norm" id="norm" value="">
                        <input type="hidden" id="kodepoli" name="kodepoli" value="">
                        <input type="hidden" id="kddokter" name="kddokter" value="">
                        <input type="hidden" id="jadwal" name="jadwal" value="">
                        <input type="hidden" id="kdbooking" name="kdbooking" class="form-control" value="">
                        <input type="hidden" id="no_antrian" name="no_antrian" class="form-control" value="">
                        <h3 class="" style="color: #000; font-size: 12pt; margin-left: 165px;"><b>Masukkan Nomor Referensi</b></h3><br>
    
                        <label><b style="color: #000; margin-left: 0px; font-size: 8pt; margin-left: 60px;">Pilih Kategori</b></label>
                        <div class="" style="margin-left: 70px;">
                            <label style="color: #000; font-size: 7pt;"><input type="radio" name="pkkunjungan" id="fktp">&nbsp; Rujukan FKTP</label>
                            <label style="color: #000; font-size: 7pt; padding-left: 20px;"><input type="radio" name="pkkunjungan" id="internal">&nbsp; Rujukan Internal</label>
                            <label style="color: #000; font-size: 7pt; padding-left: 20px;"><input type="radio" name="pkkunjungan" id="kontrol">&nbsp; Kontrol</label>
                            <label style="color: #000; font-size: 7pt; padding-left: 20px;"><input type="radio" name="pkkunjungan" id="antarrs">&nbsp; Rujukan Antar RS</label>
                        </div>
                        <div class="col-md-8" style="position: absolute; margin-left: 43px;">
                            <input type="text" id="noref" name="noref" class="form-control" value="">
                        </div>
                        <a href="javascript:void(0);" onclick="btnkunjungan()" class="col-xs-12 btn-kunjungan">
                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 12pt; font-style: normal; margin-top: 8px; margin-left: 160px;">Selanjutnya</label>
                        </a>
                        <a href="javascript:void(0);" onclick="kembali('jenis_kunjungan')" class="col-xs-12 kembali-kunjungan">
                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 12pt; font-style: normal; margin-top: 8px; margin-left: 160px;">Kembali</label>
                        </a>
                        
                    </div>
                </div>
            </div>

        </div>
      </div>
    </div>
</div>