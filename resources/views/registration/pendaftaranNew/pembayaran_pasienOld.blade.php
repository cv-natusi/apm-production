<div class="modal fade" id="PembayaranPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="row">
                <h5 class="text-center" style="color: yellow; font-size: 22px; font-family: 'Shrikhand', cursive; position: absolute; margin-left: 425px; margin-top: 50px;">SILAHKAN PILIH ANTRIAN</h5>
                <div class="col-lg-12 col-md-12 panel-antrian" style="margin-top: 100px;">
                    <div class="col-xs-6 panelPilihan" style="margin-left: 350px; margin-top: -20px;">
                        <input type="hidden" name="nikpembayaran" id="nikpembayaran" value="">
                        <input type="hidden" name="namapembayaran" id="namapembayaran" value="">
                        <input type="hidden" name="nobpjspembayaran" id="nobpjspembayaran" value="">
                        <input type="hidden" name="norm" id="norm" value="">
                        <div style="width: 400px; height: 60px; margin-top: 90px; border-radius: 5px;">
                            <input style="color: #FFFFFF;" type="text" name="bpjslama" id="bpjslama" maxlength="13" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==13) return false;" class="form-control" placeholder="Masukkan no bpjs .." value="">
                            <small class="msg-bpjs text-danger"></small>
                        </div>
                        <div style="width: 400px; height: 60px; background: #FFFFFF; margin-top: 10px; border-radius: 15px; position: absolute;">
                            <a href="javascript:void(0);" onclick="umum()" class="col-xs-12 text-center btn-umum">
                                <label style="color: #000; font-family: Arial;  font-size: 22px; margin-top: 10px; text-align: center;">PASIEN UMUM</label>
                                <input type="hidden" id="umum" name="umum" class="form-control" value="UMUM" autocomplete="off">
                            </a>
                        </div>
                        <div style="width: 400px; height: 60px; background: #FFFFFF; margin-top: 80px; border-radius: 15px; position: absolute;">
                            <a href="javascript:void(0);" onclick="bpjs()" class="col-xs-12 text-center btn-bpjs">
                                <label style="color: #000; font-family: Arial;  font-size: 22px; margin-top: 10px; text-align: center;">PASIEN BPJS</label>
                                <input type="hidden" id="bpjs" name="bpjs" class="form-control" value="BPJS" autocomplete="off">
                            </a>
                        </div>
                        <div style="width: 400px; height: 60px; background: #FFFFFF; margin-top: 150px; border-radius: 15px; position: absolute;">
                            <a href="javascript:void(0);" onclick="asuransi()" class="col-xs-12 text-center btn-lainnya">
                                <label style="color: #000; font-family: Arial;  font-size: 22px; margin-top: 10px; text-align: center;">ASURANSI LAINNYA</label>
                                <input type="hidden" id="asuransi" name="asuransi" class="form-control" value="ASURANSI LAINNYA" autocomplete="off">
                            </a>
                        </div>
                        
                        <!-- <div style="width: 400px; height: 60px; background: #FFFFFF; margin-top:260px; border-radius: 15px; position: absolute;"> -->
                            <!-- <a onclick="kembali('pembayaran')" href="javascript:void(0);" class="col-xs-12 text-center btn-cancel">
                                <label style="color: #000; font-family: Arial;  font-size: 22px; margin-top: 10px; text-align: center;">KEMBALI</label>
                            </a> -->
                        <!-- </div> -->
                        {{-- <div class="row" style="margin-top: 20em;margin-left: 7em;"> --}}
                        <div class="row" style="margin-top: 220px;margin-left: 7em;">
                            <div onclick="kembali('pembayaran')">
                                <div style="width: 47%">
                                    <img src="{{ asset('aset/images/arrow_left.png') }}"
                                        style="float: left;margin-right: 10px" alt="">
                                </div>
                                <div style="width: 53%">
                                    <p style="color: black;font-weight: bold;font-size: 20pt;float: left;margin-top:5px">
                                        KEMBALI
                                    </p>
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