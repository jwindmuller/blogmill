$(window).load(function() {
	$('#sidebarselect').fakeDropdown();
	$('#flashMessage,#authMessage').hide().show('blind').delay(3000).hide('blind');
})