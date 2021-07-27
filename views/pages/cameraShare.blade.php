<?php

use core\Application;use core\Form\Form;use models\Post;

/** @var  $post Post */

$form = Form::begin(Application::path('camera.share'), "POST", "login-form");
?>
<img src="<?= Application::$APP->session->get('pictureData')?>" alt="">
<?= $form->field($post, 'picture')->hiddentField()?>
<?= $form->field($post, 'title')->setHolder('If you want it to have one :D') ?>
<?= $form->text($post, 'comment')->setHolder('beautiful day in the beach?') ?>

<?= $form->submit('Post'); ?>
<?php Form::end();
