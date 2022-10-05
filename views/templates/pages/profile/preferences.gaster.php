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
	$form = Form::begin($pref);
	echo $form->select('language', $lang);
	echo $form->select('mail', ['1' => 'yes', '0' => 'no'])->setLabel('receive notification emails')->setDefault('1');
	echo $form->submit('Save');
	$form::end();
	?>
@endsection
