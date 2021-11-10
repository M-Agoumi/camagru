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

	use core\Application;use core\Form\Form;use models\Post;

	/** @var  $post Post */

	$form = Form::begin(Application::path('camera.share'), "POST", "login-form");
	?>
	<img src="<?= Application::$APP->session->get('pictureData')?>" alt="">
	<?= $form->field($post, 'picture')->hiddentField()?>
	<?= $form->field($post, 'title')->setHolder('If you want it to have one :D') ?>
	<?= $form->text($post, 'comment')->setHolder('beautiful day in the beach?') ?>

	<?= $form->submit('Post'); ?>
	<?php Form::end();?>
			</div>
		</main>
	</div>
	<?php include("/home/thatSiin/Desktop/camagru/views/layout/__footer.php") ?>
	<script src="http://localhost:8000/assets/js/script.js"></script>
</body>
</html>
