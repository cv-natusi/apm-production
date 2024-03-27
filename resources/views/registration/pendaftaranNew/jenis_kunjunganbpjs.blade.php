<div class="modal fade" id="JenisKunjunganBpjsModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">

            <div class="border-kunjungan">
                <div class="row" style="background: #2CBA44; width: 700px; height: 40px; margin-left: 0px;">
                    <div class="col-md-12">
                        <input type="hidden" name="nobpjskunjungan" id="nobpjskunjungan">
                        <input type="hidden" name="normkunjungan" id="normkunjungan">
                        <input type="hidden" name="kodepolibpjs" id="kodepolibpjs" value="">
                        <input type="hidden" name="kddokterbpjs" id="kddokterbpjs" value="">
                        <input type="hidden" name="jadwalbpjs" id="jadwalbpjs" value="">
                        {{-- <input type="hidden" name="kdbookingbpjs" id="kdbookingbpjs" value=""> --}}
                        {{-- <input type="hidden" name="no_antrianbpjs" id="no_antrianbpjs" value=""> --}}
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <span style="font-size: 14pt;">
                                    <b style="color: #ffffff;">NIK & NO. REFERENSI</b>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label style="color: #000; font-size: 10pt;"><b id="preferensi">PILIH REFERENSI</b></label><br>
                                        <label style="color: #000; font-size: 7pt;"><input type="radio" name="pkkunjungan" id="fktp" checked>&nbsp; Rujukan FKTP</label>
                                        <label style="color: #000; font-size: 7pt; padding-left: 20px;"><input type="radio" name="pkkunjungan" id="internal">&nbsp; Rujukan Internal</label>
                                        <label style="color: #000; font-size: 7pt; padding-left: 20px;"><input type="radio" name="pkkunjungan" id="kontrol" value="kontrol">&nbsp; Kontrol</label>
                                        <label style="color: #000; font-size: 7pt; padding-left: 20px;"><input type="radio" name="pkkunjungan" id="antarrs">&nbsp; Rujukan Antar RS</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <input type="text" id="noreferensi" name="noreferensi" class="form-control" placeholder="Masukkan no. rujukan" maxlength="19" style="width: 100%" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==19) return false;" oninput="convertToUppercase(this)" autocomplete="off">
                                        <small style="font-size: 8pt;" class="msg-noref text-danger"></small>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" style="width: 100%; height: 100%;" class="btn btn-info" onclick="btnCariRujukan()">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <label style="color: #000; font-size: 10pt;"><b>NIK (Nomor Induk Kependudukan)</b></label>
                                <input type="text" id="nikkunjungan" name="nikkunjungan" class="form-control" maxlength="16" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;">
                                <small style="font-size: 8pt;" class="msg-nikkunjungan text-danger"></small>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8"  style="top: 10px">
                                <button type="button" class="btn btn-success btn-lg btn-block" id="btnLanjut" style="width: 435; height: 40px;">
                                    <b>SELANJUTNYA</b>
                                </button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8" style="top: 20px;">
                                <button type="button" class="btn btn-danger btn-lg btn-block" style="width: 435; height: 40px;" onclick="kembali('jenis_kunjungan')">
                                    <b style="color: #000;">KEMBALI</b>
                                </button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>