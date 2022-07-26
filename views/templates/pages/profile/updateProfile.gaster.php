@layout('main')
@section('title'){{ title }} @endsection
@section('content')
	<?php

	use Simfa\Form\Form;
	use Simfa\Framework\Application;

	/** @var $user Model\User */
	?>
	<h1>Edit profile <?=$user->name?></h1>
	<?php $form = Form::begin(Application::path('user.edit'), "POST", "update-form");
	echo $form->field($user, 'name');
	echo $form->field($user, 'username');
	echo $form->field($user, 'email');
	echo $form->submit('edit');
	Form::end();
	?>
	<a href="<?=Application::path('user.update.password')?>">
	    <h1 class="">update password</h1>
	</a>
@endsection
