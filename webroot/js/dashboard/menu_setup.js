$(function(){
	var list, dialog, postListURL, sortingURL = null;
	dialog = $('<div id="post-selector"><div class="arrow" /><ul class="types"/><ul class="posts" />');
	
	function reloadOriginalList() {
		list.empty();
		var order = list.data('current-order');
		var count = order.length;
		for (var i = count - 1; i >= 0; i--){
			list.append(order[count-1-i]);
		};
	}
    function fixIndexes() {
        var i = 0;
        list.children().each(function(index, elem) {
            var _edit = $(elem).find('.actions .edit');
            var edit_link = _edit.attr('href');
            _edit.attr('href', edit_link.replace(/:(\d+)/, ':' + i));
            var _delete = $(elem).find('.actions .delete');
            var delete_link = _delete.attr('href');
            _delete.attr('href', delete_link.replace(/\d+$/, i));
        });
    }
	function fillPostList (plugin, type) {
		$.getJSON(postListURL, {"plugin" : plugin, "type" : type}, function(data, textStatus, jqXHR) {
			var posts = dialog.children('.posts').empty().removeClass('empty');
			if (data.length==0) {
                posts.addClass('empty');
			}
			for (var i=0; i < data.length; i++) {
				var li = $('<li />').append(postDetail(data[i].Post)).data('post', data[i].Post);
				li.click(function() {
                    var nameInput = $(this).parents('div.input').siblings('div.input').children('input');
                    nameInput.siblings('label').hide();
					var urlInput = $(this).parents('#post-selector').siblings('input');
                    urlInput.siblings('label').hide()
					var post = $(this).data('post');
					urlInput.val(post['url']);
                    if (nameInput.val() == '') {
                        nameInput.val(post['display']);
                    }
					hideDialog();
				});
				posts.append(li);
			};
		});
	}
	function postDetail(post) {
		var html = '<strong>' + post['display'] + '</strong>';
		    html += '<p>' + post['excerpt'] + '</p>';
		    html += '<a href="#">select</a>';
		return html;
	}
	function hideDialog() {
		dialog.children('.posts').empty();
		dialog.appendTo($('body')).hide();

	}
	$.fn.menuSetup = function(pageSelectorOptions) {
		postListURL = pageSelectorOptions.postListURL;
		list = $(this);                         
		list.children().prepend(pageSelectorOptions.handle)
			.find('input').click(function(e) {
				e.stopPropagation();
				$(this).focus();
			});
		var postTypes = pageSelectorOptions.postTypes;
		$.each(postTypes, function(plugin, val) {
            //<span class="plugin">' + plugin + '</span>
			var li = dialog.children('.types').append('<li><ul /></li>');
			var ul = dialog.children('.types').children('li:last-child').children('ul');
			for (var i=0; i < val.length; i++) {
                var name = val[i].name;
                var type = val[i].type;
				var $type = $('<li><a href="#">' + name + '</a></li>');
				$type.data('plugin', plugin);
				$type.data('type', type);
				ul.append($type);
				$type.click(function() {
					fillPostList($(this).data('plugin'), $(this).data('type'));
                });
			};
		});

		$(pageSelectorOptions.UrlInputClass).click(function(e) {
			dialog.appendTo($(this).parent()).show();
		}).focus(function() {
			dialog.appendTo($(this).parent()).show();
		});
		
        sortingURL = pageSelectorOptions.sortingURL;
		list.sortable({
			handle : '.handle',
			start : function(event, ui) {
                list.data('moving', ui.item.prevAll().length);
                var temp = [];
				var  i = 0;
				list.children(':not(.ui-sortable-placeholder)').each(function() {
					temp[i++] = this;
				});
				list.data('current-order', temp);
			},
			update : function(event, ui) {
                var img = ui.item.find('.handle');
                var src = img.attr('src');
                img.attr('src', src.replace(/\/[^\/]+$/, '/loader.gif'));
				$.ajax({
					url : sortingURL,
					type : 'POST',
                    dataType : 'json',
                    data : {"data[Setting][i]" : list.data('moving'), "data[Setting][new_pos]" : ui.item.prevAll().length},
					success : function(json, textStatus, request) {
                        img.attr('src', src);
						if (json.status == 'ERROR') {
							reloadOriginalList();
						}
                        fixIndexes();
					},
					error : function(data, textStatus, request) {
						reloadOriginalList();
					}
				});
			}
		}).disableSelection().find('.move').hide();
        

		$(document).click(function(e) {
			var elem = $(e.target);
			// Hide only if #post-selector is not a parent
			var hide = elem.is('html') || elem.parentsUntil('#post-selector').is('html');
			if (hide) {
				// If the element is an input then check if the #post-selector is a sibling
				if (!elem.is('input') || !elem.siblings('#post-selector').length>0) {
					hideDialog();
				}
			}
		});

		//TODO: handle escape key

	}
});
