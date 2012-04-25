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
		var html=$(this).html();
		$(this).button().html(html).addClass('esqoo-text-field');
	});
	$('section.esqoo-dialog-tabs').livequery(function() { 
		$(this).tabs();
	});
	$('input.upload-form[type=file]').livequery(function() { 
		$(this).uploadq();
	});
	$('input.esqoo-combobox').livequery(function() { 
		$(this).combobox({source: $.parseJSON($($(this).attr('data-combobox-source-selector')).val())});
	});
	$('select').livequery(function() { 
		var width=200;
		if ($(this).attr('data-width')) { 
			width=$(this).attr('data-width');
		}
		$(this).selectmenu({width: width});
	});
	$('textarea.esqoo-qrichedit').livequery(function() { 
		$(this).qrichedit();
	});
	$('.sqtip[data-pictureid]').livequery(function() { 
		$(this).sqtip({'picture_id':$(this).attr('data-pictureid')});
	});
	$('.esqoo-qtip').livequery(function() {
		var position_my='top middle';
		if ($(this).attr('data-qtip-position-my')) { 
			position_my=$(this).attr('data-qtip-position-my');
		}
		var position_at='bottom middle';
		if ($(this).attr('data-qtip-position-at')) { 
			position_at=$(this).attr('data-qtip-position-at');
		}
		if ($(this).attr('data-qtip-content')) { 
			$(this).qtip({content: $(this).attr('data-qtip-content'), style: { classes: 'ui-tooltip-rounded ui-tooltip-shadow', widget: true }, position: {my: position_my, at: position_at}});
		} else if ($(this).attr('title')) { 
			$(this).qtip({content: $(this).attr('title'),style: { classes: 'ui-tooltip-rounded ui-tooltip-shadow', widget: true }, position: {my: position_my, at: position_at}});
		} else if ($(this).attr('alt')) { 
			$(this).qtip({content: $(this).attr('alt'),style: { classes: 'ui-tooltip-rounded ui-tooltip-shadow', widget: true }, position: {my: position_my, at: position_at}});
		}
	});
	$('div.esqoo-mediaslide').livequery(function() { 
		var url=$(this).attr('data-mediaslide-url');
		$(this).mediaslide({esqoo_xml_ajax: url});
	});
	$('div.esqoo-thumbnailbrowse').livequery(function() { 
		var url=$(this).attr('data-thumbnailbrowse-url');
		$(this).thumbnailbrowse({url: url});
	});
	$('div.esqoo-flexigrid').livequery(function() { 
		var idfield='id';
		if ($(this).attr('data-flexigrid-id-field')) { 
			idfield=$(this).attr('data-flexigrid-id-field');
		}
		var height=350;
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
		var params=[];
		$(this).flexigrid({height: height, width: width, usepager: usepager, page: page, useRp: useRp, rp: rp, url: $(this).attr('data-flexigrid-url'), dataType: 'json', colModel: colModel,params:params,idfield: idfield,searchitems: searchitems});
	});
	
});
