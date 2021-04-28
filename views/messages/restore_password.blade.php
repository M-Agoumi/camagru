<?php
use core\Form\Form;
/** @var $user \models\User */
?>
<h1>restore your password</h1>

<?php
$form = Form::begin('', 'POST');
    echo $form->field($user, 'email')->emailField()->required();
    echo $form->submit('restore');
$form::end();