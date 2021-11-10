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

	use core\Form\Form;use models\core\languages;

	$lang = New languages();

	?>
	<h1>Preferences</h1>

	<?php
	$form = Form::begin();
	echo $form->select($pref, 'language', $lang);
	echo $form->select($pref, 'commentsMail', ['1' => 'yes', '0' => 'no'])->setLabel('receive notification emails');
	echo $form->submit('Save');
	$form::end();
	?>
			</div>
		</main>
	</div>
	<?php include("/home/thatSiin/Desktop/camagru/views/layout/__footer.php") ?>
	<script src="http://localhost:8000/assets/js/script.js"></script>
</body>
</html>
