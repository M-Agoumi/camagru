<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/assets/css/style.css">
	<title></title>
</head>
<body>
<div class="wrapper">
	<main class="page-body">
		<div class="container">
			<?php
    /** @var $user User */
    use core\Application;use core\Form\Form;use models\User;

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
        <?php endforeach;?>
    <?php endif;?>
		</div>
	</main>
</div>
<footer class="page-footer">Made with Love &#128420; By mohamed agoumi &copy; 2021</footer>
</body>
</html>
