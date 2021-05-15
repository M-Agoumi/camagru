<?php

use core\Form\Form;
use core\Application;

/** @var  $post \models\Post */

$form = Form::begin(Application::path('camera.share'), "POST", "login-form");
?>
    <img src="<?= Application::$APP->session->get('pictureData')?>" alt="">
    <?= $form->field($post, 'picture')->hiddentField()?>
    <?= $form->field($post, 'title')->setHolder('If you want it to have one :D') ?>
    <?= $form->text($post, 'comment')->setHolder('beautiful day in the beach?') ?>

    <?= $form->submit('Post'); ?>
<?php Form::end();
