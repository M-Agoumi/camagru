@layout('admin')
@section('title')Messages - Dashboard@endsection
@section('main')
<?php
/**
 * @var \Helper\TimeHelper $helper
 */
$helper = \Simfa\Framework\Helper::getHelper(\Helper\TimeHelper::class);
?>
<h1>Users Table</h1>
<table class="table table-striped">
	<thead>
	<tr>
		<th scope="col">#</th>
		<th scope="col">from</th>
		<th scope="col">user</th>
		<th scope="col">title</th>
		<th scope="col">sent</th>
		<th scope="col">read</th>
		<th scope="col">Action</th>
	</tr>
	</thead>
	<tbody>
	<?php
	/** @var $messages array */
	foreach ($messages as $message): ?>
		<tr>
			<th scope="row"><?=$message['entityID']?></th>
			<td><?=$message['email']?></td>
			<td><?=$message['logged'] ? 'yes' : 'no'?></td>
			<td><?=$message['title']?></td>
			<td><?=$helper->humanTiming($message['created_at'])?></td>
			<td><?=$message['updated_at'] ? 'yes' : 'no' ?></td>
			<td class="d-flex">
				<a class="btn btn-info" href="/dashboard/message/<?=$message['entityID']?>?<?=\Simfa\Framework\Application::$APP->session->getToken()?>">
					Open
				</a>&nbsp
				<a class="btn btn-danger" href="/dashboard/message/delete/<?=$message['entityID']?>?<?=\Simfa\Framework\Application::$APP->session->getToken()?>">
					Delete
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php foreach ($msg->pages() as $key => $page): ?>
	<?php if (is_array($page)):?>
		<a href="#" class="btn btn-danger"><?= $page['active']; ?></a>
	<?php else: ?>
		<a href="?page={{ page }}" class="btn btn-info"><?= $page; ?></a>
	<?php endif;?>
<?php endforeach;?>
@endsection
