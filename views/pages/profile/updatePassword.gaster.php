@layout('main')
@section('title'){{ title }} @endsection
@section('content')
	<?php
	use core\Application;use core\Form\Form;use models\User;
	/** @var User $user */
	?>
	<h1>Update Password</h1>
	<?php
	$form = Form::begin(Application::path('user.update.password'));
	echo $form->field($user, 'password')->setLabel('Old password')->passwordField();
	?>
	<div class="row">
	    <div class="col-25">
	        <label for="password">New password</label>
	    </div>
	    <div class="col-75">
	        <input type="password" class="" id="newPassword" name="newPassword" placeholder="New Password">
	        <div class="invalid-feedback">
				<?= $user->getFirstError('newPassword'); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-25">
	        <label for="password">Retype new password</label>
	    </div>
	    <div class="col-75">
	        <input type="password" class="" id="retypePassword" name="retypePassword" placeholder="Retype New Password">
	        <div class="invalid-feedback">
				<?= $user->getFirstError('retypePassword'); ?>
	        </div>
	    </div>
	</div>
	<?php
		echo $form->submit('update');
		$form::end();
	?>
@endsection
