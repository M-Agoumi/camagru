@layout('main')
@section('title')<?= $e->getCode()?>@endsection
@section('content')
	<h1><?= $e->getCode()?></h1>
	<h3 style="text-align: center">Page Not Found</h3>
	<p>
		<?=$e->getMessage()?> on this server,<br>
	    if you typed it manually please recheck it, or if you think this is a mistake please contact <a
	            href="/contactus">us</a> with
	    the next error code: <?=$errorCode ?? 'E0106c'?>
	</p>
@endsection
