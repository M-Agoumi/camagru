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
<form method="POST" class="login-form">
    <div class="row">
        <div class="col-25">
            <label for="email">Email</label>
        </div>
        <div class="col-75">
            <input type="email" id="email" name="email" placeholder="Example@email.com">
        </div>
    </div>
    <div class="row">
        <input type="submit" value="Sign up">
    </div>
</form>