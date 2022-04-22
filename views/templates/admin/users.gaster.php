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
	</tr>
	</thead>
	<tbody>
	<?php
	/** @var $users array */
	foreach ($users as $user): ?>
		<tr>
			<th scope="row"><?=$user['entityID']?></th>
			<td><?=$user['name']?></td>
			<td><?=$user['email']?></td>
			<td><?=$user['username']?></td>
			<td><?=$user['created_at']?></td>
			<td class="d-flex">
				<a class="btn btn-danger" href="/dashboard/users/delete/<?=$user['entityID']?>?<?=\Simfa\Framework\Application::$APP->session->getToken()?>">
					Delete
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<a href="/dashboard/emotes/new" class="btn btn-info">New Emote</a>
@endsection
