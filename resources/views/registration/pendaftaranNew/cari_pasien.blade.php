<div class="modal fade" id="CariPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">

            <div class="k-antrian" style="margin-top: 11rem;">
                <div class="row" style="background: #2CBA44; width: 1000px; height: 40px; margin-left: 0px;">
                    <div class="col-md-12 text-center">
                        <span style="color: #ffffff;">
                            <b>TEMUKAN DATA PASIEN</b>
                        </span>
                    </div>
                    <div class="col-md-12" style="top: 10rem; left: 20px; width: 95%;">
                        <div class="row">
                            <div class="col-md-12">
                                <label><span><b style="color: #000;">PILIH KATEGORI PENCARIAN :</b></span></label><br>
                                <label style="color: #000; font-size: 8pt;">
                                    <input type="radio" name="pilihkategori" id="rm">
                                    &nbsp; NO RM</label>
                                <label style="color: #000; font-size: 8pt; padding-left: 20px;">
                                    <input type="radio" name="pilihkategori" id="bpjs">&nbsp; NO BPJS / KIS
                                </label>
                                <label style="color: #000; font-size: 8pt; padding-left: 20px;">
                                    <input type="radio" name="pilihkategori" id="nik">&nbsp; NIK
                                </label>
                                <label style="color: #000; font-size: 8pt; padding-left: 20px;">
                                    <input type="radio" name="pilihkategori" id="jkn">&nbsp; PASIEN BARU (JKN)
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <input type="text" name="dtpasien" id="dtpasien" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="javascript:void(0)" style="width: 100%" class="btn btn-success btn-sm" onclick="cariPasien()">CARI PASIEN</a>
                            </div>
                            <div class="col-md-6">
                                <a href="javascript:void(0)" style="width: 100%" class="btn btn-danger btn-sm" onclick="kembali('cariPasien')">KEMBALI</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
</div>