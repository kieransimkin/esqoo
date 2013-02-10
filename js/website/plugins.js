var esqoo_website_plugins={};
esqoo_website_plugins.actions= function (id,data,row) { 
	var ret = $('<div></div>');
	if (row.Enabled==="True") { 
		$('<button></button>').attr('data-icon-primary','ui-icon-power').html('Deactivate').attr('onclick','esqoo_website_plugins.deactivate(\''+id+'\'); return false;').appendTo(ret);
		$('<button></button>').attr('data-icon-primary','ui-icon-gear').html('Setup').attr('onclick','esqoo_website_plugins.setup(\''+id+'\'); return false;').appendTo(ret);
	} else { 
		$('<button></button>').attr('data-icon-primary','ui-icon-power').html('Activate').attr('onclick','esqoo_website_plugins.activate(\''+id+'\'); return false;').appendTo(ret);
		$('<button></button>').attr('data-icon-primary','ui-icon-trash').html('Remove').attr('onclick','esqoo_website_plugins.remove(\''+id+'\'); return false;').appendTo(ret);

	}
	$('<button></button>').attr('data-icon-primary','ui-icon-info').html('Info').attr('onclick','esqoo_website_plugins.info(\''+id+'\'); return false;').appendTo(ret);
	return ret;
}
esqoo_website_plugins.activate = function (id) { 

}
esqoo_website_plugins.deactivate = function (id) { 

}
esqoo_website_plugins.remove = function (id) { 

}
esqoo_website_plugins.setup = function (id) { 

}
esqoo_website_plugins.info = function (id) { 

}
