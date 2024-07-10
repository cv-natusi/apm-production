<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height: 0px;">
	<div class="box box-primary add-form">
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="formAdd">
					<input type="hidden" name="id" value="{{ (!empty($data)) ? $data->kdpoli_rs : '' }}">
					<div class="row">
						<div class="col-md-12">
							<label>NAMA POLI</label>
							<select class="form-control" name="poli" id="poli" >
								<option value="">- PILIH POLI -</option>
								@if($poli->count()!=0)
								@foreach ($poli as $p)
								<option @if(!empty($data) && $p->kdpoli_rs == $data->kdpoli_rs) selected @endif value="{{$p->kdpoli_rs}}">{{$p->tm_poli->NamaPoli}}</option>
								@endforeach
								@endif
							</select>
						</div>
						<div class="col-md-12">
							<label>Kode Awalan</label>
							<input type="text" name="kodeawalan" class="form-control" value="{{ !empty($data->tm_poli->kode_awalan_poli->kode_awal) ? $data->tm_poli->kode_awalan_poli->kode_awal : ''}}">
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
			url: "{{route('mstpoli-store')}}",
			type: 'POST',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data) {
			if (data.code == 200) {
				swal("Success!", data.message, data.status);
				$('.other-page').fadeOut(function() {
					$('.other-page').empty();
					$('.main-layer').fadeIn();
					$('#dataTable').DataTable().ajax.reload();
				});
			}else{
				swal('Gagal!', data.message, data.status);
			}
		}).fail(function() {
			swal("MAAF!", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
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
				// var html = ''
				// html += '<tr id="kd_'+kdpoli+'">'
				// html += '<td>'+kdpoli+'</td>'
				// html += '<td>'+nmpoli+'</td>'
				// html += '<td>'
				// html += '<button type="button" class="btn btn-danger" onclick="del_poli(+kdpoli+)"><i class="fa fa-times"></i></button>'
				// html += '</td>'
				// html += '</tr>'
				var markup = "<tr id='kd_"+kdpoli+"'><td>" + kdpoli + "</td><td>" + nmpoli + "</td><td><button type='button' class='btn btn-danger' onclick='del_row_poli("+kdpoli+")'><i class='fa fa-times'></i></button></td></tr>";
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
				
				swal(data.status, data.message, data.title);
			} else {
				swal(data.status, data.message, data.title);
			}
		})
	}
</script>