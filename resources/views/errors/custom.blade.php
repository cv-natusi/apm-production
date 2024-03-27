<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{(isset($code)?$code:500).' '.(isset($title)?$title:'Internal server error')}}</title>
    @include('include.errors.style')
</head>
<body>
	<!-- partial:index.partial.html -->
	<div class="container">
		<div class="row">
			<div class="xs-12 md-6 mx-auto">
				<div id="countUp">
					{{-- <div class="number" data-count="@yield('http-request')">0</div> --}}
					<div class="number" data-count="{{isset($code)?$code:500}}">&nbsp;</div>
					<div class="text">{!!isset($title)?$title:'Internal server error'!!}</div>
					<div class="text">{!!(isset($message)?$message:'')!!}</div>
					<div class="text">{!!isset($detail)?$detail:''!!}</div>
                    @if(isset($code) && $code==404)
                    <a href="#" onclick="history.back()">kembali</a>
                    @endif
				</div>
			</div>
		</div>
	</div>
    @include('include.errors.script')
</body>
</html>
