$(document).ready(function() { 
	$('button:not(.ui-button)').livequery(function() { 
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
	$('input[type=text], input[type=email], input[type=password], input[type=url], input[type=number], input[type=search], input[type=tel] textarea').livequery(function() { 
		$(this).button().addClass('esqoo-text-field');
	});
	$('section.esqoo-dialog-tabs').livequery(function() { 
		$(this).tabs();
	});
	$('select').livequery(function() { 
		$(this).selectmenu({width: '200'});
	});
	 $("#nav-one").supersubs({ 
            minWidth:    12,   // minimum width of sub-menus in em units 
            maxWidth:    15,   // maximum width of sub-menus in em units 
            extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                               // due to slight rounding differences and font-family 
        }).superfish({ hidingspeed: 250, animation:   {opacity:'show',height:'show'}, hoverClass: 'ui-state-hover',  // fade-in and slide-down animation 
                 speed:       150, delay: 500}).find('ul');

});
