@layout('main')
@section('title')Reset Your Password@endsection
@section('content')
    <?php
    use Simfa\Form\Form;

    /** @var $password Model\Password_reset */
    ?>
    <h1>Reset your password</h1>

    <?php
    $form = Form::begin($password, '', 'POST');
        echo $form->field('email')->emailField()->required();
        echo $form->submit('restore');
    $form::end();
    ?>
@endsection
