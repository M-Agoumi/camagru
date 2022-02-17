@layout('admin')
@section('title')New Emote - Dashboard@endsection
@section('main')
<h1>New Emote</h1>
<form action="" method="POST" enctype="multipart/form-data">
	<?php \Simfa\Framework\Application::$APP->session->generateCsrf()?>
	<input type="hidden" name="__csrf" value="<?= \Simfa\Framework\Application::$APP->session->getCsrf()?>">
	<div class="form-group row">
		<label for="staticName" class="col-sm-4 col-form-label">Emote Name</label>
		<div class="col-sm-8">
			<input type="text"  id='staticName' class="form-control-plaintext" placeholder="Your Emote Name" name="name"/>
		</div>
	</div>
	<div class="form-group row">
		<label for="staticFile" class="col-sm-4 col-form-label">Emote File</label>
		<div class="col-sm-8">
			<input type="file" id='staticFile' name="image" class="form-control-file"/>
		</div>
	</div>
	<button type="submit" class="btn btn-primary mb-2">Add Emote</button>
</form>
@endsection
