var esqoo_website_themes={};
esqoo_website_themes.actions= function (id,data,row) { 
	var ret = $('<div></div>');
	if (row.Enabled==="True") { 
		$('<button></button>').attr('data-icon-primary','ui-icon-gear').html('Setup').attr('onclick','esqoo_website_themes.setup(\''+id+'\'); return false;').appendTo(ret);
	} else { 
		$('<button></button>').attr('data-icon-primary','ui-icon-power').html('Activate').attr('onclick','esqoo_website_themes.activate(\''+id+'\'); return false;').appendTo(ret);
		$('<button></button>').attr('data-icon-primary','ui-icon-trash').html('Remove').attr('onclick','esqoo_website_themes.remove(\''+id+'\'); return false;').appendTo(ret);

	}
	$('<button></button>').attr('data-icon-primary','ui-icon-info').html('Info').attr('onclick','esqoo_website_themes.info(\''+id+'\'); return false;').appendTo(ret);
	return ret;
}
esqoo_website_themes.activate = function (id) { 
	esqoo_ui.make_dialog({singleton: true, title:'Activate Plugin',closebutton:false,cancelbutton:true,savebutton:false,continuebutton:true},'/website/activate-theme/'+id);
}
esqoo_website_themes.remove = function (id) { 

}
esqoo_website_themes.setup = function (id) { 

}
esqoo_website_themes.info = function (id) { 

}
