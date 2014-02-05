list = null
back = null
fonts = ['Kelly Slab', 'Ruslan Display', 'PT Mono',   'Oranienbaum',
         'Lobster',    'Roboto Slab',    'Comfortaa', 'Open Sans Condensed', 'Play']

addEventListener 'load', () ->
	document.body.style.fontFamily = fonts[Math.round fonts.length * Math.random()] + ', ' +
		(getComputedStyle document.body, null).fontFamily
	list = document.getElementById 'list'
	document.getElementsByTagName('input')[0].setAttribute 'placeholder', 'Посилання'
	request = new XMLHttpRequest()
	image = Math.round 40 * Math.random()
	back = document.getElementById 'back'
	back.style.backgroundImage = 'url(revolution/' + image + '.jpg)'
	request.onload = () ->
		for gaslo in request.responseText.split '\n'
			anchor = document.createElement 'a'
			url_index = gaslo.indexOf ' '
			anchor.setAttribute 'href', gaslo.slice url_index
			anchor.innerHTML = gaslo.slice url_index, gaslo.length - url_index
