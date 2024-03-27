<div class="modal fade" id="PoliPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 10px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="row" style="margin-top: 30px; margin-left: 10px;">
                <input type="hidden" name="nikpoli" id="nikpoli" value="">
                <input type="hidden" name="namapoli" id="namapoli" value="">
                <input type="hidden" name="nobpjspoli" id="nobpjspoli" value="">
                <input type="hidden" name="norm" id="norm" value="">
                @foreach ($poli as $p)
                <div class="col-lg-2 col-md-2 text-center div-poli" style="margin-left: 10px; margin-top: 10px;">
                    <a href="javascript:void(0);" onclick="btnpoli(`{{$p->kdpoli}}`)" class="col-xs-12 btn-poli" style="background: #2CBA44; border-radius: 5px; width: 180px; position: relative; margin-left: -15px;">
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
      </div>
    </div>
</div>