<div class="modal fade" id="CariPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      {{-- <div> --}}
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">

            <div class="k-antrian">
                <div class="row">
                    <div class="col-md-12" style="left: 3rem; top: 2rem; width: 95%;">
                        <div class="row">
                            <div class="col-md-12 text-center"><span>CARI DATA PASIEN</span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <label style="color: #000; font-size: 8pt;"><input type="radio" name="pilihkategori" id="rm">&nbsp; No. RM</label>
                                <label style="color: #000; font-size: 8pt;"><input type="radio" name="pilihkategori" id="bpjs">&nbsp; No. BPJS</label>
                                <label style="color: #000; font-size: 8pt;"><input type="radio" name="pilihkategori" id="nik">&nbsp; NIK &nbsp;</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                {{-- <input type="text" name="dtpasien" id="dtpasien" class="form-control" onkeyup="search()"> --}}
                                <input type="text" name="dtpasien" id="dtpasien" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                {{-- <div id="btnlanjut" style="margin-top: 50px;"></div> --}}
                                {{-- <div style="width: 100%"> --}}
                                <a href="javascript:void(0)" style="width: 100%" class="btn btn-success btn-sm" onclick="cariPasien()">Cari Pasien</a>
                                {{-- </div> --}}
                            </div>
                            <div class="col-md-6">
                                <a href="javascript:void(0)" style="width: 100%" class="btn btn-danger btn-sm" onclick="kembali('cariPasien')">Kembali</a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                    </div> --}}
                </div>
                
            </div>

        </div>
      {{-- </div> --}}
    </div>
</div>