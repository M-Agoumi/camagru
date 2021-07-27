<?php
use core\Form\Form;use models\Password_reset;
/** @var $password Password_reset */
?>
<h1>restore your password</h1>

<?php
$form = Form::begin('', 'POST');
echo $form->field($password, 'email')->emailField()->required();
echo $form->submit('restore');
$form::end();
