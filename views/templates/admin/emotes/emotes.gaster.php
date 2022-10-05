@layout('admin')
@section('title')emotes - Dashboard@endsection
@section('main')
	<h1>Emotes Table</h1>
	<table class="table table-striped">
		<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col">Emote</th>
			<th scope="col">Name</th>
			<th scope="col">File</th>
			<th scope="col">Type</th>
			<th scope="col">Action</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
		/** @var $emotes array */
		foreach ($emotes as $emote): ?>
		<tr>
			<th scope="row"><?=$i++?></th>
			<?php if($emote['type']): ?>
				<td><img src="/assets/img/borders/<?=$emote['file']?>" width="100"/></td>
			<?php else: ?>
				<td><img src="/assets/img/emotes/<?=$emote['file']?>" width="100"/></td>
			<?php endif; ?>
			<td><?=$emote['name']?></td>
			<td><?=$emote['file']?></td>
			<td><?=$emote['type'] ? 'border' : 'emote'?></td>
			<td class="btn btn-danger"><a href="/dashboard/emotes/delete/<?=$emote['entityID']?>">Delete</a></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<a href="/dashboard/emotes/new" class="btn btn-info">New Emote</a>
@endsection
