@layout('auth')
@section('titles'){{ title }}@endsection
@section('content')
    <?php
    /** @var $user User */

    use Model\User;
    use Simfa\Form\Form;
    use Simfa\Framework\Application;

    ?>
    <h1>Sign In</h1>
    <?php
    $url = $_GET['ref'] ?? '/';
    $form = Form::begin(Application::path('auth.auth') . "?ref=" . $url, "POST", "login-form");
	    echo $form->field($user, 'username')->required()
		    ->setHolder('John Dracula')->setLabel('Username OR Email');
	    echo $form->field($user, 'password')->passwordField();
	    echo $form->submit('Sign in');

    Form::end();
    ?>
    <h1>
        <a href="<?=Application::path('auth.restore')?>">restore password</a>
    </h1>
    <?php if(!empty($users)):?>
        <h1>login to saved accounts</h1>
        <?php
        /** @var array $users */
        foreach ($users as $user): ?>
            <div>
                <h3>
                    <a href="<?=route('auth.magic.login', $user->token)?>">
                        <?=$user->user->name?>
                    </a>
                </h3>
            </div>
        <?php endforeach;?>
    <?php endif;?>
@endsection
