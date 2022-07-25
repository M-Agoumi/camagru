function read_cookie(key)
{
    var result;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? (result[1]) : null;
}

function hideCookieMessageIfCookiesAreActive()
{
    let cookies = read_cookie('cookies_active');
    if (cookies != 1){
        document.getElementById('cookies_not_allowed').style.display = "block";
    }
}

window.onload = function() {
    hideCookieMessageIfCookiesAreActive();
};

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function dismissMessage() {
    let source = document.getElementById('flash_message');
    source.classList.toggle('slideUp');
    await sleep(1000);
    source.style.display = "none";
}

/* starting ajax code */


/* login popup */

function loginPopUp(path)
{
    if (confirm('You need to login to do this action, login?')) {
        if (typeof path === 'undefined')
            path = window.location.pathname;
        window.location.href = "/login?ref=" + path;
    }
}

var reacts = ['like', 'love', 'wow', 'haha', 'sad', 'angry'];
/* like button */
function likePost(post, elem, react = 0) {
    try {
        // Create XHR Object
        let xhr = new XMLHttpRequest();
        const likes = document.getElementById('reactsCounter');

        // Open - type, url/file, asyc
        xhr.open('post', "/post/like/" + post + "?react=" + react, true);

        xhr.onload = function () {
            // check if request is okay
            if (this.status == 200) {
                if (this.responseText == -1)
                    loginPopUp();
                else {
                    if (this.responseText == 1) {
						removeReactActive();
                        elem.classList.add(reacts[react] + '-active');
                        likes.innerHTML = parseInt(likes.textContent) + 1;
                    } else if(this.responseText == 2) {
						removeReactActive();
						elem.classList.add(reacts[react] + '-active');
					} else {
						likes.innerHTML = parseInt(likes.textContent) -	 1;
					}
                }
            } else {
                console.log('error ' + this.status);
                console.log('return: ' + this.responseText);
            }
        }

        // Send request
        xhr.send();
    } catch (e) {
        throw new Error(e.message);
    }
    return false;
}

function removeReactActive()
{
	const tooltips = document.getElementsByClassName('icon')

	for (let tooltip of tooltips) {
		const myElem = tooltip.children[1];
		myElem.className = '';
	}
}

/** show people who liked the post */

function hideLikes() {
    document.getElementsByClassName('usersLikes')[0].style.display = 'none';
}

function showLikes(post) {
    try {
        var xhr = new XMLHttpRequest();

        // Open - type, url/file, asyc
        xhr.open('post', "/api/post/likes/" + post, true);

        xhr.onload = function () {
            // check if request is okay
            if (this.status === 200) {
                if (this.responseText == -1)
                    loginPopUp();
                else {
                    var users = JSON.parse(this.responseText);

                    var output = '<span class="fa fa-close close" onclick="hideLikes()"></span>';
                    if(Object.keys(users).length === 0){
                        output += "this post has no likes yet, why don't you be the first?";
						document.getElementsByClassName('content')[0].innerHTML = output;
						document.getElementsByClassName('usersLikes')[0].style.display = 'block';
						document.getElementsByClassName('usersLikes')[0].style.color = '#424242';
                    } else {
                        for (let i in users) {
                            output += '<ul class="XD">' +
                                '<li class="user-img"><img src="' + users[i].picture + '" alt="profile picture"></li>' +
                                '<li class="user-react">' + users[i].user + '</li>';
                            if (users[i].react == 0)
                                output += '<li class="user-react"><span class="fas fa-thumbs-up"></span></li>';
                            else if (users[i].react == 1)
                                output += '<li class="user-react"><span class="fas fa-heart"></span></li>';
							else if (users[i].react == 2)
								output += '<li class="user-react"><span class="fas fa-grin-alt"></span></li>';
							else if (users[i].react == 3)
								output += '<li class="user-react"><span class="fas fa-grin-squint-tears"></span></li>';
							else if (users[i].react == 4)
								output += '<li class="user-react"><span class="fas fa-sad-tear"></span></li>';
							else if (users[i].react == 5)
								output += '<li class="user-react"><span class="fas fa-angry"></span></li>';

                            output += '</ul>';
							document.getElementsByClassName('content')[0].innerHTML = output;
							document.getElementsByClassName('usersLikes')[0].style.display = 'block';
                        }
                    }

                }
            } else {
                console.log('error ' + this.status);
            }
        }

        // Send request
        xhr.send();

    } catch (e) {
        throw new Error(e.message);
    }
}

/** add a new comment */
let UserName = 'name';

getUserName();

function addComment(e, slug) {
    e.preventDefault();

    const form = document.getElementById('addCommentForm');

    const xhr = new XMLHttpRequest();

    xhr.open('POST', '/api/post/comment/' + slug);

    let data = new FormData(form);

    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(data);

    xhr.onload = () => {
        let response = JSON.parse(xhr.responseText);

		document.getElementById('csrf_comment').value = response.token;
		if (response.code === -2)
			loginPopUp();
		if (response.code === -1) {
			document.getElementsByClassName('invalid-feedback')[0].innerHTML = 'Post is not available anymore';
			document.getElementById('content').classList.add('is-invalid');
		}
		if (response.code === -3) {
			document.getElementsByClassName('invalid-feedback')[0].innerHTML = 'invalid token, please try again or refresh the page';
			document.getElementById('content').classList.add('is-invalid');
		}
		if (response.code === 0) {
			document.getElementsByClassName('invalid-feedback')[0].innerHTML = 'comment is not valid';
			document.getElementById('content').classList.add('is-invalid');
		}
		if (response.code === 1) {
			const table = document.getElementById("commentsTable");
			let row = table.insertRow(0);
			let cell1 = row.insertCell(0);
			let cell2 = row.insertCell(1);
			cell1.innerHTML = '<a href="/profile">' + UserName + '</a>';
			cell2.innerText = document.getElementById('content').value;
			document.getElementById('content').value = "";
		}
    }

    return false;
}

function getUserName() {
    return new Promise(resolve => {
        var xhr = new XMLHttpRequest();

        var name = 'name';
        xhr.open('POST', '/api/user/name');

        xhr.onload = function () {
            resolve(this.responseText);
            UserName = this.responseText;
        }

        xhr.send();
    });
}

const menuBtn = document.querySelector(".c-menu");

const cNav = document.querySelector(".c-nav");

menuBtn.onclick = () => {
	cNav.classList.toggle("open");
}
