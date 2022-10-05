@layout('main')
@section('title'){{ title }}@endsection
@section('content')
    <?php
    /** @var $user User */

    use Model\User;
    use Simfa\Form\Form;
    use Simfa\Framework\Application;

    ?>
    <h1>Sign In</h1>
    <?php
    $url = $_GET['ref'] ?? '';
    $form = Form::begin($user,Application::path('auth.auth') . "?ref=" . $url, "POST", "login-form");
	    echo $form->field('username')->required()
		    ->setHolder('John Dracula')->setLabel('Username OR Email');
	    echo $form->field('password')->passwordField();
	    echo $form->submit('Sign in');

    Form::end();
    ?>
    <h1>
        <a href="<?=Application::path('auth.restore')?>">restore password</a>
    </h1>
    <?php if(!empty($users)):?>
        <h1>login to saved accounts</h1>
		<div class="login-cards">
			<?php
			/** @var $users Simfa\Model\UserToken[] */
			foreach ($users as $token): ?>
				<div class="profile-card">
					<a href="<?=route('auth.magic.login', $token->getToken())?>">
						<img src="/uploads/dps/boobies.png" alt="Avatar" style="width:100%">
						<div class="profile-card-container">
							<h4><b><?=$token->getUser()->getName()?></b></h4>
							<p><?=$token->getUser()->getUsername()?></p>
						</div>
					</a>
				</div>
			<?php endforeach;?>
		</div>
	<?php endif;?>
@endsection
