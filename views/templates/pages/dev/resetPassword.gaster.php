@layout('main')
@section('title')reset password@endsection
@section('content')
    <?php

    use Simfa\Form\Form;

    $form = Form::begin($user);
    echo $form->field('username')->required();
    echo $form->field('password')->required()->passwordField();
    echo $form->submit('set');
    $form::end();
    ?>
@endsection
