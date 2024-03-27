@extends('registration.layoutNew')

@section('content-antrian-registration')
    <div class="col-lg-12 col-md-12 panelContentRegistration">
        <div class="col-lg-12 col-md-12 panel-antrian">
            <div class="border-kunjungan">
                <h3 class="text-center" style="color: #000; font-size: 14pt;"><b>Masukkan Nomor Referensi</b></h3><br>

                <label><b style="color: #000; margin-left: 40px; font-size: 11pt;">Pilih Kategori</b></label>
                <div class="text-center">
                    <label style="color: #000; font-size: 8pt;"><input type="radio" name="jenis_barang" id="ck_obat"  value="OBAT">&nbsp; Rujukan FKTP &nbsp;&nbsp;</label>
                    <label style="color: #000; font-size: 8pt;"><input type="radio" name="jenis_barang" id="ck_bmhp"  value="BMHP">&nbsp; Rujukan Internal &nbsp;&nbsp;</label>
                    <label style="color: #000; font-size: 8pt;"><input type="radio" name="jenis_barang" id="ck_alkes" value="ALKES">&nbsp; Kontrol &nbsp;&nbsp;</label>
                    <label style="color: #000; font-size: 8pt;"><input type="radio" name="jenis_barang" id="ck_alkes" value="ALKES">&nbsp; Rujukan Antar RS &nbsp;</label>
                </div>
                <div class="col-md-11" style="position: absolute; margin-left: 20px;">
                    <input type="text" name="no.referensi" id="no_referensi" class="form-control">
                </div>
                <a href="javascript:void(0);" onclick="cari()" class="col-xs-12 btn-referensi">
					<label style="color: #FFFFFF; font-family: Arial;  font-size: 14pt; font-style: normal; margin-top: 5px; margin-left: 150px;">Selanjutnya</label>
					{{-- <input type="hidden" id="ya" name="geriatri" value="Y"> --}}
				</a>
			</div>
        </div>
    </div>    
@stop

@section('script-registration')
	<script src="{!! url('AssetsAdmin/dist/js/jquery.PrintArea.js') !!}"></script>
	<script type="text/javascript">
	</script>
@stop