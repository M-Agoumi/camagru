<?php
	/** @var $user User */
	use core\Application;
	use core\Form\Form;
	use models\User;

?>
<h1>Sign In</h1>
<?php
 	$form = Form::begin(Application::path('auth.auth2') . "?ref=". $_GET['ref'], "POST", "login-form");
		echo $form->field($user, 'username', 'Username OR Email')->required()->setHolder('John Dracula');
		echo $form->field($user, 'password')->passwordField();
        echo $form->submit('Sign in');

    Form::end();
?>
<div class="center">
    <a href="<?=Application::path('auth.restore')?>">restore password</a>
</div>
