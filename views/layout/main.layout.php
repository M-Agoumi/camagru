<?php use core\Application; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?= asset("assets/css/font-awesome.min.css") ?>">
	<link rel="stylesheet" href="<?= asset("assets/css/style.css") ?>">
	<title>{{ title }}</title>
</head>
<body>
<div class="wrapper">
	<?php include "__header.php" ?>
	<main class="page-body">
		<div class="container">
			<?php if (Application::$APP->session->getFlash('success')): ?>
				<div class="alert alert-success" id="flash_message">
					<?= Application::$APP->session->getFlash('success') ?>
					<span href="#" onclick="dismissMessage()">x</span>
				</div>
			<?php endif; ?>
			<?php //todo optimize this ?>
			<?php if (Application::$APP->session->getFlash('error')): ?>
				<div class="alert alert-error" id="flash_message">
					<?= Application::$APP->session->getFlash('error') ?>
					<span href="#" onclick="dismissMessage()">x</span>
				</div>
			<?php endif; ?>
			{{ body }}
		</div>
	</main>
</div>
<?php include "__footer.php" ?>
<script src="<?= asset("assets/js/script.js") ?>"></script>
</body>
</html>
