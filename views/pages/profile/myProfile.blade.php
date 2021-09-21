@layout('main')
@section('title'){{ title }} @endsection
@section('content')
	<?php
	/** @var $user User */
	?>

	<div class="my-profile">
		<div class="profile-cover"></div>
		<div class="profile-logo"></div>
		{{-- <h1>Welcome back <?=$user->name?></h1> --}}
		<div class="profile-user-info">
			<p><?=$user->name?></p>
			<p>Username: <?=$user->username?></p>
		</div>
		<div class="profile-parent">
			<div class="profile-info">
				<div class="user-info">
					<p><i class="fa fa-envelope"></i> <?=$user->email?></p>
					<p><i class="fa fa-check"></i> <?=$user->status ? 'Activated' : 'Not Activated'?></p>
					<p><i class="fa fa-calendar"></i> <?=explode(" ", $user->created_at)[0]?></p>
				</div>
				<div class="buttons">
					<a href="<?=route('user.edit')?>">Edit Information</a>
					<a href="<?=route('user.preferences')?>">update preferences</a>
				</div>
			</div>
			<div class="profile-posts">
				<h3>Posts</h3>
				<div class="post"></div>
				<div class="post"></div>
				<div class="post"></div>
				<div class="post"></div>
			</div>
		</div>
	</div>

@endsection


