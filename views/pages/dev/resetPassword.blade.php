<?php

use core\Form\Form;

$form = Form::begin();
echo $form->field($user, 'email')->required()->emailField();
echo $form->field($user, 'password')->required()->passwordField();
echo $form->submit('set');
$form::end();
