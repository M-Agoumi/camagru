<?php
?>
<h1>test</h1>
<img src="/uploads/diwali_sweets.jpg" id="image" height="400"/>
<canvas id="canvas" width="400px" height="400px"></canvas>
<a id="magic" onclick="magic()">magic</a>
<h1><?=$name?></h1>
<script>

    var canvas = document.getElementById('canvas');
    ctx = canvas.getContext('2d');

    let image = new Image();

    function magic() {
        canvas.width = 400;
        canvas.height = 400;
        ctx.drawImage(image, 0, 0);

        image.src = document.getElementById("image").src;
    }

</script>
