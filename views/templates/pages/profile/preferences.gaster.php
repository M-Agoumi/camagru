@layout('main')
@section('title'){{ title }} @endsection
@section('content')
	<?php

	use Simfa\Form\Form;
	use Simfa\Model\Language;

	$lang = New Language();

	?>
	<h1>Preferences</h1>

	<?php
	$form = Form::begin();
	echo $form->select($pref, 'language', $lang);
	echo $form->select($pref, 'mail', ['1' => 'yes', '0' => 'no'])->setLabel('receive notification emails');
	echo $form->submit('Save');
	$form::end();
	?>
@endsection
