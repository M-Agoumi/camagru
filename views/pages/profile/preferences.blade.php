@layout('main')
@section('title'){{ title }} @endsection
@section('content')
	<?php

	use core\Form\Form;use models\core\languages;

	$lang = New languages();

	?>
	<h1>Preferences</h1>

	<?php
	$form = Form::begin();
	echo $form->select($pref, 'language', $lang);
	echo $form->submit('Save');
	$form::end();
	?>
@endsection
