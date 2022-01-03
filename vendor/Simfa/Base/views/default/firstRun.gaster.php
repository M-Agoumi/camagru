<!DOCTYPE html>
<html lang="en">
<head>
	<title>Getting Started</title>
</head>
<body style="
background: #73C8A9;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #373B44, #73C8A9);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #373B44, #73C8A9); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
	<div style="position: absolute; left: 50%; top: 40%; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%);-moz-transform:translate(-50%, -50%)">
		<h1 style="text-align: center">Welcome in Simfa framework</h1>
		<p style="margin: auto; width: 500px; font-size: medium; font-family: Arial,serif; font-weight: bold;text-align: center; color: #FFF">
			you are seeing this page because you didn't set any routes for your page yet, head to routes/<?=
			\Simfa\Framework\Application::$APP->interface?>.php and do some magic ;-)</p>
	</div>
</body>
</html>
