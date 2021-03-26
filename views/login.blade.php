<?php
	/** @var $user User */
	include_once Application::$ROOT_DIR . "/core/Form/Form.php";
?>
<h1>Sign In</h1>
<?php
 	$form = Form::begin('/login', "POST", "login-form");
		echo $form->field($user, 'username', 'Username OR Email', 'John Dracula')->required();
		echo $form->field($user, 'password')->passwordField();
        echo $form->submit('Sign up');

    Form::end();
?>
<!--
<form method="POST" class="login-form">
    <div class="row">
        <div class="col-25">
            <label for="username">Username\Email</label>
        </div>
        <div class="col-75">
            <input type="text" id="username" name="username" placeholder="Example@email.com">
        </div>
    </div>
    <div class="row">
        <div class="col-25">
            <label for="password">Password</label>
        </div>
        <div class="col-75">
            <input type="password" id="password" name="password" placeholder="Your Password">
        </div>
    </div>
    <div class="row">
        <input type="submit" value="Sing in">
    </div>
</form>
-->