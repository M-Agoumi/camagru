@layout('main')
@section('title'){{ title }}@endsection
@section('content')
<div style="text-align: center;width: 100%;position: absolute;" id="loading"><?= lang("Content loading...")?></div>
<div id="message">
</div>
<div class="gal-one grid" id="gallery">

</div>

<div onclick="scrollToTop()" class="scrollTop">Top</div>
<script>

	function scrollToTop() {
		window.scrollTo(0, 0);
	}

	let shouldRemove = true;
	let firstRun = true;
	window.onload = loadPosts();

	function loadPosts() {
		const xhr = new XMLHttpRequest();
		xhr.open('POST', '/posts');

		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

		xhr.onload = () => {
			let posts = JSON.parse(xhr.responseText);

			if (shouldRemove) {
				shouldRemove = false;
				document.getElementById('loading').remove();
			}

			if (posts.length === 0) {
				if (firstRun) {
					document.getElementById('message').insertAdjacentHTML('beforeend', "<h1 class='endOfPosts'>there is no posts yet, start by creating the first one</h1>");
					firstRun = false;
				}

			} else {
				for (let i in posts)
				{
					let id = Math.random().toString(36).slice(2);
					let post = '';

					post += '<div class="image">';

					post += '<a href="/post/' + posts[i].slug + '">';
					post += '<img id=' + id + ' alt="' + posts[i].title + '"/>';
					post += '</a>';

					post += '</div>';



					document.getElementById('gallery').insertAdjacentHTML('beforeend', post);
					loadImage(posts[i].picture, id);
				}
			}
		}

		xhr.send();
	}

	function loadImage(url, id)
	{
		document.getElementById(id).src = '/uploads/' + url;
	}

	function getScrollPercent() {
		var h = document.documentElement,
			b = document.body,
			st = 'scrollTop',
			sh = 'scrollHeight';
		return Math.round((h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100);
	}

	document.addEventListener('DOMContentLoaded', function(e) {
	    document.addEventListener('scroll', function(e) {
	        // console.log(getScrollPercent());
	        if (firstRun)
	            firstRun = false;
	        else
	            if (getScrollPercent() === 100)
	                loadPosts();
	    })
	})
</script>
@endsection
