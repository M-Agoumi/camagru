<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

</body>
</html>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="/assets/favicon.png">

	<title>@yield('title')</title>

	<!-- Bootstrap core CSS -->
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="/assets/css/cover.css" rel="stylesheet">

	<!-- my custom css -->
	<link href="/assets/css/custom.css" rel="stylesheet">
</head>

<body class="text-center">

<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
	<header class="masthead mb-auto">
		<div class="inner">
			<h3 class="masthead-brand">Camagru</h3>
			<nav class="nav nav-masthead justify-content-center">
				<a class="nav-link<?=\Simfa\Framework\Request::getSimpleUrl() == 'dashboard' ? ' active' : ''?>" href="/dashboard">Dashboard</a>
				<a class="nav-link<?=\Simfa\Framework\Request::getSimpleUrl() == 'emotes' ? ' active' : ''?>" href="/dashboard/emotes">Emotes</a>
				<a class="nav-link<?=\Simfa\Framework\Request::getSimpleUrl() == 'users' ? ' active' : ''?>" href="/dashboard/users">Users</a>
				<a class="nav-link<?=\Simfa\Framework\Request::getSimpleUrl() == 'posts' ? ' active' : ''?>" href="/dashboard/posts">Posts</a>
			</nav>
		</div>
	</header>

	<main role="main" class="inner cover">
		@yield('main')
	</main>

	<footer class="mastfoot mt-auto">
		<div class="inner">
			<p>Cover template for <a href="https://getbootstrap.com/">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
		</div>
	</footer>
</div>
</body>
</html>


