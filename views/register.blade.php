<?php
/** @var $user User */
include_once Application::$ROOT_DIR . "/core/Form/Form.php";
?>
<title><?=$title ?? ''?></title>
<h1>Sign Up</h1>
<?php
$form = Form::begin('', "POST", "login-form");
echo $form->field($user, 'email')->emailField()->required()->setHolder('Example@email.com');
echo $form->submit('Sign up');
Form::end();
?>