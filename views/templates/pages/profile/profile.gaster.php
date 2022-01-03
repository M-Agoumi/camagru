@layout('main')
@section('title'){{ title }} @endsection
@section('content')
	<?php
	use Simfa\Framework\Application;
	?>
    <h1>Welcome to <?=$user->name?> profile</h1>
    <p>Name: <?=$user->name?></p>
    <p class="center">
        already friends? <a href="<?=Application::path('auth.login')?>">login</a>
        to see his complete profile
    </p>
@endsection
