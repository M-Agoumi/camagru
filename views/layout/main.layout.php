<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="wrapper">
    <?php include "__header.php" ?>
    <main class="page-body">
        <div class="container">
			<?php if (Application::$APP->session->getFlash('success')): ?>
				<div class="alert alert-success" id="flash_message">
					<?=Application::$APP->session->getFlash('success')?>
					<a href="#" onclick="dismissMessage()">x</a>
				</div>
			<?php endif; ?>
            {{ body }}
        </div>
    </main>
</div>
    <?php include "__footer.php" ?>
	<script src="/assets/js/script.js"></script>
</body>
</html>