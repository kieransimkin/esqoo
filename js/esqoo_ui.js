var esqoo_ui = {};
esqoo_ui.dialog_singletons = [];
$(document).ready(function() { 
	esqoo_ui.dialog_singletons.length=0;
});
esqoo_ui.make_dialog = function(options,url,params) { 
	var parameters = {
		source : "dialog"
	};
	if (typeof(params) == "object")
		parameters = $.extend(parameters, params);
	if (typeof(params) == "number")
		parameters['id'] = params;
	if (typeof url == "undefined") { 
		url=String(document.location);
	}
	if (typeof options['params'] == "object") {
		parameters = $.extend(parameters,options['params']);
	}
	var buttons={};
	var createButtonFunc = function(bname) { 
		buttons[bname]=function() {
		};
	}
	if (options['cancelbutton']==1) { 
		buttons['Cancel']=function() { 
			$(this).dialog("close");
		};
	}
	if (options['closebutton']==1 || typeof options['closebutton'] == "undefined") { 
		buttons['Close']=function() { $(this).dialog("close") };	
	}
	if (options['savebutton']==1 || typeof options['savebutton'] == "undefined") { 
		createButtonFunc('Save');
	}
	if (options['okbutton']==1) { 
		createButtonFunc('Ok');
	}
	if (options['continuebutton']==1) { 
		createButtonFunc('Continue');
	}
	if (options['postbutton']==1) { 
		createButtonFunc('Post');
	}
	if (options['donebutton']==1) { 
		buttons['Done']=function() { 
			$(this).dialog("close");
		};
	}
	if (options.singleton && typeof(esqoo_ui.dialog_singletons[url])!='undefined' && esqoo_ui.dialog_singletons[url]!==null) { 
		esqoo_ui.bring_to_front(esqoo_ui.dialog_singletons[url]);
		return;
	} else if (options.singleton && esqoo_ui.dialog_singletons[url]===null) { 
		return;
	}
	esqoo_ui.dialog_singletons[url]=null;
	var dialog=$("<div></div>").dialog($.extend({
		autoOpen: true,
		modal: options['modal'],
		title: "",
		position:'center',
		buttons: buttons,
		open: function() {
			if (options.singleton) { 
				esqoo_ui.dialog_singletons[url]=$(this);
			}
			esqoo_ui.setup_dialog_html($(this),url,parameters);
		},
		close: function() { 
			delete esqoo_ui.dialog_singletons[url];
		}

	},options || {}));
}
esqoo_ui.setup_dialog_html = function(d,url,params) { 
	esqoo_ui.buttonify_dialog(d);
	esqoo_ui.set_dialog_loading_state(d);
	esqoo_ui.populate_dialog(d,url,params);
}
esqoo_ui.populate_dialog = function(d,url,params) { 
	$.ajax({url: url, dataType: 'json', type: 'post', data: params, success: function(data) { 
		esqoo_ui.update_dialog_html(d,data);
	}}).error(function() { 
		alert('Unable to parse dialog JSON');
		$(d).dialog('close');
	});
}
esqoo_ui.update_dialog_html = function(d,data) { 
	if (data.rettype===null || data.rettype==='failure') { 
		esqoo_ui.unset_dialog_loading_state(d);
		$(d).html(data.html);
		esqoo_ui.prepare_dialog_html(d);
	} else if (data.rettype==='success') { 
		$(d).dialog("close");
	} else { 
		alert('Invalid return type');
	}
}
esqoo_ui.prepare_dialog_html = function(d) { 
	if (d.parent().find('input[type=text]:first').val()=='') { 
		d.parent().find('input[type=text]:first').focus();
	}
	console.log(d.find('form'));
}
esqoo_ui.set_dialog_loading_state = function(d) { 

}
esqoo_ui.unset_dialog_loading_state = function (d) { 

}
esqoo_ui.bring_to_front = function(d) { 
	$(d).dialog('moveToTop');
}
esqoo_ui.buttonify_button = function(b,icon,submitbutton) { 
	// Really kludgey way of hacking icons into jQuery's dialog
	if (b.attr('data-done-iconify')=='true') {
		return;
	}
	b.attr('data-done-iconify','true');
	if (typeof(submitbutton)!='undefined' && submitbutton===true) { 
		b.attr('data-submit-button','true');
	}
	b.find('.ui-button-text').css('position','relative');
	b.find('.ui-button-text').css('left','4px');
	b.prepend('<span class="esqoo-ui-dialog-button-icon ui-icon '+icon+'"></span> ');
	var newwidth=b.width()+30;
	b.width(newwidth);

}
esqoo_ui.buttonify_dialog = function(d) { 
	// Really kludgey way of hacking icons into jQuery's dialog
	var btnCancel=d.parent().find('.ui-dialog-buttonpane').find('button:contains("Close"),button:contains("Cancel")');
	btnCancel.each(function() { 
		esqoo_ui.buttonify_button($(this),'ui-icon-closethick');
	});
	var btnOk=d.parent().find('.ui-dialog-buttonpane').find('button:contains("Ok")');
	btnOk.each(function() { 
		esqoo_ui.buttonify_button($(this),'ui-icon-check',true);
	});
	var btnSave=d.parent().find('.ui-dialog-buttonpane').find('button:contains("Save")');
	btnSave.each(function() { 
		esqoo_ui.buttonify_button($(this),'ui-icon-disk',true);
	});
	var btnContinue=d.parent().find('.ui-dialog-buttonpane').find('button:contains("Continue")');
	btnContinue.each(function() { 
		esqoo_ui.buttonify_button($(this),'ui-icon-circle-arrow-e',true);
	});
	var btnPost=d.parent().find('.ui-dialog-buttonpane').find('button:contains("Post")');
	btnPost.each(function() { 
		esqoo_ui.buttonify_button($(this),'ui-icon-mail-closed',true);
	});
	var btnDone=d.parent().find('.ui-dialog-buttonpane').find('button:contains("Done")');
	btnDone.each(function() { 
		esqoo_ui.buttonify_button($(this),'ui-icon-check');
	});
}
