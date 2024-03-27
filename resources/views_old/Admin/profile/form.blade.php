<div class="box box-warning" id='panel-add'>
	<button type="button" class="btn btn-warning btn-cancel"><span class="fa fa-chevron-left"></span> Kembali</button>
	<hr>
	<form method='post' action="{{ route('doUpdatePasswordAdmin') }}" enctype='multipart/form-data'>
		{{ csrf_field() }}
		<div class="col-md-12" style="padding: 0px">
			<div class="form-group" style="padding: 10px 0px">
				<label class="col-lg-4 col-md-4">
					Password Baru
				</label>
				<div class="col-lg-8 col-md-8">
					<input id="baru" class="form-control" type="password" name="baru" required>
				</div>
			</div>
			<div class="clearfix" style="margin-bottom: 10px"></div>
			<div class="form-group" style="padding: 10px 0px">
				<label class="col-lg-4 col-md-4">
					Ulangi
				</label>
				<div class="col-lg-8 col-md-8">
					<input id="ulang" class="form-control" type="password" required>
					<p id="hasil"></p>
				</div>
			</div>
			<div class="clearfix" style="margin-bottom: 10px"></div>
			<div class="form-group" style="padding: 10px 0px">
				<label class="col-lg-4 col-md-4">
					Password Lama
				</label>
				<div class="col-lg-8 col-md-8">
					<input type="password" class="form-control" name="lama" required>
				</div>
			</div>
			<div class="clearfix" style="margin-bottom: 10px"></div>
			<div class="form-group">
				<div class="col-lg-4 col-md-4">
					<input type="submit" id="simpan" disabled class="btn btn-primary" value="Save" style="width:80%;padding: 20px">								
				</div>
			</div>
			<div class="clearfix" style="margin-bottom: 10px"></div>
		</div>
		<div class='clearfix'></div>
	</form>
</div>

<link rel="stylesheet" href="{{url('tag_input/bootstrap-tagsinput.css')}}">
<script src="{{url('tag_input/bootstrap-tagsinput.js')}}"></script>
<script src="{{url('tag_input/bootstrap3-typeahead.js')}}"></script>

<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/ckeditor.js') !!}"></script>
<script src="{!! url('AssetsAdmin/dist/js/ckeditor1/adapters/jquery.js') !!}"></script>
<script type="text/javascript">

	
	$('#ulang').keyup(function(){
		var baru = $('#baru').val();
		var ulang = $('#ulang').val();
		if(ulang){
			if(ulang==baru){
				$('#hasil').html("Terima Kasih");
				$('#simpan').removeAttr('disabled');
			}else{
				$('#hasil').html("Maaf tidak sama");
				$('#simpan').attr('disabled','disabled');
			}
		}else{
			$('#hasil').html("Harap diisi sesuai password baru");
			$('#simpan').attr('disabled','disabled');
		}
	});

	$('#category').tagsinput({
	  typeahead: {
	    source: ['Mojokerto','Jombang','Kediri','Pare']
	  },
	  freeInput: true
	});
	
	$('#category').on('itemAdded', function(event) {
	    setTimeout(function(){
	        $(">input[type=text]",".bootstrap-tagsinput").val("");
	    },1);
	});

    $('.btn-cancel').click(function(e){
    	e.preventDefault();
    	$('#panel-add').animateCss('bounceOutDown');
    	$('.other-page').fadeOut(function(){
    		$('.other-page').empty();
            $('.main-layer').fadeIn();
        });
    });
</script>