<div class="modal fade" id="NoAntrianPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="border-manual text-center">
                <h4 style="color: #000; margin-top: 20px;">Anda Telah Memilih Poli</h4>
                
                <div class="col-md-12">
                    <hr class="line2">
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <input type="hidden" id="kodepoli" name="kodepoli" class="form-control" value="">
                    <input type="hidden" id="id_antrian" name="id_antrian" class="form-control" value="">
                    <h3 style="color: #000; font-size: 14pt; margin-top: -10px;" id="poli"></h3>
                    <h1 style="color: #000; font-size: 28pt" id="no_antrian"></h1>
                    <p style="color: #000; font-size: 10pt;">Silahkan Tekan Tombol <span style="color: red;">Cetak Antrian </span>Dan Menuju Ke <span style="color: red;">Loket</span></p>
                    <div class="col-xs-6 panelPilihan" style="">
                        <a href="javascript:void(0);" onclick="cetak()" class="col-xs-12 text-center btn-cetak">
                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 10pt; font-style: normal; margin-top: 5px; text-align: center;"><i class="fa fa-print"></i> Cetak Antrian</label>
                        </a>
                    </div>
                </div>
            </div>

        </div>
      </div>
    </div>
</div>