<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
	<div class="box box-primary add-form">
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="formAdd">
					<input type="hidden" name="id" value="{{ (!empty($data)) ? $data->id : '' }}">
					{{-- JIKA FORM EDIT --}}
					@if ($menu == 'Edit')
					<input type="hidden" name="user_id" value="{{$data->user_id}}">
					@endif
					<div class="row">
						<div class="col-md-12">
							<label>Nama Konter Poli</label>
							<input type="text" name="konterpoli" id="konterpoli" class="form-control" value="{{ (!empty($data)) ? $data->nama_konterpoli : '' }}">
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-11">
									<label><strong>Melayani Poli</strong></label><br>
									<select class="form-control" multiple data-live-search="true" name="poli_id[]" id="poli_id" style="height: 500px;">
										<option disabled value="">- PILIH POLI -</option>
										@if ($poli->count()!=0)
											@foreach ($poli as $poli)
												@if(!empty($poli->tm_poli))
													<option  value="{{$poli->kdpoli_rs}}">{{$poli->tm_poli->NamaPoli}}</option>
												@endif
											@endforeach
										@endif
									</select>
								</div>
								<div class="col-md-1" style="top: 25px;">
									<button type="button" id="add" class="btn btn-primary"><i class="fa fa-plus"></i></button>
								</div>
							</div>
						</div>

						<!-- JIKA FORM TAMBAH -->
						@if ($menu == 'Tambah')
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<label><strong>Pilih User</strong></label><br>
									<select class="form-control" name="user_id" id="user_id" style="height: 500px;">
										<option selected value="">- PILIH COUNTER -</option>
										@if (!empty($getUser))
											@foreach ($getUser as $usr)
												<option  value="{{$usr->id}}">{{$usr->email}}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
						</div>
						@endif

						<!-- JIKA FORM EDIT -->
						@if ($menu == 'Edit')
						<div class="col-md-12" style="margin-top: 1rem;">
							<table id="table_poli" class="table table-bordered mt-2">
								<thead>
									<tr>
										<th>Kode Poli</th>
										<th>Nama</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody id="tempatData">
									@foreach (!empty($data->trans_konter_poli)?$data->trans_konter_poli:[] as $dt)
									<tr id="kd_{{$dt->tm_poli->KodePoli}}">
										<td>{{$dt->tm_poli->KodePoli}}</td>
										<td>{{$dt->tm_poli->NamaPoli}}</td>
										<td>
											<button type="button" class="btn btn-danger" onclick="del_poli(`{{$dt->tm_poli->KodePoli}}`)"><i class="fa fa-times"></i></button>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						@endif
						<div class="col-md-12">
							<label>URL Video</label>
							<input type="text" name="url" id="url" class="form-control" value="{{ (!empty($data)) ? $data->url : '' }}">
						</div>
					</div>
				</form>
			</div>
			<div class="panel-footer">
				<button type="button" class="btn btn-sm btn-warning btn-cancel">Kembali</button>
				<input type="submit" value="Simpan" class="btn btn-sm btn-success btn-store">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('select').selectpicker();
	});

	// BTN KEMBALI
	$('.btn-cancel').click(function(e) {
		e.preventDefault();
		$('.add-form').fadeOut();
		$('.other-page').fadeOut(function() {
			$('.other-page').empty();
			$('.main-layer').fadeIn();
		});
	});

	// BTN SIMPAN
	$('.btn-store').click(function(e) {
		e.preventDefault();
		var data = new FormData($('.formAdd')[0]);
		$.ajax({
			url: "{{route('mstkonterpoli-store')}}",
			type: 'POST',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data) {
			if (data.status == 'success') {
				swal("Success!", 'Data Berhasil Disimpan', "success");
				$('.other-page').fadeOut(function() {
					$('.other-page').empty();
					$('.main-layer').fadeIn();
					$('#dataTable').DataTable().ajax.reload();
				});
			} else if (data.status == 'error') {
				$('.btn-store').val('Simpan').removeAttr('disabled');
				swal('Sorry!', 'Data Gagal Disimpan', 'warning');
			}
		}).fail(function() {
			swal("MAAF!", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
			$('.btn-store').val('Simpan').removeAttr('disabled');
		});
	});

	// ADD POLI
	$('#add').click(function() {
		var id = $('#poli_id').val();
		var tindakan = '';
		$.ajax({
			url: "{{ route('get-poli') }}",
			type: 'POST',
			data: {
				id: id
			},
		}).done(function(data) {
			$.each(data, function (index, v) {
				var kdpoli = v.KodePoli;
				var nmpoli = v.NamaPoli;
				var markup = "<tr id='kd_"+kdpoli+"'><td>" + kdpoli + "</td><td>" + nmpoli + "</td><td><button type='button' class='btn btn-danger' onclick='del_row_poli(`"+kdpoli+"`)'><i class='fa fa-times'></i></button></td></tr>";
				$("#tempatData").append(markup);
			});
		}).fail(function() {
			Swal.fire("MAAF!", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
		});
	});

	// DELETE ROW
	function del_row_poli(kdpoli) {
		$('#kd_'+kdpoli).remove();
	}

	// DELETE POLI
	function del_poli(id) {
		$.post("{{route('delete-poli')}}", {id:id}).done((data) => {
			if (data.status == 'success') {
				$('#kd_'+id).remove();
				swal(data.title, data.message, data.status);
			} else {
				swal(data.title, data.message, data.status);
			}
		})
	}
</script>