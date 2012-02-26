var esqoo_ui = {};
esqoo_ui.make_dialog = function(options,url,params,modal) { 
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
	var dialog=$("<div></div>").dialog($.extend({
		autoOpen: true,
		modal: modal,
		title: "",
		position:'center',
		buttons: buttons,
		open: function() {
			esqoo_ui.setup_dialog_html($(this));
		}	

	},options || {}));
}
esqoo_ui.setup_dialog_html = function(d) { 
	esqoo_ui.buttonify_dialog(d);
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
	console.log(btnCancel);
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
	if (d.parent().find('input[type=text]:first').val()=='') { 
		d.parent().find('input[type=text]:first').focus();
	}
}
