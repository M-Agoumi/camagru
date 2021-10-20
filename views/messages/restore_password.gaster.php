@layout('main')
@section('title')Reset Your Password@endsection
@section('content')
    <?php
    use core\Form\Form;use models\Password_reset;
    /** @var $password Password_reset */
    ?>
    <h1>Reset your password</h1>

    <?php
    $form = Form::begin('', 'POST');
        echo $form->field($password, 'email')->emailField()->required();
        echo $form->submit('restore');
    $form::end();
    ?>
@endsection
