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
	$form = Form::begin($post, Application::path('camera.share'), "POST", "login-form");
	?>
	<?= $form->field('picture')->hiddenField()?>
	<?= $form->field('title')->setHolder('If you want it to have one :D') ?>
	<?= $form->text('comment')->setHolder('beautiful day in the beach?') ?>
	<div class="row">
		<div class="col-25 onofflabel">
			<label for="Title">Mark as spoiler</label>
		</div>
		<div class="col-75">
			<span class="onoff">
				<input type="checkbox" name="spoiler" value="1" id="checkboxID"><label for="checkboxID"></label>
			</span>
			<div class="invalid-feedback">

			</div>
		</div>
	</div>
	<?= $form->submit('Post'); ?>
	<?php Form::end();?>
@endsection
