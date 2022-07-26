@layout('gallery')
@section('title')Home@endsection
@section('content')
<div id="loading" style="text-align: center"><h1>Loading...</h1></div>
<div id="message"></div>
<div class="container" id="gallery">
	<?php /** @var array{title: string, picture: string, slug: string} $posts */?>
</div>
<div onclick="scrollToTop()" class="scrollTop">Top</div>
<script src="<?= asset("assets/js/gallery.js") ?>"></script>
@endsection
