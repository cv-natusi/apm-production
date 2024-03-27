<div class="row" id="divrowPraktek" style="">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-calendar"></i>
				<h3 class="box-title">Jadwal Praktek Rumah Sakit</h3>
			</div>
			<div class="box-body">
				<div id="divSpesialis">
					<div class="alert alert-info alert-dismissible">
						<p>
							1. Untuk Melihat Jadwal Praktek Dokter klik Nama Spesialis/SubSpesialis<br>
							2. Jumlah Rencana Kontrol Merupakan Penjumlahan dari Rencana Kontrol Spesialis/Subspesialis Per Tanggal<br>
							3. Kapasitas Merupakan Jumlah Maksimal Layanan yang Dapat dilayani Oleh Spesialis/Subspesialis<br>
						</p>
					</div>
					<div id="tblSpesialis_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<div class="row">
							<div class="col-sm-6"></div>
							<div class="col-sm-6">
								<div id="tblSpesialis_filter" class="dataTables_filter">
									<label>
										Search:
										<input type="search" class="form-control input-sm" placeholder="" aria-controls="tblSpesialis">
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-striped dataTable no-footer" id="tblSpesialis" style="font-size: small; width: 100%;" role="grid" aria-describedby="tblSpesialis_info" cellpadding="0">
									<thead>
										<tr role="row">
											<th class="sorting_asc" tabindex="0" aria-controls="tblSpesialis" rowspan="1" colspan="1" style="width: 55px;" aria-label="No: activate to sort column descending" aria-sort="ascending">No</th>
											<th class="sorting" tabindex="0" aria-controls="tblSpesialis" rowspan="1" colspan="1" style="width: 433px;" aria-label="Nama Spesialis/Sub: activate to sort column ascending">Nama Spesialis/Sub</th>
											<th class="sorting" tabindex="0" aria-controls="tblSpesialis" rowspan="1" colspan="1" style="width: 121px;" aria-label="Kapasitas: activate to sort column ascending">Kapasitas</th>
											<th class="sorting" tabindex="0" aria-controls="tblSpesialis" rowspan="1" colspan="1" style="width: 139px;" aria-label="Jml.Rencana Kontrol &amp;amp; Rujukan: activate to sort column ascending">Jml.Rencana Kontrol &amp; Rujukan</th>
											<th class="sorting" tabindex="0" aria-controls="tblSpesialis" rowspan="1" colspan="1" style="width: 129px;" aria-label="Prosentase: activate to sort column ascending">Prosentase</th>
										</tr>
									</thead>
									<tbody>
										<tr role="row" class="odd">
											<td class="sorting_1">1</td>
											<td>
												<div class="btn-group"> 
													<button type="button" class="btn btnViewSpesialis btn-default btn-xs" title="Klik Lihat Jadwal Praktek">Urologi </button> 
												</div>
											</td>
											<td>70</td>
											<td>4</td>
											<td>5.71</td>
										</tr>
									</tbody>
								</table>
								<div id="tblSpesialis_processing" class="dataTables_processing" style="display: none;">
									Processing...
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5">
								<div class="dataTables_info" id="tblSpesialis_info" role="status" aria-live="polite">
									Showing 1 to 1 of 1 entries
								</div>
							</div>
							<div class="col-sm-7">
								<div class="dataTables_paginate paging_simple_numbers" id="tblSpesialis_paginate">
									<ul class="pagination">
										<li class="paginate_button previous disabled" id="tblSpesialis_previous"><a href="#" aria-controls="tblSpesialis" data-dt-idx="0" tabindex="0">Previous</a></li>
										<li class="paginate_button active"><a href="#" aria-controls="tblSpesialis" data-dt-idx="1" tabindex="0">1</a></li>
										<li class="paginate_button next disabled" id="tblSpesialis_next"><a href="#" aria-controls="tblSpesialis" data-dt-idx="2" tabindex="0">Next</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>    <button class="btn btn-danger" id="btnBatalJadwalRujKontrol" type="button"> <i class="fa fa-undo"></i> Batal</button>

	</div>
</div>