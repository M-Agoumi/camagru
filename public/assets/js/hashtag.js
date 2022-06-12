function scrollToTop() {
	window.scrollTo(0, 0);
}

let shouldRemove = true;
let shouldPrint = true;
let firstRun = true;
let page = 0;
window.onload = loadPosts();

function loadPosts() {
	if (shouldPrint) {
		const xhr = new XMLHttpRequest();
		xhr.open('POST', url);

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
				} else
					document.getElementById('message').insertAdjacentHTML('beforeend', "<h1 class='endOfPosts'>there is no more related posts</h1>");
				shouldPrint = false;
			} else {
				for (let i in posts) {
					let id = Math.random().toString(36).slice(2);
					let post = '';

					post += '<div class="gallery-container" id=' + id + '>';
					post += '<a href="/post/' + posts[i].slug + '">';
					post += '<div class="gallery-item">';
					post += '<div class="image">';
					post += '<img alt="' + posts[i].title + '" src="/uploads/' + posts[i].picture + '"/>';
					post += '</div>';
					post += '<div class="text">' + posts[i].title + '</div>';
					post += '</div>';
					post += '</a>';
					post += '</div>';


					document.getElementById('gallery').insertAdjacentHTML('beforeend', post);
					loadImage(id, posts[i].picture);
				}
			}
		}

		xhr.send('page=' + page++);
	}
}

function loadImage(id, url){
	let img = new Image();

	img.onload = function(){
		const height = img.height;
		const width = img.width;

		const orientation = imageOrientation(img);
		if (orientation === 'landscape') {
			if (width < height * 2)
				document.getElementById(id).classList.add('w-2');
			else{
				document.getElementById(id).classList.add('w-3');
				document.getElementById(id).classList.add('h-2');
			}
		} else if (orientation === 'portrait') {
			if (height < width * 2)
				document.getElementById(id).classList.add('h-2');
			else {
				document.getElementById(id).classList.add('h-3');
				document.getElementById(id).classList.add('w-2');
			}
		} else {
			document.getElementById(id).classList.add('w-1');
		}
	}

	img.src = '/uploads/' + url;
}

function imageOrientation(img) {

	let orientation;

	if (img.naturalWidth > img.naturalHeight * 1.2) {
		orientation = 'landscape';
	} else if (img.naturalWidth < img.naturalHeight * 1.2) {
		orientation = 'portrait';
	} else {
		orientation = 'even';
	}

	return orientation;

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
