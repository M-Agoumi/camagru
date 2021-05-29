<?php
use core\Application;
use core\Form\Form;

/** @var $user \models\User */
?>
<h1>Edit profile <?=$user->name?></h1>
<?php $form = Form::begin(Application::path('user.update'), "POST", "update-form");
        echo $form->field($user, 'name');
        echo $form->field($user, 'username');
        echo $form->field($user, 'email');
        echo $form->submit('edit');
    Form::end();
?>
<a href="<?=Application::path('user.update.password')?>">
    <h4 class="center">update password</h4>
</a>