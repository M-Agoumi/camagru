<?php use core\Application; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;0,700;1,400&display=swap" />
	<link rel="stylesheet" href="<?= asset("assets/css/font-awesome.min.css") ?>">
	<link rel="stylesheet" href="<?= asset("assets/css/style.css") ?>">
	<title>@yield('title')</title>
</head>
<body>
	<div class="wrapper">
		@include ('layout/__header.php')
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
				@yield('content')
			</div>
		</main>
	</div>
	@include("layout/__footer.php")
	<script src="<?= asset("assets/js/script.js") ?>"></script>
</body>
</html>
