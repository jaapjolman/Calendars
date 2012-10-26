var get_label_from_span = function(value)
{

	// Switch it up
	switch ( value )
	{
		case 'D': return 'days';break;
		case 'W': return 'weeks';break;
		case 'M': return 'months';break;
		case 'Y': return 'years';break;
	}
}

$(document).ready(function(){

	/*
	 *	When changing the span for repeating
	 *	events, the marker needs to be
	 *	added / changed depending on the radio choice.
	 */

	// First add it
	var initial_value = get_label_from_span($('input[type="radio"]').val());

	$('#repeats_every').after('&nbsp;&nbsp;<span id="repeats_every_label_helper">' + initial_value + '</span>');

	// Add the listener
	$('input[type="radio"]').change(function(){

		// Get the label
		var label = get_label_from_span($(this).val());
		// Set it
		$('#repeats_every_label_helper').html(label);
	});

});