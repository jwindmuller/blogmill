$(function(){
	var list, form, dialog = null;
	
	function updateIndexes() {
		var i = 0;
		list.children().each(function() {
			var deleteLink = $(this).children('a').last();
			var href = deleteLink.attr('href').replace(/(.+)\d/, '$1' + i);
			deleteLink.attr('href', href);
			i++;
		});
	}
	function reloadOriginalList() {
		list.empty();
		var order = list.data('current-order');
		var count = order.length;
		for (var i = count - 1; i >= 0; i--){
			list.append(order[count-1-i]);
		};
	}
	
	$.fn.menuSetup = function(pageSelectorOptions) {
		list = $(this);
		form = list.closest('form');
		
		list.sortable({
			start : function() {
				var temp = [];
				var  i = 0;
				list.children(':not(.ui-sortable-placeholder)').each(function() {
					temp[i++] = this;
				});
				list.data('current-order', temp);
			},
			update : function(event, ui) {
				$.ajax({
					url : form.attr('action'),
					type : 'POST',
					data : form.serialize(),
					dataType : 'html',
					success : function(data, textStatus, request) {
						if (data == 'OK') {
							updateIndexes();
						} else {
							reloadOriginalList();
						}
					},
					error : function(data, textStatus, request) {
						reloadOriginalList();
					}
				});
			}
		}).disableSelection();
		
		// pageSelectorOptions['input'] = form.find('input[type=text]').last();
		// dialog = $('<div id="page-selector" />');
		// $('body').append(dialog.hide());
		// dialog.pageSelector(pageSelectorOptions);
	}
});