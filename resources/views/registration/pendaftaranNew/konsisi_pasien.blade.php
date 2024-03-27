<div class="modal fade" id="KonsisiPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="border-manual">
                <div class="row">
                    <input type="hidden" name="konsisinik" id="konsisinik" value="">
                    <input type="hidden" name="konsisirm" id="konsisirm" value="">
                    <input type="hidden" name="konsisibpjs" id="konsisibpjs" value="">
                    <div class="col-md-12">
                        <div class="row" style="background: green; height: 50px; border-radius: 10px; width: 600px; margin-left: 0px;">
                            <div class="col-md-12 text-center">
                                <span style="font-size: 14pt;">
                                    <b style="color: #ffffff;"> PERTANYAAN</b>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8 text-center">
                                <span style="color: black; font-size: 14pt;"><b>Apakah Anda Seorang Penyandang</b></span><br>
                                <span style="color: black; font-size: 14pt;"><b>DISABILITAS (Berkebutuhan Khusus) ?</b></span>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8"  style="top: 3rem">
                                <button type="button" class="btn btn-success btn-lg btn-block" style="width: 400; height: 40px;" onclick="ya()">
                                    <b>YA, SAYA DISABILITAS</b>
                                </button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8" style="top: 4rem">
                                <button type="button" class="btn btn-primary btn-lg btn-block" style="width: 400; height: 40px;" onclick="tidak()">
                                    <b>TIDAK</b>
                                </button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8" style="top: 5rem">
                                <button type="button" class="btn btn-secondary btn-lg btn-block" style="width: 400; height: 40px;" onclick="kembali('konsisi')">
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