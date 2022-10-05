@layout('main')
@section('title')Update Your Password@endsection
@section('content')

	<?php
		use Simfa\Form\Form;
    ?>
    <h1>Update Password</h1>
    <?php
        $form = Form::begin($user);
            echo $form->field('password')->passwordField()->required();
            echo $form->submit('update');
        $form::end();
    ?>
@endsection
