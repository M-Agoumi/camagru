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
    if (confirm('You need to login to like this post, login?')) {
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

