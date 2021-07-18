<?php
	/** @var $user User */
	use core\Application;
	use core\Form\Form;
	use models\User;

?>
<h1>Sign In</h1>
<?php
    $url = $_GET['ref'] ?? '/';
 	$form = Form::begin(Application::path('auth.auth') . "?ref=" . $url, "POST", "login-form");
		echo $form->field($user, 'username', 'Username OR Email')->required()->setHolder('John Dracula');
		echo $form->field($user, 'password')->passwordField();
        echo $form->submit('Sign in');

    Form::end();
?>
<h1>
    <a href="<?=Application::path('auth.restore')?>">restore password</a>
</h1>
<?php if(!empty($users)):?>
<h1>login to saved accounts</h1>
<?php
    /** @var array $users */
    foreach ($users as $user): ?>
        <div>
            <h3>
                <a href="<?=route('auth.magic.login', $user->token)?>">
                    <?=$user->user->name?>
                </a>
            </h3>
        </div>
    <?php endforeach;
endif;
