@layout('main')
@section('title'){{ title }}@endsection
@section('content')
<h1><?=lang('home')?></h1>
<div class="masonry-container">
	<div style="text-align: center" id="loading">Content loading...</div>
</div>
<div class="gal-one grid" id="gallery">

	<?php
	foreach($posts as $post):
		?>
		<div class="image">
			<a href="/post/<?= $post['slug']; ?>">
				<img src="/uploads/<?=$post['picture']?>" alt="<?=$post['title']?>">
			</a>
		</div>
	<?php endforeach; ?>


</div>

<div onclick="scrollToTop()" class="scrollTop">Top</div>


</div>
<style>

	#gallery {
		/* padding: 10px;
		max-width: 1100px;
		margin: 0 auto;
		background: #f2f2f2;
		display: grid;
		gap: 10px;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		grid-auto-rows: 250px;
		grid-auto-flow: dense; */
		position: relative;
		column-count: 4;
		padding: 20px;
	}

	.image {
		display: inline-block;
		margin-bottom: 10px;
		overflow: hidden;
	}

	.image img {
		width: 100%;
		transition: 0.25s ease-in-out;
	}

	.image:hover img {
		transform: scale(1.1);
		cursor: pointer;
	}

	@media screen and (max-width: 1024px) {
		#gallery {
			column-count: 3;
		}
	}

	@media screen and (max-width: 768px) {
		#gallery {
			column-count: 3;
		}
	}

	@media screen and (max-width: 567px) {
		#gallery {
			column-count: 2;
		}
	}

	@media screen and (max-width: 375px) {
		#gallery {
			column-count: 1;
		}
	}

	.scrollTop {
		position: fixed;
		bottom: 30px;
		right: 30px;
		padding: 10px 15px;
		background-color: #000;
		color: #FFF;
		border-radius: 5px;
		border: 1px solid #FFF;
		cursor: pointer;
		transition: all .5s ease-in-out;
	}

	/*
	#gallery {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		text-align: center;
		margin: 40px 20px 0 20px;
		width: 100%;
	}

	.img-box {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
	}

	.img-parent {
		display: flex;
		flex-direction: column;
		width: 32.5%;
	}

	.img-parent img {
		width: 100%;
		padding-bottom: 15px;
		border-radius: 5px;
	}
	*/







	/* #gallery {
		line-height: 0;
		-webkit-column-count: 5;
		-webkit-column-gap:   0px;
		-moz-column-count:    5;
		-moz-column-gap:      0px;
		column-count:         5;
		column-gap:           0px;
		display: flex;
		flex-wrap: wrap;
	} */

	/* .panel {
		width: 30% !important;
	}

	.panel .panel-wrapper {
		width: 100%;
	}

	.panel .panel-wrapper .panel-vingette {
		width: 100%;
	} */

	/* .panel-wrapper {
		width: 100% !important;
	} */



</style>
<script>

	function scrollToTop() {
		window.scrollTo(0, 0);
	}

	let shouldRemove = true;
	let page = 1;
	let firstRun = true;
	let reachedEnd = false;
	// window.onload = loadPosts();

	function loadPosts() {
		console.log(`Page Number ${page}`);
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
				if (page !== 1) {
					page = 1;
					loadPosts();
				} else {
					if (!reachedEnd) {
						reachedEnd = true;
						document.getElementById('gallery').insertAdjacentHTML('beforeend', "<h1 class='endOfPosts'>there is no posts yet, start by creating the first one</h1>");
					}
				}
			} else {
				for (let i in posts)
				{
					let id = Math.random().toString(36).slice(2);
					let post = '';
					// let grid = document.createElement('div');

					// var elem = document.querySelector('.grid');
					// var msnry = new Masonry( elem, {
					// // options
					// itemSelector: '.grid-item',
					// columnWidth: 200
					// })

					post += '<div class="panel grid-item">';

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

					// grid.appendChild(post);


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
		return Math.round((h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100);
	}

	// document.addEventListener('DOMContentLoaded', function(e) {
	//     document.addEventListener('scroll', function(e) {
	//         // console.log(getScrollPercent());
	//         if (firstRun)
	//             firstRun = false;
	//         else
	//             if (getScrollPercent() === 100)
	//                 loadPosts();
	//     })
	// })
</script>
@endsection
