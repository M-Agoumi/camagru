<?php
	use core\Form\Form;
/** @var $user User */
?>
<title><?=$title ?? ''?></title>
<h1>Sign Up</h1>
<?php
$form = Form::begin('', "POST", "login-form");
echo $form->field($user, 'email')->emailField()->required()->setHolder('Example@email.com');
echo $form->submit('Sign up');
Form::end();
?>