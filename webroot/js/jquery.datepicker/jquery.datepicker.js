(function  ($) {
	
	var dateSelected = function(dateText, inst) {
		var dateParts = dateText.split('/');
		hiddenInputs.month.val(dateParts[0]);
		hiddenInputs.day.val(dateParts[1]);
		hiddenInputs.year.val(dateParts[2]);
	}
	
	var values = {
		'year' : null, 'month' : null, 'day' : null
	};  
	var hiddenInputs = {
		'year' : null, 'month' : null, 'day' : null
	};
	var nameBase = '';
	var $orig;
	var $time = $('<div>');
	var $dummy = $('<input type="text" />');
	$().ready(function () {
		$('.datetime select').each(function (elem) {
			var name = this.name.match(/(.*)\[(.+)\]$/);
			nameBase = name[1];
			name = name[2];       
			if (name in values) {
				$orig = $(this).parent();
				values[name] = this.value;
				var $hiddenInput = $('<input>').attr({
					'name' : this.name, 'type' : 'hidden', 'value' : this.value
				});
				hiddenInputs[name] = $hiddenInput;
				$(this).remove();
			} else {
				$time.append($(this));
			}
		});
		var $timezone = $orig.find('span');
		$orig.empty().append(
			$dummy, hiddenInputs.year, hiddenInputs.month, hiddenInputs.day
		);
		$dummy.datepicker({
			onSelect: dateSelected
		}).datepicker('setDate', values.month + '/' + values.day + '/' + values.year);
		$orig.append($time.append($timezone));
	});
})(jQuery);