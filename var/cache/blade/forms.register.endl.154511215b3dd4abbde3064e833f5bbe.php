<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/style.css">
	<title></title>
</head>
<body>
	<div class="wrapper">
		<?php include("/home/thatSiin/Desktop/camagru/views/layout/__header.php") ?>
		<main class="page-body">
			<div class="container">
																<?php
    /** @var $user User */
    use core\Form\Form;
    ?>
    <h1>You almost there just fill those fields and welcome onboard</h1>
    <?php
    $form = Form::begin(route('auth.insertUser'), "POST", "login-form");
    echo $form->field($user, 'name', 'Full Name')->setHolder('John Dracula')->required();
    echo $form->field($user, 'email', 'Email')
        ->default($email)/** @var $email */
        ->disabled()
        ->emailField();
    echo $form->field($user, 'username')->required();
    echo $form->field($user, 'password')->passwordField()->required();
    echo $form->submit('Sign up');

    Form::end();
    ?>
			</div>
		</main>
	</div>
	<?php include("/home/thatSiin/Desktop/camagru/views/layout/__footer.php") ?>
	<script src="http://localhost:8000/assets/js/script.js"></script>
</body>
</html>
