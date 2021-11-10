<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/style.css">
	<title>Reset Your Password</title>
</head>
<body>
<div class="wrapper">
	<main class="page-body">
		<div class="container">
			<div>
        Someone just asked us to rest your password, if it's you please follow the next link
        <a style="text-decoration: none; color: chartreuse;" href='http://localhost:<?=$port?>/verify-token/<?=$token?>'>
            <div style="background: darkgray; width: 200px; text-align: center; margin: auto; padding: 15px 20px; border-radius: 5px">
                reset your password
            </div>
        </a>
        if it's not you, just ignore this email, or maybe click it to change your password to be secure :D
    </div>
		</div>
	</main>
</div>
<footer class="page-footer">Made with Love &#128420; By mohamed agoumi &copy; 2021</footer>
</body>
</html>
