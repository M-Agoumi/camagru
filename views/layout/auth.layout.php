<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/style.css">
    <title><?=$title?></title>
</head>
<body>
<div class="wrapper">
    <main class="page-body">
        <div class="container">
            {{ body }}
        </div>
    </main>
</div>
    <?php include "__footer.php" ?>
</body>
</html>