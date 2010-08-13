$().ready(function() {
	$('div.input.htmleditor textarea').tinymce({
		theme : "advanced",
		theme_advanced_buttons1 : "bold,italic,bullist,numlist,blockquote,|,link,unlink,|,cut,copy,paste,undo,redo",
		theme_advanced_buttons2 : '',
		theme_advanced_buttons3 : '',
		theme_advanced_resizing : true,
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_toolbar_align : "left",
		width : "100%",
		height : '300'
	}).parent().addClass('htmleditor');
});