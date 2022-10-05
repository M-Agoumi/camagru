@layout('main')
@section('title')Sign up@endsection
@section('content')
    <?php
    use Model\User;
    use Simfa\Form\Form;

    /** @var $user User */
    ?>
    <title><?=$title ?? ''?></title>
    <h1>Sign Up</h1>
    <?php
    $form = Form::begin($user, '', "POST", "login-form");
	echo $form->field('email')->emailField()->required()->setHolder('Example@email.com')
		->setLabel('Email');
    echo $form->submit('Sign up');
    Form::end();
    ?>
@endsection
