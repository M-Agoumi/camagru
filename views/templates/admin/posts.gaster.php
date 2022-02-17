@layout('admin')
@section('title')Posts - Dashboard@endsection
@section('main')
<h1>Posts Table</h1>
<table class="table table-striped">
	<thead>
	<tr>
		<th scope="col">#</th>
		<th scope="col">picture</th>
		<th scope="col">title</th>
		<th scope="col">author</th>
		<th scope="col">Created</th>
		<th scope="col">Action</th>
	</tr>
	</thead>
	<tbody>
	<?php
	/** @var $posts array */

	use Model\User;
	use Simfa\Framework\Application;

	$user = new User();
	foreach ($posts as $post): ?>
		<tr>
			<th scope="row"><?=$post['entityID']?></th>
			<td><img width="150" src="/uploads/<?=$post['picture']?>"/></td>
			<td><?=$post['title']?></td>
			<?php
				$user->getOneBy($post['author']);
			?>
			<td><?=$user->getUsername()?></td>
			<td><?=$post['created_at']?></td>
			<td class="btn btn-danger">
				<a href="/dashboard/posts/delete/<?=$post['entityID']?>?<?= Application::$APP->session->getToken()?>">
					Delete
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<a href="/dashboard/emotes/new" class="btn btn-info">New Emote</a>
@endsection
