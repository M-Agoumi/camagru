<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="http://localhost:8000/assets/css/style.css">
	<title>Contact Us</title>
</head>
<body>
	<div class="wrapper">
		<?php include("/home/thatSiin/Desktop/camagru/views/layout/__header.php") ?>
		<main class="page-body">
			<div class="container">
																<?php
    use core\Application;
    use core\Form\Form;
    use models\ContactUs;
    ?>
    <h1>Contact Us</h1>

    <?php
    /** @var ContactUs $contact */
    $form = Form::begin(Application::path('contact.us'));
    echo $form->field($contact, 'title')->required();
    if (Application::isGuest())
        echo $form->field($contact, 'email')->required();
    else
        echo $form->field($contact, 'email')->disabled()->default(Application::$APP->user->email);
    echo $form->text($contact, 'content')->setLabel('Message')->required();
    echo $form->submit('Send');
    $form::end();

    ?>
			</div>
		</main>
	</div>
	<?php include("/home/thatSiin/Desktop/camagru/views/layout/__footer.php") ?>
	<script src="http://localhost:8000/assets/js/script.js"></script>
</body>
</html>
