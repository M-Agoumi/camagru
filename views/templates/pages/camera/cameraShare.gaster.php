@layout('main')
@section('title'){{ title }}@endsection
@section('content')

	<?php

	use Simfa\Form\Form;
	use Simfa\Framework\Application;

	/** @var  $post Model\Post */
	?>
	<div style="width: 100%; text-align: center">
		<img src="/tmp/<?= $post->getPicture()?>" alt="new image" style="max-width: 1080px; margin: auto">
	</div>
	<?php
	$form = Form::begin(Application::path('camera.share'), "POST", "login-form");
	?>
	<?= $form->field($post, 'picture')->hiddenField()?>
	<?= $form->field($post, 'title')->setHolder('If you want it to have one :D') ?>
	<?= $form->text($post, 'comment')->setHolder('beautiful day in the beach?') ?>
	<?= $form->checkbox($post, 'spoiler') ?>
	<p class="onoff"><input type="checkbox" value="1" id="checkboxID"><label for="checkboxID"></label></p>
	<?= $form->submit('Post'); ?>
	<?php Form::end();?>
@endsection
