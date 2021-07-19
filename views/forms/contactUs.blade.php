<?php

use core\Application;use core\Form\Form;use models\ContactUs;

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
