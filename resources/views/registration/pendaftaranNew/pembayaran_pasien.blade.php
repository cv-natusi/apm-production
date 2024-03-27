<div class="modal fade" id="PembayaranPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">

            <div class="row">
                <input type="hidden" name="pembayarannik" id="pembayarannik" value="">
                <input type="hidden" name="pembayaranrm" id="pembayaranrm" value="">
                <input type="hidden" name="pembayaranbpjs" id="pembayaranbpjs" value="">
                <div class="col-md-12 text-center" style="background: #ffffff; top: 6rem; width: 100%; height: 50px;">
                  <div class="row">
                    <div class="col-md-12" style="background: #2CBA44; width: 100%; height: 45px; margin-top: 2px;">
                        <span><b style="color: #ffffff; font-size: 16pt;">PILIH JENIS PEMBAYARAN ANDA</b></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="top: 10rem">
                        <button type="button" class="btn btn-success btn-lg btn-block" style="width: 580px; height: 60px;" onclick="umum()">
                        <b>PEMBAYARAN UMUM</b>
                        </button>
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                  <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="top: 11rem">
                      <button type="button" class="btn btn-info btn-lg btn-block" style="width: 580px; height: 60px;" onclick="bpjs()">
                        <b>PEMBAYARAN BPJS</b>
                      </button>
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                  <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="top: 12rem">
                      <button type="button" class="btn btn-primary btn-lg btn-block" style="width: 580px; height: 60px;" onclick="asuransi()">
                        <b>ASURANSI LAINNYA</b>
                      </button>
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                  <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="top: 13rem">
                      <button type="button" class="btn btn-secondary btn-lg btn-block" style="width: 580px; height: 60px;" onclick="kembali('pembayaran')">
                        <b style="color: #000;">KEMBALI</b>
                      </button>
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>