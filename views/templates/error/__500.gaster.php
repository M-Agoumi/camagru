<?php /** @var $e Exception */ ?>
<!DOCTYPE html>
<html lang="en"><head>
	<title>{{ title }}</title>
</head>
<body style="text-align: center">
	<h1>500</h1>
	<h3>Internal Server Error</h3>
	<p>
	    please try again later, if the problem still exists contact <a href="/contactus">us</a> with
	    the next error code: <?=$errorCode ?? '500'?> and description of how you reached this page
	</p>
</body>
</html>
