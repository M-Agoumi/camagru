@layout('admin')
@section('title')Messages - Dashboard@endsection
@section('main')
<?php
/**
 * @var \Helper\TimeHelper $helper
 */
$helper = \Simfa\Framework\Helper::getHelper(\Helper\TimeHelper::class);
?>
<table class="table table-striped">
	<tr>
		<th scope="row" class="text-primary"><?=lang('title')?></th>
		<th scope="row"><?=$message->getTitle()?></th>
	</tr>
	<tr>
		<th scope="row" class="text-primary"><?=lang('logged')?></th>
		<th scope="row"><?=$message->getLogged() ? 'yes' : 'no' ?></th>
	</tr>
	<tr>
		<th scope="row" class="text-primary"><?=lang('user')?></th>
		<?php if ($message->getUser()->getId()): ?>
			<th scope="row"><a href="/dashboard/user/{{ message->getUser()->getId() }}"><?=$message->getUser()->getUsername()?></a></th>
		<?php else: ?>
			<th scope="row">---</th>
		<?php endif ?>
	</tr>
	<tr>
		<th scope="row" class="text-primary"><?=lang('email')?></th>
		<th scope="row"><?=$message->getEmail()?></th>
	</tr>
	<tr>
		<th scope="row" class="text-primary"><?=lang('content')?></th>
		<th scope="row"><?=$message->getContent()?></th>
	</tr>
	<tr>
		<th scope="row" class="text-primary"><?=lang('time')?></th>
		<th scope="row"><?=$helper->humanTiming($message->getCreated_at())?> ({{message->getCreated_at()}})</th>
	</tr>
</table>

@endsection
