<?php use Simfa\Framework\Application; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;0,700;1,400&display=swap" />
	<link rel="stylesheet" href="/assets/css/fontawesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
	<link rel="stylesheet" href="<?= asset("assets/css/style.css") ?>">
	<title>@yield('title') - Camagru</title>
	@yield('head')
</head>
<body>
	<noscript>
		<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/javascript-disabled">
	</noscript>
	<div id="cookies_not_allowed" style="background: #FFEA61; width: 100%; font-weight: bold; text-align: center; padding: 3px; display: none">
		please allow access to cookies, because they are essential for this application to work properly
	</div>
	<div class="wrapper">
		@include ('layout/__header.php')
		<main class="page-body">
			<div class="container">
				@include ('layout/__messages.php')
				@yield('content')
			</div>
		</main>
	</div>
	@include("layout/__footer.php")
	<script src="<?= asset("assets/js/script.js") ?>"></script>
</body>
</html>
