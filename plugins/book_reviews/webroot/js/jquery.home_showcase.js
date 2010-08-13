$(function() {
	var list = $('#showcase .latest ul li');
	var current = list.first();
	var stop = false;
	var timer;
	function next() {
		if (!stop) {
			hide(current);
			current = getNext(current);
			show(current);
		}
		timer = setTimeout(next, 5000);
	}
	timer = setTimeout(next, 5000);

	function getNext(item) {
		var next = item.next();
		if (next.length == 0) {
			next = list.first();
		}
		return next;
	}
	function getPrev(item) {
		var prev = item.prev();
		if (prev.length == 0) {
			prev = list.last();
		}
		return prev;
	}
	function hide(item) {
		item.find('.showcase_image').fadeOut();
	}
	function show(item) {
		item.find('.showcase_image').fadeIn();
	}
	function setActive(item) {
		hide(getNext(item));
		hide(getPrev(item));
		show(item);
		current = item;
	}
	function resume() {
		$(this).parentsUntil('ul').filter('li').removeClass('showcase-paused');
		var pauseButtons = list.find('.paused');
		pauseButtons.hide();
		stop = false;
		next();
	}
	function pause(clicked) {
		list.removeClass('showcase-paused');
		clicked.parent().parent().addClass('showcase-paused');
		stop=true;
		clearTimeout(timer);
		var pauseButtons = list.find('.paused');
		if (pauseButtons.length == 0) {
			list.find('.current-selection').after('<div class="paused" />');
			pauseButtons = list.find('.paused');
			pauseButtons.hide().click(resume);
		}
		pauseButtons.hide();
		pauseButtons.delay(200).fadeTo('fast', 0.7);
	}

	list.find('p.side a').click(function(event) {
		var isCurrent = $(this).parent().parent().hasClass(current.get(0).className);
		if (isCurrent && stop) return;
		event.preventDefault();
		pause($(this));
		setActive($(this).parent().parent());
	});
	
	function contact_form() {
		$('#showcase .latest .contact form').find('input,textarea').focus(function() {
			$(this).siblings('label').fadeOut('fast');
		}).blur(function() {
			if ($(this).val() == '')
				$(this).siblings('label').fadeIn('fast');
		});
		var submit = $('#showcase .latest .contact form').find('.submit input').hide();
		submit.after('<a href="#">' + submit.val() + '</a>');
	}
	contact_form();
});