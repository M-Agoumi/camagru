@layout('main')
@section('title'){{ title }}@endsection
@section('content')
    <h1><?=lang('home')?></h1>
    <div class="masonry-container">
	    <div style="text-align: center" id="loading">Content loading...</div>
    </div>
    <div class="gal-one" id="gallery">

    </div>
	<script>
		let shouldRemove = true;
        let page = 1;
        let firstRun = true;
        let reachedEnd = false;
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
                    if (!reachedEnd) {
                        reachedEnd = true;
                        document.getElementById('gallery').insertAdjacentHTML('beforeend', "<h1 class='endOfPosts'>there is no more posts</h1>");
                    }
                } else {
                    for (let i in posts)
                    {
                        let id = Math.random().toString(36).slice(2);
                        let post = '';
                        post += '<div class="panel">';
                        post += '<a href="/post/' + posts[i].slug + '">';
                        post += '<div class="panel-wrapper">';
                        post += '<div class="panel-overlay">';
                        post += '<div class="panel-text">';
                        post += '<div class="panel-title">' + posts[i].title + '</div>';
                        post += '<div class="panel-tags">';
                        post += '<span class="tag-icon">';
                        post += '<img class="tag-icon-img" src="/assets/icon/tag-icon.svg" alt=""/>';
                        post += '</span>';
                        post += '<span class="tags-list">' + posts[i].hashtags + '</span>'
                        post += '</div>';
                        post += '</div>';
                        post += '<img class="panel-gradient" src="/assets/icon/base-gradient.png" alt=""/>';
                        post += '<img class="panel-vingette" src="/assets/icon/darken-gradient.png" alt=""/>';
                        post += '</div>';
                        post += '<img class="panel-img" id=' + id + ' alt="' + posts[i].title + '"/>';
                        post += '</div>';
                        post += '</a>';
                        post += '</div>';

                        document.getElementById('gallery').insertAdjacentHTML('beforeend', post);
                        loadImage(posts[i].picture, id);
                    }
                    page++;
                }
            }

            xhr.send("page=" + page);
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
            return (h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100;
        }

        document.addEventListener('DOMContentLoaded', function(e) {
            document.addEventListener('scroll', function(e) {
	            if (firstRun)
                    firstRun = false;
                else
	                if (getScrollPercent() === 100)
                        loadPosts();
            })
        })
	</script>
@endsection
