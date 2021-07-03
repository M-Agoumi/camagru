// get the canvas object so we can modify it without calling
// getElementById over and over
let canvas = document.getElementById("canvas");

// a global variable to store our applied filters so we don't
// override one when we modify another one

var appliedFilters = [];

async function getWebCam() {
    try {
        const videoSrc = await navigator.mediaDevices.getUserMedia({video: true, audio: false});
        let video = document.getElementById("video");
        video.srcObject = videoSrc;
        document.getElementById('picture').style.display = 'none';
        document.getElementById('camera').style.display = 'block';
    } catch (e) {
        console.log(e);
    }
}
getWebCam();

/** image filters functions */

const filters = {
    grayscale: 0,
    brightness: 1,
    blur: "0px",
    contrast : "100%",
    'hue-rotate': '0deg',
    invert : '0%'
};

// function that will apply the global filters
function applyFilters() {
    const filterString = Object.keys(filters).map(key =>
        `${key}(${filters[key]})`
    );
    console.log(filterString.join(' '));
    canvas.style.filter = filterString.join(' ');
}


// clear all filters
function clearFilters()
{
    if (canvas.style.filter == 'none')
        applyFilters();
    else
        canvas.style.filter = 'none';
}

// increase the brightness of the image

function brightnessToggle()
{
    if (filters.brightness != 1)
        filters.brightness = 1;
    applyFilters();
}

function brightnessUp()
{

    if (filters.brightness < 1.6)
        filters.brightness += 0.1;
    applyFilters();
}

// decrease the brightness of the image
function brightnessDown()
{
    if (filters.brightness > 0.5)
        filters.brightness -= 0.1;
    applyFilters();
}

// toggle grayscale
function grayscaleToggle()
{
    if (filters.grayscale == 0)
        filters.grayscale = 1;
    else
        filters.grayscale = 0;
    applyFilters();
}

// increase grayscale

function grayscaleUp()
{
    if (filters.grayscale < 1)
        filters.grayscale += 0.1;
    applyFilters();
}

// decrease grayscale

function grayscaleDown()
{
    if (filters.grayscale > 0)
        filters.grayscale -= 1;
    applyFilters();
}

// toggle blur
function blurToggle()
{
    if (filters.blur != 0)
        filters.blur = 0;
    applyFilters();
}

// increase grayscale

function blurUp()
{
    if (parseInt( filters.blur, 10 ) < 6)
        filters.blur = parseInt( filters.blur, 10 ) + 1 + "px";
    applyFilters();
}

// decrease grayscale

function blurDown()
{
    if (parseInt( filters.blur, 10 ) > 0)
        filters.blur = parseInt( filters.blur, 10 ) - 1 + "px";
    applyFilters();
}

// toggle contrast
function contrastToggle()
{
    if (parseInt( filters.contrast, 10 ) != '100%')
        filters.contrast = '100%';
    applyFilters();
}

// increase grayscale

function contrastUp()
{
    if (parseInt( filters.contrast, 10 ) < 200)
        filters.contrast = parseInt( filters.contrast, 10 ) + 10 + "%";
    applyFilters();
}

// decrease contrast

function contrastDown()
{
    if (parseInt( filters.contrast, 10 ) > 0)
        filters.contrast = parseInt( filters.contrast, 10 ) - 10 + "%";
    applyFilters();
}

// toggle hue-rotate

function hueToggle()
{
    if (filters['hue-rotate'] != '0deg')
        filters['hue-rotate'] = '0deg';
    applyFilters();
}


// increase Hue

function hueUp()
{
    if (parseInt( filters['hue-rotate'], 10 ) != 360)
        filters['hue-rotate'] = parseInt( filters['hue-rotate'], 10 ) + 10 + 'deg';
    else
        filters['hue-rotate'] = '0deg';
    applyFilters();
}

// decrease Hue

function hueDown()
{
    if (parseInt( filters['hue-rotate'], 10 ) != 0)
        filters['hue-rotate'] = parseInt( filters['hue-rotate'], 10 ) - 10 + 'deg';
    else
        filters['hue-rotate'] = '360deg';
    applyFilters();
}

// toggle invert

function invertToggle()
{
    if (parseInt( filters.invert, 10 ) != 0)
        filters.invert = '0%';
    else
        filters.invert = '100%';
    applyFilters();
}

// increase invert

function invertUp()
{
    if (parseInt( filters.invert, 10 ) < 100)
        filters.invert = parseInt( filters.invert, 10 ) + 10 + "%";
    applyFilters();
}

// decrease invert

function invertdow()
{
    if (parseInt( filters.invert, 10 ) < 0)
        filters.invert = parseInt( filters.invert, 10 ) - 10 + "%";
    applyFilters();
}

// DEALING WITH MENU BUTTON
let menuBtn  = document.querySelector(".menu");
let navBar = document.querySelector(".nav .nav-left");


menuBtn.onclick = function(e) {
    navBar.classList.toggle("show");
}