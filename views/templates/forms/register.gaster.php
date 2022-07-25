@layout('main')
@section('title')@endsection
@section('content')

    <?php
    /** @var $user Model\User */
    use Simfa\Form\Form;
    ?>
    <h1>You almost there just fill those fields and welcome onboard</h1>
    <?php
    $form = Form::begin(route('auth.insertUser'), "POST", "login-form");
    echo $form->field($user, 'name')->setHolder('John Dracula')->required()->setLabel('Full Name');
    echo $form->field($user, 'email')
        ->value($email)/** @var $email */
        ->disabled()
        ->emailField();
    echo $form->field($user, 'username')->required();
    echo $form->field($user, 'password')->passwordField()->required();
    echo $form->submit('Sign up');

    Form::end();
    ?>
@endsection
