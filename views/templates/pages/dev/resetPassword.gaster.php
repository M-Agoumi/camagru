@layout('main')
@section('title')reset password@endsection
@section('content')
    <?php

    use Simfa\Form\Form;

    $form = Form::begin();
    echo $form->field($user, 'email')->required()->emailField();
    echo $form->field($user, 'password')->required()->passwordField();
    echo $form->submit('set');
    $form::end();
    ?>
@endsection
