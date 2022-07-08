@layout('gallery')
@section('title')#{{ hashtag }}@endsection
@section('content')
<h1>#{{ hashtag }}</h1>
<div id="loading" style="text-align: center"><h1>Loading...</h1></div>
<div class="container" id="gallery">
	<?php /** @var array{title: string, picture: string, slug: string} $posts */?>
</div>
<div id="message"></div>
<div onclick="scrollToTop()" class="scrollTop">Top</div>
<script>
	var url = '/posts/hashtag/{{ hashtag }}';
</script>
<script src="<?= asset("assets/js/hashtag.js") ?>"></script>
@endsection
