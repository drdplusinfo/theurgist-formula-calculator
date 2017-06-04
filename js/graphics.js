var background = document.getElementsByClassName('background')[0];

background.style.height = window.height;

window.addEventListener('resize', function () {
    background.style.height = window.height;
    console.log('zde');
});