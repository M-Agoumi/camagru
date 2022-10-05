@layout('admin')
@section('title')Users - Dashboard@endsection
@section('main')
<h1>Users Table</h1>
<table class="table table-striped">
	<thead>
	<tr>
		<th scope="col">#</th>
		<th scope="col">Name</th>
		<th scope="col">Username</th>
		<th scope="col">Email</th>
		<th scope="col">Created</th>
		<th scope="col">Action</th>
		<th scope="col">admin</th>
	</tr>
	</thead>
	<tbody>
	<?php
	/** @var $users array */
	foreach ($users as $user): ?>
		<tr>
			<th scope="row"><?=$user['entityID']?></th>
			<td><?=$user['name']?></td>
			<td><?=$usr->getEmail($user['entityID'])?></td>
			<td><?=$user['username']?></td>
			<td><?=$user['created_at']?></td>
			<td class="d-flex">
				<a class="btn btn-danger" href="/dashboard/users/delete/<?=$user['entityID']?>?<?=\Simfa\Framework\Application::$APP->session->getToken()?>">
					Delete
				</a>
			</td>
			<td><?=$user['admin'] ? 'yes' : 'no'?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php foreach ($usr->pages() as $key => $page): ?>
	<?php if (is_array($page)):?>
		<a href="#" class="btn btn-danger"><?= $page['active']; ?></a>
	<?php else: ?>
		<a href="?page={{ page }}" class="btn btn-info"><?= $page; ?></a>
	<?php endif;?>
<?php endforeach;?>
@endsection
