<div class="modal fade" id="KonfirmasiPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 560px; background: rgba(217, 236, 214, 0.75) !important;">
            <div class="konfirmasi-online">
                <div class="row" style="background: #2CBA44; width: 1000px; height: 40px; margin-left: 0px;">
                    <div class="col-md-12 text-center" style="margin-top: 10px;">
                        <span style="color: #ffffff; ">
                            <b>KONFIRMASI ANTRIAN ONLINE</b>
                        </span>
                    </div>
                    <div class="col-md-12" style="top: 2rem;">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="text-center" style="color: #000;"><b>KONFIRMASI SIMAPAN</b></h5>
                                <div class="brcode text-center"></div>
                                <span style="color: #000; font-weight: bold">Informasi</span><br>
                                <span style="font-size: 8pt; color: #000">Silahkan konfirmasi antrian RSUD Dr. Wahidin Sudiro Husodo.</span><br>
                                <span style="font-size: 8pt; color: #000">1. Buka aplikasi SIMAPAN di handphone anda</span><br>
                                <span style="font-size: 8pt; color: #000">2. Pilih konfirmasi antrian</span><br>
                                <span style="font-size: 8pt; color: #000">3. Lakukan scan qr code</span><br>
                                <span style="font-size: 8pt; color: #000">4. Antrian anda telah terkonfirmasi</span>
                            </div>
                            <div class="col-md-4 text-center">
                                <h5 class="text-center" style="color: #000;"><b>KONFIRMASI MOBILE JKN</b></h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <img id="qrBarcodeJkn" src="{{ asset("assets/qr-mjkn/konfirmasi-mjkn.jpeg") }}" style="margin-top: 2rem;height: 160px; width: 160px" alt="qr-code-mjkn">
                                    </div>
                                </div>
                                <div class="row btnKembali">
                                    <div class="col-md-12" style="margin-top: 14rem;">
                                        <button type="button" style="width: 100%; color: #000; font-weight: bold;" class="btn btn-warning" onclick="kembali('konfirmasi_pasien')">KEMBALI</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-center" style="color: #000;"><b>KONFIRMASI MANUAL WA/SIMAPAN</b></h5>
                                <div class="row" style="margin-top: 50px">
                                    <div class="col-md-12">
                                        <span style="color: #000; font-size: 8pt;">Tulis Kode Booking Anda!</span>
                                        <input type="text" name="kodeUnik" id="kodeUnik" autocomplete="off" class="form-control">
                                        <a href="javascript:void(0);" id="k_manual" style="width:100%;" class="text-center btn btn-primary">
                                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 12px; font-style: normal; margin-top: 3px; text-align: center;">KONFIRMASI</label><br>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>