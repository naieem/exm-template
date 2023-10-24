/**
 * Ce script doit être loadé inline le plus haut possible dans le head
 * 
 */
window.CI360 = { notInitOnLoad: true } // pour empeicher le plugin 360 d'init on load

var c = document.documentElement.className; 
c=c.replace(/no-js/,'js');
document.documentElement.className = c;
window.addEventListener("load", function (e) {
    var c = document.documentElement.className;
    c=c.replace(/preload/,'');
    document.documentElement.className = c;
    document.getElementById('cdm-loader').remove();
});
