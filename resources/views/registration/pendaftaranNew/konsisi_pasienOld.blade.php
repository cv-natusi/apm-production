<div class="modal fade" id="KonsisiPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="border-manual">
                <div class="row">
                    <div class="text-center" style="margin-top: 0px; margin-left: 0px;">
                        <input type="hidden" name="nikkonsisi" id="nikkonsisi" value="">
                        <input type="hidden" name="nobpjskonsisi" id="nobpjskonsisi" value="">
                        <input type="hidden" name="norm" id="norm" value="">
                        <h4 style="color: #000; font-size: 14pt; margin-top: 10px;"><b>APAKAH ANDA</b></h4>
                        <h4 style="color: #000; font-size: 14pt;"><b>PENYANDANG DISABILITAS</b></h4>
                        <p style="color: #000; font-size: 12pt;">(BERKEBUTUHAN KHUSUS)</p>
                        <br>
                    </div>
                    
                    <div class="col-4 text-center" style="margin-top: -10px; margin-left: 60px; border-radius: 10px;" >
                        <a href="javascript:void(0);" onclick="ya()" class="col-xs-12 btn-ya">
                        <label style="color: #FFFFFF; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 8px; text-align: center;">YA, SAYA DISABILITAS</label>
                        <input type="hidden" id="ya" name="geriatri" value="Y">
                    </a>
                    </div>
                    <div class="col-4 text-center" style="margin-top: 40px; margin-left: 60px;">
                        <a href="javascript:void(0);" onclick="tidak()" class="col-xs-12 btn-tidak">
                            <label style="color: #FFFFFF; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 8px; text-align: center;">SAYA TIDAK</label>
                            <input type="hidden" id="tidak" name="geriatri" value="N">
                        </a>
                    </div>
                </div>
                <div class="row" style="margin-top: 60px; margin-left: 200px;">
                    <div onclick="kembali('konsisi')">
                        <div style="width: 47%" >
                            <img src="{{ asset('aset/images/arrow_left.png') }}" style="float: left;margin-right: 10px" alt="">
                        </div>
                        <div style="width: 53%" >
                            <p style="color: black;font-weight: bold;font-size: 20pt;float: left;margin-top:5px">KEMBALI</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
      </div>
    </div>
</div>