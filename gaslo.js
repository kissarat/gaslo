// Generated by CoffeeScript 1.6.3
var back, fonts, list;

list = null;

back = null;

fonts = ['Kelly Slab', 'Ruslan Display', 'PT Mono', 'Oranienbaum', 'Lobster', 'Roboto Slab', 'Comfortaa', 'Open Sans Condensed', 'Play'];

addEventListener('load', function() {
  var image, request;
  document.body.style.fontFamily = fonts[Math.round(fonts.length * Math.random())] + ', ' + (getComputedStyle(document.body, null)).fontFamily;
  list = document.getElementById('list');
  document.getElementsByTagName('input')[0].setAttribute('placeholder', 'Посилання');
  request = new XMLHttpRequest();
  image = Math.round(40 * Math.random());
  back = document.getElementById('back');
  back.style.backgroundImage = 'url(revolution/' + image + '.jpg)';
  return request.onload = function() {
    var anchor, gaslo, url_index, _i, _len, _ref, _results;
    _ref = request.responseText.split('\n');
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      gaslo = _ref[_i];
      anchor = document.createElement('a');
      url_index = gaslo.indexOf(' ');
      anchor.setAttribute('href', gaslo.slice(url_index));
      _results.push(anchor.innerHTML = gaslo.slice(url_index, gaslo.length - url_index));
    }
    return _results;
  };
});
