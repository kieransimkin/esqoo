$(document).ready(function() { 
	$('button').livequery(function() { 
		var primary = null;
		var secondary = null;
		if ($(this).attr('data-icon-primary')) { 
			primary=$(this).attr('data-icon-primary');
		}
		if ($(this).attr('data-icon-secondary')) { 
			secondary=$(this).attr('data-icon-secondary');
		}
		$(this).button({icons: {primary: primary, secondary: secondary}});
	});
	$('input[type=text], input[type=email], input[type=password], input[type=url], input[type=number], input[type=search], input[type=tel]').livequery(function() { 
		$(this).button().addClass('esqoo-text-field');
	});
});
