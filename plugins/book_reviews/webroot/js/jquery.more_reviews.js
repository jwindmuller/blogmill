$(function() {
	var list = $('#latest-books ul li');	
	function show(index) {
		var divToShow = $(list.find('div.book-detail').get(index));
		if (!divToShow.is(':visible')) {
			divToShow.fadeIn('fast');
		}
	}
	function hideShow(index) {
		list.find('div.book-detail').filter(function(i) {return index != i;}).fadeOut('fast', function() {show(index);});
	}
	list.find('a.cover').click(function(event) {
		var index = $(this).parent().prevAll().length;
		var divToShow = $(list.find('div.book-detail').get(index));
		if (divToShow.is(':visible')) {
			return;
		}
		event.preventDefault();
		hideShow(index);
	});
});