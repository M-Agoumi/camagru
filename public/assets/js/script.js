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

    // document.write('<img src="'+img+'"/>');
    console.log(img);
    document.getElementById('inputPicture').value = img;
}

/* starting ajax code */


/* login popup */

function loginPopUp()
{
    if (confirm('You need to login to do this action, login?')) {
        var path = window.location.href;
        window.location.href = "/login?ref=" + path;
    }
}

/* like button */
function likePost(post, elem) {
    try {
        // Create XHR Object
        var xhr = new XMLHttpRequest();
        var liked = elem.textContent;
        var likes = elem.previousElementSibling;

        console.log(parseInt(likes.textContent));

        // Open - type, url/file, asyc
        xhr.open('post', "/post/like/" + post, true);

        xhr.onload = function () {
            // check if request is okay
            if (this.status == 200) {
                // console.log(this.responseText);
                if (this.responseText == -1)
                    loginPopUp();
                else {
                    if (this.responseText == 0) {
                        elem.innerHTML = "Like";
                        likes.innerHTML = parseInt(likes.textContent) - 1;
                    } else {
                        elem.innerHTML = "Liked";
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