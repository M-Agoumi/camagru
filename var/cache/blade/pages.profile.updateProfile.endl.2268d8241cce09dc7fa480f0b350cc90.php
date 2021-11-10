<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/style.css">
	<title><?=htmlspecialchars($title)?></title>
</head>
<body>
	<div class="wrapper">
		<?php include("/home/thatSiin/Desktop/camagru/views/layout/__header.php") ?>
		<main class="page-body">
			<div class="container">
																<?php
	use core\Application;use core\Form\Form;use models\User;

	/** @var $user User */
	?>
	<h1>Edit profile <?=$user->name?></h1>
	<?php $form = Form::begin(Application::path('user.update'), "POST", "update-form");
	echo $form->field($user, 'name');
	echo $form->field($user, 'username');
	echo $form->field($user, 'email');
	echo $form->submit('edit');
	Form::end();
	?>
	<a href="<?=Application::path('user.update.password')?>">
	    <h1 class="">update password</h1>
	</a>
			</div>
		</main>
	</div>
	<?php include("/home/thatSiin/Desktop/camagru/views/layout/__footer.php") ?>
	<script src="http://localhost:8000/assets/js/script.js"></script>
</body>
</html>
