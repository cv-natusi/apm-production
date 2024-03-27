<div class="modal fade" id="KonfirmasiPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            <div class="k-antrian">
                <div class="row">
                    <div class="col-md-12">
                        {{-- <div class="row text-center">
                            <h2 style="color: #000;">KONFIRMASI ANTRIAN</h2>
                        </div> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-center" style="color: #000;">KONFIRMASI SIMAPAN</h3>
                            </div>
                            <div class="col-md-6">
                                <h3 class="text-center" style="color: #000;">KONFIRMASI WA / JKN MOBILE</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" style="border-right: 3px solid black; height: 70%;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="brcode"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <span><span style="color: #000; ">Silahkan Konfirmasi Antrian RSUD Dr. Wahidin Sudiro Husodo</span><br>
                                        <span style="color: #000; ">1. Buka aplikasi<b> SIMAPAN </b>di Handphone Anda</span><br>
                                        <span style="color: #000; ">2. Pilih<b> Konfirmasi Antrian</b></span><br>
                                        <span style="color: #000; ">3. Lakukan<b> Scan QR Code</b></span><br>
                                        <span style="color: #000; ">4. Antrian Anda<b> Telah Terkonfirmasi</b></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label style="color: #000; font-size: 10pt;" class="pull-left">Masukkan Kode Booking Anda</label>
                                        <input type="text" name="kodeUnik" id="kodeUnik" class="form-control" style="width:90%;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 panelPilihan">
                                        <a href="javascript:void(0);" id="k_manual" style="width:90%;" class="text-center btn btn-primary">
                                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 12px; font-style: normal; margin-top: 3px; text-align: center;">KONFIRMASI</label><br>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12" style="margin-top: 10px; ">
                                <button type="button" class="btn btn-secondary text-center btn-kembali-konfirmasi" style="width: 90%; color: #000;" onclick="kembali('konfirmasi_pasien')"> KEMBALI </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>