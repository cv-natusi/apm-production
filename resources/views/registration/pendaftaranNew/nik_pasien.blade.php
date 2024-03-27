<div class="modal fade" id="NikPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">

            <div class="border-manual">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="bpjspasienlama" id="bpjspasienlama" value="">
                        <input type="hidden" name="rmpasienlama" id="rmpasienlama" value="">
                        <input type="hidden" name="kodepoli" id="kodepoli" value="">
                        <input type="hidden" name="kddokter" id="kddokter" value="">
                        <input type="hidden" name="jadwal" id="jadwal" value="">
                        {{-- <input type="hidden" name="kdbooking" id="kdbooking" value=""> --}}
                        {{-- <input type="hidden" name="no_antrian" id="no_antrian" value=""> --}}
                        <div class="row" style="background: #2CBA44; height: 40px; width: 600px; border-radius: 10px; margin-left: 0px;">
                            <div class="col-md-12 text-center">
                                <span style="font-size: 14pt;">
                                    <b style="color: #ffffff;">NIK</b>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <label style="color: #000;"> NIK (Nomor Induk Kependudukan) </label>
                                <input type="text" id="nikpasien" name="nikpasien" class="form-control" maxlength="16" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;">
                                <small class="msg-nik text-danger"></small>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8"  style="top: 1rem">
                                <button type="button" id="btn-next-umum" class="btn btn-success btn-lg btn-block" style="width: 400; height: 40px;" >
                                    <b>SELANJUTNYA</b>
                                </button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8" style="top: 2rem">
                                <button type="button" class="btn btn-secondary btn-lg btn-block" style="width: 400; height: 40px;" onclick="kembali('nikpasien')">
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