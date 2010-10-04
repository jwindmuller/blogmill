(function() {

	var dialog, postsLink, externalLink, content = null;
	$.fn.pageSelector = function(options) {
		dialog = $(this);
		dialog.append('<ul class="types"><li><a class="posts" href="#">Posts</a></li><li><a class="external" href="#">External URL</a></li></ul>');
		dialog.append('<div class="content" />');

		postsLink = dialog.find('.types a.posts');
		postsLink.click(function(event) {
			event.preventDefault();
			event.stopPropagation();
			for (var i in options.postTypes) {
				console.debug(i);
			}
			// for (var i=0; i < options.postTypes.length; i++) {
			// 	var pluginTypes = options.postTypes[i];
			// 	debug(pluginTypes);
			// 	for (var j=0; j < pluginTypes.length; j++) {
			// 		pluginTypes[j]
			// 	};
			// };
			console.debug(options.postTypes);
			// $.ajax({
			// 				url : options.postListURL,
			// 				type : 'GET',
			// 				dataType : 'json',
			// 				success : function(data, textStatus, request) {
			// 					
			// 				}
			// 			});
		});
		
		externalLink = dialog.find('.types a.external');
		content = dialog.find('.types .content');
		// Setup URL selector on focus		
		options.input.focus(function() {
			dialog.dialog({
				title : 'Web Page Selector'
			});
			postsLink.click();
		})

	}

})();