<div class="modal fade" id="DialogKunjunganModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            
            <div class="border-manual">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row" style="background: #2CBA44; width: 600px; height: 40px; margin-left: 0px;">
                            <div class="col-md-12 text-center">
                                <span style="font-size: 14pt;">
                                    <b style="color: #ffffff;">IDENTITAS ANDA</b>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 1rem">
                            <div class="col-md-2"></div>
                            <div class="col-md-8" style="color: #000">
                                <span>Nama : <b id="pbarunama"></b></span><br>
                                <span>NIK : <b id="pbarunik"></b></span><br>
                                <span>No RM : <b id="pbarurm"></b></span><br>
                                <span>No BPJS : <b id="pbarubpjs"></b></span><br>
                                <span>Tgl Lahir : <b id="pbarulahir"></b></span>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row" style="margin-top: 3rem;">
                            <div class="col-md-2"></div>
                            <div class="col-md-8 text-center">
                                <button type="button" class="btn btn-success btn-lg btn-block" style="width: 400; height: 40px;" onclick="btndialog()">
                                    <b>SELANJUTNYA</b>
                                </button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8" style="top: 0rem">
                                <button type="button" class="btn btn-secondary btn-lg btn-block" style="width: 400; height: 40px;" onclick="kembali('dialog-pasien-lama')">
                                    <b style="color: #000;">KEMBALI</b>
                                </button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
      </div>
    </div>
</div>