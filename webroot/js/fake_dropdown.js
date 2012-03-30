(function() {
	var controller = function(elem) {
		var className = $(elem).attr('class') || '';
		if (className.indexOf('ddowid:') != -1) return false;
		$(elem).addClass('ddowid:' + new Date().getTime()).addClass('select');
		
		var itemsController = {
			currentItem : undefined,
			goingBack : false,
			init : function() {
				itemsController.handlers = {
					40 : itemsController.next,
					38 : itemsController.prev,
					9 : itemsController.tab,
					27 : itemsController.escape
				};
				selectController.base.find('.item')
				.keydown(function(event) {
					var k = event.keyCode;
					if (itemsController.call(k, event)) {
						event.preventDefault()
					}
				}).focus(function() {
					itemsController.currentItem = this;
					itemsController.parent().addClass('current');
				}).blur(function(event) {
					itemsController.parent().removeClass('current');
					var n = itemsController.currentNumber();
					var total = itemsController.totalItems();
					if (!itemsController.goingBack) {
						if (n+1 == total) selectController.blur();
					} else {
						if (n == 0) selectController.blur();
					}
				});
			},
			parent : function() {
				return $(itemsController.currentItem).parent();
			},
			totalItems : function() {
				return selectController.base.find('ul>li').length;
			},
			currentNumber : function() {
				var n = itemsController.parent().attr('class');
				if (n == undefined) return -1;
				n = n.match(/nth(\d)/);
				if (n == undefined) n=-1;
				else n = parseInt(n[1]);
				return n;
			},
			next : function() {
				itemsController.goingBack = false;
				var next = itemsController.getNextItem();
				if (next.length > 0) {
					if (itemsController.currentItem)
						itemsController.currentItem.blur();
					next.children().focus();
				}
				return true;
			},
			prev : function() {
				itemsController.goingBack = true;
				var prev = itemsController.getPrevItem();
				if (prev.length > 0) {
					itemsController.currentItem.blur();
					prev.children().focus();
				} else {
					if (typeof itemsController.currentItem == 'undefined') {
						selectController.escape();
						return;
					}
					itemsController.currentItem.blur();
					itemsController.currentItem=undefined;
					selectController.base.children('a').focus();
				}
				return true;
			},
			tab : function(event) {
				if (event.shiftKey) {
					itemsController.goingBack = true;
				} else {
					itemsController.goingBack = false;
				}
				return false;
			},
			escape : function(event) {
				itemsController.currentItem = undefined;
				itemsController.goingBack = true;
				selectController.blur();
				selectController.hideDropdown();
				return;
				var current = itemsController.currentNumber();
				if (current + 1 >= 0) {
					var tmp = $(itemsController.currentItem).blur();
					itemsController.goingBack = true;
					itemsController.currentItem = undefined;
					tmp.blur();
				} else {
					itemsController.goingBack = true;
					$(itemsController.currentItem).blur();
				}
				return true;
			},
			call : function(index, event) {
				if (itemsController.handlers[index] == undefined) return false;
				return itemsController.handlers[index](event);
			},
			getPrevItem : function() {
				if (typeof itemsController.currentItem == "undefined") {
					return [];
				} else {
					prev = $(itemsController.currentItem).parent().prev();
				}
				if (prev.children('a.item').length>0) {
					return prev;
				}
				prev = $(itemsController.currentItem).parent().parent().parent().prev().children();
				if (prev.is('ul')) {
					prev = prev.first().children('li:last-child');
				}
				return prev
			},
			getNextItem : function() {
				if (typeof itemsController.currentItem == "undefined") {
					next = selectController.base.find('.item').first().parent();
				} else {
					next = $(itemsController.currentItem).parent().next();
				}
				if (next.children('a.item').length>0) {
					return next;
				}
				next = $(itemsController.currentItem).parent().parent().parent().next().children();
				if (next.is('ul')) {
					next = $(next.first().children('li').get(1));
				}
				return next
			}
		};
		var selectController = {
			base : null,
			init : function() {
				selectController.handlers = {
					40 : selectController.next,
					38 : selectController.prev,
					27 : selectController.escape
				};
				selectController.base = $(elem);
				var elements = selectController.base.children();
				var selectLabel = $(elements.get(0));
				selectLabel.wrap('<a href="#"></a>');
				var dropdownList = $(elements.get(1));
				dropdownList
					.hide()
					.find('li>a').addClass('item')
					.parent().each(function(i,j){
						$(this).addClass('nth' + i);
					}).parent();
				var dropdownTitle = dropdownList.siblings('a');
				dropdownTitle
					.focus(selectController.focus)
					.click(selectController.focus)
					.keydown(function(event) {
						var k = event.keyCode;
						if (selectController.handlers[k] != undefined && selectController.handlers[k]()){
							event.preventDefault()
						}
					})
				.parent().css({
					'position':'relative'
				});
				$(document).click(function(e) {
					var elem = $(e.target);
					var found = false;
					while (elem.length != 0 && !found) {
						if (selectController.base.get(0) == elem.get(0)) {
							found = true
						}
						elem = elem.parent();
					}
					if (!found) {
						selectController.hideDropdown();
					}
				});
				itemsController.init();
			},
			focus : function(event) {
				event.preventDefault();
				selectController.showDropdown();
			},
			blur : function(e) {
				selectController.hideDropdown();
				if (itemsController.goingBack) {
					selectController.base.children('a').get(0).focus();
				}
			},
			escape : function(event) {
				selectController.hideDropdown();
				selectController.base.children('a').blur();
			},
			next : function() {
				selectController.showDropdown();
				itemsController.next();
				return true;
			},
			prev : function() {
				itemsController.prev();
				return true;
			},
			hideDropdown : function() {
				selectController.base.children('ul').hide();
			},
			showDropdown : function() {
				selectController.base.children('ul').show()
			}
		}
		selectController.init();
	}
	
	$.fn.fakeDropdown = function() {
		this.each(function() {
			new controller(this);
		});
	}
})();