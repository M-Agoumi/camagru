@layout('main')
@section('title')filters test@endsection
@section('content')
    <button>
        <a id="invert">invert</a>
    </button>
    <button>
        <a id="brightnessUp">bright +</a>
    </button>
    <button>
        <a id="brightnessDown">bright -</a>
    </button>
    <button>
        <a id="constructUp">construct +</a>
    </button>
    <button>
        <a id="constructDown">construct -</a>
    </button>
    <button>
        <a id="grayscale">grayscale</a>
    </button>
    <img style="display: none" src="/uploads/cover/boobies.png" id="SourceImage" height=""/>
    <div class="video-container"></div>
    <canvas id="canvas" style="width: 1080px"></canvas>
    <br>
    <script>

        let canvas;
        let context;
        let brightness = 0;
        let construct = 0;
        const image = document.getElementById('SourceImage');

        // function to read the image from our source and draw it
        // to the canvas
        function drawImage() {
            canvas = document.getElementById('canvas');
            context = canvas.getContext('2d');
            // Set the canvas the same width and height of the image
            canvas.width = image.width;
            canvas.height = image.height;

            context.drawImage(image, 0, 0);
        }

        // first run to have an image drawn on our canvas
        drawImage();

        // invert colors function
        function colorsInverter(data) {
            for (var i = 0; i < data.length; i+= 4) {
                data[i] = data[i] ^ 255; // Invert Red
                data[i+1] = data[i+1] ^ 255; // Invert Green
                data[i+2] = data[i+2] ^ 255; // Invert Blue
            }
        }

        function invertColors() {
            let imageData = context.getImageData(0, 0, canvas.width, canvas.height);

            colorsInverter(imageData.data);

            // Update the canvas with the new data
            context.putImageData(imageData, 0, 0);
        }

        function applyBrightness(data, brightness) {
            for (let i = 0; i < data.length; i+= 4) {
                data[i] += 255 * (brightness / 100);
                data[i+1] += 255 * (brightness / 100);
                data[i+2] += 255 * (brightness / 100);
            }
        }

        function adjustBrightnessUp()
        {
            drawImage();
            let data = context.getImageData(0, 0, canvas.width, canvas.height);

            if (brightness < 15)
                brightness += 1;
            console.log(brightness);

            applyBrightness(data.data, brightness);
            context.putImageData(data, 0, 0);
        }

        function adjustBrightnessDown()
        {
            drawImage();
            let data = context.getImageData(0, 0, canvas.width, canvas.height);

            if (brightness > -15)
                brightness -= 1;
            console.log(brightness);

            applyBrightness(data.data, brightness);
            context.putImageData(data, 0, 0);

        }

        function truncateColor(value) {
            if (value < 0)
                value = 0;
            else if (value > 255)
                value = 255;


            return value;
        }

        function applyContrast(data, contrast) {
            console.log('yes: ' + contrast)
            const factor = (259.0 * (contrast + 255.0)) / (255.0 * (259.0 - contrast));

            for (let i = 0; i < data.length; i+= 4) {
                data[i] = truncateColor(factor * (data[i] - 128.0) + 128.0);
                data[i+1] = truncateColor(factor * (data[i+1] - 128.0) + 128.0);
                data[i+2] = truncateColor(factor * (data[i+2] - 128.0) + 128.0);
            }
        }

        function adjustContrastUp() {
            drawImage();
            let imageData = context.getImageData(0, 0, canvas.width, canvas.height);

            if (construct < 100)
                construct += 10;

            applyContrast(imageData.data, construct);
            context.putImageData(imageData, 0, 0);
        }

        function adjustContrastDown() {
            drawImage();
            let imageData = context.getImageData(0, 0, canvas.width, canvas.height);

            if (construct > -100)
                construct -= 10;

            applyContrast(imageData.data, construct);
            context.putImageData(imageData, 0, 0);
        }

        const invert = document.getElementById('invert');
        invert.addEventListener('click', invertColors);

        const brightUpButton = document.getElementById('brightnessUp');
        brightUpButton.addEventListener('click', adjustBrightnessUp);

        const brightDownButton = document.getElementById('brightnessDown');
        brightDownButton.addEventListener('click', adjustBrightnessDown);

        const constUpButton = document.getElementById('constructUp');
        constUpButton.addEventListener('click', adjustContrastUp);

        const constDownButton = document.getElementById('constructDown');
        constDownButton.addEventListener('click', adjustContrastDown);

        const grayscaleButton = document.getElementById('grayscale');
        grayscaleButton.addEventListener('click', processToGrayImage);

        //Color image grayscale
        function processToGrayImage(){
            //Get image data
            var imgData=context.getImageData(10,10,50,50);
            var canvasData = context.getImageData(0, 0, canvas.width, canvas.height);
            //This loop is to obtain each point of the image, and set the gray to the original image after calculating the gray

            for (var x = 0; x < canvasData.width; x++) {
                //alert("x="+x);
                for (var y = 0; y < canvasData.height; y++) {
                    //alert("y="+y);
                    // Index of the pixel in the array
                    var idx = (x + y * canvas.width) * 4;

                    // The RGB values
                    var r = canvasData.data[idx];
                    var g = canvasData.data[idx + 1];
                    var b = canvasData.data[idx + 2];
                    //Update image data
                    var gray = CalculateGrayValue(r , g , b);
                    canvasData.data[idx] = gray;
                    canvasData.data[idx + 1] = gray;
                    canvasData.data[idx + 2] = gray;
                }
            }
            context.putImageData(canvasData, 0, 0);
        }

        //Calculate the gray value of the image, the formula is: Gray = R*0.299 + G*0.587 + B*0.114
        function CalculateGrayValue(rValue, gValue, bValue){
            return parseInt(rValue * 0.299 + gValue * 0.587 + bValue * 0.114);
        }

    </script>
@endsection
