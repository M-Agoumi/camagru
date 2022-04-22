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
			<td><a href="/post/<?=$post['slug']?>"><img width="150" src="/uploads/<?=$post['picture']?>"/></a></td>
			<td><?=$post['title']?></td>
			<?php
				$user->getOneBy($post['author']);
			?>
			<td><?=$user->getUsername()?></td>
			<td><?=$post['created_at']?></td>
			<td class="d-flex">
				<a class="btn btn-danger" href="/dashboard/posts/delete/<?=$post['entityID']?>?<?= Application::$APP->session->getToken()?>">
					Delete
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php foreach ($pst->pages() as $key => $page): ?>
	<?php if (is_array($page)):?>
		<a href="#" class="btn btn-danger"><?= $page['active']; ?></a>
	<?php else: ?>
		<a href="?page={{ page }}" class="btn btn-info"><?= $page; ?></a>
	<?php endif;?>
<?php endforeach;?>
@endsection
