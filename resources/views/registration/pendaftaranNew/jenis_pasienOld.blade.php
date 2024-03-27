<div class="modal fade" id="JenisPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="row">
              <div class="col-4 text-center" style="margin-top: 200px; margin-left: 410px; border-radius: 10px;" >
                <a href="javascript:void(0);" onclick="pasienbaru()" class="col-xs-12 text-center btn-baru">
                    <label style="color: #FFFFFF; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 10px; text-align: center;">PASIEN BARU</label><br>
                    <strong style="color: #000; font-size: 10px; text-align: center;">Baru Pertama Periksa / Dirawat di RSU Dr. Wahidin Sudiro Husodo</strong>
                    <input type="hidden" id="baru" name="pasien" value="Y">
                </a>
              </div>
              <div class="col-4 text-center" style="margin-top: 300px; margin-left: 410px;">
                <a href="javascript:void(0);" onclick="pasienlama()" class="col-xs-12 text-center btn-lama">
                    <label style="color: #FFFFFF; font-family: Arial;  font-size: 22px; font-style: normal; margin-top: 10px; text-align: center;">PASIEN LAMA</label><br>
                    <strong style="color: #000; font-size: 10px; text-align: center;">Pasien Sudah Pernah Periksa / Dirawat di RSU Dr. Wahidin Sudiro Husodo</strong>
                    <input type="hidden" id="lama"  name="pasien" value="N">
                </a>
              </div>
            </div>
            <div class="row" style="margin-top: 50px; margin-left: 500px;">
              <div onclick="kembali('jenis_pasien')">
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