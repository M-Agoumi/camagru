<?php
use core\Application;
use core\Form\Form;
use models\User;
?>
<h1>test</h1>
<?php
    $form = Form::begin('', 'POST');
        echo $form->field($user, 'name');
        echo $form->submit('go');
    $form::end();
