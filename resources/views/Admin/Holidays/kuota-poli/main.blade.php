<div class="tab-pane fade in active" id="main-kuota-poli">
	<div
		id="content-kuota-poli"
	>
		<div class="row" style="margin-bottom: 1rem;">
			<div class="col-md-4 col-sm-4 col-xs-12 form-inline main-layer">
				<button type="button" class="btn btn-sm btn-primary" onclick="addForm('kuota-poli')">
					<span class="fa fa-plus"></span> &nbsp Kuota Poli
				</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					{{-- <table class="table table-striped b-t b-light" id="table-kuota-poli"> --}}
					{{-- <table class="table table-striped" id="table-kuota-poli"> --}}
					<table
						id="table-kuota-poli"
						class="table table-striped"
						style="
							width: 100%;
							overflow-x: auto;
							white-space: nowrap;
						"
					>
						<thead>
							<tr>
								<th>No</th>
								<th class="text-center">Tanggal / Hari</th>
								<th>Poli</th>
								<th class="text-center">Kuota WA</th>
								<th class="text-center">Kuota KIOSK</th>
								<th class="text-center">Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div
		class="other-kuota-poli"
		id="other-kuota-poli"
		style="display: none;"
	>
	</div>
</div>