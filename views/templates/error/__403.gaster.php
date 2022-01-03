@layout('main')
@section('title')<?=$title ?? ''?>@endsection
@section('content')
	<?php /** @var $e Exception */ ?>
	<h1><?php echo $e->getCode(); ?></h1>
	<h3 class="center">Access denied</h3>
	<p>
		<?=$e->getMessage()?><br>
	    please try again later, if you think this is a mistake please contact <a href="/contactus">us</a> with
	    the next error code: <?=$errorCode ?? 'E0102b'?>
	</p>
@endsection
