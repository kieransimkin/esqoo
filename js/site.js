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
		var text=true;
		if ($(this).html()=='') { 
			text=false;
		}
		$(this).button({text: text, icons: {primary: primary, secondary: secondary}});
	});
	$('input[type=text], input[type=email], input[type=password], input[type=url], input[type=number], input[type=search], input[type=tel]').livequery(function() { 
		$(this).button().addClass('esqoo-text-field');
	});
	$('textarea:not(.plain-textarea)').livequery(function() { 
		$(this).button().addClass('esqoo-text-field');
	});
	$('section.esqoo-dialog-tabs').livequery(function() { 
		$(this).tabs();
	});
	$('input.upload-form[type=file]').livequery(function() { 
		$(this).uploadq();
	});
	$('select').livequery(function() { 
		$(this).selectmenu({width: '200'});
	});
	$('div.esqoo-flexigrid').livequery(function() { 
		var idfield='id';
		if ($(this).attr('data-flexigrid-id-field')) { 
			idfield=$(this).attr('data-flexigrid-id-field');
		}
		var height=200;
		if ($(this).attr('data-flexigrid-height')) { 
			height=$(this).attr('data-flexigrid-height');
		}
		var width='auto';
		if ($(this).attr('data-flexigrid-width')) { 
			width=$(this).attr('data-flexigrid-width');
		}
		var usepager=false;
		if ($(this).attr('data-flexigrid-usepager')) { 
			usepager=$(this).attr('data-flexigrid-usepager');	
		}
		var page=1;
		if ($(this).attr('data-flexigrid-page')) { 
			page=$(this).attr('data-flexigrid-page');
		}
		var useRp=true;
		if ($(this).attr('data-flexigrid-userp')) { 
			useRp=$(this).attr('data-flexigrid-userp');
		}
		var rp=15;
		if ($(this).attr('data-flexigrid-rp')) { 
			rp=$(this).attr('data-flexigrid-rp');
		}
		/*
		searchitems : [
			{display: 'User', name : 'user_metadata.username', isdefault: true},
			{display: 'Subject', name : 'subject'}
			]
		*/
		var colModel=[];
		for (var c=0; true; c++) { 
			if (!$(this).attr('data-flexigrid-col'+c+'-name')) { 
				break;
			}
			var sortable=true;
			if ($(this).attr('data-flexigrid-col'+c+'-sortable')=='false') { 
				sortable=false;
			}
			var align='left';
			if ($(this).attr('data-flexigrid-col'+c+'-align') && $(this).attr('data-flexigrid-col'+c+'-align')!='left') { 
				align=$(this).attr('data-flexigrid-col'+c+'-align');
			}
			var jsfilter='';
			if ($(this).attr('data-flexigrid-col'+c+'-js-filter')) { 
				jsfilter=$(this).attr('data-flexigrid-col'+c+'-js-filter');
				sortable=false;
			}
			colModel.push({display: $(this).attr('data-flexigrid-col'+c+'-display'), name: $(this).attr('data-flexigrid-col'+c+'-name'), width: $(this).attr('data-flexigrid-col'+c+'-width'), sortable: sortable, align: align, jsfilter: jsfilter});
		}
		var searchitems=[];
		for (var c=0; true; c++) {
			if (!$(this).attr('data-flexigrid-searchitem'+c+'-name')) { 
				break;
			}
			var isdefault=false;
			if ($(this).attr('data-flexigrid-searchitem'+c+'-isdefault')=='true') { 
				isdefault=true;
			}
			searchitems.push({display: $(this).attr('data-flexigrid-searchitem'+c+'-display'), name: $(this).attr('data-flexigrid-searchitem'+c+'-name'), isdefault: isdefault});
		}
		var params=[{name: 'ResponseFormat',value:'json'}];
		$(this).flexigrid({height: height, width: width, usepager: usepager, page: page, useRp: useRp, rp: rp, url: $(this).attr('data-flexigrid-url'), dataType: 'json', colModel: colModel,params:params,idfield: idfield,searchitems: searchitems});
	});
	 $("#nav-one").supersubs({ 
            minWidth:    12,   // minimum width of sub-menus in em units 
            maxWidth:    15,   // maximum width of sub-menus in em units 
            extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                               // due to slight rounding differences and font-family 
        }).superfish({ hidingspeed: 250, animation:   {opacity:'show',height:'show'}, hoverClass: 'ui-state-hover',  // fade-in and slide-down animation 
                 speed:       150, delay: 500}).find('ul');

});
