<div class="modal fade" id="PoliPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">

            <div class="row">
                <input type="hidden" name="polinik" id="polinik" value="">
                <input type="hidden" name="polirm" id="polirm" value="">
                <input type="hidden" name="polibpjs" id="polibpjs" value="">
                <div class="col-md-12">
                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-1 text-center">
                            <div onclick="kembali('pasien_poli')">
                                <img src="{{ asset('aset/images/arrow_left.png') }}" alt="">
                                <div>
                                    <p style="color: #000; font-weight: bold;">KEMBALI</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10" style="margin-left: 10px;">
                            <div class="row">
                                @foreach ($poli as $p)
                                <div class="col-lg-2 col-md-2 text-center div-poli" style="margin-left: 10px;" onclick="btnpoli(`{{$p->kdpoli}}`)">
                                    <a href="javascript:void(0);" class="col-xs-12 btn-poli" style="background: #2CBA44; border-radius: 5px; width: 150px; position: relative; margin-left: -15px;">
                                        <label style="color: #FFFFFF; font-family: Arial;  font-size: 8pt;">
                                            <?php
                                            $myvalue = $p->NamaPoli;
                                            $arr = explode(' ',trim($myvalue));
                                            echo $arr[0];
                                            ?>
                                        </label>
                                    </a>
                        
                                    <small class="polikatalog">{{$p->NamaPoli}}</small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>