<style type="text/css">
	#modalDialog{
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		height: 90%;
		width: 80%;
	}
	.cetakUlang{
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		border-radius: 5px;
		width: 50%;
		height: 70%;
		background: #FFFFFF;
	}
	.headerCU{
		background: #2CBA44;
		width: 100%;
		position: relative;
		left: 15px !important;
		border-radius: 5px 5px 0px 0px;
	}
	.backCU{
		position: relative;
		top: 4%;
		left: 55%;
		transform: translate(-48%, -50%);
	}
	.confirmCU{
		position: relative;
		color: #FFFFFF;
		font-family: Arial;
		font-size: 12px;
		font-style: normal;
		top: 0%;
		left: 55%;
		transform: translate(-48%, -50%);
	}
	.centerInputCU{
		position: relative;
		top: 28%;
		left: 57%;
		transform: translate(-50%, -50%);
		width: 100%;
	}
</style>
<div class="modal fade" id="cetakUlang" tabindex="-1" role="dialog" aria-labelledby="CetakUlangModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-full-width" id="modalDialog" role="document">
		<div class="modal-body" style="height: 70%; background: rgba(217, 236, 214, 0.75) !important;">
			<div class="cetakUlang">
				<div class="row headerCU">
					<div class="col-md-12 text-center">
						<span style="color: #ffffff;">
							<b>TEMUKAN DATA ANTRIAN</b>
						</span>
					</div>
				</div>
				<div class="row centerInputCU" style="height:50%; margin-top:3rem;">
					<div class="col-md-12">
						{{-- <div class=""> --}}
							<label style="color: #000; font-size: 10pt;" class="pull-left">Masukkan Kode Booking Anda</label>
							<input type="text" name="kodeCetakUlang" id="kodeCetakUlang" class="form-control" style="width:90%;">
						{{-- </div> --}}
					</div>
				</div>
				<div class="row confirmCU">
					<div class="col-md-12">
						<button type="button" class="btn btn-primary text-center" id="cariCetakUlang" style="width: 90%;"> KONFIRMASI </button>
					</div>
				</div>
				<div class="row backCU">
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary text-center" style="width: 90%; color: #000;" onclick="kembali('cetakUlang')"> KEMBALI </button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>