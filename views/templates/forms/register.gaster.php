@layout('main')
@section('title')register@endsection
@section('content')

    <?php
    /** @var $user Model\User */
    use Simfa\Form\Form;
    ?>
    <h1>You almost there just fill those fields and welcome onboard</h1>
    <?php
    $form = Form::begin($user, route('auth.insertUser'), "POST", "login-form");
    echo $form->field('name')->setHolder('John Dracula')->required()->setLabel('Full Name');
    echo $form->field('email')
        ->value($email)/** @var $email */
        ->disabled()
        ->emailField();
    echo $form->field('username')->required();
    echo $form->field('password')->passwordField()->required();
    echo $form->submit('Sign up');

    Form::end();
    ?>
@endsection
