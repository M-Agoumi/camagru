<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/style.css">
	<title>Someone Commented On Your Post</title>
</head>
<body>
<div class="wrapper">
	<main class="page-body">
		<div class="container">
			<div style="background: darkgray; width: 200px; text-align: center; margin: auto; padding: 15px 20px; border-radius: 5px">
			<?=htmlspecialchars($name)?> commented on your post
			<a style="text-decoration: none; color: chartreuse;"
			   href='<?=$postUrl?>'>
				go to post
			</a>
		</div>
		</div>
	</main>
</div>
<footer class="page-footer">Made with Love &#128420; By mohamed agoumi &copy; 2021</footer>
</body>
</html>
