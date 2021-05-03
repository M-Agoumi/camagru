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


/* like button */
function likePost(e) {
    e.preventDefault();
    try {
        // Create XHR Object
        var xhr = new XMLHttpRequest();

        // Open - type, url/file, asyc
        xhr.open('post', "/post/like/1", true);

        xhr.onload = function () {
            // check if request is okay
            if (this.status == 200) {
                console.log(this.responseText);
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

