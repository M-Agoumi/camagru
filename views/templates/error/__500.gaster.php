@layout('main')
@section('title')500@endsection
@section('content')
	<h1>500</h1>
	<h3 style="text-align: center">Internal Server Error</h3>
	<p>
	    please try again later, if the problem still exists contact <a href="<?=route('contact.us')?>">us</a> with
	    the next error code: <?=$errorCode ?? '500'?> and description of how you reached this page
	</p>
@endsection
