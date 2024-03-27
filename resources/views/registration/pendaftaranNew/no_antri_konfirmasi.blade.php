<div class="modal fade" id="NoAntriKonfirmasiModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="border-manual">
                <div class="row" style="background: #8DCAED; width: 600px; height: 40px; margin-left: 0px;">
                    <input type="hidden" name="kodepoli" id="kodepoli" value="">
                    <input type="hidden" name="id_antrian" id="id_antrian" value="">
                    <input type="hidden" name="is_pasien" id="is_pasien" value="">
                    <input hidden id="token" value="" type="text">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <span style="font-size: 14pt;">
                                    <b style="color: #000;">NOMOR PENDAFTARAN</b>
                                </span>
                            </div>
                        </div>
                        <div class="row text-center" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <span style="font-size: 30pt; color: #000;"><b id="noAntrian"></b></span>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12" style="top: 1rem">
                                <span style="font-size: 12pt; color: #000;">Kode Booking <b id="kodeBooking"></b></span>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12" style="top: 2rem">
                                <span style="font-size: 12pt; color: #000;"><b>TUJUAN <strong id="namaPoli"></strong></b></span>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12" style="top: 3rem">
                                <p style="font-size: 10pt; color: #000;">Silahkan Tekan Tombol <b style="color: red;">Cetak Antrian</b> Dan Menuju Ke <b><strong id="tujuan"></strong></b></p>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12" style="top: 4rem;">
                                <button type="button" class="btn btn-primary" onclick="cetak('antri_konfirmasi')">
                                    <i class="fa fa-print"></i> Cetak Antrian
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>