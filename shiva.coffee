addEventListener 'load', () ->
	VK.init
		appId: 2830838
		onlyWidgets: true
	VK.Widgets.Comments 'vk_comments',
		limit: 20
		width: 520
		attach: '*'