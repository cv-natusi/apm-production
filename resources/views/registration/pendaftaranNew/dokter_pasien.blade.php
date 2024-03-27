<div class="modal fade" id="DokterPasienModal" tabindex="-1" role="dialog" aria-labelledby="JenisPasienModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-full-width" role="document" style="margin-top: 30px; width: 1200px;">
      <div>
        <div class="modal-body" style="height: 580px; background: rgba(217, 236, 214, 0.75) !important;">
            <input type="hidden" name="dokternik" id="dokternik" value="">
            <input type="hidden" name="dokterrm" id="dokterrm" value="">
            <input type="hidden" name="dokterbpjs" id="dokterbpjs" value="">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8 text-center" style="margin-top: 1rem">
                    <h3>
                        <b style="font-size: 20pt; color: #ffffff;">JADWAL DOKTER HARI INI</b>
                    </h3>
                </div>
                <div class="col-md-2"></div>
            </div>
            <div class="row">
                <div class="col-md-2 text-center">
                    <div onclick="kembali('pilih_dokter')" style="margin-top: 30px;">
                        <img src="{{ asset('aset/images/arrow_left.png') }}" alt="">
                        <div>
                            <p style="color: #000; font-weight: bold;">KEMBALI</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered" style="margin-top: 3rem; color: #000;">
                        <thead>
                            <tr>
                                <td>Kode Poli</td>
                                <td>Nama Dokter</td>
                                <td>Jadwal Dokter</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody id="tempatData">

                        </tbody>
                    </table>
                </div>
                <div class="col-md-2"></div>
            </div>
            <!-- <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8" style="margin-top: 20px;">
                    <button type="button" class="btn btn-secondary btn-lg btn-block" style="width: 100%; height: 50px;" onclick="kembali('pilih_dokter')">
                        <b style="color: #000;">KEMBALI</b>
                    </button>
                </div>
                <div class="col-md-2"></div>
            </div> -->

        </div>
      </div>
    </div>
</div>