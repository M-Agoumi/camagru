@layout('main')
@section('title'){{ title }}@endsection
@section('content')
	<?= $form = \Simfa\Form\Form::begin('/myaction');?>
		<?= $form->field($user, 'name')?>
	<?= \Simfa\Form\Form::end()?>
@endsection
