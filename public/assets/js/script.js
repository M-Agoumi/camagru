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
    source.classList.toggle('fade');
    await sleep(1000);
    source.style.display = "none";
}

async function capture() {
    const canvas = document.getElementById('canvas');
    let context = canvas.getContext('2d');

    context.drawImage(video, 0, 0, 650, 490);
    document.getElementById('picture').style.display = 'block';
    document.getElementById('camera').style.display = 'none';
}

function save() {
    var canvas = document.getElementById("canvas");
    var img    = canvas.toDataURL("image/jpeg");

    var tmp = document.getElementById('tmp');
    tmp.innerHTML = '<img src="'+img+'" alt="tmp image"/>';
    console.log(img);
    document.getElementById('inputPicture').value = img;
}

/* starting ajax code */


/* login popup */

function loginPopUp(path)
{
    console.log('we good ' + path);
    if (confirm('You need to login to do this action, login?')) {
        if (typeof path === 'undefined')
            path = window.location.href;
        window.location.href = "/login?ref=" + path;
    }
}

var reacts = ['Like', 'Heart', 'Wow', 'Haha', 'Sad', 'Angry'];
/* like button */
function likePost(post, elem, react = 1) {
    try {
        // Create XHR Object
        var xhr = new XMLHttpRequest();
        var liked = elem.textContent;
        var likes = elem.previousElementSibling;

        // Open - type, url/file, asyc
        console.log('request: ' + "/post/like/" + post + "?react=" + react);
        xhr.open('post', "/post/like/" + post + "?react=" + react, true);

        xhr.onload = function () {
            // check if request is okay
            if (this.status == 200) {
                console.log('API: ' + this.responseText);
                if (this.responseText == -1)
                    loginPopUp();
                else {
                    if (this.responseText == 0) {
                        elem.innerHTML = reacts[react];
                        likes.innerHTML = parseInt(likes.textContent) - 1;
                    } else {
                        elem.innerHTML = reacts[react] + 'ed';
                        likes.innerHTML = parseInt(likes.textContent) + 1;
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
    return false;
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
                    if(!Object.keys(users).length){
                        output += "this post has no likes yet, why don't you be the first?";
                    } else {
                        for (let i in users) {
                            output += '<ul>' +
                                '<li><img src="' + users[i].picture + '" alt="profile picture"></li>' +
                                '<li>' + users[i].user + '</li>';
                            if (users[i].react == 0)
                                output += '<li><span class="fa fa-thumbs-o-up"></span></li>';
                            else if (users[i].react == 1)
                                output += '<li><span class="fa fa-heart"></span></li>';


                            output += '</ul>';
                        }
                    }
                    console.log(users);
                    document.getElementsByClassName('content')[0].innerHTML = output;
                    document.getElementsByClassName('usersLikes')[0].style.display = 'block';
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

function addComment(e, slug) {
    e.preventDefault();

    getUserName();
    const form = document.getElementById('addCommentForm');

    const xhr = new XMLHttpRequest();

    xhr.open('POST', '/api/post/comment/' + slug);

    let data = new FormData(form);

    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.send(data);

    xhr.onload = () => {
        let response = xhr.responseText;
        console.log(response);

        if (response == -2)
            loginPopUp();
        if (response == -1)
            console.log('post not found');
        if (response == 0) {
            document.getElementsByClassName('invalid-feedback')[0].innerHTML = 'comment is not valid';
            document.getElementById('content').classList.add('is-invalid');
        }
        if (response == 1) {
            var table = document.getElementById("commentsTable");
            var row = table.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            // console.log('username: ' + getUserName());
            cell1.innerHTML = UserName;
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
