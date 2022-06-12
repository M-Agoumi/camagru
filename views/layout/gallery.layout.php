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
	<link rel="stylesheet" href="<?= asset("assets/css/style.css") ?>">
	<link rel="stylesheet" href="<?= asset("assets/css/gallery.css") ?>">
	<title>@yield('title')</title>
</head>
<body>
	<noscript>
		<META HTTP-EQUIV="Refresh" CONTENT="0;URL=/javascript-disabled">
	</noscript>
	<div id="cookies_not_allowed" style="background: #FFEA61; width: 100%; font-weight: bold; text-align: center; padding: 3px; display: none">
		please allow access to cookies, cause they are essential for this application to work properly
	</div>
	@include ('layout/__header.php')
	<main class="page-body">
		@include ('layout/__messages.php')
	</main>
	@yield('content')
	<div style="position: fixed; bottom: 0; width: 100%">
		@include("layout/__footer.php")
	</div>
	<script src="<?= asset("assets/js/script.js") ?>"></script>
</body>
</html>
